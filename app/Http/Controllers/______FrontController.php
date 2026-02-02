<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetMail;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Customer;
use App\Models\CustomerCouponApplyed;
use App\Models\Gallery;
use App\Models\Offer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductAttributes;
use App\Models\Productphotos;
use App\Models\Testimonial;
use App\Models\Shipping;
use App\Models\Setting;
use App\Models\State;
use App\Models\Wishlist;
use App\Models\OtherPages;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Inquiry;
use App\Models\Ledger;
use App\Models\MetaData;
use App\Models\Payment;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

class FrontController extends Controller
{
    private function getCountryCode($ip)
    {
        // Use ipapi.co for free location lookup
        $response = @file_get_contents("https://ipapi.co/{$ip}/json/");
        if ($response) {
            $data = json_decode($response, true);
            if (!empty($data['country'])) {
                return $data['country']; // returns 'IN', 'US', etc.
            }
        }
        // Default fallback
        return 'US';
    }

    public function old_index(Request $request)
    {
        $currency = session('currency', 'USD');

        try {
            $Banner = Banner::orderBy('banner.bannerId', 'desc')
                ->where(['banner.iStatus' => 1, 'banner.isDelete' => 0])
                ->get();

            $Video = Video::orderBy('id', 'desc')
                ->where(['iStatus' => 1, 'isDelete' => 0])
                ->first();

            $offers = Offer::orderBy('id', 'desc')
                ->take(1)
                ->where(['iStatus' => 1, 'isDelete' => 0])
                ->get();

            $featuredProduct = Product::select(
                'products.id',
                'products.categoryId',
                'products.productname',
                'products.rate',
                'products.cut_price',
                'products.weight',
                'products.description',
                'products.isStock',
                'products.slugname',
                DB::raw('(SELECT strphoto FROM productphotos WHERE  productphotos.productid=products.id ORDER BY products.id  LIMIT 1) as photo'),
                DB::raw('(SELECT categories.slugname FROM categories WHERE  categories.id=products.categoryId ORDER BY products.id  LIMIT 1) as category_slug')
            )
                ->orderBy('id', 'desc')
                ->where(['products.iStatus' => 1, 'products.isDelete' => 0, 'products.isFeatures' => 1])
                ->get();

            $recentproducts = Product::select(
                'products.id',
                'products.categoryId',
                'products.productname',
                'products.rate',
                'products.cut_price',
                'products.usd_rate',
                'products.usd_cut_price',

                'products.weight',
                'products.description',
                'products.isStock',
                'products.slugname',
                DB::raw('(SELECT strphoto FROM productphotos WHERE productphotos.productid=products.id ORDER BY products.id LIMIT 1) as photo'),
                DB::raw('(SELECT categories.slugname FROM categories WHERE  categories.id=products.categoryId ORDER BY products.id  LIMIT 1) as category_slug'),
                DB::raw('(
                        SELECT MIN(CAST(product_attribute_price AS DECIMAL(10,2)))
                        FROM product_attributes
                        WHERE product_attributes.product_id = products.id
                    ) AS min_attr_price')
            )
                ->orderBy('id', 'desc')
                ->take(8)
                ->where(['products.iStatus' => 1, 'products.isDelete' => 0, 'products.isFeatures' => 0])
                ->get();

            return view('frontview.index', compact('Banner', 'featuredProduct', 'recentproducts', 'offers', 'Video'));
        } catch (\Throwable $th) {
            Log::error('Home Page Error: ' . $th->getMessage(), [
                'exception' => $th
            ]);
            return redirect()->back()->withInput()->with('error', 'Failed to load homepage. Please try again.');
        }
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
            // dd($Testimonial);

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
                'products.cut_price',
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

            return view('frontview.index', compact('Testimonial', 'blogs', 'featuredProduct', 'offers', 'countryCode'));
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
        $blogs = Blog::orderBy('blogId', 'asc')
            ->where(['iStatus' => 1, 'isDelete' => 0])
            ->paginate(12);

        return view('frontview.blog', compact('seo', 'blogs'));
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
                return redirect()->back()->with('error', 'Category not found.');
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
            Log::error('Product List Page Error: ' . $th->getMessage(), [
                'categoryid' => $categoryid,
                'request' => $request->all(),
                'exception' => $th
            ]);
            return redirect()->back()->withInput()->with('error', 'Something went wrong while loading the product list.');
        }
    }

    public function product_detail(Request $request, $category_id = null, $product_id = null)
    {
        try {
            $ProductDetail = Product::select(
                'products.id',
                'products.productname',
                'products.rate',
                'products.weight',
                'products.description',
                'products.isStock',
                'products.categoryId',
                'products.isFeatures',
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

            $Category = Category::where(['slugname' => $category_id])->first();

            $Photos = "";
            if ($ProductDetail) {
                $Photos = Productphotos::where([
                    'productphotos.iStatus' => 1,
                    'productphotos.isDelete' => 0,
                    'productphotos.productid' => $ProductDetail->id
                ])
                    ->get();
            }

            $RelatedProduct = Product::select(
                'products.id',
                'products.productname',
                'products.rate',
                'products.cut_price',
                'products.description',
                'products.isStock',
                'products.slugname',
                'products.categoryId',
                'products.isFeatures',
                DB::raw('(SELECT strphoto FROM productphotos WHERE  productphotos.productid=products.id ORDER BY products.id  LIMIT 1) as photo'),
                DB::raw('(SELECT strphoto FROM productphotos WHERE  productphotos.productid=products.id ORDER BY products.id LIMIT 1,1) as backphoto'),
                DB::raw('(SELECT categories.id FROM categories inner join multiplecategory on categories.id=multiplecategory.categoryid where multiplecategory.productid=products.id ORDER BY products.id LIMIT 1) as categoryId')
            )
                ->orderBy('id', 'DESC')
                ->take(4)
                ->where([
                    'products.iStatus' => 1,
                    'products.isDelete' => 0,
                    'products.categoryId' => $Category->id,
                ])
                ->where('products.slugname', '!=', $product_id)
                ->get();

            $attributes = ProductAttributes::select(
                'product_attributes.*',
                'attributes.name as attribute_name'
            )
                ->leftJoin('attributes', 'product_attributes.product_attribute_id', '=', 'attributes.id')
                ->where('product_attributes.product_id', $ProductDetail->id)
                ->orderByRaw('CAST(product_attributes.product_attribute_qty AS UNSIGNED) desc')
                ->get();

            return view('frontview.productdetail', compact('ProductDetail', 'Photos',  'Category', 'category_id', 'product_id', 'RelatedProduct', 'attributes'));
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
        // try {
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

        if ($SendEmailDetails) {
            $msg = [
                'FromMail' => $SendEmailDetails->strFromMail,
                'Title' => $SendEmailDetails->strTitle,
                'ToEmail' => $SendEmailDetails->ToMail,
                'Subject' => $SendEmailDetails->strSubject
            ];

            // ✅ Send email
            Mail::send('emails.contactemail', ['data' => $data], function ($message) use ($msg) {
                $message->from($msg['FromMail'], $msg['Title']);
                $message->to($msg['ToEmail'])->subject($msg['Subject']);
            });
        }

        return redirect()->route('contactthankyou');
        // } catch (\Throwable $th) {
        //     Log::error('Contact Form Submission Error: ' . $th->getMessage(), [
        //         'request_data' => $request->all(),
        //         'exception' => $th
        //     ]);

        //     return redirect()->back()
        //         ->withInput()
        //         ->with('error', 'Something went wrong while submitting the form. Please try again later.');
        // }
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

    public function checkout(Request $request)
    {
        try {
            $Coupon = $request->session()->get('data');
            $session = Session::get('customerid');
            $cartItems = \Cart::getContent();

            if ($cartItems->isEmpty()) {
                return redirect()->route('front.index')->with('error', 'Your cart is empty!');
            }

            $Shipping = Shipping::orderBy('id', 'desc')->first();
            $State = State::orderBy('stateName', 'asc')->get();

            return view('frontview.checkout', compact('Shipping', 'Coupon', 'State'));
        } catch (\Throwable $th) {
            Log::error('Checkout View Error', [
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ]);
            return redirect()->back()->with('error', 'Failed to load checkout page. Please try again.');
        }
    }

    public function couponcodeapply(Request $request)
    {
        try {
            $session = Session::get('customerid');
            $couponInput = $request->coupon ?? '';
            $totalAmount = $request->totalAmount ?? 0;

            Log::info("Attempting to apply coupon", [
                'coupon' => $couponInput,
                'customer' => $session,
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

            $alreadyApplied = CustomerCouponApplyed::where([
                'customerId' => $session,
                'offerId' => $Offer->id
            ])->count();

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

            $discount = round(($totalAmount * $Offer->percentage) / 100);

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


    public function checkoutstore(Request $request)
    {
        $request->validate([
            'billFirstName' => 'required|string|max:255',
            'billLastName' => 'required|string|max:255',
            'billPhone' => 'required|digits:10',
            'billEmail' => 'nullable|email',
            'billStreetAddress1' => 'required|string|max:255',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'pincode' => 'required|digits_between:5,10',
            'country' => 'required|string|max:100',
        ]);

        $cartItems = \Cart::getContent();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        DB::beginTransaction();
        // try {
        $subtotal = \Cart::getSubTotal();
        $discount = Session::get('discount', 0);
        $amount = $subtotal - $discount;

        $existingCustomer = Customer::where([
            'isDelete' => 0,
            'iStatus' => 1,
            'customermobile' => $request->billPhone
        ])->first();

        $customerid = $existingCustomer ? $existingCustomer->customerid : 0;
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
                'state' => $request->billState,
                'city' => $request->shipping_city,
                'pincode' => $request->billPinCode,
                'country' => $request->strCountry,
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
                'state' => $request->billState,
                'city' => $request->shipping_city,
                'pincode' => $request->billPinCode,
                'country' => $request->strCountry,
                'updated_at' => now(),
                'strIP' => $request->ip()
            ]);
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
                'created_at' => now(),
                "strIP" => $request->ip()
            ]);
        }

        $api = new Api(config('app.razorpay_key'), config('app.razorpay_secret'));
        $razorpayOrder = $api->order->create([
            'receipt' => $order->id . '-' . date('YmdHis'),
            'amount' => $amount * 100,
            'currency' => 'INR',
        ]);

        Payment::create([
            'order_id' => $razorpayOrder['id'],
            'oid' => $order->id,
            'amount' => $amount,
            'currency' => 'INR',
            'receipt' => $razorpayOrder['receipt'],
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'razorpay_order_id' => $razorpayOrder['id'],
            'amount' => $amount,
            'email' => $request->billEmail,
            'mobile' => $request->billPhone,
            'customer_name' => $request->billFirstName . ' ' . $request->billLastName,
            'order_id' => $order->id
        ]);
        // } catch (\Throwable $th) {
        //     DB::rollBack();

        //     Log::error('Checkout Store Error', [
        //         'message' => $th->getMessage(),
        //         'line' => $th->getLine(),
        //         'file' => $th->getFile(),
        //         'input' => $request->all()
        //     ]);

        //     return redirect()->back()->with('error', 'Something went wrong while processing your order.');
        // }
    }

    public function payment_success()
    {
        try {
            return view('frontview.payment_success');
        } catch (\Throwable $th) {
            Log::error('Payment Success Page Error: ' . $th->getMessage());
            return redirect()->route('FrontIndex')->with('error', 'Failed to load success page.');
        }
    }

    public function payment_fail()
    {
        try {
            return view('frontview.payment_fail');
        } catch (\Throwable $th) {
            Log::error('Payment Fail Page Error: ' . $th->getMessage());
            return redirect()->route('FrontIndex')->with('error', 'Failed to load failure page.');
        }
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
        // dd($customer);

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
            'otp_expires_at' => null,
        ]);

        // Example: store session or redirect to dashboard
        session(['customer_id' => $customer->customerid]);

        return redirect()->route('front.dashboard')->with('success', 'Login successful!');
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
            if (Session::get('customerid')) {
                return view('frontview.profile');
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
            if (Session::get('customerid')) {
                return view('frontview.myaccount');
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
            $session = Session::get('customerid');

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
            $customerId = Session::get('customerid');

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

            return view('frontview.after_login.myorders', compact('orders'));
        } catch (\Throwable $th) {
            Log::error('My Orders Error: ' . $th->getMessage(), ['line' => $th->getLine()]);
            return redirect()->back()->with('error', 'Unable to load orders. Please try again later.');
        }
    }

    public function myordersdetails(Request $request, $id)
    {
        try {
            $session = Session::get('customerid');

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

            $session = Session::get('customerid');

            if (!$session) {
                return redirect()->route('front.login')->with('error', 'Please login to access wishlist.');
            }

            $wishlist = wishlist::select(
                'products.id',
                'products.productname',
                'products.slugname',
                'products.rate',
                DB::raw('(SELECT strphoto FROM productphotos WHERE  productphotos.productid=products.id ORDER BY products.id  LIMIT 1) as photo')
            )
                ->join("products", "wishlist.productid", '=', 'products.id')
                ->where(['wishlist.iStatus' => 1, 'wishlist.isDelete' => 0, 'wishlist.customerid' => $session])
                ->orderBY('id', 'desc')
                ->get();

            return view('frontview.after_login.mywishlist', compact('wishlist'));
        } catch (\Throwable $th) {
            Log::error('My Wishlist Error: ' . $th->getMessage(), ['line' => $th->getLine()]);
            return redirect()->back()->with('error', 'Unable to load wishlist.');
        }
    }

    public function wishlist_store(Request $request)
    {
        try {
            $session = Session::get('customerid');

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
            $request->session()->forget([
                'customerid',
                'customername',
                'customermobile',
                'customeremail'
            ]);

            return redirect()->route('FrontIndex');
        } catch (\Throwable $th) {
            Log::error('Logout Error: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Failed to logout.');
        }
    }

    public function checkmobile(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'phone' => 'required|digits:10', // Ensure phone field is required and has exactly 10 digits
            ]);

            $Data = Customer::orderBy('customerid', 'DESC')
                ->where(['customermobile' => $request->phone])
                ->first();

            return response()->json($Data);
        } catch (\Throwable $th) {
            Log::error('Check Mobile Error: ' . $th->getMessage());
            return response()->json(['error' => 'Failed to check mobile.'], 500);
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
