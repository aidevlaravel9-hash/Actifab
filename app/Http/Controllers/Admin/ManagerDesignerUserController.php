<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManagerDesignerUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class ManagerDesignerUserController extends Controller
{
    public function index(Request $request)
    {
        $query = ManagerDesignerUser::query();

        if ($request->search) {
            $query->where('first_name','like',"%{$request->search}%")
                  ->orWhere('email','like',"%{$request->search}%")
                  ->orWhere('mobile_number','like',"%{$request->search}%");
        }

        $data = $query->latest('manager_designer_user_id')->paginate(10);

        return view('admin.manager-designer-user.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'=>'required',
            'email'=>'required|email|unique:manager_designer_user,email',
            'mobile_number'=>'required',
        ]);

    $plainPassword = $request->first_name . '@123';
        ManagerDesignerUser::create([
            'first_name'=>$request->first_name,
            'email'=>$request->email,
            'mobile_number'=>$request->mobile_number,
            'password'=>Hash::make($plainPassword),
            'role_type'=>$request->role_type,
            'status'=>$request->status,
        ]);
        
        //
         $sendEmailDetails = DB::table('sendemaildetails')->where('id', 9)->first();

    // 👉 Mail config
    $msg = [
        'FromMail' => $sendEmailDetails->strFromMail ?? '',
        'Title'    => $sendEmailDetails->strTitle ?? '',
        'ToEmail'  => $request->email,
        'Subject'  => 'Your Account Created',
    ];

    $data = [
        "name"     => $request->first_name,
        "email"    => $request->email,
        "password" => $plainPassword,
        "role"     => ucfirst($request->role_type),
    ];

    // 👉 Send Mail
    $dat =Mail::send('emails.user-create-mail', ['data' => $data], function ($message) use ($msg) {
        $message->from($msg['FromMail'], $msg['Title']);
        $message->to($msg['ToEmail'])->subject($msg['Subject']);
    });
    
        return back()->with('success','User Created');
    }

    public function edit($id)
    {
        return ManagerDesignerUser::findOrFail($id);
    }

    public function update(Request $request)
    {
        $user = ManagerDesignerUser::findOrFail($request->edit_id);

        $user->update([
            'first_name'=>$request->first_name,
           // 'last_name'=>$request->last_name,
            'email'=>$request->email,
            'mobile_number'=>$request->mobile_number,
            'role_type'=>$request->role_type,
            'status'=>$request->status,
        ]);

        return back()->with('success','Updated');
    }

   public function destroy($id)
    {
    ManagerDesignerUser::findOrFail($id)->delete();

    return redirect()->back()->with('success', 'User deleted successfully.');
}
}