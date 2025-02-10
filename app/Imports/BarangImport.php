<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Illuminate\Support\Facades\Hash;
use App\Models\Barang;

class BarangImport implements ToModel, WithMappedCells
{
    public function mapping(): array
    {
        return [
            'kode'          => 'B1',
            'nama_barang'   => 'C1',
            'jenis_id'      => 'D1',
            'size'          => 'E1',
            'stok_minimum'  => 'F1',
            'stok_maximum'  => 'G1',
            'stok'          => 'H1',
            'supplier' => 'I1',
            'harga'         => 'J1'
        ];
    }

    public function model(array $row)
    {
        return new Barang([
            'kode'           => $row['kode'],
            'nama_barang'    => $row['nama_barang'],
            'jenis_id'       => $row['jenis_id'],
            'size'           => $row['size'],
            'stok_minimum'   => $row['stok_minimum'],
            'stok_maximum'   => $row['stok_maximum'],
            'stok'           => $row['stok'],
            'nama_supplier'  => $row['supplier'],
            'price'          => $row['harga'],
        ]);
    }

    public function rules(): array
    {
        return [
            'jenis_id' => 'required|exists:jenis,id',
            'kode' => 'required|unique:barangs,kode',
            'nama_barang' => 'required',
            'size' => 'required',
            'stok_minimum' => 'required|numeric',
            'stok_maximum' => 'required|numeric',
            'stok' => 'required|numeric',
            'supplier' => 'required',
            'harga' => 'required|numeric'
        ];
    }

    public function customValidationMessages()
    {
        return [
            'jenis_id.exists' => 'ID Jenis tidak ditemukan dalam database.',
        ];
    }
}
