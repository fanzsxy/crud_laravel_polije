<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;

class DashboardController extends Controller
{
    public function index() {
        $data = array(
            "title" => "Dashboard",
            "menuDashboard" => "active",
            "jumlahUser" => User::count(),
            "jumlahAdmin" => User::where('jabatan','Admin')->count(),
            "jumlahKaryawan" => User::where('jabatan','Karyawan')->count(),
            "jumlahDitugaskan" => User::where('jabatan','Karyawan')->where('is_tugas',True)->count(),
            "jumlahBelumditugaskan" => User::where('jabatan','Karyawan')->where('is_tugas',false)->count(),


        );
        return view('dashboard', $data);
    }
}
