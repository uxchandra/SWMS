<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index()
    {
        
        $barangMasuk = BarangMasuk::query()
            ->join('users', 'barang_masuks.user_id', '=', 'users.id')
            ->select(
                'barang_masuks.tanggal_masuk',
                'barang_masuks.user_id',
                'users.name as user_name'
            )
            ->selectRaw('COUNT(*) as items_count')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->groupBy('tanggal_masuk', 'user_id', 'users.name')
            ->orderBy('tanggal_masuk', 'desc')
            ->get();
        
        return view('barang-masuk.index', compact('barangMasuk'));
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
        $barang = Barang::where('kode', $kode)->first();

        if ($barang) {
            return response()->json([
                'success' => true,
                'barang' => $barang
            ]);
        }

        return response()->json([
            'success' => false
        ]);
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
        // Validate the request
        $request->validate([
            'barang_id' => 'required|array',
            'qty' => 'required|array',
            'qty.*' => 'required|numeric|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Loop through each item in the form
            foreach ($request->barang_id as $key => $barangId) {
                // Create barang masuk record
                BarangMasuk::create([
                    'tanggal_masuk' => now(),
                    'barang_id' => $barangId,
                    'quantity' => $request->qty[$key],
                    'user_id' => Auth::user()->id
                ]);

                // Update stock in barang table
                $barang = Barang::findOrFail($barangId);
                $barang->increment('stok', $request->qty[$key]);
            }

            DB::commit();

            return redirect()
                ->route('barang-masuk.index')
                ->with('success', 'Barang masuk berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
