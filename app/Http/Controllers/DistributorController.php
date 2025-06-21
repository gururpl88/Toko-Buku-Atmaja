<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Modul;

class DistributorController extends Controller
{
    public function index(Request $request)
    {
        $cari = $request->input('search');
        $moduls = Modul::all();
        if ($cari) {
            $dtDistributor = Distributor::where('kode', 'like', "%$cari%")
                            ->orWhere('nama_distributor', 'like', "%$cari%")
                            ->orWhere('alamat', 'like', "%$cari%")
                            ->orWhere('telp_distributor', 'like', "%$cari%")
                            ->orWhere('id', 'like', "%$cari%")
                            ->orderBy('kode')
                            ->paginate(6)
                            ->appends(['search' => $cari]);
        } else {
            $dtDistributor = Distributor::orderBy('kode')->paginate(6);
        }
        return view('distributor.index', compact('dtDistributor', 'cari', 'moduls'));
    }

    public function store(Request $request)
    {
        Distributor::create($request->all());
        return redirect()->route('distributor.index')->with('success', 'Data berhasil disimpan!');
    }

    public function edit(Distributor $distributor)
    {
        $distributor = Distributor::findOrFail($id);
        return view('distributor.edit', compact('distributor'));
    }

    public function update(Request $request, $id)
    {
        $distributor = Distributor::findOrFail($id);

        $validasiDistributor = $request->validate([
            'kode' => 'required|string|max:255',
            'nama_distributor' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'telp_distributor' => 'required|string|max:255',
            'id' => 'required|string|max:255',
        ]);

        $distributor->update($validasiDistributor);

        return redirect()->route('distributor.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy(Distributor $distributor)
    {
        $distributor->delete();
        return redirect()->route('distributor.index')->with('success', 'Data berhasil dihapus!');
    }
}
