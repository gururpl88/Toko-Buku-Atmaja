<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Modul;
use App\Models\Distributor;
use App\Models\Buku;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $cari = $request->input('search');
        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');
        $tanggalHistory = $request->input('tanggal_history');
        $cariBuku = $request->input('search_buku');

        $moduls = Modul::all();
        $dtDistributor = Distributor::orderBy('nama_distributor')->get();

        // Query pembelian dengan relasi distributor dan buku
        $dtPembelian = Pembelian::with(['distributor', 'buku'])
        ->when($cari, fn($q) => $q->where('id_pembelian', 'like', "%$cari%"))
        ->when($tanggalAwal && $tanggalAkhir, fn($q) => $q->whereBetween('tanggal_beli', [$tanggalAwal, $tanggalAkhir]))
        ->orderBy('tanggal_beli', 'desc')
        ->paginate(10)
        ->appends(request()->query());

        if ($cari) {
            $dtPembelian->where(function ($query) use ($cari) {
                $query->where('id_pembelian', 'like', "%$cari%")
                    ->orWhere('id_distributor', 'like', "%$cari%")
                    ->orWhere('id_buku', 'like', "%$cari%")
                    ->orWhere('tanggal_beli', 'like', "%$cari%")
                    ->orWhereHas('distributor', function ($q) use ($cari) {
                        $q->where('nama_distributor', 'like', "%$cari%");
                    })
                    ->orWhereHas('buku', function ($q) use ($cari) {
                        $q->where('judul_buku', 'like', "%$cari%");
                    });
            });
        }

        if ($tanggalAwal && $tanggalAkhir) {
            $dtPembelian->whereBetween('tanggal_beli', [$tanggalAwal, $tanggalAkhir]);
        }

        if ($tanggalHistory) {
            // Gunakan whereDate supaya logika lebih tepat dan query tidak bentrok
            $dtPembelian->whereDate('tanggal_beli', $tanggalHistory);
        }

        $dtPembelian = Pembelian::with(['distributor', 'buku'])
            ->orderBy('tanggal_beli', 'desc')
            ->paginate(6)
            ->appends([
                'search' => $cari,
                'tanggal_awal' => $tanggalAwal,
                'tanggal_akhir' => $tanggalAkhir,
                'tanggal_history' => $tanggalHistory,
            ]);

        // Filter & paginasi buku
        if ($cariBuku) {
            $dtBuku = Buku::where('judul_buku', 'like', "%$cariBuku%")
                ->orWhere('nomor_isbn', 'like', "%$cariBuku%")
                ->orWhere('penulis', 'like', "%$cariBuku%")
                ->orWhere('penerbit', 'like', "%$cariBuku%")
                ->orWhere('tahun_terbit', 'like', "%$cariBuku%")
                ->orderBy('judul_buku')
                ->paginate(6)
                ->appends(['search_buku' => $cariBuku]);
        } else {
            $dtBuku = Buku::orderBy('judul_buku')->paginate(6);
        }

        // Options untuk select box
        $distributorOptions = Distributor::pluck('nama_distributor', 'id');
        $bukuOptions = Buku::pluck('judul_buku', 'id_buku');

        return view('pembelian.index', compact(
            'dtPembelian',
            'cari',
            'tanggalAwal',
            'tanggalAkhir',
            'tanggalHistory',
            'dtBuku',
            'cariBuku',
            'moduls',
            'dtDistributor',
            'distributorOptions',
            'bukuOptions'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pembelian' => 'required|string|max:255|unique:pembelian,id_pembelian',
            'id_buku' => 'required|string|exists:buku,id_buku',  
            'id_distributor' => 'required|integer|exists:distributor,id',
            'jumlah_beli' => 'required|integer|min:1',
            'tanggal_beli' => 'required|date',
        ]);

        Pembelian::create($validated);

        return redirect()->route('pembelian.index')->with('success', 'Data berhasil disimpan!');
    }

    public function edit($id)
    {
        $pembelian = Pembelian::findOrFail($id);
        return view('pembelian.edit', compact('pembelian'));
    }

    public function update(Request $request, $id)
    {
        $pembelian = Pembelian::findOrFail($id);

        $validated = $request->validate([
            'id_pembelian' => 'required|string|max:255|unique:pembelian,id_pembelian',
            'id_buku' => 'required|string|exists:buku,id_buku',  // jadi string dan pakai id_buku
            'id_distributor' => 'required|integer|exists:distributor,id',
            'jumlah_beli' => 'required|integer|min:1',
            'tanggal_beli' => 'required|date',
        ]);

        $pembelian->update($validated);

        return redirect()->route('pembelian.index')->with('success', 'Data pembelian berhasil diperbarui!');
    }

    public function destroy(Pembelian $pembelian)
    {
        $pembelian->delete();
        return redirect()->route('pembelian.index')->with('success', 'Data berhasil dihapus!');
    }
}
