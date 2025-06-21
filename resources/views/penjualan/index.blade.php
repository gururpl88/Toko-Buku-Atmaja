@extends('layouts.master')

@section('title', 'Data Penjualan & Buku')

@section('content')
<div class="row">
    <!-- Kolom Kiri: Buku -->
    <div class="col-md-4 border-end" style="max-height: 90vh; overflow-y: auto;">
        <h5>Cari Buku</h5>
        <form method="GET" action="{{ route('penjualan.index') }}">
            <div class="input-group mb-3">
                <input type="text" name="search" class="form-control" placeholder="Cari buku..." value="{{ $cari }}">
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
            </div>
        </form>

        @foreach($dtBuku as $buku)
            <div class="card mb-2">
                <div class="card-body p-2">
                    <strong>{{ is_object($buku) ? $buku->judul_buku : '-' }}</strong><br>
                    <small>{{ is_object($buku) ? $buku->penulis : '-' }} - {{ is_object($buku) ? $buku->penerbit : '-' }}</small><br>
                    <small>Tahun: {{ is_object($buku) ? $buku->tahun_terbit : '-' }}</small>
                    <span class="badge bg-{{ is_object($buku) && $buku->stok > 0 ? 'primary' : 'danger' }}">Stok: {{ is_object($buku) ? $buku->stok : 0 }}</span>
                    <div>
                        @php
                            $hargaDiskon = is_object($buku) ? $buku->harga_jual - ($buku->harga_jual * $buku->diskon / 100) : 0;
                        @endphp
                        @if(is_object($buku) && $buku->diskon > 0)
                            <small class="text-muted text-decoration-line-through">
                                Rp {{ number_format($buku->harga_jual, 0, ',', '.') }}
                            </small>
                            <span class="badge bg-warning text-dark">-{{ $buku->diskon }}%</span>
                        @endif
                        <div class="fw-bold text-success">
                            Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        {{ $dtBuku->links() }} 
    </div>

    <!-- Kolom Kanan: Penjualan -->
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5>Data Penjualan</h5>
            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahPenjualan">
                <i class="bi bi-plus-circle"></i> Tambah Penjualan
            </button>
        </div>

        <form method="GET" action="{{ route('penjualan.index') }}" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Cari penjualan" value="{{ $cari }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="tanggal_awal" class="form-control" value="{{ $tanggalAwal }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="tanggal_akhir" class="form-control" value="{{ $tanggalAkhir }}">
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100">Cari</button>
            </div>
        </form>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID Penjualan</th>
                    <th>Kasir</th>
                    <th>Tanggal</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dtPenjualan as $item)
                    <tr>
                        <td>{{ $item->id_penjualan }}</td>
                        <td>{{ $item->kasir->nama_kasir ?? '-' }}</td>
                        <td>{{ $item->tanggal_penjualan }}</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalDetailPenjualan{{ $item->id_penjualan }}">
                                <i class="bi bi-eye"></i>
                            </button>
                            <form action="{{ route('penjualan.destroy', $item->id_penjualan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalDetailPenjualan{{ $item->id_penjualan }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Penjualan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>ID Penjualan:</strong> {{ $item->id_penjualan }}</p>
                                    <p><strong>Kasir:</strong> {{ $item->kasir->nama_kasir ?? '-' }}</p>
                                    <p><strong>Tanggal:</strong> {{ $item->tanggal_penjualan }}</p>
                                    <hr>
                                    <p><strong>Detail Buku:</strong></p>
                                    <ul>
                                        @foreach ($item->detailPenjualan as $detail)
                                            <li>{{ $detail->buku->judul_buku ?? '-' }} - Jumlah: {{ $detail->jumlah_jual }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>

        {{ $dtPenjualan->links() }}
    </div>
</div>

<div class="modal fade" id="modalTambahPenjualan" tabindex="-1" aria-labelledby="modalTambahPenjualanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('penjualan.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahPenjualanLabel">Tambah Penjualan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label for="id_penjualan" class="form-label">ID Penjualan</label>
                        <input type="text" class="form-control" name="id_penjualan" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_penjualan" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal_penjualan" required>
                    </div>
                    <div class="col-md-6">
                        <label for="id_kasir" class="form-label">Kasir</label>
                        <select class="form-select" name="id_kasir" required>
                            <option value="">-- Pilih Kasir --</option>
                            @foreach ($kasirOptions as $id => $nama)
                                <option value="{{ $id }}">{{ $nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="id_buku" class="form-label">Buku</label>
                        <select class="form-select" name="id_buku" required>
                            <option value="">-- Pilih Buku --</option>
                            @foreach ($bukuOptions as $id_buku => $judul)
                                <option value="{{ $id_buku }}">{{ $judul }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="jumlah_jual" class="form-label">Jumlah Jual</label>
                        <input type="number" class="form-control" name="jumlah_jual" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
