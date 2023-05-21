<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function show(Request $request){
        $query = Seller::query();

        if ($request->has('accepted') ) {
            $query->where('accepted', $request->accepted);
        }
  
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

    public function acceptSeller(Request $request){
        
        $seller = new Seller();
        $seller->where("seller_id", $request->seller_id)
            ->update(['accepted' => 1]);
        return response()->json(["res" => "your seller is accepted now"]);
    }
    public function refuseSeller(Request $request){
        
        $seller = new Seller();
        $seller->where("seller_id", $request->seller_id)
            ->update(['accepted' => -1]);
        return response()->json(["res" => "your seller is refused now"]);
    }


    public function delete(Request $request)
    {
        $user = new User();
        $res = $user->where("userid", $request->seller_id)
            ->first()->delete();
        return response()->json(['res' => 'the seller profile is deleted']);
    }
}
