@extends('layouts.master')

@section('title', 'Daftar Kasir')

@section('content')

@if(session('success'))
    <div id="alert-success" class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row mb-3">
    <div class="col-md-12">
        <form method="GET" action="{{ route('kasir.index') }}">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari Kasir..." value="{{ $cari }}">
                <button class="btn btn-outline-primary" type="submit">
                    <i class="bi bi-search"></i> Cari
                </button>
                <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#modalTambahKasir">
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
                <th>ID Kasir</th>
                <th>Nama Kasir</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dtKasir as $index => $kasir)
            <tr>
                <td class="text-center">{{ $dtKasir->firstItem() + $index }}</td>
                <td>{{ $kasir->id_kasir }}</td>
                <td>{{ $kasir->nama_kasir }}</td>
                <td class="text-center">
                    <form action="{{ route('kasir.toggleStatus', $kasir->id_kasir) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-check form-switch d-inline-block">
                            <input class="form-check-input" type="checkbox" onchange="this.form.submit()" {{ $kasir->status_kasir == 'Aktif' ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $kasir->status_kasir }}</label>
                        </div>
                    </form>
                </td>
                <td class="text-end">
                    <div class="d-flex justify-content-end gap-1">
                        <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalDetailKasir{{ $kasir->id_kasir }}">
                            <i class="bi bi-eye"></i>
                        </button>
                        <form action="{{ route('kasir.destroy', $kasir->id_kasir) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>

            <!-- Modal Detail Kasir -->
            <div class="modal fade" id="modalDetailKasir{{ $kasir->id_kasir }}" tabindex="-1" aria-labelledby="modalDetailLabel{{ $kasir->id_kasir }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalDetailLabel{{ $kasir->id_kasir }}">Detail Kasir</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <h4>{{ $kasir->nama_kasir }}</h4>
                            <p><strong>ID Kasir:</strong> {{ $kasir->id_kasir }}</p>
                            <p><strong>Alamat:</strong> {{ $kasir->alamat_kasir }}</p>
                            <p><strong>No. Telp.:</strong> {{ $kasir->telp }}</p>
                            <p><strong>Status:</strong> {{ $kasir->status_kasir }}</p>
                            <p><strong>Username:</strong> {{ $kasir->username }}</p>
                            <p><strong>Password:</strong> {{ $kasir->password }}</p>
                            <p><strong>Akses:</strong> {{ $kasir->akses }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            @empty
            <tr>
                <td colspan="5" class="text-center text-muted">Data kasir tidak ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center mt-3">
    {{ $dtKasir->links() }}
</div>

<!-- Modal Tambah Kasir -->
<div class="modal fade" id="modalTambahKasir" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('kasir.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kasir Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <x-input name="id_kasir" label="ID Kasir" required />
                    <x-input name="nama_kasir" label="Nama Kasir" required />
                    <x-input name="alamat_kasir" label="Alamat" required />
                    <x-input name="telp" label="No. Telp" required />
                    <x-input name="username" label="Username" required />
                    <x-input name="password" label="Password" required />
                    <x-input name="akses" label="Akses" required />
                    <div class="form-group">
                        <label for="status_kasir">Status Kasir</label>
                        <select name="status_kasir" class="form-control" required>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
