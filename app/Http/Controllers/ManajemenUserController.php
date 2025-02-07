<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class ManajemenUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('data-pengguna.index', [
            'penggunas' => User::all(),
            'roles'     => Role::all()
        ]);
    }

    public function getDataPengguna()
    {
        $penggunas = User::with('role')->get();

        return response()->json([
            'success'   => true,
            'data'      => $penggunas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('data-pengguna.create', [
            'penggunas' => User::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'username'     => 'required',
            'password'  => 'required|min:1',
            'role_id'   => 'required'
        ], [
            'name.required'     => 'Form Nama Wajib Di isi !',
            'username.required'    => 'Form Username Wajib Di isi !',
            'password.required' => 'Form Password Wajib Di isi !',
            'password.min'      => 'Password Minimal 4 Huruf/Angka/Karakter !',
            'role_id.required'  => 'Tentukan Role/Hak Akses !',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $pengguna = User::create([
            'name'      => $request->name,
            'username'     => $request->username,
            'password'  => Hash::make($request->password),
            'plain_password' => Crypt::encryptString($request->password),
            'role_id'   => $request->role_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Tersimpan',
            'data'     => $pengguna
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pengguna = User::find($id);
        return response()->json([
            'success'   => true,
            'message'   => 'Edit Data Pengguna',
            'data'      => $pengguna
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pengguna = User::find($id);

        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'username'     => 'required',
            'role_id'   => 'required'
        ], [
            'name.required'     => 'Form Nama Wajib Di isi !',
            'username.required'    => 'Form Username Wajib Di isi !',
            'role_id.required'  => 'Tentukan Role/Hak Akses !',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $userData = [
            'name'      => $request->name,
            'username'     => $request->username,
            'role_id'   => $request->role_id
        ];

        // Cek apakah password diubah atau tidak
        if (!empty($request->password)) {
            $validatorPassword = Validator::make($request->all(), [
                'password'  => 'min:1'
            ], [
                'password.min'  => 'Password minimal 4 Huruf/Angka/Karakter !'
            ]);

            if ($validatorPassword->fails()) {
                return response()->json($validatorPassword->errors(), 422);
            }

            $userData['password'] = Hash::make($request->password);
        }

        $pengguna->update($userData);

        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil Terupdate',
            'data'      => $pengguna
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        User::find($id)->delete($id);
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!'
        ]);
    }

    /**
     * Get Role
     */
    public function getRole()
    {
        $roles = Role::all();

        return response()->json($roles);
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
            Excel::import(new UsersImport, $request->file('file'));

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Diimport!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
