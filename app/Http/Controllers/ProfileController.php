<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function show(Request $request){
        $usertype = DB::table('users')->where('userid', $request->userid)->value('role');
        if ($usertype == 'seller') {
            $userdata = DB::table('users')->where('userid', $request->userid)
                ->join("sellers", 'sellers.seller_id', 'users.userid')->select('users.*', "sellers.*")->first();
            return response()->json(["userdata" => $userdata]);
        } else if ($usertype == "client") {
            $userdata = DB::table('users')->where('userid', $request->userid)
                ->join("clients", 'clients.client_id', 'users.userid')->select('users.*', "clients.*")->first();
            return response()->json(["userdata" => $userdata]);
        } else {
            $userdata = DB::table('users')->where('userid', $request->userid)->first();
            return response()->json(["userdata" => $userdata]);
        }
    }

    public function updatePhoto(Request $request){
        $photo = "user_profile.png";
        if ($request->has("photo")) {
            $file = $request->file('photo');
            $photoname = uniqid() . $file->getClientOriginalName();
            $file->move(public_path("photos"), $photoname);
            $photo = $photoname;
        }
        User::where("userid",$request->userid)->update(['photo'=>$photo]);
        return response()->json(["res" => 'your profile photo was updated successfully']);

    }
}
