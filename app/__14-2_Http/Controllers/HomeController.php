<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Attributes;
use App\Models\Offer;
use App\Models\Inquiry;
use App\Models\Courier;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $Today = date('Y-m-d');
        // $Category = Category::orderBy('categoryId', 'DESC')->where(['iStatus' => 1, 'isDelete' => 0])->count();
        // $Attribute = Attributes::orderBy('id', 'DESC')->count();
        // $Product = Product::orderBy('productId', 'DESC')->where(['iStatus' => 1, 'isDelete' => 0])->count();
        // $Banner = Banner::orderBy('id', 'DESC')->where(['iStatus' => 1, 'isDelete' => 0])->count();
        // $Courier = Courier::orderBy('id', 'DESC')->where(['iStatus' => 1, 'isDelete' => 0])->count();
        // $Offer = Offer::orderBy('id', 'DESC')->where(['iStatus' => 1, 'isDelete' => 0])->count();

        // $TodaysOrder = Order::orderBy('order_id', 'DESC')
        //     ->where(DB::raw('DATE(created_at)'), $Today) // Filter by date
        //     // ->where(['iStatus' => 1, 'isDelete' => 0 ,'created_at'=> $Today])
        //     ->count();
        // $PendingOrder = Order::orderBy('id', 'DESC')
        //     ->where(['iStatus' => 1, 'isDelete' => 0, 'isDispatched' => 0, 'dispatchCourierId' => 0, 'isPayment' => 1])
        //     ->count();
        // $PendingOrderTirupati = Order::orderBy('id', 'DESC')
        //     ->where(['iStatus' => 1, 'isDelete' => 0, 'isDispatched' => 0, 'dispatchCourierId' => 1])
        //     ->count();
        // $PendingOrderDelivery = Order::orderBy('id', 'DESC')
        //     ->where(['iStatus' => 1, 'isDelete' => 0, 'isDispatched' => 0, 'dispatchCourierId' => 2])
        //     ->count();
        // $DispatchedOrder = Order::orderBy('id', 'DESC')
        //     ->where(DB::raw('DATE(updated_at)'), $Today)
        //     ->where(['order.iStatus' => 1, 'order.isDelete' => 0, 'order.isDispatched' => 1])
        //     ->count();
        // $TodaysCollection = Order::orderBy('order_id', 'DESC')
        //     ->where(['iStatus' => 1, 'isDelete' => 0, 'order.isPayment' => 1])
        //     ->where(DB::raw('DATE(created_at)'), $Today)
        //     ->sum('netAmount');
        //  dd($Product);

        return view('home');
    }

    /**
     * User Profile
     * @param Nill
     * @return View Profile
     * @author Shani Singh
     */
    public function getProfile()
    {
        $session = Auth::user()->id;
        // dd($session);
        $users = User::where('users.id',  $session)
            ->first();
        // dd($users);
        return view('profile', compact('users'));
    }


    public function EditProfile()
    {
        $roles = Role::where('id', '!=', '1')->get();
        return view('Editprofile', compact('roles'));
    }

    /**
     * Update Profile
     * @param $profileData
     * @return Boolean With Success Message
     * @author Shani Singh
     */
    public function updateProfile(Request $request)
    {
        $session = auth()->user()->id;
        $user = User::where(['status' => 1, 'id' => $session])->first();

        $request->validate([
            'email' => 'required|unique:users,email,' . $user->id . ',id',
        ]);

        try {

            #Update Profile Data
            User::whereId(auth()->user()->id)->update([
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'email'         => $request->email,
                'mobile_number' => $request->mobile_number,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            #Commit Transaction

            #Return To Profile page with success
            return back()->with('success', 'Profile Updated Successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Change Password
     * @param Old Password, New Password, Confirm New Password
     * @return Boolean With Success Message
     * @author Shani Singh
     */
    public function changePassword(Request $request)
    {
        $session = Auth::user()->id;

        $user = User::where('id', '=', $session)->where(['status' => 1])->first();

        if (Hash::check($request->current_password, $user->password)) {
            $newpassword = $request->new_password;
            $confirmpassword = $request->new_confirm_password;

            if ($newpassword == $confirmpassword) {
                $Student = DB::table('users')
                    ->where(['status' => 1, 'id' => $session])
                    ->update([
                        'password' => Hash::make($confirmpassword),
                    ]);
                return back()->with('success', 'User Password Updated Successfully.');
            } else {
                return back()->with('error', 'password and confirm password does not match');
            }
        } else {
            return back()->with('error', 'Current Password does not match');
        }
    }
}
