<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function show(Request $request){
        $query = Client::query();
 
        if ($request->has('customSearch')) {
            $query->where(function ($query) use ($request) {
                $query->where('fullname', 'LIKE', '%' . $request->customSearch . '%')
                    ->orWhere('city', 'LIKE', '%' . $request->customSearch . '%');
            });
        }

        $sellers = $query->join('users', 'users.userid', '=', "sellers.seller_id")
            ->orderBy("users.id", $request->order);
        return response()->json($sellers->get());
    }
    public function delete(Request $request)
    {
        $user = new User();
        $res = $user->where("userid", $request->client_id)
            ->first()->delete();
        return response()->json(['res' => 'the client profile is deleted']);
    }
}
