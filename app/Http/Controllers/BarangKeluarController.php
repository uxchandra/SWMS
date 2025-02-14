<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangKeluar;
use App\Models\BarangKeluarItem;
use App\Models\Barang;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BarangKeluarController extends Controller
{
    public function processScan(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'tanggal_keluar' => 'required|date',
            'barang_id' => 'required|array',
            'barang_id.*' => 'exists:barangs,id',
            'qty' => 'required|array',
            'qty.*' => 'integer|min:1',
        ]);

        // Ambil data order
        $order = Order::findOrFail($id);

        // Buat entri di tabel barang_keluars
        $barangKeluar = BarangKeluar::create([
            'order_id' => $order->id,
            'tanggal_keluar' => $request->tanggal_keluar,
            'user_id' => Auth::id(), // ID user yang melakukan scan
        ]);

        // Simpan barang yang di-scan ke tabel barang_keluar_items
        foreach ($request->barang_id as $index => $barangId) {
            $quantity = $request->qty[$index];

            // Cek stok barang
            $barang = Barang::findOrFail($barangId);
            if ($barang->stok < $quantity) {
                return redirect()->back()->with('error', 'Stok barang ' . $barang->nama_barang . ' tidak mencukupi.');
            }

            // Kurangi stok barang
            $barang->stok -= $quantity;
            $barang->save();

            // Simpan ke tabel barang_keluar_items
            BarangKeluarItem::create([
                'barang_keluar_id' => $barangKeluar->id,
                'barang_id' => $barangId,
                'quantity' => $quantity,
            ]);
        }

        // Update status order menjadi "Processed"
        $order->status = 'Ready';
        $order->save();

        // Redirect dengan pesan sukses
        return redirect()->route('orders.index')->with('success', 'Barang berhasil di-scan dan data telah disimpan.');
    }

    public function index()
    {
        $barangKeluar = BarangKeluar::with([
            'user',
            'items',
            'order.department'  
        ])
            ->withCount('items')
            ->withSum('items as total_quantity', 'quantity')
            ->orderBy('tanggal_keluar', 'desc')
            ->get();

        return view('barang-keluar.index', compact('barangKeluar'));
    }

    /**
     * Menampilkan detail barang keluar untuk modal
     * Termasuk informasi items dengan kode dan nama barang
     */
    public function detail(BarangKeluar $barangKeluar)
    {
        // Memuat relasi yang dibutuhkan dalam satu query untuk optimasi
        $barangKeluar->load([
            'items.barang',  // Memuat data barang untuk setiap item
            'user',          // Memuat data user yang melakukan scan
            'order'          // Memuat data order terkait
        ]);
        
        // Memformat data untuk response JSON
        return response()->json([
            // Informasi umum transaksi
            'tanggal_keluar' => Carbon::parse($barangKeluar->tanggal_keluar)->translatedFormat('d F Y'),
            'waktu' => $barangKeluar->created_at->format('H:i'),
            'user_name' => $barangKeluar->user->name,
            
            // Informasi order terkait jika ada
            'order_info' => $barangKeluar->order ? [
                'id' => $barangKeluar->order->id,
                'status' => $barangKeluar->order->status,
                'keterangan' => $barangKeluar->order->keterangan,
            ] : null,
            
            // Detail items yang dikeluarkan
            'items' => $barangKeluar->items->map(function ($item) {
                return [
                    'kode_barang' => $item->barang->kode ?? '-',
                    'nama_barang' => $item->barang->nama_barang ?? 'Barang tidak ditemukan',
                    'quantity' => $item->quantity,
                    'satuan' => $item->barang->satuan ?? '-',
                ];
            }),
            
            // Informasi summary
            'summary' => [
                'total_items' => $barangKeluar->items->count(),
                'total_quantity' => $barangKeluar->items->sum('quantity'),
            ]
        ]);
    }

}
