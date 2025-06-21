@extends('layouts.master')

@section('title', 'Daftar Distributor')

@section('content')

@if(session('success'))
    <div id="alert-success" class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row mb-3">
    <div class="col-md-12">
        <form method="GET" action="{{ route('distributor.index') }}">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari Distributor..." value="{{ $cari }}">
                <button class="btn btn-outline-primary" type="submit">
                    <i class="bi bi-search"></i> Cari
                </button>
                <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#modalTambahDistributor">
                    <i class="bi bi-plus-circle"></i> Tambah
                </button>
            </div>
        </form>
    </div>
</div>


<div class="table-responsive shadow-sm">
    <table class="table table-bordered align-middle table-hover">
        <thead class="table-primary text-center">
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Distributor</th>
                <th style="width: 130px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dtDistributor as $index => $distributor)
            <tr>
                <td class="text-center">{{ $dtDistributor->firstItem() + $index }}</td>
                <td>{{ $distributor->kode }}</td>
                <td>{{ $distributor->nama_distributor }}</td>

                <td class="text-end">
                    <div class="d-flex justify-content-end gap-1">
                        {{-- Show --}}
                        <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalDetailDistributor{{ $distributor->id }}">
                            <i class="bi bi-eye"></i>
                        </button>

                        {{-- Edit --}}
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditDistributor{{ $distributor->id }}">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        {{-- Delete --}}
                        <form action="{{ route('distributor.destroy', $distributor) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted">Data distributor tidak ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center mt-3">
    {{ $dtDistributor->links() }}
</div>
@foreach ($dtDistributor as $distributor)
<!-- Modal Detail Buku -->
<div class="modal fade" id="modalDetailDistributor{{ $distributor->id }}" tabindex="-1" aria-labelledby="modalDetailLabel{{ $distributor->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailLabel{{ $distributor->id }}">Detail Distributor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h4>{{ $distributor->nama_distributor }}</h4>
                <p><strong>Kode:</strong> {{ $distributor->kode }}</p>
                <p><strong>Alamat:</strong> {{ $distributor->alamat }}</p>
                <p><strong>No. Telp.:</strong> {{ $distributor->telp_distributor }}</p>
                <p><strong>ID:</strong> {{ $distributor->id }}</p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Buku -->
<div class="modal fade" id="modalEditDistributor{{ $distributor->id }}" tabindex="-1" aria-labelledby="modalEditLabel{{ $distributor->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('distributor.update', $distributor->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel{{ $distributor->id }}">Edit Distributor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row g-3">
                    <x-input name="kode" label="Kode Distributor" value="{{ $distributor->kode }}" required />
                    <x-input name="nama_distributor" label="Nama Distributor" value="{{ $distributor->nama_distributor }}" required />
                    <x-input name="alamat" label="Alamat Distributor" value="{{ $distributor->alamat }}" required />
                    <x-input name="telp_distributor" label="No. Contact Distributor" value="{{ $distributor->telp_distributor }}" required />
                    <x-input name="id" label="ID Distributor" value="{{ $distributor->id }}" required />
                    
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
<div class="modal fade" id="modalTambahDistributor" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('distributor.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Distributor Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    
                    <x-input name="kode" label="Kode Distributor" required />
                    <x-input name="nama_distributor" label="Nama Distributor" required />
                    <x-input name="alamat" label="Alamat Distributor" required />
                    <x-input name="telp_distributor" label="No. Contact Distributor" required />
                    <x-input name="id" label="ID Distributor" required />
                    
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
