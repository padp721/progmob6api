<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Simpanan;
use App\User;
use App\JenisTransaksi;
use DateTime;
use DB;
use Illuminate\Support\Facades\Session;

class SimpananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $anggota = Anggota::where('status_aktif','1')->orderBy('no_anggota','asc')->get();
        // // return $data;
        // return view('Transaksi.start',['anggota'=>$anggota]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // $anggota = Anggota::where('no_anggota',$request->anggota_id)->first();
        // if(!$anggota){
        //     return back()->with('status','Nasabah tidak ditemukan!');
        // }
        // $uang = Simpanan::select(DB::raw('SUM(nominal_transaksi) as saldo'))->where('anggota_id',$anggota->id)->first();
        // date_default_timezone_set("Asia/Singapore");
        // $now = date ("Y-m-d H:i:s");
        // // return $now;
        // return view('Transaksi.create',['anggota'=>$anggota, 'now'=>$now, 'uang'=>$uang]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //METHOD SIMPAN TRANSAKSI
    public function store(Request $request)
    {
        
        $get_jenis = JenisTransaksi::where('id',$request->jenis_transaksi)->first();
        // if($get_jenis == NULL){
        //     return back()->with('status','Silahkan pilih jenis transaksi!');
        // }
        //jenis nominal
        $request['nominal_transaksi'] = $request['nominal_transaksi']*$get_jenis->tipe;
        // return $request['nominal_transaksi'];
        // $request['id_user'] = Session::get('id');
        $uang = Simpanan::select(DB::raw('SUM(nominal_transaksi) as saldo'))->where('id_user_nasabah',$request['id_user_nasabah'])->first();
        if($uang->saldo < $request['nominal_transaksi'] && $request->jenis_transaksi == 2){
            return response()->json(['error' => TRUE, 'msg' => 'Saldo tidak cukup!']);
        }
        Simpanan::create($request->all());        
        return response()->json(['error' => FALSE, 'msg' => 'Berhasil Transaksi!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Simpanan $simpanan)
    {
        $not_verified = Simpanan::where('status','Not Verified')->get();
        return response()->json(['data' => $not_verified]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $data=Simpanan::where('id','=',$id)->first();
        // return view('Anggota.edit',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //METHOD VERIFY TRANSAKSI
    //CONTOH:
    //http://127.0.0.1:8000/api/verify/101/11
    public function update($pegawai, $id)
    {
       $verify = Simpanan::find($id);
       $verify->status = 'Verified';
       $verify->id_user_karyawan = $pegawai;
       $verify->save();
       return response()->json(['error' => FALSE, 'msg' => 'Berhasil Melakukan Verifikasi!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Simpanan::where('id',$id)->delete();
        // return redirect('/anggota');
    }
}
