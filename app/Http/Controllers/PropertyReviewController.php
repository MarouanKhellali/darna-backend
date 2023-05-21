<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyReviewController extends Controller
{
    public function store(Request $request)
    {
         
        PropertyReview::create(
            [
                'property_id' => $request->property_id,
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
            $reviewedProperty = new Property();
       
        $ratingData = $reviewedProperty->where("property_id", $request->property_id)
        ->value($where);
        $updateStars = [$where => $ratingData += 1];
        $reviewedProperty->where("property_id", $request->property_id)->update($updateStars);
        $userdata = $reviewedProperty->where("property_id", $request->property_id)->first();
        $score = $userdata->fiveStars * 5 + $userdata->fourStars * 4 + $userdata->threeStars * 3 + $userdata->twoStars * 2 + $userdata->oneStar * 1;
        $response = $userdata->fiveStars + $userdata->fourStars + $userdata->threeStars + $userdata->twoStars + $userdata->oneStar;
        $newRating = $score / $response;
        $rating = ["rating" => $newRating];
        $reviewedProperty->where("property_id", $request->property_id)->update($rating);


        return response()->json(["res" => "your reviews was sent successfully"]);
    }
    public function show(Request $request)
    {

        $reviews = DB::table("property_reviews")->where("property_reviews.property_id", $request->property_id)
            ->join("users", "users.userid", "=", "property_reviews.client_id")
            ->join("properties", "properties.property_id", "=", "property_reviews.property_id")
            ->select("users.fullname", "users.photo", "property_reviews.*","properties.*")
            ->get();
        return response()->json(["reviews" => $reviews]);
    }

 
}
