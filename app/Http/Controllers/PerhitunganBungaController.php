<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PerhitunganBunga;
use App\Bunga;
use App\User;
use App\Simpanan;
use DB;
use Illuminate\Support\Facades\Session;

class PerhitunganBungaController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $data = PerhitunganBunga::paginate(5);
        // return view('HitungBunga.index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    //METHOD HITUNG BUNGA
    public function create($pegawai)
    {
        date_default_timezone_set("Asia/Singapore");
        
        $trx_bulan = date('m');
        $trx_tahun = date('Y');

        $get_validasi = PerhitunganBunga::where('trx_bulan',$trx_bulan)->where('trx_tahun',$trx_tahun)->first();
        if($get_validasi){
            return response()->json(['error' => TRUE, 'msg' => 'Perhitungan bunga bulan ini dan tahun ini sudah pernah dilakukan!']);
        }
        else {
            $get_bunga = Bunga::whereRaw('tanggal_mulai_berlaku = (SELECT tanggal_mulai_berlaku FROM tb_master_bunga_simpanan WHERE DATEDIFF(CURDATE(),tanggal_mulai_berlaku) >= 0  ORDER BY tanggal_mulai_berlaku DESC LIMIT 1)')->first();

            $tanggal_proses = date ("Y-m-d H:i:s");
            $persentase_bunga = $get_bunga->persentase;
            // $id_user = Session::get('id');
            $id_user_pegawai = $pegawai;

            $insert_bunga = new PerhitunganBunga;
            $insert_bunga->trx_bulan = $trx_bulan;
            $insert_bunga->trx_tahun = $trx_tahun;
            $insert_bunga->tanggal_proses = $tanggal_proses;
            $insert_bunga->persentase_bunga = $persentase_bunga;
            $insert_bunga->id_user_pegawai = $id_user_pegawai;
            $insert_bunga->save();

            $anggota = Simpanan::select('id_user_nasabah',DB::raw('SUM(nominal_transaksi) as saldo'))->groupBy('id_user_nasabah')->get();

            foreach ($anggota as $orang) {
                $nominal_transaksi = $orang->saldo * $persentase_bunga / 100;
                $tanggal = date ("Y-m-d H:i:s");

                $insert_orang = new Simpanan;
                $insert_orang->id_user_nasabah = $orang->id_user_nasabah;
                $insert_orang->tanggal = $tanggal;
                $insert_orang->jenis_transaksi = 3;
                $insert_orang->nominal_transaksi = $nominal_transaksi;
                $insert_orang->id_user_karyawan = $id_user_pegawai;
                $insert_orang->status = 'Verified';
                $insert_orang->save();
            }
            return response()->json(['error' => FALSE, 'msg' => 'Berhasil hitung bunga!', 'data' => $insert_orang]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PerhitunganBunga $perhitunganBunga)
    {
        // return view('',['data'=>$perhitunganBunga]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $data=PerhitunganBunga::where('id','=',$id)->first();
        // return view('Anggota.edit',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    //    PerhitunganBunga::where('id',$id)->update($request->except('_token','_method')); 
    //    return redirect('');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // PerhitunganBunga::where('id',$id)->delete();
        // return redirect('/anggota');
    }
}
