<?php

namespace App\Http\Controllers;

use App\Bunga;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BungaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Bunga::orderBy('tanggal_mulai_berlaku','desc')->get();
        // return view('Bunga.bunga',['data'=>$data]);
        return response()->json(['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $simpan = Bunga::create($request->all());
        // return redirect('/bunga');
        if($simpan){
 			return response()->json(['error' => FALSE, 'msg' => 'Berhasil Disimpan!']);
        }
        else {
 			return response()->json(['error' => TRUE, 'msg' => 'Gagal Disimpan!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bunga  $bunga
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data=Bunga::where('id','=',$id)->first();
        return response()->json(['data' => $data]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bunga  $bunga
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data=Bunga::where('id','=',$id)->first();
        // return view('Bunga.edit',['data'=>$data]);
        return response()->json(['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bunga  $bunga
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $update = Bunga::where('id',$id)->update($request->except('_token','_method')); 
        // return redirect('/bunga');
        if($update){
 			return response()->json(['error' => FALSE, 'msg' => 'Berhasil Diubah!']);
        }
        else {
 			return response()->json(['error' => TRUE, 'msg' => 'Gagal Diubah!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bunga  $bunga
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Bunga::where('id',$id)->delete();
        // return redirect('/bunga');
        if($delete){
 			return response()->json(['error' => FALSE, 'msg' => 'Berhasil Dihapus!']);
        }
        else {
 			return response()->json(['error' => TRUE, 'msg' => 'Gagal Dihapus!']);
        }
    }
}
