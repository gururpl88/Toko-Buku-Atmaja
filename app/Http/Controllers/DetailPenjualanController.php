<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;

class DetailPenjualanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_penjualan' => 'required',
            'id_buku' => 'required',
            'jumlah_jual' => 'required|integer|min:1'
        ]);

        DetailPenjualan::create($request->all());

        return back()->with('success', 'Detail penjualan berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        DetailPenjualan::findOrFail($id)->delete();
        return back()->with('success', 'Detail berhasil dihapus.');
    }
}
