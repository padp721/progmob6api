<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\User;
use App\Post;
use App\Notification;
use App\Simpanan;
class FirebaseNotificationController extends Controller
{
    public $apiKey = "AAAAjleJQSg:APA91bEqiJD7S5HM2Gtc0zuqRWuYVo6cMwUvsfvNECcB6cREJ2xye4kbWh8iE4NO_Iud6gaZLkZj9r1wPju7ukASmfdahIEUl1SY_18CC_bXznlUUeld2tRk_GwWsoFhS388AzVF9ERG";
    //notif ada yang nemuin

    public function setoran(Request $request){
        $user = User::select('fcm_token')->where('user_role', "Admin")->whereNotNull('fcm_token')->get();
        $fcm_tokens = [];
        foreach ($user as $key => $value) {
            array_push($fcm_tokens,$value->fcm_token);
        }
        $body = "Transaksi Terbaru oleh segera cek";
        $title = "Transaksi terbaru!";
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://fcm.googleapis.com/fcm/send',
            // You can set any number of default request options.
            'timeout'  => 2.0,
            'verify' =>false,
            'headers' => ['Authorization' => 'key='.$this->apiKey]
        ]);
        $r = $client->request('POST','https://fcm.googleapis.com/fcm/send',[
            'json' => array(
            "notification" =>array(
                "body"=>$body,
                "title"=>$title
            ),
            // "data"=>array(
            //     "Nick"=>"Mario",
            //     "Room"=>"PortugalVSDenmark"
            // ),
            "registration_ids"=>$fcm_tokens
            )
        ]);
        // $notification = New Notification();
        // $notification->id_simpanan = $transaksi->id;
        // $notification->id_user = $user->id;
        // $notification->title = $title;
        // $notification->body = $body;
        // $notification->save();
        // return $notification;
        return response()->json(['error' => FALSE, 'msg' => 'Notif Dikirim!']);
    }

    public function approval(Request $request){
        $user = User::find($request->id); //user_id yang posting ketemu barang
        $userFCMToken = $user->fcm_token; 
        $transaksi = Simpanan::find($request->id); //post yang barangnya diklaim ketemu
        $postTitle = $transaksi->jenis_transaksi;
        $userClaim = Simpanan::find($request->id_user_karyawan)->nama; //user_id yang claim punya barang
        $body = "Transaksi Berhasil ".$postTitle." telah disetujui oleh ".$userClaim;
        $title = "Transaksi Berhasil!";
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://fcm.googleapis.com/fcm/send',
            // You can set any number of default request options.
            'timeout'  => 2.0,
            'verify' =>false,
            'headers' => ['Authorization' => 'key='.$this->apiKey]
        ]);
        $r = $client->request('POST','https://fcm.googleapis.com/fcm/send',[
            'json' => array(
            "notification" =>array(
                "body"=>$body,
                "title"=>$title
            ),
            // "data"=>array(
            //     "Nick"=>"Mario",
            //     "Room"=>"PortugalVSDenmark"
            // ),
            "registration_ids"=>$userFCMToken
            )
        ]);
        $notification = New Notification();
        $notification->id_simpanan = $simpanan->id;
        $notification->id_user = $user->id;
        $notification->title = $title;
        $notification->body = $body;
        $notification->save();
        return $notification;
    }

    // public function verification(Request $request){
    //     $user = User::find($request->user_id); //user_id yang ngaku punya barang
    //     $userFCMToken = $user->fcm_token; 
    //     $post = Post::find($request->post_id); //post yang barangnya ngaku pumya
    //     $postTitle = $post->judul;
    //     $userFind = User::find($request->user_id_find)->name; //user_id yang pegang barang ketemu
    //     $body = $userFind." ingin meminta verifikasimu untuk claim barang ".$postTitle;
    //     $title = "Verifikasi Barang";
    //     $client = new Client([
    //         // Base URI is used with relative requests
    //         'base_uri' => 'https://fcm.googleapis.com/fcm/send',
    //         // You can set any number of default request options.
    //         'timeout'  => 2.0,
    //         'verify' =>false,
    //         'headers' => ['Authorization' => 'key='.$this->apiKey]
    //     ]);
    //     $r = $client->request('POST','https://fcm.googleapis.com/fcm/send',[
    //         'json' => array(
    //         "notification" =>array(
    //             "body"=>$body,
    //             "title"=>$title
    //         ),
    //         // "data"=>array(
    //         //     "Nick"=>"Mario",
    //         //     "Room"=>"PortugalVSDenmark"
    //         // ),
    //         "to"=>$userFCMToken
    //         )
    //     ]);
    //     $notification = New Notification();
    //     $notification->id_post = $post->id;
    //     $notification->id_user = $user->id;
    //     $notification->title = $title;
    //     $notification->body = $body;
    //     $notification->save();
    //     return $notification;
    // }

    // public function verified(Request $request){
    //     $user = User::find($request->user_id);  //user_id yang posting ketemu barang
    //     $userFCMToken = $user->fcm_token;
    //     $post = Post::find($request->post_id); //post yang posting ketemu barang
    //     $postTitle = $post->judul;
    //     $userClaim = User::find($request->user_id_find)->name; //user_id yang ngaku punya barang
    //     $body = $userClaim." sudah mengirim verifikasi untuk claim barang ".$postTitle;
    //     $title = "Verifikasi Barang";
    //     $client = new Client([
    //         // Base URI is used with relative requests
    //         'base_uri' => 'https://fcm.googleapis.com/fcm/send',
    //         // You can set any number of default request options.
    //         'timeout'  => 2.0,
    //         'verify' =>false,
    //         'headers' => ['Authorization' => 'key='.$this->apiKey]
    //     ]);
    //     $r = $client->request('POST','https://fcm.googleapis.com/fcm/send',[
    //         'json' => array(
    //         "notification" =>array(
    //             "body"=>$body,
    //             "title"=>$title
    //         ),
    //         "data"=>array(
    //             "Nick"=>"Mario",
    //             "Room"=>"PortugalVSDenmark"
    //         ),
    //         "to"=>$userFCMToken
    //         )
    //     ]);
    //     $notification = New Notification();
    //     $notification->id_post = $post->id;
    //     $notification->id_user = $user->id;
    //     $notification->title = $title;
    //     $notification->body = $body;
    //     $notification->save();
    //     return $notification;
    // }

    // public function verificationConfirmed(Request $request){
    //     $user = User::find($request->user_id);  //user_id yang ngaku punya barang
    //     $userFCMToken = $user->fcm_token; 
    //     $post = Post::find($request->post_id); //post yang barangnya ngaku pumya
    //     $postTitle = $post->judul;
    //     $userFind = User::find($request->user_id_find)->name; //user_id yang pegang barang ketemu
    //     $body = $userFind." menyetujui verifikasimu untuk barang ".$postTitle.". Silahkan ketemuan";
    //     $title = "Verifikasi Barang Berhasil!";
    //     $client = new Client([
    //         // Base URI is used with relative requests
    //         'base_uri' => 'https://fcm.googleapis.com/fcm/send',
    //         // You can set any number of default request options.
    //         'timeout'  => 2.0,
    //         'verify' =>false,
    //         'headers' => ['Authorization' => 'key='.$this->apiKey]
    //     ]);
    //     $r = $client->request('POST','https://fcm.googleapis.com/fcm/send',[
    //         'json' => array(
    //         "notification" =>array(
    //             "body"=>$body,
    //             "title"=>$title
    //         ),
    //         // "data"=>array(
    //         //     "Nick"=>"Mario",
    //         //     "Room"=>"PortugalVSDenmark"
    //         // ),
    //         "to"=>$userFCMToken
    //         )
    //     ]);
    //     $notification = New Notification();
    //     $notification->id_post = $post->id;
    //     $notification->id_user = $user->id;
    //     $notification->title = $title;
    //     $notification->body = $body;
    //     $notification->save();
    //     return $notification;
    // }

    // public function verificationRejected(Request $request){
    //     $user = User::find($request->user_id); //user_id yang ngaku punya barang
    //     $userFCMToken = $user->fcm_token; 
    //     $post = Post::find($request->post_id);  //post yang barangnya ngaku pumya
    //     $postTitle = $post->judul; 
    //     $userFind = User::find($request->user_id_find)->name; //user_id yang pegang barang ketemu
    //     $body = $userFind." tidak menyetujui verifikasimu untuk barang ".$postTitle;
    //     $title = "Verifikasi Barang Gagal!";
    //     $client = new Client([
    //         // Base URI is used with relative requests
    //         'base_uri' => 'https://fcm.googleapis.com/fcm/send',
    //         // You can set any number of default request options.
    //         'timeout'  => 2.0,
    //         'verify' =>false,
    //         'headers' => ['Authorization' => 'key='.$this->apiKey]
    //     ]);
    //     $r = $client->request('POST','https://fcm.googleapis.com/fcm/send',[
    //         'json' => array(
    //         "notification" =>array(
    //             "body"=>$body,
    //             "title"=>$title
    //         ),
    //         // "data"=>array(
    //         //     "Nick"=>"Mario",
    //         //     "Room"=>"PortugalVSDenmark"
    //         // ),
    //         "to"=>$userFCMToken
    //         )
    //     ]);
    //     $notification = New Notification();
    //     $notification->id_post = $post->id;
    //     $notification->id_user = $user->id;
    //     $notification->title = $title;
    //     $notification->body = $body;
    //     $notification->save();
    //     return $notification;
    // }

    public function allNotif(Request $request){
        $notification = Notification::where('id_user',Auth::user()->id)->get();
        return $notification;
    }

    
}
