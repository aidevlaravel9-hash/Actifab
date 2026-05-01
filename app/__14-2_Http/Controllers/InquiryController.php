<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiry;
use Illuminate\Support\Facades\DB;

class InquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inquiries = Inquiry::orderBy('id', 'DESC')->where(['iStatus' => 1, 'isDelete' => 0])->paginate(25);

        return view('admin.inquiries.index', compact('inquiries'));
    }


    public function delete(Request $request)
    {

        Inquiry::where(['iStatus' => 1, 'isDelete' => 0, 'id' => $request->inquiry_id])->delete();

        return redirect()->route('Inquiry.index')->with('success', 'Deleted Successfully!.');
    }

    public function viewdetail(Request $request, $id)
    {

        $data = Inquiry::where(['iStatus' => 1, 'isDelete' => 0, 'id' => $id])->first();

        echo json_encode($data);
    }
}
