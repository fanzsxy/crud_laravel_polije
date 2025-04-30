<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\SimpleExcel\SimpleExcelWriter;

class UserController extends Controller
{
    public function index(){
        $data = array(
            "title"         => " Data User",
            "menuAdminUser" => "active",
            'user'          => User::orderBy('jabatan','asc')->get(),
        );
        return view('admin/user/index' ,$data);
        
    }

    public function create(){
        $data = array(
            "title"         => "Tambah Data User",
            "menuAdminUser" => "active",
            'user'          => User::get(),
        );
        return view('admin/user/create' ,$data);
        
    }
    public function store(Request $request){
        $request->validate([
            'nama' => 'required',
            'email' => 'required|unique:users,email',
            'jabatan' => 'required',
            'password' => 'required|confirmed|min:8',
            
        ],[
            'nama.required' => 'Nama Tidak Boleh kosong',
            'email.required' => 'Email Tidak Boleh kosong',
            'email.unique' => 'Email Sudah Ada',
            'jabatan.required' => 'Pilih Salah Satu Jabatan',
            'password.required' => 'Password Tidak Boleh kosong',
            'password.confirmed' => 'Password Tidak Sama',
            'password.min' => 'Password Minimal 8 Karakter',
        ]);

        $user = new User;
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->jabatan = $request->jabatan;
        $user->password = Hash::make($request->password);
        $user->is_tugas = false;
        $user->save();
        
        return redirect()->route('user')->with('success','Data Berhasil Ditambahkan');
    }
    
    public function edit($id){
        $data = array(
            "title"         => "Edit Data User",
            "menuAdminUser" => "active",
            'user'          => User::findOrFail($id),
        );
        return view('admin/user/edit' ,$data);
        
    }
    public function update(Request $request ,$id){
        $request->validate([
            'nama' => 'required',
            'email' => 'required|unique:users,email,'.$id,
            'jabatan' => 'required',
            'password' => 'nullable|confirmed|min:8',
            
        ],[
            'nama.required' => 'Nama Tidak Boleh kosong',
            'email.required' => 'Email Tidak Boleh kosong',
            'email.unique' => 'Email Sudah Ada',
            'jabatan.required' => 'Pilih Salah Satu Jabatan',
            'password.confirmed' => 'Password Tidak Sama',
            'password.min' => 'Password Minimal 8 Karakter',
        ]);

        $user = User::findOrFail($id);
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->jabatan = $request->jabatan;
        if ($request->filled('password')){
            $user->password = Hash::make($request->password);
        }
        $user->save();
        
        return redirect()->route('user')->with('success','Data Berhasil Diubah');
    }

    public function destroy($id){
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('user')->with('success','Data Berhasil Dihapus');
    }
    public function exportExcel()
{
    $fileName =  'data_user_'.date('d-m-Y_H.i.s').'.xlsx';
    $filePath = storage_path("app/{$fileName}");

    SimpleExcelWriter::create($filePath, 'xlsx')
        ->addRows(
            User::all()->map(function ($user) {
                return [
                    'Nama'     => $user->nama,
                    'Email'    => $user->email,
                    'Jabatan'  => $user->jabatan,
                    'Status'   => $user->is_tugas ? 'Sudah Ditugaskan' : 'Belum Ditugaskan',
                ];
            })->toArray()
        );

    return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
  }
  
}