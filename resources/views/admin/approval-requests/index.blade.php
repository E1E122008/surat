@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-clipboard-check"></i> Permintaan Persetujuan</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($approvalRequests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama User</th>
                                <th>Tanggal Request</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvalRequests as $index => $request)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $request->user->name }}</td>
                                    <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($request->status === 'pending')
                                            <span class="badge bg-warning">Menunggu</span>
                                        @elseif($request->status === 'approved')
                                            <span class="badge bg-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($request->status === 'pending')
                                            <div class="d-flex gap-2">
                                                <form action="{{ route('admin.approval-requests.approve', $request->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check"></i> Setujui
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.approval-requests.reject', $request->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-times"></i> Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-muted">Sudah diproses</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
                    <p class="text-muted">Tidak ada permintaan persetujuan</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 