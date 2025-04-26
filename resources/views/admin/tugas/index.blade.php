@extends('layouts/app')

@section('content')
  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-tasks mr-2"></i>
    {{ $title}}
</h1>
<div class="card">
    <div class="card-header d-flex flex-wrap justify-content-center justify-content-xl-between">
        <div class="mb-1 mr-2">
            <a href="" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-2"></i>
                Tambah Data
            </a>
        </div>
        <div>
            <a href="" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel mr-2"></i>
                Excel
            </a>
            <a href="" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf mr-2"></i>
                Pdf
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-white">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Nama</th>
                        <th>Tugas</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>
                            <i class="fas fa-cog"></i>
                            Opsi
                        </th>
                    </tr>
                </thead>
              
                    <tr class="text-center">
                        <td>1</td>
                        <td>Donna Snider</td>
                        <td>Customer Support</td>
                        <td class="text-center">
                            <span class="badge badge-info babge-pill">
                                01-02-2025
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-success babge-pill">
                                04-02-2025
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="#" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                      
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection