<?php
namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
 
class UserController extends Controller
{
    private $successStatus  =   200;
 
    //----------------- [ Register user ] -------------------
    public function registerUser(Request $request) {

    	$validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $user = User::where('email', $request->email)->first();
        if(!is_null($user)) {
            $data['message'] = "Sorry! this email is already registered";
            return response()->json(['success' => false, 'status' => 'failed', 'data' => $data]);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('token')->accessToken;
        $success['name'] =  $user->name;
        return response()->json(['error'=> FALSE], $this->successStatus);
 
        // $validator  =   Validator::make($request->all(),
        //     [
        //         'name'              =>      'required|min:3',
        //         'email'             =>      'required|email',
        //         'password'          =>      'required|alpha_num|min:5',
        //         'confirm_password'  =>      'required|same:password'
        //     ]
        // );
 
        // if($validator->fails()) {
        //     return response()->json(['Validation errors' => $validator->errors()]);
        // }
 
        // check if email already registered
        $user = User::where('email', $request->email)->first();
        if(!is_null($user)) {
            $data['message'] = "Sorry! this email is already registered";
            return response()->json(['success' => false, 'status' => 'failed', 'data' => $data]);
        }

        // $input = $request->all();
        // $input['password'] = bcrypt($input['password']);
        // $success['token'] =  $user->createToken('token')->accessToken;
        // $success['name'] =  $user->name;
        // // create and return data
        // $user                   =       User::create($input);         
        // $success['message']     =       "You have registered successfully";

        // return response()->json(['error'=> FALSE], $this->successStatus);
    }
 
    // -------------- [ User Login ] ------------------
 
    public function userLogin(Request $request) {
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
             
            // getting auth user after auth login
            $user = Auth::user();
 
            $token                  =       $user->createToken('token')->accessToken;
            $success['success']     =       true;
            $success['message']     =       "Success! you are logged in successfully";
            $success['token']       =       $token;
 			return response()->json(['error' => FALSE, 'user' => $user], $this->successStatus);
        }
 
        else {
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }
}