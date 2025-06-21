<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Modul;

class BukuController extends Controller
{

    public function index(Request $request)
    {
        $cari = $request->input('search');
        $moduls = Modul::all();
        if ($cari) {
            $dtBuku = Buku::where('judul_buku', 'like', "%$cari%")
                            ->orWhere('nomor_isbn', 'like', "%$cari%")
                            ->orWhere('penulis', 'like', "%$cari%")
                            ->orWhere('penerbit', 'like', "%$cari%")
                            ->orWhere('tahun_terbit', 'like', "%$cari%")
                            ->orderBy('judul_buku')
                            ->paginate(6)
                            ->appends(['search' => $cari]);
        } else {
            $dtBuku = Buku::orderBy('judul_buku')->paginate(6);
        }
        return view('buku.index', compact('dtBuku', 'cari', 'moduls'));
    }

    public function store(Request $request)
    {
        Buku::create($request->all());
        return redirect()->route('buku.index')->with('success', 'Data berhasil disimpan!');
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        return view('buku.edit', compact('buku'));
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $validasiBuku = $request->validate([
            'id_buku' => 'required',
            'judul_buku' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'stok' => 'required',
            'harga_pokok' => 'required',
            'harga_jual' => 'required',
            'diskon' => 'nullable|numeric|min:0|max:100',
        ]);

        $buku->update($validasiBuku);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy(Buku $buku)
    {
        $buku->delete();
        return redirect()->route('buku.index')->with('success', 'Data berhasil dihapus!');
    }
}
