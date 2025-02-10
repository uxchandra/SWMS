<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('department.index', [
            'departments' => Department::all()
        ]);
    }

    public function getDataDepartment()
    {
        return response()->json([
            'success' => true,
            'data'    => Department::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('department.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_departemen'  => 'required'
        ],[
            'nama_departemen.required' => 'Form Jenis Barang Wajib Di Isi !'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $nama_departemen = Department::create([
            'nama_departemen' => $request->nama_departemen,
            'user_id'      => Auth::user()->id
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Disimpan !',
            'data'      => $nama_departemen
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $jenis)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $department = Department::findOrFail($id);   
        return response()->json([
            'success' => true,
            'message' => 'Edit Data Department',
            'data'    => $department
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $department = Department::find($id);

        $validator = Validator::make($request->all(),[
            'nama_departemen'  => 'required',
        ],[
            'nama_departemen.required' => 'Form Department Tidak Boleh Kosong'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $department->update([
            'nama_departemen'  => $request->nama_departemen,
            'user_id'      => Auth::user()->id
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Terupdate',
            'data'      => $department
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Department::find($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!'
        ]);    
    }
}
