@extends('layouts.master')

@section('title', 'Daftar Buku')

@section('content')

<div class="row mb-3">
    <div class="col-md-12">
        <form method="GET" action="{{ route('buku.index') }}" class="d-flex" role="search">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari Buku..." value="{{ $cari ?? '' }}">
                <button class="btn btn-outline-primary" type="submit">
                    <i class="bi bi-search"></i> Cari
                </button>
                <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#modalTambahBuku">
                    <i class="bi bi-plus-circle"></i> Tambah
                </button>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
    <div id="alert-success" class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    @forelse ($dtBuku as $buku)
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-start border-4 border-primary shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-start">
                <h5 class="card-title text-truncate" style="max-width: 200px;">{{ $buku->judul_buku }}</h5>
                <span class="badge bg-{{ $buku->stok > 0 ? 'primary' : 'danger' }}">Stok: {{ $buku->stok }}</span>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Penulis:</strong> {{ $buku->penulis }}</p>
                <p class="mb-1"><strong>Penerbit:</strong> {{ $buku->penerbit }}</p>
                <p class="mb-1"><strong>Tahun:</strong> {{ $buku->tahun_terbit }}</p>
                <p class="mb-1"><strong>ISBN:</strong> {{ $buku->nomor_isbn }}</p>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
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
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalDetailBuku{{ $buku->id_buku }}">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalEditBuku{{ $buku->id_buku }}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        
                        <form action="{{ route('buku.destroy', $buku) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center">Data buku tidak ditemukan.</div>
    </div>
    @endforelse
</div>
<div class="d-flex justify-content-center mt-4">
    {{ $dtBuku->links() }}
</div>


@foreach ($dtBuku as $buku)
<!-- Modal Detail Buku -->
<div class="modal fade" id="modalDetailBuku{{ $buku->id_buku }}" tabindex="-1" aria-labelledby="modalDetailLabel{{ $buku->id_buku }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailLabel{{ $buku->id_buku }}">Detail Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h4>{{ $buku->judul_buku }}</h4>
                <p><strong>ID Buku:</strong> {{ $buku->id_buku }}</p>
                <p><strong>ISBN:</strong> {{ $buku->nomor_isbn }}</p>
                <p><strong>Penulis:</strong> {{ $buku->penulis }}</p>
                <p><strong>Penerbit:</strong> {{ $buku->penerbit }}</p>
                <p><strong>Tahun:</strong> {{ $buku->tahun_terbit }}</p>
                <p><strong>Stok:</strong> {{ $buku->stok }}</p>
                <p><strong>Harga Pokok:</strong> Rp{{ number_format($buku->harga_pokok) }}</p>
                <p><strong>Harga Jual:</strong> Rp{{ number_format($buku->harga_jual) }}</p>
                <p><strong>Diskon:</strong> {{ $buku->diskon }}%</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Buku -->
<div class="modal fade" id="modalEditBuku{{ $buku->id_buku }}" tabindex="-1" aria-labelledby="modalEditLabel{{ $buku->id_buku }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('buku.update', $buku->id_buku) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel{{ $buku->id_buku }}">Edit Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row g-3">
                    <x-input name="id_buku" label="ID Buku" value="{{ $buku->id_buku }}" required />
                    <x-input name="judul_buku" label="Judul Buku" value="{{ $buku->judul_buku }}" required />
                    <x-input name="nomor_isbn" label="Nomor ISBN" value="{{ $buku->nomor_isbn }}" required />
                    <x-input name="penulis" label="Penulis" value="{{ $buku->penulis }}" required />
                    <x-input name="penerbit" label="Penerbit" value="{{ $buku->penerbit }}" required />
                    <x-input name="tahun_terbit" label="Tahun Terbit" type="number" value="{{ $buku->tahun_terbit }}" required />
                    <x-input name="stok" label="Stok" type="number" value="{{ $buku->stok }}" required />
                    <x-input name="diskon" label="Diskon (%)" type="number" value="{{ $buku->diskon }}" />
                    <x-input name="harga_pokok" label="Harga Pokok" type="number" value="{{ $buku->harga_pokok }}" required />
                    <x-input name="harga_jual" label="Harga Jual" type="number" value="{{ $buku->harga_jual }}" required />
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach




<!-- Modal Tambah Buku -->
<div class="modal fade" id="modalTambahBuku" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('buku.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Buku Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <x-input name="id_buku" label="ID Buku" required />
                    <x-input name="judul_buku" label="Judul Buku" required />
                    <x-input name="nomor_isbn" label="Nomor ISBN" required />
                    <x-input name="penulis" label="Penulis" required />
                    <x-input name="penerbit" label="Penerbit" required />
                    <x-input name="tahun_terbit" label="Tahun Terbit" type="number" required />
                    <x-input name="stok" label="Stok" type="number" required />
                    <x-input name="diskon" label="Diskon (%)" type="number" value="0" />
                    <x-input name="harga_pokok" label="Harga Pokok" type="number" required />
                    <x-input name="harga_jual" label="Harga Jual" type="number" required />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Buku</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
