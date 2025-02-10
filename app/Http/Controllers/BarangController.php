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

    public function update(Request $request, Barang $barang)
    {
        $validator = Validator::make($request->all(), [
            'nama_barang'   => 'required',
            'deskripsi'     => 'required',
            'gambar'        => 'nullable|mimes:jpeg,png,jpg',
            'stok_minimum'  => 'required|numeric',
            'jenis_id'      => 'required',
            'satuan_id'      => 'required',
            'kode_barang'   => 'required|string|max:15|unique:barangs,kode_barang,'
        ], [
            'nama_barang.required'  => 'Form Nama Barang Wajib Di Isi !',
            'deskripsi.required'    => 'Form Deskripsi Wajib Di Isi !',
            'gambar.mimes'          => 'Gunakan Gambar Yang Memiliki Format jpeg, png, jpg !',
            'stok_minimum.required' => 'Form Stok Minimum Wajib Di Isi !',
            'stok_minimum.numeric'  => 'Gunakan Angka Untuk Mengisi Form Ini !',
            'jenis_id.required'     => 'Pilih Jenis Barang !',
            'satuan_id.required'    => 'Pilih Satuan Barang !',
            'kode_barang.required'   => 'Form Kode Barang Wajib Di Isi !',
            'kode_barang.unique'     => 'Kode Barang Sudah Terdaftar !',
        ]);
    
        // cek apakah gambar diubah atau tidak
        if($request->hasFile('gambar')){
            // hapus gambar lama
            if($barang->gambar) {
                unlink('.'.Storage::url($barang->gambar));
            }
            $path       = 'gambar-barang/';
            $file       = $request->file('gambar');
            $fileName   = $file->getClientOriginalName();
            $gambar     = $file->storeAs($path, $fileName, 'public');
            $path      .= $fileName; 
        } else {
            // jika tidak ada file gambar, gunakan gambar lama
            $validator = Validator::make($request->all(), [
                'nama_barang'   => 'required',
                'deskripsi'     => 'required',
                'stok_minimum'  => 'required|numeric',
                'jenis_id'      => 'required',
                'satuan_id'      => 'required',
                'kode_barang'   => 'required|string|max:15|unique:barangs,kode_barang,' . $barang->id
            ], [
                'nama_barang.required'  => 'Form Nama Barang Wajib Di Isi !',
                'deskripsi.required'    => 'Form Deskripsi Wajib Di Isi !',
                'stok_minimum.required' => 'Form Stok Minimum Wajib Di Isi !',
                'stok_minimum.numeric'  => 'Gunakan Angka Untuk Mengisi Form Ini !',
                'jenis_id.required'     => 'Pilih Jenis Barang !',
                'satuan_id.required'    => 'Pilih Satuan Barang !',
                'kode_barang.required'   => 'Form Kode Barang Wajib Di Isi !',
                'kode_barang.unique'     => 'Kode Barang Sudah Terdaftar !',
            ]);

            $path = $barang->gambar;
        } 
        
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
    
        $barang->update([
            'nama_barang'   => $request->nama_barang,
            'stok_minimum'  => $request->stok_minimum, 
            'deskripsi'     => $request->deskripsi,
            'user_id'       => Auth::user()->id,
            'gambar'        => $path,
            'jenis_id'      => $request->jenis_id,
            'satuan_id'     => $request->satuan_id,
            'kode_barang'   => $request->kode_barang
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
        unlink('.'.Storage::url($barang->gambar));
    
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
            $result = Excel::import($import, $request->file('file'));

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Diimport!'
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
