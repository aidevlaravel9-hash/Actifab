<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Customer;
use App\Models\CustomerCouponApplyed;
use App\Models\Offer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductAttributes;
use App\Models\Productphotos;
use App\Models\Testimonial;
use App\Models\Shipping;
use App\Models\State;
use App\Models\Wishlist;
use App\Models\OtherPages;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Country;
use App\Models\Inquiry;
use App\Models\MetaData;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class FrontController extends Controller
{
    private function getCountryCode($ip)
    {
        // $ip = '8.8.8.8';
        $response = @file_get_contents("http://ip-api.com/json/{$ip}");

        if ($response) {
            $data = json_decode($response, true);
            if (!empty($data['countryCode'])) {
                return $data['countryCode']; // e.g. 'IN'
            }
        }
        return 'US';
    }



    public function index(Request $request)
    {
        $currency = session('currency', 'USD');

        try {
            $Testimonial = Testimonial::orderBy('id', 'desc')
                ->where(['iStatus' => 1, 'isDelete' => 0])
                ->get();

            $blogs = Blog::orderBy('blogId', 'desc')
                ->where(['iStatus' => 1, 'isDelete' => 0])
                ->take(3)
                ->get();

            $offers = Offer::orderBy('id', 'desc')
                ->take(1)
                ->where(['iStatus' => 1, 'isDelete' => 0])
                ->get();

            $ip = $request->ip();
            $countryCode = $this->getCountryCode($ip);

            $featuredProduct = Product::select(
                'products.id',
                'products.categoryId',
                'products.productname',
                'products.rate',
                'products.usd_rate',
                'products.cut_price',
                'products.usd_cut_price',
                'products.description',
                'products.slugname',
                // DB::raw('(SELECT strphoto FROM productphotos WHERE  productphotos.productid=products.id ORDER BY products.id  LIMIT 1) as photo'),
                DB::raw('(SELECT categories.slugname FROM categories WHERE  categories.id=products.categoryId ORDER BY products.id  LIMIT 1) as category_slug'),

                DB::raw('(SELECT strphoto FROM productphotos WHERE  productphotos.productid=products.id ORDER BY products.id LIMIT 1) as photo'),

                // ✅ Select full product attribute details from the same lowest-price row
                DB::raw('(SELECT pa.id
                FROM product_attributes pa
                WHERE pa.product_id = products.id
                ORDER BY CAST(pa.product_attribute_price AS DECIMAL(10,2)) ASC
                LIMIT 1) AS attribute_id'),

                DB::raw('(SELECT pa.product_attribute_qty
                FROM product_attributes pa
                WHERE pa.product_id = products.id
                ORDER BY CAST(pa.product_attribute_price AS DECIMAL(10,2)) ASC
                LIMIT 1) AS product_attribute_qty'),

                DB::raw('(SELECT a.name
                FROM attributes a
                JOIN product_attributes pa2 ON pa2.product_attribute_id = a.id
                WHERE pa2.product_id = products.id
                ORDER BY CAST(pa2.product_attribute_price AS DECIMAL(10,2)) ASC
                LIMIT 1) AS attribute_name'),

                DB::raw('(SELECT pa3.product_attribute_price
                FROM product_attributes pa3
                WHERE pa3.product_id = products.id
                ORDER BY CAST(pa3.product_attribute_price AS DECIMAL(10,2)) ASC
                LIMIT 1) AS product_attribute_price')
            )
                ->orderBy('id', 'desc')
                ->take(8)
                ->where(['products.iStatus' => 1, 'products.isDelete' => 0])
                ->get();
            // dd($featuredProduct);
            
             $category = Category::orderBy('id', 'desc')
                ->where(['iStatus' => 1, 'isDelete' => 0, 'id' => 1])
                ->first();

            return view('frontview.index', compact('Testimonial', 'blogs', 'featuredProduct', 'offers', 'countryCode', 'category'));
        } catch (\Throwable $th) {
            Log::error('Home Page Error: ' . $th->getMessage(), [
                'exception' => $th
            ]);
            return redirect()->back()->withInput()->with('error', 'Failed to load homepage. Please try again.');
        }
    }

    public function about(Request $request)
    {
        try {
            return view('frontview.about');
        } catch (\Throwable $th) {
            Log::error('About Page Error: ' . $th->getMessage(), [
                'exception' => $th
            ]);
            return redirect()->back()->withInput()->with('error', 'Failed to load about page.');
        }
    }

    public function blog(Request $request)
    {
        $seo = MetaData::where('id', '=', '2')->first();

        // Fetch active categories
        $categories = BlogCategory::orderBy('strCategoryName', 'asc')
            ->where(['iStatus' => 1, 'isDelete' => 0])
            ->get();

        // Base blog query
        $query = Blog::orderBy('blogId', 'desc')
            ->where(['iStatus' => 1, 'isDelete' => 0]);

        // Filter by category slug (if present)
        if ($request->has('category') && $request->category != 'all') {
            $category = BlogCategory::where('strSlug', $request->category)
                ->where(['iStatus' => 1, 'isDelete' => 0])
                ->first();

            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // ✅ Keep category filter in pagination links
        $blogs = $query->paginate(12)->appends(['category' => $request->category]);

        return view('frontview.blog', compact('seo', 'blogs', 'categories'));
    }

    public function blog_detail(Request $request, $id)
    {
        $Blog = Blog::orderBy('blogId', 'asc')
            ->where(['iStatus' => 1, 'isDelete' => 0, 'strSlug' => $id])
            ->first();

        $RecentBlog = Blog::orderBy('blogId', 'asc')
            ->where(['iStatus' => 1, 'isDelete' => 0])
            ->where('strSlug', '!=', $id)
            ->take(4)
            ->get();

        return view('frontview.blog_detail', compact('Blog', 'RecentBlog'));
    }

    public function product_list(Request $request, $categoryid)
    {

        try {
            $Category = Category::orderBy('id', 'desc')->where(['isDelete' => 0, 'slugname' => $categoryid])->first();

            if (!$Category) {
                abort(404); // better than redirect back for wrong slugs
            }

            $limit = $request->input('limit',  16);
            $filter = $request->input('filter');

            $ip = $request->ip();
            $countryCode = $this->getCountryCode($ip);

            $products = Product::select(
                'products.id',
                'products.categoryId',
                'products.productname',
                'products.description',
                'products.slugname',
                'products.rate',
                'products.usd_rate',
                'products.cut_price',
                'products.usd_cut_price',
                DB::raw('(SELECT strphoto FROM productphotos WHERE  productphotos.productid=products.id ORDER BY products.id LIMIT 1) as photo'),

                DB::raw('(SELECT categories.slugname FROM categories WHERE  categories.id=products.categoryId ORDER BY products.id  LIMIT 1) as category_slug'),

                // ✅ Select full product attribute details from the same lowest-price row
                DB::raw('(SELECT pa.id
              FROM product_attributes pa
              WHERE pa.product_id = products.id
              ORDER BY CAST(pa.product_attribute_price AS DECIMAL(10,2)) ASC
              LIMIT 1) AS attribute_id'),

                DB::raw('(SELECT pa.product_attribute_qty
              FROM product_attributes pa
              WHERE pa.product_id = products.id
              ORDER BY CAST(pa.product_attribute_price AS DECIMAL(10,2)) ASC
              LIMIT 1) AS product_attribute_qty'),

                DB::raw('(SELECT a.name
              FROM attributes a
              JOIN product_attributes pa2 ON pa2.product_attribute_id = a.id
              WHERE pa2.product_id = products.id
              ORDER BY CAST(pa2.product_attribute_price AS DECIMAL(10,2)) ASC
              LIMIT 1) AS attribute_name'),

                DB::raw('(SELECT pa3.product_attribute_price
              FROM product_attributes pa3
              WHERE pa3.product_id = products.id
              ORDER BY CAST(pa3.product_attribute_price AS DECIMAL(10,2)) ASC
              LIMIT 1) AS product_attribute_price')
            )
                ->join('categories', 'products.categoryId', '=', 'categories.id')
                ->where('categories.slugname', $categoryid)
                ->where(['products.iStatus' => 1, 'products.isDelete' => 0]);

            // ✅ Apply filter if provided
            if ($filter == 'bestsellers') {
                $products->where('products.isBestSeller', 1);
            } elseif ($filter == 'newarrivals') {
                $products->where('products.isNewArrival', 1);
            } elseif ($filter == 'giftboxes') {
                $products->where('products.isGiftBoxes', 1);
            } elseif ($filter == 'combopacks') {
                $products->where('products.isComboPacks', 1);
            }

            // Apply pagination with dynamic limit
            $products = $products->paginate($limit)->appends($request->all());
            // dd($products);

            return view('frontview.products', compact('products', 'Category', 'categoryid', 'countryCode', 'filter'));
        } catch (\Throwable $th) {
            if ($th instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                abort(404);
            }

            Log::error('Product List Page Error: ' . $th->getMessage(), [
                'slug' => $categoryid,
                'request' => $request->all(),
                'exception' => $th
            ]);

            return redirect()->back()->withInput()->with('error', 'Something went wrong while loading the product list.');
        }
    }

    public function product_detail(Request $request, $category_id = null, $product_id = null)
    {
        try {
            $ip = $request->ip();
            $countryCode = $this->getCountryCode($ip);

            $ProductDetail = Product::select(
                'products.id',
                'products.productname',
                'products.rate',
                'products.usd_rate',
                'products.cut_price',
                'products.usd_cut_price',
                'products.description',
                'products.categoryId',
                DB::raw('(SELECT strphoto FROM productphotos WHERE  productphotos.productid=products.id  LIMIT 1) as photo'),

                DB::raw('(
                        SELECT MIN(CAST(product_attribute_price AS DECIMAL(10,2)))
                        FROM product_attributes
                        WHERE product_attributes.product_id = products.id
                    ) AS product_attribute_price'),

                // ⬇ id of the cheapest attribute (ties broken by id)
                DB::raw('(
                        SELECT pa1.id
                        FROM product_attributes pa1
                        WHERE pa1.product_id = products.id
                        ORDER BY CAST(pa1.product_attribute_price AS DECIMAL(10,2)) ASC, pa1.id ASC
                        LIMIT 1
                    ) AS min_attr_id')
            )
                ->orderBy('products.id', 'DESC')
                ->where(['products.iStatus' => 1, 'products.isDelete' => 0, 'products.slugname' => $product_id])
                ->first();

            $Photos = "";
            if ($ProductDetail) {
                $Photos = Productphotos::where([
                    'productphotos.iStatus' => 1,
                    'productphotos.isDelete' => 0,
                    'productphotos.productid' => $ProductDetail->id
                ])
                    ->get();
            }

            $attributes = ProductAttributes::select(
                'product_attributes.*',
                'attributes.name as attribute_name'
            )
                ->leftJoin('attributes', 'product_attributes.product_attribute_id', '=', 'attributes.id')
                ->where('product_attributes.product_id', $ProductDetail->id)
                ->orderByRaw('CAST(product_attributes.product_attribute_qty AS UNSIGNED) desc')
                ->get();

            return view('frontview.productdetail', compact('ProductDetail', 'Photos', 'category_id', 'product_id', 'attributes', 'countryCode'));
        } catch (\Throwable $th) {
            Log::error('Product Detail Page Error: ' . $th->getMessage(), [
                'category_id' => $category_id,
                'product_id' => $product_id,
                'exception' => $th
            ]);
            return redirect()->back()->withInput()->with('error', 'Something went wrong while loading the product details.');
        }
    }

    public function contactus(Request $request)
    {
        try {
            return view('frontview.contact');
        } catch (\Throwable $th) {
            Log::error('Contact Page Load Error: ' . $th->getMessage(), [
                'exception' => $th
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to load contact page. Please try again.');
        }
    }

    public function contact_us_store(Request $request)
    {
        try {
        $request->validate(
            [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email',
                'subject' => 'required|string|max:255',
                'message' => 'required|string',
                'captcha' => 'required|captcha'
            ],
            [
                'captcha.captcha' => 'Invalid captcha code.'
            ]
        );

        $data = array(
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            "strIp" => $request->ip(),
            "created_at" => now()
        );
        Inquiry::create($data);

        $SendEmailDetails = DB::table('sendemaildetails')->where(['id' => 4])->first();
        //dd($SendEmailDetails);

        if ($SendEmailDetails) {
            $msg = [
                'FromMail' => $SendEmailDetails->strFromMail,
                'Title' => $SendEmailDetails->strTitle,
                'ToEmail' => $SendEmailDetails->ToMail,
                'Subject' => $SendEmailDetails->strSubject
            ];

            // ✅ Send email
          $mail =   Mail::send('emails.contactemail', ['data' => $data], function ($message) use ($msg) {
                $message->from($msg['FromMail'], $msg['Title']);
                $message->to($msg['ToEmail'])->subject($msg['Subject']);
            });
       
        }

        return redirect()->route('contactthankyou');
        } catch (\Throwable $th) {
            Log::error('Contact Form Submission Error: ' . $th->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $th
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong while submitting the form. Please try again later.');
        }
    }

    public function contactthankyou()
    {
        try {
            return view('frontview.contactthankyou');
        } catch (\Throwable $th) {
            Log::error('Contact Thank You Page Load Error: ' . $th->getMessage(), [
                'exception' => $th
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Unable to load the thank you page. Please try again.');
        }
    }

    public function refreshCaptcha()
    {
        return response()->json(['captcha' => captcha_img()]);
    }

    public function couponcodeapply(Request $request)
    {
        try {
            $session = Session::get('customer_id');

            $couponInput = $request->coupon ?? '';
            $totalAmount = $request->totalAmount ?? 0;

            Log::info("Attempting to apply coupon", [
                'coupon' => $couponInput,
                'total' => $totalAmount
            ]);

            $Offer = Offer::where([
                'iStatus' => 1,
                'isDelete' => 0,
                'offercode' => $request->coupon
            ])->first();

            if (!$Offer) {
                Log::warning("Invalid or inactive coupon: $couponInput");
                return redirect()->back()->with('couponnotfound', 'Invalid or inactive coupon.');
            }

            $today = date('Y-m-d');

            if ($couponInput !== $Offer->offercode) {
                return redirect()->back()->with('error', 'Coupon Code does not match.');
            }

            if ($totalAmount < $Offer->minvalue) {
                return redirect()->back()->with('error', 'Cart total is below the minimum coupon value.');
            }

            if (!($today >= $Offer->startdate && $today <= $Offer->enddate)) {
                return redirect()->back()->with('error', 'Coupon is expired!');
            }

            $discount = number_format(($totalAmount * $Offer->percentage) / 100, 2);

            CustomerCouponApplyed::create([
                'offerId' => $Offer->id ?? 0,
                'customerId' => $session ?? 0,
                'result' => $discount ?? 0,
                'created_at' => now(),
                "strIP" => $request->ip()
            ]);

            Session::put('discount', $discount);
            Session::put('applied_coupon_code', $couponInput);

            Log::info("Coupon applied successfully", [
                'customerId' => $session,
                'discount' => $discount,
                'coupon' => $couponInput
            ]);

            return back()->with([
                'success' => 'Coupon Code Applied Successfully!',
            ]);
        } catch (\Throwable $e) {
            Log::error('Coupon Apply Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Something went wrong while applying the coupon.');
        }
    }

    public function removeCoupon(Request $request)
    {
        try {
            Session::forget('discount');
            Session::forget('applied_coupon_code');

            return redirect()->back()->with('success', 'Coupon removed successfully!');
        } catch (\Throwable $e) {
            Log::error('Remove Coupon Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Something went wrong while removing the coupon.');
        }
    }

    public function checkout(Request $request)
    {
        try {
            $Coupon = $request->session()->get('data');
            $cartItems = \Cart::getContent();

            if ($cartItems->isEmpty()) {
                return redirect()->route('front.index')->with('error', 'Your cart is empty!');
            }

            $ip = $request->ip();
            $countryCode = $this->getCountryCode($ip);

            $Shipping = Shipping::orderBy('id', 'desc')->first();
            $State = State::orderBy('stateName', 'asc')->get();
            $countries = Country::orderBy('countryName', 'asc')->get();

            return view('frontview.checkout', compact('Shipping', 'Coupon', 'State', 'countries', 'countryCode'));
        } catch (\Throwable $th) {
            Log::error('Checkout View Error', [
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ]);
            return redirect()->back()->with('error', 'Failed to load checkout page. Please try again.');
        }
    }

    public function get_userdata(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'phone' => 'required|digits:10', // Ensure phone field is required and has exactly 10 digits
            ]);

            $Data = Customer::where(['customermobile' => $request->phone])->first();

            return response()->json($Data);
        } catch (\Throwable $th) {
            Log::error('Check Mobile Error: ' . $th->getMessage());
            return response()->json(['error' => 'Failed to check mobile.'], 500);
        }
    }

    public function checkoutstore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'billFirstName' => 'required|string|max:255',
            'billLastName' => 'required|string|max:255',
            'billPhone' => [
                'required',
                'digits:10',
                // Rule::unique('customer', 'customermobile')->where(fn($q) => $q->where('isDelete', 0)),
                // Rule::unique('customer', 'customermobile')->where(function ($q) use ($request) {
                //     return $q->where('isDelete', 0)
                //         ->where('country', $request->country);
                // }),
            ],
            'billEmail' => [
                'nullable',
                'email',
                // Rule::unique('customer', 'customeremail')->where(fn($q) => $q->where('isDelete', 0)),
            ],
            'billStreetAddress1' => 'required|string|max:255',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'pincode' => 'required|digits_between:5,10',
            'country' => 'required|string|max:100',
        ]
        // [
        //     'billPhone.unique' => 'This phone number is already registered.',
        //     'billEmail.unique' => 'This email address is already registered.',
        // ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $cartItems = \Cart::getContent();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        DB::beginTransaction();

        $subtotal = \Cart::getSubTotal();
        $discount = Session::get('discount', 0);
        $amount = $subtotal - $discount;

        // Check if existing customer matches both email and phone
        $existingCustomer = Customer::where([
            'isDelete' => 0,
            'iStatus' => 1,
            'customermobile' => $request->billPhone,
            'customeremail' => $request->billEmail
        ])->first();

        $uniqueNumber = Str::uuid();

        if (!$existingCustomer) {

            $customer = Customer::create([
                'firstname' => $request->billFirstName,
                'lastname' =>  $request->billLastName,
                'customername' => $request->billFirstName . ' ' . $request->billLastName,
                'guid' => $uniqueNumber,
                'customermobile' => $request->billPhone,
                'customeremail' => $request->billEmail,
                'address' => $request->billStreetAddress1,
                'address1' => $request->billStreetAddress2,
                'state' => $request->state,
                'city' => $request->city,
                'pincode' => $request->pincode,
                'country' => $request->country ?? 0,
                'created_at' => now(),
                'strIP' => $request->ip()
            ]);
            $customerid = $customer->customerid;
        } else {

            $existingCustomer->update([
                'firstname' => $request->billFirstName,
                'lastname' =>  $request->billLastName,
                'customername' => $request->billFirstName . ' ' . $request->billLastName,
                'customermobile' => $request->billPhone,
                'customeremail' => $request->billEmail,
                'address' => $request->billStreetAddress1,
                'address1' => $request->billStreetAddress2,
                'state' => $request->state,
                'city' => $request->city,
                'pincode' => $request->pincode,
                'country' => $request->country ?? 0,
                'updated_at' => now(),
                'strIP' => $request->ip()
            ]);
            $customerid = $existingCustomer->customerid;
        }

        $ip = $request->ip();
        $countryCode = $this->getCountryCode($ip);

        if ($countryCode === 'IN') {
            $symbol = '₹';
        } else {
            $symbol = '$';
        }

        $order = Order::create([
            'customerid' => $customerid ?? 0,
            'shipping_cutomerName' => $request->billFirstName . ' ' . $request->billLastName,
            'shipping_mobile' => $request->billPhone,
            'shipping_email' => $request->billEmail,
            'shiiping_address1' => $request->billStreetAddress1,
            'shiiping_address2' => $request->billStreetAddress2,
            'shipping_city' => $request->city,
            'shiiping_state' => $request->state,
            'shipping_pincode' => $request->pincode,
            'country' => $request->country,
            'amount' => $subtotal,
            'currency' => $symbol,
            'discount' => $discount,
            'netAmount' => $amount,
            'created_at' => now(),
            'strIP' => $request->ip()
        ]);

        foreach ($cartItems as $cartItem) {
            OrderDetail::create([
                'orderID' => $order->id,
                'customerid' => $customerid,
                'categoryId' => $cartItem->categoryId,
                'productId' => $cartItem->productid,
                'quantity' => $cartItem->quantity,
                'size' => $cartItem->id,
                'rate' => $cartItem->price,
                'amount' => $cartItem->price * $cartItem->quantity,
                'currency' => $symbol,
                'created_at' => now(),
                "strIP" => $request->ip()
            ]);
        }

        $ip = $request->ip();
        $countryCode = $this->getCountryCode($ip);

        $api = new Api(config('app.razorpay_key'), config('app.razorpay_secret'));

        $currency = $countryCode == 'IN' ? 'INR' : 'USD';
        $razorpayOrder = $api->order->create([
            'receipt' => $order->id . '-' . date('YmdHis'),
            'amount' => $amount * 100,
            'currency' => $currency,
        ]);

        Payment::create([
            'order_id' => $razorpayOrder['id'],
            'oid' => $order->id,
            'amount' => $amount,
            'currency' => $currency,
            'receipt' => $razorpayOrder['receipt'],
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'razorpay_order_id' => $razorpayOrder['id'],
            'amount' => $amount,
            'currency' => $currency,
            'email' => $request->billEmail,
            'mobile' => $request->billPhone,
            'customer_name' => $request->billFirstName . ' ' . $request->billLastName,
            'order_id' => $order->id
        ]);
    }


    public function frontlogin(Request $request)
    {
        try {
            return view('frontview.login');
        } catch (\Throwable $th) {
            Log::error('Front Login Page Error: ' . $th->getMessage());
            return redirect()->route('FrontIndex')->with('error', 'Failed to load login page.');
        }
    }

    public function frontloginstore(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {

            $customer = Customer::where('customeremail', $request->email)->first();

            if (!$customer) {
                return back()->with('error', 'Email not registered. Please sign up first.');
            }

            // Generate 6-digit OTP
            $otp = rand(1000, 9999);

            // Expiry time (5 minutes from now)
            $expiresAt = Carbon::now()->addMinutes(5);

            // Update customer table with OTP and expiry
            $customer->update([
                'otp' => $otp,
                'otp_expires_at' => $expiresAt,
            ]);

            // Store email in session for OTP verification
            session(['front_login_email' => $request->email]);

            $SendEmailDetails = DB::table('sendemaildetails')->where(['id' => 8])->first();

            if ($SendEmailDetails) {
                $msg = [
                    'FromMail' => $SendEmailDetails->strFromMail,
                    'Title' => $SendEmailDetails->strTitle,
                    'ToEmail' => $request->email,
                    'Subject' => $SendEmailDetails->strSubject
                ];

                $data = [
                    'name' => $customer->customername ?? 'User',
                    'email' => $request->email,
                    'otp' => $otp,
                    'title' => $SendEmailDetails->strTitle ?? 'Login Verification',
                    'subject' => $SendEmailDetails->strSubject ?? 'Your Login OTP',
                    'company_name' => 'Oroveda'
                ];

                // ✅ Send email
                Mail::send('emails.login_verification', ['data' => $data], function ($message) use ($msg) {
                    $message->from($msg['FromMail'], $msg['Title']);
                    $message->to($msg['ToEmail'])->subject($msg['Subject']);
                });
            } else {
                Log::warning('SendEmailDetails with ID 8 not found.');
            }

            return redirect()->route('front.otp')->with('success', 'OTP has been sent to your email. It will expire in 5 minutes.');
        } catch (\Throwable $th) {
            Log::error('Front Login Error: ' . $th->getMessage(), [
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ]);

            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function otp(Request $request)
    {
        try {
            return view('frontview.otp');
        } catch (\Throwable $th) {
            Log::error('Front Login Page Error: ' . $th->getMessage());
            return redirect()->route('FrontIndex')->with('error', 'Failed to load login page.');
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric'
        ]);

        $customer = Customer::where('customeremail', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$customer) {
            return back()->with('error', 'Invalid OTP.');
        }

        // Check expiry
        if (Carbon::now()->greaterThan($customer->otp_expires_at)) {
            return back()->with('error', 'OTP expired. Please request a new one.');
        }

        // ✅ OTP valid — you can log them in or mark verified
        $customer->update([
            'otp' => null,
            'otp_expires_at' => null
        ]);

        // Example: store session or redirect to dashboard
        session(['customer_id' => $customer->customerid]);

        return redirect()->route('front.index')->with('success', 'Login successful!');
    }

    public function resendOtp()
    {
        $email = session('front_login_email');
        if (!$email) {
            return redirect()->route('front.login')->with('error', 'Session expired. Please login again.');
        }

        // Reuse your OTP sending logic here
        return $this->frontloginstore(new Request(['email' => $email]));
    }

    public function profile(Request $request)
    {
        try {
            if (Session::get('customer_id')) {
                $id = Session::get('customer_id');
                $customer = Customer::where('customerid', $id)->first();
                $Country = Country::where('id', $customer->country)->first();

                return view('frontview.after_login.profile', compact('customer', 'Country'));
            } else {
                return redirect()->route('front.login')->with('error', 'Please login to access your profile.');
            }
        } catch (\Throwable $th) {
            Log::error('Profile Page Error: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function myaccount(Request $request)
    {
        try {
            if (Session::get('customer_id')) {
                return view('frontview.after_login.orders');
            } else {
                return redirect()->route('front.login')->with('error', 'Please login to access your account.');
            }
        } catch (\Throwable $th) {
            Log::error('My Account Page Error: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function myaccountedit(Request $request)
    {
        try {
            $session = Session::get('customer_id');

            if (!$session) {
                return redirect()->route('front.login')->with('error', 'Session expired. Please login again.');
            }

            Session::forget(['customername', 'customeremail', 'customermobile']);

            $update = DB::table('customer')
                ->where(['iStatus' => 1, 'isDelete' => 0, 'customerid' => $session])
                ->update([
                    'customername' => $request->customername,
                    'customeremail' => $request->customeremail,
                    'customermobile' => $request->customermobile,
                    'updated_at' => now()
                ]);

            Session::put('customername', $request->customername);
            Session::put('customeremail', $request->customeremail);
            Session::put('customermobile', $request->customermobile);

            return back()->with('myaccountupdatesuccess', 'Profile Updated Successfully!');
        } catch (\Throwable $th) {
            Log::error('My Account Update Error: ' . $th->getMessage(), [
                'input' => $request->all(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ]);
            return redirect()->back()->withInput()->with('error', 'Something went wrong while updating your account.');
        }
    }

    public function myorders(Request $request)
    {
        try {
            $customerId = Session::get('customer_id');

            if (!$customerId) {
                return redirect()->route('front.login')->with('error', 'Please login to view your orders.');
            }

            $orderItems  = OrderDetail::select(
                'order.order_id as order_id_in_order',
                'order.isPayment',
                'orderdetail.*',
                'products.id',
                'products.productname',
                'products.rate',
                DB::raw('(SELECT strphoto FROM productphotos WHERE  productphotos.productid=products.id ORDER BY products.id  LIMIT 1) as photo'),
                'product_attributes.product_attribute_qty',
                'attributes.name',
            )
                ->where([
                    'orderdetail.iStatus' => 1,
                    'orderdetail.isDelete' => 0,
                    'orderdetail.customerid' => $customerId
                ])
                ->leftjoin('products', 'orderdetail.productId', '=', 'products.id')
                ->leftjoin('order', 'orderdetail.orderID', '=', 'order.order_id')
                ->leftJoin('product_attributes', 'orderdetail.size', '=', 'product_attributes.id')
                ->leftJoin('attributes', 'product_attributes.product_attribute_id', '=', 'attributes.id')
                ->orderBy('orderdetail.orderID', 'desc')
                ->paginate(10);

            $groupedOrders = $orderItems->groupBy('orderID');

            $orders = $groupedOrders->map(function ($items, $orderId) {
                return (object)[
                    'order_id' => $orderId,
                    'items' => $items,
                    'isPayment' => $items->first()->isPayment,
                ];
            });

            return view('frontview.after_login.orders', compact('orders'));
        } catch (\Throwable $th) {
            Log::error('My Orders Error: ' . $th->getMessage(), ['line' => $th->getLine()]);
            return redirect()->back()->with('error', 'Unable to load orders. Please try again later.');
        }
    }

    public function myordersdetails(Request $request, $id)
    {
        try {
            $session = Session::get('customer_id');

            if (!$session) {
                return redirect()->route('front.login')->with('error', 'Please login to view order details.');
            }

            $Order = OrderDetail::select(
                'orderdetail.orderID',
                'orderdetail.created_at',
                'orderdetail.quantity',
                'orderdetail.weight',
                'orderdetail.rate',
                'orderdetail.amount',
                'product.productname',
                DB::raw('(SELECT product_attribute_size FROM product_attributes WHERE  product_attributes.id=orderdetail.size  LIMIT 1) as size'),
                DB::raw('(SELECT strphoto FROM productphotos WHERE  productphotos.productid=product.productId  LIMIT 1) as photo')
            )
                ->join('product', 'orderdetail.productId', '=', 'product.productId')
                ->where(['orderdetail.iStatus' => 1, 'orderdetail.isDelete' => 0, 'orderdetail.customerid' => $session, 'orderdetail.orderID' => $id])
                ->get();

            return response()->json($Order);
        } catch (\Throwable $th) {
            Log::error('Order Details Error: ' . $th->getMessage(), ['line' => $th->getLine()]);
            return redirect()->back()->with('error', 'Unable to load order details.');
        }
    }

    public function mywish_list(Request $request)
    {
        try {

            $session = Session::get('customer_id');

            if (!$session) {
                return redirect()->route('front.login')->with('error', 'Please login to access wishlist.');
            }

            $wishlist = wishlist::select(
                'wishlist.id as wishlist_id',
                'products.id',
                'products.productname',
                'products.slugname',
                'products.rate',
                DB::raw('(SELECT strphoto FROM productphotos WHERE  productphotos.productid=products.id ORDER BY products.id LIMIT 1) as photo'),
                'product_attributes.product_attribute_qty',
                'attributes.name',
            )
                ->join("products", "wishlist.productid", '=', 'products.id')
                ->leftJoin('product_attributes', 'wishlist.attribute_id', '=', 'product_attributes.id')
                ->leftJoin('attributes', 'product_attributes.product_attribute_id', '=', 'attributes.id')
                ->where(['wishlist.iStatus' => 1, 'wishlist.isDelete' => 0, 'wishlist.customerid' => $session])

                ->orderBY('id', 'desc')
                ->get();

            return view('frontview.after_login.wishlist', compact('wishlist'));
        } catch (\Throwable $th) {
            Log::error('My Wishlist Error: ' . $th->getMessage(), ['line' => $th->getLine()]);
            return redirect()->back()->with('error', 'Unable to load wishlist.');
        }
    }

    public function wishlist_store(Request $request)
    {
        try {
            $session = Session::get('customer_id');

            if (!$session) {
                return redirect()->route('front.login')->with('error', 'Please login to use wishlist.');
            }

            $wishlist = Wishlist::where([
                'wishlist.iStatus' => 1,
                'wishlist.isDelete' => 0,
                'wishlist.customerid' => $session,
                'productid' => $request->productid
            ])
                ->count();

            if ($wishlist == 0) {
                Wishlist::create([
                    "customerid" => $session,
                    "productid" => $request->productid,
                    "attribute_id" => $request->attribute_id,
                    "price" => $request->price,
                    'created_at' => now(),
                    'strIP' => $request->ip()
                ]);

                return back()->with('success', 'Product Added To Wishlist!');
            } else {
                return back()->with('error', 'Product is already in your wishlist.');
            }
        } catch (\Throwable $th) {
            Log::error('Wishlist Store Error: ' . $th->getMessage(), ['line' => $th->getLine()]);
            return redirect()->back()->with('error', 'Unable to update wishlist.');
        }
    }

    public function wishlist_delete(Request $request)
    {
        try {
            $wishlist = Wishlist::find($request->id);

            if (!$wishlist) {
                return back()->with('error', 'Item not found in wishlist.');
            }

            $wishlist->delete();

            return back()->with('success', 'Item removed from wishlist successfully.');
        } catch (\Throwable $th) {
            Log::error('Wishlist Delete Error: ' . $th->getMessage(), [
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }


    public function CancellationandRefund()
    {
        try {
            $datas = OtherPages::where(['iStatus' => 1, 'isDelete' => 0, 'id' => 2])->first();
            return view('frontview.CancellationandRefund', compact('datas'));
        } catch (\Throwable $th) {
            Log::error('Cancellation and Refund Error: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function cms_pages($slugname)
    {
        try {
            $datas = OtherPages::where(['iStatus' => 1, 'isDelete' => 0, 'slugname' => $slugname])->first();

            return view('frontview.cms_pages', compact('datas'));
        } catch (\Throwable $th) {
            Log::error('CMS Page Error: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Page not found or unavailable.');
        }
    }

    public function Frontlogout(Request $request)
    {
        try {
            $request->session()->forget(['customer_id']);

            return redirect()->route('front.index');
        } catch (\Throwable $th) {
            Log::error('Logout Error: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Failed to logout.');
        }
    }



    public function customerorder(Request $request, $ORDER_ID, $guid)
    {
        try {
            $Total = Order::where(['isDelete' => 0, 'iStatus' => 1, 'order_id' => $ORDER_ID])->first();

            if ($Total) {
                // dd('if');
                $Customer = Customer::where(['isDelete' => 0, 'iStatus' => 1, 'guid' => $guid])->first();
                //   dd($Customer);
                $Order = OrderDetail::select(
                    'product.productname',
                    'orderdetail.quantity',
                    'orderdetail.productId',
                    'orderdetail.customerid',
                    'orderdetail.orderID',
                    'orderdetail.rate',
                    'orderdetail.size',
                    DB::raw('(SELECT strphoto FROM productphotos WHERE  productphotos.productid=product.productId ORDER BY product.productId  LIMIT 1) as photo'),

                )
                    ->where(['orderdetail.isDelete' => 0, 'orderdetail.iStatus' => 1, 'orderdetail.orderID' => $ORDER_ID, 'orderdetail.customerid' => $Customer->customerid])
                    ->leftjoin('product', 'orderdetail.productId', '=', 'product.productId')
                    ->get();
                //  dd($Order);
                // if(isset($Order) && $Order != "" && $Order != null && $Order != []){
                return view('frontview.customerorder', compact('Customer', 'Order', 'Total'));
            } else {
                // dd('else');
                return redirect()->route('ordernotavailable');
            }
        } catch (\Throwable $th) {
            Log::error('Customer Order Error: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Unable to retrieve order.');
        }
    }
}
