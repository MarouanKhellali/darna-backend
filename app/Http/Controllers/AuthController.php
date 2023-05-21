<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Seller;
use App\Models\Client;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'signup']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */


     public function createid()
    {

        $id = ""; 
        for ($i = 0; $i <= rand(4, 19); $i++) {
            $id .= rand(0, 9);
        }

        return $id;
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $photo = "user_profile.png";
        $userid=$this->createid();
        if ($request->has("photo")) {
            $file = $request->file('photo');
            $photoname = uniqid() . $file->getClientOriginalName();
            $file->move(public_path("photos"), $photoname);
            $photo = $photoname;
        }

        $user = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'photo' => $photo,
            'userid' => $userid,
        ]);

        if ($user->role == "seller") {
            $seller = Seller::create([
                'seller_id' => $userid,
                'city' => $request->city,
                'address' => $request->address,
                'phone' => $request->phone,
                'bio' => $request->bio,
                'type' => $request->type
            ]);
        } else if ($user->role == "client") {
            $client = Client::create([
                'client_id' => $userid,
                'city' => $request->city,
                'address' => $request->address,
                'phone' => $request->phone,
            ]);
        }
        $token = auth()->login($user);
        $user->update(['remember_token' => $token]);
        return $this->respondWithToken($token);
    }


    /**
 * Update the user's profile.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function updateUser(Request $request)
{
    
    $validator = Validator::make($request->all(), [
        'fullname' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }
    $user = User::where('userid', $request->userid)->first();

if ($user) {
    $user->fullname = $request->fullname;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->save();

    if ($user->role == "seller") {
        $seller = Seller::where('seller_id', $request->userid)->update([
            "city" => $request->city,
            "address" => $request->address,
            "phone" => $request->phone,
            "bio" => $request->bio,
            "type" => $request->type
        ]);
    }
    else if ($user->role == "client" ) {
        $client = Client::where('client_id', $request->userid)->update(
            [ "city" => $request->city,
             "address" => $request->address,
            "phone" => $request->phone ]
        );
        
    }
}
$token = auth()->refresh();

return $this->respondWithToken($token);
 }

 public function refresh()
 {
     return $this->respondWithToken(auth()->refresh());
 }



    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

     

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user = auth()->user();
    
        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    
}
