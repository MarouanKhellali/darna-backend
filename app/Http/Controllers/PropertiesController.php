<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertiesController extends Controller {
   
    public function createid()
    {

        $id = ""; 
        for ($i = 0; $i <= rand(4, 19); $i++) {
            $id .= rand(0, 9);
        }

        return $id;
    }

    public function store(Request $request){ 
        Property::create([
            'property_id' =>  $this->createid(),
            'property_seller_id' =>  $request->property_seller_id,
            'title' =>  $request->title,
            'propertytype' => $request->propertytype,
            'actiontype' => $request->actiontype,
            'price' => $request->price,
            'city' => $request->city,
            'image' => $request->image,
            'area' => $request->area,
            'bedrooms' => $request->bedrooms,
            'address' => $request->address
        ]);
 
        return response()->json(['res'=>"your property was created successfully"]);
    }


    public function show(Request $request){
        $query = Property::query();

        if ($request->has('actiontype') ) {
            $query->where('actiontype', $request->actiontype);
        }
        if ($request->has('propertytype') ) {
            $query->where('propertytype', $request->propertytype);
        }

        if ($request->has('bedrooms') ) {
            $query->where('bedrooms', $request->bedrooms);
        }

        if ($request->has('area') ) {
            $query->where('area', $request->area);
        }
        if ($request->has('city') ) {
            $query->where('city', $request->city);
        }
  
        if ($request->has('customSearch')) {
            $query->where(function ($query) use ($request) {
                $query->where('title', 'LIKE', '%' . $request->customSearch . '%')
                    ->orWhere('city', 'LIKE', '%' . $request->customSearch . '%');
            });
        }

        $properties = $query->join('users', 'users.userid', '=', "properties.property_seller_id")
            ->orderBy("properties.rating", $request->orderBy);
        return response()->json($properties->get());
    }
    
 
    public function delete(Request $request)
    {
        $property = new Property();
         $property->where("property_id", $request->property_id)->first()->delete();
        return response()->json(['res' => 'the property was deleted successfully']);
    }

   public function check(Request $request){
    $res=Property::where("property_id",$request->property_id)->where("isactive",1)->first();
    if(empty($res)){
        return response()->json(['res'=>false]);
    }else{
        return response()->json(['res'=>true]);

    }
   }

}
