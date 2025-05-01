<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

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
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Judul
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'Data User');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
        // Tanggal & Jam
        $sheet->setCellValue('A2', 'Tanggal: ' . now()->format('d-m-Y'));
        $sheet->setCellValue('A3', 'Pukul: ' . now()->format('H:i:s'));
        $sheet->mergeCells('A2:E2');
        $sheet->mergeCells('A3:E3');
        $sheet->getStyle('A2:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
        // Header tabel
        $header = ['No', 'Nama', 'Email', 'Jabatan', 'Status'];
        $sheet->fromArray($header, null, 'A5');
    
        // Styling Header
        $sheet->getStyle('A5:E5')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9D9D9'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ]
            ]
        ]);
    
        // Data isi
        $users = \App\Models\User::all();
        $row = 6;
        foreach ($users as $index => $user) {
            $sheet->setCellValue("A{$row}", $index + 1);
            $sheet->setCellValue("B{$row}", $user->nama);
            $sheet->setCellValue("C{$row}", $user->email);
            $sheet->setCellValue("D{$row}", $user->jabatan);
            $sheet->setCellValue("E{$row}", $user->is_tugas ? 'Sudah Ditugaskan' : 'Belum Ditugaskan');
    
            // Rata tengah & border untuk tiap baris
            $sheet->getStyle("A{$row}:E{$row}")->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ]
                ]
            ]);
            $row++;
        }
    
        // Auto size kolom
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    
        // Output
        $filename = 'Data_User_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);
    
        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }
    
  public function pdf()
  {
      $filename = 'Data_User_' . now()->format('d-m-Y_H.i.s') . '.pdf';
  
      $data = [
          'user' => User::all(),
          'tanggal' => now()->format('d-m-Y'),
          'jam' => now()->format('H.i.s'),
      ];
  
      $pdf = Pdf::loadView('admin.user.pdf', ['data' => $data])
                ->setPaper('A4', 'landscape'); // A4 landscape agar lebih lebar
  
      return $pdf->download($filename);
  }
}