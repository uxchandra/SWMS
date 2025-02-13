<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Department;
use App\Models\Barang;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['requester', 'department', 'orderItems.barang'])->get();
        $departments = Department::all();
        $barangs = Barang::all();

        return view('orders.index', compact('orders', 'departments', 'barangs'));
    }
    public function create()
    {
        $departments = Department::all();
        return view('orders.create', compact('departments'));
    }

    public function store(Request $request)
    {

        // Validasi data yang diterima dari form
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'barang_id' => 'required|array',
            'barang_id.*' => 'exists:barangs,id',
            'quantity' => 'required|array',
            'quantity.*' => 'integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        // Buat order baru
        $order = Order::create([
            'requester_id' => Auth::id(),
            'department_id' => $request->department_id,
            'status' => 'Pending',
            'catatan' => $request->catatan,
        ]);

        // Simpan item-item yang diminta
        foreach ($request->barang_id as $index => $barangId) {
            $barang = Barang::find($barangId);
            if ($barang) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'barang_id' => $barangId,
                    'quantity' => $request->quantity[$index],
                ]);
            }
        }

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('orders.index')->with('success', 'Permintaan barang berhasil dibuat.');
    }

    public function show(Order $order)
    {
        $order->load(['requester', 'department', 'orderItems.barang']);
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $departments = Department::all();
        return view('orders.edit', compact('order', 'departments'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Pending,Approved by Kadiv,Approved by Kagud,Processed',
            'catatan' => 'nullable|string',
        ]);

        $order->update([
            'status' => $request->status,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('orders.index')->with('success', 'Order berhasil diperbarui');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order berhasil dihapus');
    }
}
