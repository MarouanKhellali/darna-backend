<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $reviewedAlready = Review::where("seller_id", $request->seller_id)
        ->where("client_id", $request->client_id)->first();
        if ($reviewedAlready != null) {
            return response()->json(["res" => "you've already reviewed this person!"]);
        }
        Review::create(
            [
                'seller_id' => $request->seller_id,
                'client_id' => $request->client_id,
                'review' => $request->review,
                'rating' => $request->rating,

            ]
        );

        $where = "";
        switch ($request->rating) {
            case 1:
                $where = "oneStar";
                break;
            case 2:
                $where = "twoStars";
                break;
            case 3:
                $where = "threeStars";
                break;
            case 4:
                $where = "fourStars";
                break;
            case 5:
                $where = "fiveStars";
                break;
        }
            $reviewedUser = new Seller();
       
        $ratingData = $reviewedUser->where("seller_id", $request->seller_id)->value($where);
        $updateStars = [$where => $ratingData += 1];
        $reviewedUser->where("seller_id", $request->seller_id)->update($updateStars);
        $userdata = $reviewedUser->where("seller_id", $request->seller_id)->first();
        $score = $userdata->fiveStars * 5 + $userdata->fourStars * 4 + $userdata->threeStars * 3 + $userdata->twoStars * 2 + $userdata->oneStar * 1;
        $response = $userdata->fiveStars + $userdata->fourStars + $userdata->threeStars + $userdata->twoStars + $userdata->oneStar;
        $newRating = $score / $response;
        $rating = ["rating" => $newRating];
        $reviewedUser->where("seller_id", $request->seller_id)->update($rating);


        return response()->json(["res" => "your reviews was sent successfully"]);
    }
    public function show(Request $request)
    {

        $reviews = DB::table("reviews")->where("reviews.seller_id", $request->seller_id)
            ->join("users", "users.userid", "=", "reviews.client_id")
            ->select("users.fullname", "users.photo", "reviews.*")
            ->get();
        return response()->json(["reviews" => $reviews]);
    }


    public function check(Request $request)
    {

        $checked = DB::table("reviews")->where("reviews.seller_id", $request->seller_id)
            ->where("reviews.client_id", $request->client_id)
            ->first();
        if ($checked != null) {
            return response()->json(["checked" => false]);
        } else {
            return response()->json(["checked" => true]);
        }
    }
}
