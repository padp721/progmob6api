<?php
namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Hash;
 
class UserController extends Controller
{
    private $successStatus  =   200;

 
    //=================================================== ADMIN ==============================================//


    //=================REGISTER
    public function registerUser(Request $request) {
        //validasi input
    	$validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error_msg'=>$validator->errors()], 401);            
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('nApp')->accessToken;
        $success['name'] =  $user->name;

        return response()->json(['error'=> FALSE, 'success'=>$success], $this->successStatus);
        //simpan data dari input

    }
    //REGISTER=================



    //=================LOGIN
    public function userLogin(Request $request) {
        //cek input
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $user['token'] =  $user->createToken('nApp')->accessToken;
            return response()->json(['error' => FALSE, 'user' => $user], $this->successStatus);
        }
        else{
            return response()->json(['error_msg'=>'Unauthorised'], 401);
        }
    }
    //LOGIN=================

    public function details()
    {
        $userDetail = Auth::user();
        return response()->json($user, $this->successStatus);
    }
    //LOGIN=================



    public function userEdit(Request $request, $id){

        // $validator = Validator::make($request->all(), [  
        //     'name' => 'required',
        //     'email' => 'required|email',
        //     'password' => 'required',
        //     'confirm_password' => 'required|same:password',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json(['error'=>$validator->errors()], 401);           
        // }

            //  $user = User::find($id);

        $user = User::find($id);
        $password = $request->input('password');
        if (!Hash::check($password, $user->password)) {
            return response()->json(['success'=>false, 'message' => 'Password salah, coba cek lagi']);
        }else{
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();
            return response()->json(['success'=>true,'message'=>'success', 'data' => $user]);
        }

       
        // return "Berhasil di update";


    }



    //================================================ KARYAWAN ==================================================//

       public function registerKaryawan(Request $request) {

        //validasi input
        $validator = Validator::make($request->all(), [  
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);           
        }
        //validasi input

        //cek email
        $user = User::where('email', $request->email)->first();
        if(!is_null($user)) {
            $data['message'] = "Sorry! this email is already registered";
            return response()->json(['success' => false, 'status' => 'failed', 'data' => $data]);
        }
        //cek email

        //simpan data dari input
        $input = $request->all();
        $input['user_role'] = "Karyawan";
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('token')->accessToken;
        $success['name'] =  $user->name;
        // return response()->json(['error'=> FALSE], $this->successStatus);
        return response()->json(['error' => FALSE, 'user' => $user], $this->successStatus);
        //simpan data dari input

    }
    //REGISTER=================

    public function updateKaryawan(Request $request, $id)
    {

        $user = User::all();
        $id = $user->where('email', $request->email)->find('id');
        $password = $request->input('password');
        if (!Hash::check($password, $user->password)) {
            return response()->json(['success'=>false, 'message' => 'Password salah, coba cek lagi']);
        }else{
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();
            return response()->json(['success'=>true,'message'=>'success', 'data' => $user]);
        }

       // User::where('id',$id)->update($request->except('_token','_method')); 
       // return response()->json(['error'=> FALSE], $this->successStatus);
    }

    public function hapusKaryawan($id)
    {
        User::where('id',$id)->delete();
        return response()->json(['error'=> FALSE], $this->successStatus);
    }
}