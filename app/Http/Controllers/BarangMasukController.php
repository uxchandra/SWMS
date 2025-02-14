<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangMasukItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index()
    {
        $barangMasuk = BarangMasuk::query()
            ->join('users', 'barang_masuks.user_id', '=', 'users.id')
            ->leftJoin('barang_masuk_items', 'barang_masuks.id', '=', 'barang_masuk_items.barang_masuk_id')
            ->select(
                'barang_masuks.id',
                'barang_masuks.tanggal_masuk',
                'barang_masuks.created_at', // Tambahkan ini
                'barang_masuks.user_id',
                'users.name as user_name'
            )
            ->selectRaw('COUNT(DISTINCT barang_masuk_items.barang_id) as items_count')
            ->selectRaw('COALESCE(SUM(barang_masuk_items.quantity), 0) as total_quantity')
            ->groupBy('barang_masuks.id', 'tanggal_masuk', 'barang_masuks.created_at', 'user_id', 'users.name')
            ->orderBy('tanggal_masuk', 'desc')
            ->get();
        
        return view('barang-masuk.index', compact('barangMasuk'));
    }


    /**
     * Retrieve all data of 'Barang Masuk' records and return it as a JSON response.
     *
     * @return \Illuminate\Http\JsonResponse
     */

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

            // Create satu record barang_masuk (header)
            $barangMasuk = BarangMasuk::create([
                'tanggal_masuk' => now(),
                'user_id' => Auth::user()->id
            ]);

            // Loop untuk menyimpan items
            foreach ($request->barang_id as $key => $barangId) {
                // Create record di barang_masuk_items
                BarangMasukItem::create([
                    'barang_masuk_id' => $barangMasuk->id,
                    'barang_id' => $barangId,
                    'quantity' => $request->qty[$key]
                ]);

                // Update stock di tabel barang
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
        
    }

    public function detail($id)
    {
        // Ambil data transaksi barang masuk berdasarkan ID
        $transaksi = BarangMasuk::with(['items.barang', 'user'])
                                ->findOrFail($id);

        // Format data untuk response JSON
        $response = [
            'tanggal_masuk' => \Carbon\Carbon::parse($transaksi->tanggal_masuk)->format('d F Y'),
            'user_name' => $transaksi->user->name,
            'items' => $transaksi->items->map(function ($item) {
                return [
                    'kode_barang' => $item->barang->kode,
                    'nama_barang' => $item->barang->nama_barang,
                    'quantity' => $item->quantity,
                ];
            }),
        ];

        return response()->json($response);
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
