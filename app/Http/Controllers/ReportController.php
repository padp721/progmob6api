<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Simpanan;
use DB;

class ReportController extends Controller
{
    //
    public function report()
    {
        $data = Simpanan::where('status','Verified')->get();
        // return $data;
        return response()->json(['error' => FALSE,'data'=>$data]);
    }

    public function nasabah()
    {
        $data = Simpanan::select(DB::raw('id_user_nasabah, SUM(nominal_transaksi) as saldo'))->where('status','Verified')->groupBy('id_user_nasabah')->get();
        // return $new;
        return response()->json(['error' => FALSE,'data'=>$data]);
    }

    public function detailnasabah($id)
    {
        $user = User::where('id',$id)->first();
        $histori = Simpanan::where('id_user_nasabah',$id)->orderBy('tanggal','asc')->get();
        $uang = Simpanan::select(DB::raw('SUM(nominal_transaksi) as saldo'))
        ->where('id_user_nasabah',$id)
        ->first();
        // return $histori->jenis->transaksi;
        return response()->json(['error' => FALSE,'user'=>$user,'histori'=>$histori,'uang'=>$uang]);
    }

    public function detailnasabahtarik($id)
    {
        $user = User::where('id',$id)->first();
        $histori = Simpanan::where('id_user_nasabah',$id)->where('jenis_transaksi', 2)->orderBy('tanggal','asc')->get();
        $uang = Simpanan::select(DB::raw('SUM(nominal_transaksi) as saldo'))
        ->where('id_user_nasabah',$id)
        ->first();
        // return $histori->jenis->transaksi;
        return response()->json(['error' => FALSE,'user'=>$user,'histori'=>$histori,'uang'=>$uang]);
    }

    public function detailnasabahsetor($id)
    {
        $user = User::where('id',$id)->first();
        $histori = Simpanan::where('id_user_nasabah',$id)->where('jenis_transaksi', 1)->orderBy('tanggal','asc')->get();
        $uang = Simpanan::select(DB::raw('SUM(nominal_transaksi) as saldo'))
        ->where('id_user_nasabah',$id)
        ->first();
        // return $histori->jenis->transaksi;
        return response()->json(['error' => FALSE,'user'=>$user,'histori'=>$histori,'uang'=>$uang]);
    }

    public function harian()
    {
        date_default_timezone_set("Asia/Singapore");
        $date = date('Y-m-d');
        $data = Simpanan::whereDate('tanggal',$date)->where('status','Verified')->get();
        // return $data;
        return response()->json(['error' => FALSE,'data'=>$data]);
    }

    public function harians(Request $request)
    {
        $data = Simpanan::whereDate('tanggal',$request->tanggal)->where('status','Verified')->get();
        return response()->json(['error' => FALSE,'data'=>$data]);
    }

    public function mingguanNow(){
        date_default_timezone_set("Asia/Singapore");
        $date = new \DateTime();
        $request = new \Illuminate\Http\Request();
        $request->setMethod('POST');
        $request->request->add(['tanggal'=>$date->format("Y-m-d")]);
        return $this->mingguan($request);
    }

    public function mingguan(Request $request)
    {
        $date = new \DateTime($request->tanggal);
        $week = $date->format("W");
        $data = Simpanan::
        groupBy(DB::raw('DATE(tanggal),jenis_transaksi'))
        ->whereRaw('WEEK(tanggal,3) = ?',$week)
        ->selectRaw('SUM(nominal_transaksi) AS nominal_transaksi,tanggal,WEEK(tanggal) AS minggu,MONTH(tanggal) AS bulan,YEAR(tanggal) AS tahun, jenis_transaksi, count(id) as jumlah')
        ->with('jenis')
        ->where('status','Verified')
        ->get()
        ;
        // return $week;
        return response()->json(['error' => FALSE,'data'=>$data]);
    }

    public function bulanan(Request $request)
    {
        $data = Simpanan::
        groupBy(DB::raw('DATE(tanggal),jenis_transaksi'))
        ->whereMonth('tanggal',$request->bulan)
        ->whereYear('tanggal',$request->tahun)
        ->selectRaw('SUM(nominal_transaksi) AS nominal_transaksi,count(id) as jumlah,tanggal, jenis_transaksi')
        ->with('jenis')
        ->where('status','Verified')
        ->get()
        ;
        
        return response()->json(['error' => FALSE,'data'=>$data]);
    }

    public function bulananNow(){
        date_default_timezone_set("Asia/Singapore");
        $date = date('Y-m-d');
        $request = new \Illuminate\Http\Request();
        $request->setMethod('POST');
        $request->request->add(['bulan' => date('m', strtotime($date)), 'tahun' => date('Y', strtotime($date))]);
        return $this->bulanan($request);
    }

    public function tahunan(Request $request){
        $data = Simpanan::
        groupBy(DB::raw('MONTH(tanggal),jenis_transaksi'))
        ->whereYear('tanggal',$request->tahun)
        ->selectRaw('SUM(nominal_transaksi) AS nominal_transaksi,MONTH(tanggal) AS bulan,YEAR(tanggal) AS tahun, count(id) as jumlah, jenis_transaksi')
        ->with('jenis')
        ->where('status','Verified')
        ->get()
        ;
        return response()->json(['error' => FALSE,'data'=>$data]);
    }

    public function tahunanNow(){
        date_default_timezone_set("Asia/Singapore");
        $date = new \DateTime();
        $request = new \Illuminate\Http\Request();
        $request->setMethod('POST');
        $request->request->add(['tahun' => $date->format("Y")]);
        return $this->tahunan($request);
    }
}
