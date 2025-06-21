@extends('layouts.master')

@section('title', 'Data Pembelian & Buku')

@section('content')
<div class="row">
    <!-- Kolom Kiri: Buku -->
    <div class="col-md-4 border-end" style="max-height: 90vh; overflow-y: auto;">
        <h5>Cari Buku</h5>
        <form method="GET" action="{{ route('pembelian.index') }}">
            <div class="input-group mb-3">
                <input type="text" name="search_buku" class="form-control" placeholder="Cari buku..." value="{{ $cariBuku }}">
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
            </div>
        </form>

        @foreach($dtBuku as $buku)
            <div class="card mb-2">
                <div class="card-body p-2">
                    <strong>{{ $buku->judul_buku }}</strong><br>
                    <small>{{ $buku->penulis }} - {{ $buku->penerbit }}</small><br>
                    <small>Tahun: {{ $buku->tahun_terbit }}</small>
                    <span class="badge bg-{{ $buku->stok > 0 ? 'primary' : 'danger' }}">Stok: {{ $buku->stok }}</span>
                    <div>
                        @php
                            $hargaDiskon = $buku->harga_jual - ($buku->harga_jual * $buku->diskon / 100);
                        @endphp
                        @if($buku->diskon > 0)
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

    <!-- Kolom Kanan: Pembelian -->
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5>Data Pembelian</h5>
            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahPembelian">
                <i class="bi bi-plus-circle"></i> Tambah Pembelian
            </button>
        </div>

        <form method="GET" action="{{ route('pembelian.index') }}" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Cari pembelian" value="{{ $cari }}">
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
                    <th>Judul Buku</th>
                    <th>ID Pembelian</th>
                    <th>Tanggal Beli</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dtPembelian as $item)
                    <tr>
                        <td>{{ $item->buku->judul_buku ?? '-' }}</td>
                        <td>{{ $item->id_pembelian }}</td>
                        <td>{{ $item->tanggal_beli }}</td>
                        <td class="text-end">
                            <!-- Tombol View Detail -->
                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalDetailPembelian{{ $item->id }}">
                                <i class="bi bi-eye"></i>
                            </button>

                            <!-- Form Hapus -->
                            <form action="{{ route('pembelian.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pembelian ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Detail Pembelian -->
                    <div class="modal fade" id="modalDetailPembelian{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Pembelian</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>ID Pembelian:</strong> {{ $item->id_pembelian }}</p>
                                    <p><strong>Buku:</strong> {{ $item->buku->judul_buku ?? '-' }}</p>
                                    <p><strong>Distributor:</strong> {{ $item->distributor->nama_distributor ?? '-' }}</p>
                                    <p><strong>Jumlah Beli:</strong> {{ $item->jumlah_beli }}</p>
                                    @php
                                        $stokAwal = optional($item->buku)->stok - $item->jumlah_beli;
                                    @endphp
                                    <p><strong>Stok Sebelum Pembelian:</strong> {{ $stokAwal }}</p>
                                    <p><strong>Stok Setelah Pembelian:</strong> {{ $item->buku->stok }}</p>
                                    <p><strong>Tanggal Beli:</strong> {{ $item->tanggal_beli }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div>
            {{ $dtPembelian->links() }}
        </div>
    </div>
</div>

<!-- Modal Tambah Pembelian -->
@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif


<div class="modal fade" id="modalTambahPembelian" tabindex="-1" aria-labelledby="modalTambahPembelianLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('pembelian.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTambahPembelianLabel">Tambah Pembelian</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="id_pembelian" class="form-label">ID Pembelian</label>
              <input type="text" class="form-control" name="id_pembelian" required>
            </div>
            <div class="col-md-6">
              <label for="tanggal_beli" class="form-label">Tanggal Beli</label>
              <input type="date" class="form-control" name="tanggal_beli" required>
            </div>
            <div class="col-md-6">
              <label for="id_distributor" class="form-label">Distributor</label>
              <select class="form-select" name="id_distributor" required>
                <option value="">-- Pilih Distributor --</option>
                @foreach ($distributorOptions as $id => $nama)
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
              <label for="jumlah_beli" class="form-label">Jumlah Beli</label>
              <input type="number" class="form-control" name="jumlah_beli" min="1" required>
            </div>
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
