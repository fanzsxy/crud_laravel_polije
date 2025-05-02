@extends('layouts/app')

@section('content')
  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-user mr-2"></i>
    {{ $title}}
</h1>
<div class="card">
    <div class="card-header d-flex flex-wrap justify-content-center justify-content-xl-between">
        <div class="mb-1 mr-2">
            <a href="{{ route('userCreate')}}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-2"></i>
                Tambah Data
            </a>
        </div>
        <div>
            <a href="{{ route('user.exportExcel') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel mr-2"></i>
                Excel
            </a>
            <a href="{{route('userPdf')}}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf mr-2"></i>
                Pdf
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover " id="dataTable " width="100%" cellspacing="0">
                <thead class="bg-primary text-white">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th>
                            <i class="fas fa-cog"></i>
                            Opsi
                        </th>
                    </tr>
                </thead>
              
                <tbody>
                    @foreach ($user as $item)
                        <tr class="text-center">
                            <td>{{ $loop->iteration}}</td>
                            <td>{{ $item->nama}}</td>
                            <td>
                                <span class="badge badge-primary">
                                    {{ $item->email}}
                                </span>
                            </td>
                            <td>
                                @if ($item->jabatan == 'Admin')
                                <span class="badge badge-dark">
                                    {{ $item->jabatan}}
                                </span>
                                @else 
                                <span class="badge badge-info">
                                    {{ $item->jabatan}}
                                </span>
                                @endif
                             

                            </td>
                            <td>
                                @if ($item->is_tugas == false)
                                <span class="badge badge-danger">
                                    Belum Ditugaskan
                                </span>
                                @else 
                                <span class="badge badge-success">
                                    Sudah Ditugaskan
                                </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{route('userEdit', $item->id)}}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#exampleModal{{ $item->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @include('admin/user/modal', ['item' => $item, 'title' => $title])

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection