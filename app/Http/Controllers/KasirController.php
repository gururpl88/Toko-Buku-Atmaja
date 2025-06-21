<?php

namespace App\Http\Controllers;

use App\Models\Kasir;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Modul;

class KasirController extends Controller
{
    public function index(Request $request)
    {
        $cari = $request->input('search');
        $moduls = Modul::all();
        if ($cari) {
            $dtKasir = Kasir::where('id_kasir', 'like', "%$cari%")
                            ->orWhere('nama_kasir', 'like', "%$cari%")
                            ->orWhere('alamat_kasir', 'like', "%$cari%")
                            ->orWhere('telp', 'like', "%$cari%")
                            ->orWhere('status_kasir', 'like', "%$cari%")
                            ->orderBy('id_kasir')
                            ->paginate(6)
                            ->appends(['search' => $cari]);
        } else {
            $dtKasir = Kasir::orderBy('id_kasir')->paginate(6);
        }
        return view('kasir.index', compact('dtKasir', 'cari', 'moduls'));
    }

    public function toggleStatus($id_kasir)
    {
        $kasir = Kasir::findOrFail($id_kasir);
        $kasir->status_kasir = $kasir->status_kasir === 'Aktif' ? 'Tidak Aktif' : 'Aktif';
        $kasir->save();

        return redirect()->route('kasir.index')->with('success', 'Status kasir berhasil diperbarui!');
    }

    public function create(Request $request)
    {
        $cari = $request->input('search');
        $moduls = Modul::all();
        if ($cari) {
            $dtKasir = Kasir::where('id_kasir', 'like', "%$cari%")
                            ->orWhere('nama_kasir', 'like', "%$cari%")
                            ->orWhere('alamat_kasir', 'like', "%$cari%")
                            ->orWhere('telp', 'like', "%$cari%")
                            ->orWhere('status_kasir', 'like', "%$cari%")
                            ->orderBy('id_kasir')
                            ->paginate(6)
                            ->appends(['search' => $cari]);
        } else {
            $dtKasir = Kasir::orderBy('id_kasir')->paginate(6);
        }
        return view('kasir.index', compact('dtKasir', 'cari', 'moduls'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kasir' => 'required|string|max:255|unique:kasir,id_kasir',
            'nama_kasir' => 'required|string|max:255',  
            'alamat_kasir' => 'required|string|max:255',
            'telp' => 'required|string|max:255',
            'status_kasir' => 'required',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'akses' => 'required',
        ]);

        Kasir::create($validated);

        return redirect()->route('kasir.index')->with('success', 'Data berhasil disimpan!');
    }

    public function edit($id)
    {
        $kasir = Kasir::findOrFail($id);
        return view('kasir.edit', compact('kasir'));
    }

    public function update(Request $request, $id)
    {
        $kasir = Kasir::findOrFail($id);

        $validated = $request->validate([
            'id_kasir' => 'required|string|max:255|unique:kasir,id_kasir',
            'nama_kasir' => 'required|string|max:255',  
            'alamat_kasir' => 'required|string|max:255',
            'telp' => 'required|string|max:255',
            'status_kasir' => 'required',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'akses' => 'required',
        ]);

        $kasir->update($validated);

        return redirect()->route('kasir.index')->with('success', 'Data pembelian berhasil diperbarui!');
    }

    public function destroy(Kasir $kasir)
    {
        $kasir->delete();
        return redirect()->route('kasir.index')->with('success', 'Data berhasil dihapus!');
    }
}
