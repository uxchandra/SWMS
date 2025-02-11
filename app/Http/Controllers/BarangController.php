<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Jenis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Imports\BarangImport;
use Maatwebsite\Excel\Facades\Excel;


class BarangController extends Controller
{
    public function index()
    {
        return view('barang.index', [
            'barangs'         => Barang::all(),
            'jenis_barangs'   => Jenis::all(),
        ]);
    }

    public function getDataBarang()
    {
        $barangs = Barang::with('jenis')->get();
        
        return response()->json([
            'success'   => true,
            'data'      => $barangs
        ]);
    }

    public function create()
    {
        return view('barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode'          => 'required|string|max:15|unique:barangs',
            'nama_barang'   => 'required',
            'jenis_id'      => 'required',
            'size'          => 'required|string|max:10',
            'stok_minimum'  => 'required|numeric',
            'stok_maximum'  => 'required|numeric',
            'stok'          => 'nullable|numeric',
            'nama_supplier' => 'required|string|max:100',
            'price'         => 'required|numeric',
        ], [
            'kode.required'         => 'Form Kode Barang Wajib Di Isi !',
            'kode.unique'           => 'Kode Barang Sudah Terdaftar !',
            'nama_barang.required'  => 'Form Nama Barang Wajib Di Isi !',
            'jenis_id.required'     => 'Pilih Jenis Barang !',
            'size.required'         => 'Form Size Wajib Di Isi !',
            'stok_minimum.required' => 'Form Stok Minimum Wajib Di Isi !',
            'stok_minimum.numeric'  => 'Gunakan Angka Untuk Mengisi Form Ini !',
            'stok_maximum.required' => 'Form Stok Maksimum Wajib Di Isi !',
            'stok_maximum.numeric'  => 'Gunakan Angka Untuk Mengisi Form Ini !',
            'stok.numeric'          => 'Gunakan Angka Untuk Mengisi Form Ini !',
            'nama_supplier.required'=> 'Form Nama Supplier Wajib Di Isi !',
            'price.required'        => 'Form Harga Wajib Di Isi !',
            'price.numeric'         => 'Gunakan Angka Untuk Mengisi Form Harga !',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $barang = Barang::create([
            'kode'          => $request->kode,
            'nama_barang'   => $request->nama_barang,
            'jenis_id'      => $request->jenis_id,
            'size'          => $request->size,
            'stok_minimum'  => $request->stok_minimum,
            'stok_maximum'  => $request->stok_maximum,
            'stok'          => $request->stok ?? 0,  // Default 0 jika null
            'nama_supplier' => $request->nama_supplier,
            'price'         => $request->price,
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Disimpan !',
            'data'      => $barang
        ]);
    }


    public function show(Barang $barang)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Barang',
            'data'    => $barang
        ]);
    }

    public function edit(Barang $barang)
    {
        return response()->json([
            'success' => true,
            'message' => 'Edit Data Barang',
            'data'    => $barang
        ]);
    }

    public function update(Request $request, Barang $barang)
    {
        $validator = Validator::make($request->all(), [
            'kode'          => 'required|string|max:15|unique:barangs,kode,' . $barang->id,
            'nama_barang'   => 'required',
            'jenis_id'      => 'required',
            'size'          => 'required|string|max:10',
            'stok_minimum'  => 'required|numeric',
            'stok_maximum'  => 'required|numeric',
            'stok'          => 'nullable|numeric',
            'nama_supplier' => 'required|string|max:100',
            'price'         => 'required|numeric',
        ], [
            'kode.required'         => 'Form Kode Barang Wajib Di Isi !',
            'kode.unique'           => 'Kode Barang Sudah Terdaftar !',
            'nama_barang.required'  => 'Form Nama Barang Wajib Di Isi !',
            'jenis_id.required'     => 'Pilih Jenis Barang !',
            'size.required'         => 'Form Size Wajib Di Isi !',
            'stok_minimum.required' => 'Form Stok Minimum Wajib Di Isi !',
            'stok_minimum.numeric'  => 'Gunakan Angka Untuk Mengisi Form Ini !',
            'stok_maximum.required' => 'Form Stok Maksimum Wajib Di Isi !',
            'stok_maximum.numeric'  => 'Gunakan Angka Untuk Mengisi Form Ini !',
            'stok.numeric'          => 'Gunakan Angka Untuk Mengisi Form Ini !',
            'nama_supplier.required'=> 'Form Nama Supplier Wajib Di Isi !',
            'price.required'        => 'Form Harga Wajib Di Isi !',
            'price.numeric'         => 'Gunakan Angka Untuk Mengisi Form Harga !',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update data barang tanpa gambar
        $barang->update([
            'kode'          => $request->kode,
            'nama_barang'   => $request->nama_barang,
            'jenis_id'      => $request->jenis_id,
            'size'          => $request->size,
            'stok_minimum'  => $request->stok_minimum,
            'stok_maximum'  => $request->stok_maximum,
            'stok'          => $request->stok ?? 0,  // Default 0 jika null
            'nama_supplier' => $request->nama_supplier,
            'price'         => $request->price,
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Terupdate',
            'data'      => $barang
        ]);
    }

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    { 
        Barang::destroy($barang->id);

        return response()->json([
            'success' => true,
            'message' => 'Data Barang Berhasil Dihapus!'
        ]);
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $import = new BarangImport();
            Excel::import($import, $request->file('file'));

            // Ambil data terbaru setelah import
            $barangs = Barang::with('jenis')->get();

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Diimport!',
                'data' => $barangs
            ]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }

            return response()->json([
                'success' => false,
                'message' => 'Validation Errors',
                'errors' => $errorMessages
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
