<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Department;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        // Ambil data orders dengan relasi requester dan department
        $orders = Order::with(['requester', 'department'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        // Ambil data barang dari database
        $barangs = Barang::all(); // Sesuaikan dengan model dan query Anda

        // Kirim data orders dan barangs ke view
        return view('orders.index', compact('orders', 'barangs'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('orders.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'catatan' => 'nullable|string',
        ]);

        Order::create([
            'requester_id' => Auth::user()->id,
            'department_id' => $request->department_id,
            'status' => 'Pending',
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('orders.index')->with('success', 'Order berhasil dibuat');
    }

    public function show(Order $order)
    {
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
