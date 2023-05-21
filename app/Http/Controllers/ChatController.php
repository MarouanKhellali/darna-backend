<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{

    public function createid()
    {

        $id = ""; 
        for ($i = 0; $i <= rand(4, 19); $i++) {
            $id .= rand(0, 9);
        }

        return $id;
    }


    function store(Request $request)
    {
        $chat = new Chat();

        $res = $chat->where("sender_id", $request->sender_id)->where("receiver_id", $request->receiver_id)
            ->orWhere("sender_id", $request->receiver_id)->where("receiver_id", $request->sender_id)->first();
        $msg = null;
        if ($request->has("msg")) {
            if ($request->msg != "") {
                $msg = $request->msg;
            }
        }
        $photo = null;
        $hasFile = 0;
        if ($request->has("file")) {
            if ($request->file != "") {
                $file = $request->file('file');
                $photoname = uniqid() . $file->getClientOriginalName();
                $file->move(public_path("chat_files"), $photoname);
                $photo = $photoname;
                $hasFile = 1;
            }
        }

        if ($res) {
            $chat->insert([
                "msg_id" => $res->msg_id,
                "sender_id" => $request->sender_id,
                "receiver_id" => $request->receiver_id,
                "msg" => $msg,
                "hasFile" => $hasFile,
                "file" => $photo,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ]);
            return response()->json(["res" => "succeed"]);
        } else {
            $chat->insert([
                "msg_id" => $this->createid(),
                "sender_id" => $request->sender_id,
                "receiver_id" => $request->receiver_id,
                "msg" => $msg,
                "hasFile" => $hasFile,
                "file" => $photo,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ]);
            return response()->json(["res" => "message sent"]);
        }
    }
    function show(Request $request)
    {
        $msgs = DB::table("chats")
            ->where(function ($query) use ($request) {
                $query->where("sender_id", $request->sender_id)
                    ->where("receiver_id", $request->receiver_id);
            })
            ->orWhere(function ($query) use ($request) {
                $query->where("sender_id", $request->receiver_id)
                    ->where("receiver_id", $request->sender_id);
            })
            ->join('users as sender_user', 'sender_user.userid', '=', 'chats.sender_id')
            ->join('users as receiver_user', 'receiver_user.userid', '=', 'chats.receiver_id')
            ->select(
                'chats.*',
                'sender_user.userid as sender_user_id',
                'sender_user.fullname as sender_user_name',
                'sender_user.photo as sender_user_photo',
                'receiver_user.userid as receiver_user_id',
                'receiver_user.fullname as receiver_user_name',
                'receiver_user.photo as receiver_user_photo'
            )

            ->orderBy("chats.created_at", "asc")
            ->get();
        return response()->json(["msgs" => $msgs]);
    }

    function showMessagedUsers(Request $request)
    {

        $messagedUsers = DB::table("users")->where("userid", "!=", $request->userid)
            ->Join("chats", function ($join) {
                $join->on("users.userid", "=", "chats.sender_id")->orOn("users.userid", "=", "chats.receiver_id");
            })
            ->select("chats.msg_id", DB::raw("MAX(users.userid) as userid"), DB::raw("MAX(users.fullname) as fullname"), DB::raw("MAX(users.photo) as photo"))
            ->groupBy("chats.msg_id")->get();


        return response()->json(["messagedUsers" => $messagedUsers]);
    }
    function update(Request $request)
    {
        $chat = new Chat();
        $chat->where("sender_id", $request->sender_id)
            ->where("receiver_id", $request->receiver_id)
            ->update(["seen" => 1]);
        return response()->json(["res" => "status updated successfully"]);
    }
}
