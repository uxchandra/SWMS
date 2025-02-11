<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BarangMasukController extends Controller
{
    public function index()
    {
        return view('barang-masuk.index', [
            'barangs'      => Barang::all(),
            'barangsMasuk' => BarangMasuk::all(),
        ]);
    }

    public function getDataBarangMasuk()
    {
        return response()->json([
            'success'   => true,
            'data'      => BarangMasuk::all(),
        ]);
    }

    public function getBarangByKode($kode)
    {
        try {
            $barang = Barang::where('kode', $kode)->firstOrFail();
            return response()->json([
                'success' => true,
                'barang' => $barang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('barang-masuk.create', [
            'barangs'   => Barang::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_masuk'     => 'required',
            'nama_barang'       => 'required',
            'jumlah_masuk'      => 'required',
        ],[
            'tanggal_masuk.required'    => 'Pilih Barang Terlebih Dahulu !',
            'nama_barang.required'      => 'Form Nama Barang Wajib Di Isi !',
            'jumlah_masuk.required'     => 'Form Jumlah Stok Masuk Wajib Di Isi !',
        ]);


        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $barangMasuk = BarangMasuk::create([
            'tanggal_masuk'     => $request->tanggal_masuk,
            'nama_barang'       => $request->nama_barang,
            'jumlah_masuk'      => $request->jumlah_masuk,
            'user_id'           => Auth::user()->id
        ]); 

        if ($barangMasuk) {
            $barang = Barang::where('nama_barang', $request->nama_barang)->first();
            if ($barang) {
                $barang->stok += $request->jumlah_masuk;
                $barang->save();
            }
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Disimpan !',
            'data'      => $barangMasuk
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(BarangMasuk $barangMasuk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangMasuk $barangMasuk)
    {
        return response()->json([
            'success' => true,
            'message' => 'Edit Data Barang',
            'data'    => $barangMasuk
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangMasuk $barangMasuk)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangMasuk $barangMasuk)
    {
        $jumlahMasuk = $barangMasuk->jumlah_masuk;
        $barangMasuk->delete();

        $barang = Barang::where('nama_barang', $barangMasuk->nama_barang)->first();
        if ($barang) {
            $barang->stok -= $jumlahMasuk;
            $barang->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Data Barang Berhasil Dihapus!'
        ]);
    }
}
