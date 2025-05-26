@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-people-fill"></i> Daftar Pelanggan</span>
        <!-- <a href="{{ route('pelanggan.export') }}" class="btn btn-success btn-sm me-2"><i class="bi bi-file-earmark-excel"></i> Ekspor Semua Pelanggan</a> -->
        <!-- <a href="{{ route('pelanggan.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Tambah Pelanggan</a> -->
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('pelanggan.index') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan Nama atau ID Pelanggan..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Cari</button>
                @if(request('search'))
                <a href="{{ route('pelanggan.index') }}" class="btn btn-outline-danger"><i class="bi bi-x-circle"></i> Reset</a>
                @endif
            </div>
        </form>

        @if($pelanggans->isEmpty())
            <div class="alert alert-info text-center">
                Belum ada data pelanggan. Silakan tambahkan pelanggan baru.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">ID Pelanggan</th>
                            <th scope="col">Nama Pelanggan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pelanggans as $index => $pelanggan)
                        <tr>
                            <th scope="row">{{ $pelanggans->firstItem() + $index }}</th>
                            <td>{{ $pelanggan->id_pelanggan }}</td>
                            <td>{{ $pelanggan->nama_pelanggan }}</td>
                            <td class="action-icons">
                                <a href="{{ route('pelanggan.show', $pelanggan->id) }}" class="text-info" title="Lihat Detail"><i class="bi bi-eye-fill"></i></a>
                                <a href="{{ route('pelanggan.edit', $pelanggan->id) }}" class="text-primary" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                                <form action="{{ route('pelanggan.destroy', $pelanggan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini? Semua data terkait (pemakaian, tagihan, pembayaran) juga akan terhapus.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0" title="Hapus"><i class="bi bi-trash-fill"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $pelanggans->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection