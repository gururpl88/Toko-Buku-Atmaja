<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailPenjualan;
use App\Models\Kasir;
use App\Models\Buku;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cari = $request->input('search');
        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        $query = Penjualan::with(['kasir', 'detailPenjualan.buku']);

        if ($cari) {
            $query->where('id_penjualan', 'like', "%$cari%")
                  ->orWhereHas('kasir', function ($q) use ($cari) {
                      $q->where('nama_kasir', 'like', "%$cari%");
                  });
        }

        if ($tanggalAwal && $tanggalAkhir) {
            $query->whereBetween('tanggal_penjualan', [$tanggalAwal, $tanggalAkhir]);
        }

        $dtPenjualan = $query->orderByDesc('tanggal_penjualan')->paginate(10);

        $kasirOptions = Kasir::pluck('nama_kasir', 'id_kasir');
        $bukuOptions = Buku::pluck('judul_buku', 'id_buku');

        $dtBuku = Buku::when($cari, function ($q) use ($cari) {
            $q->where('judul_buku', 'like', "%$cari%")
              ->orWhere('penulis', 'like', "%$cari%")
              ->orWhere('penerbit', 'like', "%$cari%");
        })->orderBy('judul_buku')->paginate(10); 
        

        return view('penjualan.index', compact(
            'dtPenjualan', 
            'kasirOptions', 
            'bukuOptions', 
            'dtBuku', 
            'cari', 
            'tanggalAwal', 
            'tanggalAkhir'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_penjualan' => 'required|unique:penjualan,id_penjualan',
            'id_kasir' => 'required',
            'tanggal_penjualan' => 'required|date',
            'id_buku' => 'required',
            'jumlah_jual' => 'required|integer|min:1'
        ]);

        $penjualan = Penjualan::create($request->only(['id_penjualan', 'id_kasir', 'tanggal_penjualan']));

        DetailPenjualan::create([
            'id_penjualan' => $penjualan->id_penjualan,
            'id_buku' => $request->id_buku,
            'jumlah_jual' => $request->jumlah_jual
        ]);

        return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        Penjualan::findOrFail($id)->delete();
        return back()->with('success', 'Data penjualan berhasil dihapus.');
    }
}
