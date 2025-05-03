@extends('layouts/app')

@section('content')
  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-tasks mr-2"></i>
    {{ $title}}
</h1>
<div class="card">
    <div class="card-header d-flex flex-wrap justify-content-center justify-content-xl-between">
            <a href="#" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf mr-2"></i>
                Pdf
            </a>
        </div>
    </div>
    <div class="card-body">
        <h1>halaman karyawan</h1>
    </div>
</div>


@endsection