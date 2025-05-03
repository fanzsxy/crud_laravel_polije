<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TugasController extends Controller
{
    public function index(){
        $user = Auth::user();

        if ($user->jabatan == 'Admin') {
            $data = array(
                "title"         => " Data Tugas",
                "menuAdminTugas" => "active",
                'tugas'          => Tugas::with('user')->get(),
            );
            return view('admin.tugas.index' ,$data);
        }else{
            $data = array(
                "title"         => " Data Tugas",
                "menuKaryawanTugas" => "active",
            );
            return view('karyawan.tugas.index' ,$data);
        }
    }   
    public function create(){
        $data = array(
            "title"         => "Tambah Data tugas",
            "menuAdminTugas" => "active",
            'user'          => User::where('jabatan','karyawan')->where('is_tugas', false)->get(),
        );
        return view('admin/tugas/create' ,$data);
        
    }
    public function store(Request $request){
        $request->validate([
            'user_id' => 'required',
            'tugas' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
            
        ],[
            'user_id.required' => 'Nama Tidak Boleh kosong',
            'tugas.required' => 'Tugas Tidak Boleh kosong',
            'tanggal_mulai.required' => 'Tanggal Mulai Tidak Boleh kosong',
            'tanggal_selesai.required' => 'Tanggal Selesai Tidak Boleh kosong',
            

        ]);

        $user = User::findOrFail($request->user_id);
        $tugas = new Tugas;
        $tugas -> user_id = $request->user_id;
        $tugas -> tugas = $request->tugas;
        $tugas -> tanggal_mulai = $request->tanggal_mulai;
        $tugas -> tanggal_selesai = $request->tanggal_selesai;
        $tugas -> save();
        $user->is_tugas = true;
        $user->save();
        
        return redirect()->route('tugas')->with('success','Data Berhasil Ditambahkan');
    }
    public function edit($id){
        $data = array(
            "title"         => "Edit Data tugas",
            "menuAdminTugas" => "active",
            "tugas" => Tugas::with('user')->findOrFail($id),
        );
        return view('admin/tugas/update' ,$data);
        
    }
    public function update(Request $request,$id){
        $request->validate([
           
            'tugas' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
            
        ],[
           
            'tugas.required' => 'Tugas Tidak Boleh kosong',
            'tanggal_mulai.required' => 'Tanggal Mulai Tidak Boleh kosong',
            'tanggal_selesai.required' => 'Tanggal Selesai Tidak Boleh kosong',
            

        ]);

        $tugas = Tugas::findOrFail($id);
        $tugas -> tugas = $request->tugas;
        $tugas -> tanggal_mulai = $request->tanggal_mulai;
        $tugas -> tanggal_selesai = $request->tanggal_selesai;
        $tugas -> save();
    
        return redirect()->route('tugas')->with('success','Data Berhasil Diedit');
    }
    public function destroy($id){
        $tugas = Tugas::findOrFail($id);
        $tugas->delete();
        $user = User::where('id', $tugas->user_id)->first();
        $user->is_tugas = false;
        $user->save();
        return redirect()->route('tugas')->with('success','Data Berhasil Dihapus');
    }
    public function exportExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Judul
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'Data Tugas');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
        // Tanggal & Jam
        $sheet->setCellValue('A2', 'Tanggal: ' . now()->format('d-m-Y'));
        $sheet->setCellValue('A3', 'Pukul: ' . now()->format('H:i:s'));
        $sheet->mergeCells('A2:E2');
        $sheet->mergeCells('A3:E3');
        $sheet->getStyle('A2:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
        // Header tabel
        $header = ['No', 'Nama', 'Tugas', 'Tanggal Mulai', 'Tanggal Selesai'];
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
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ]
        ]);
    
        // Ambil data tugas + relasi user
        $tugas = Tugas::with('user')->get();
        $row = 6;
        $tugas = Tugas::with('user')->get();
    $row = 6;
    foreach ($tugas as $index => $item) {
        $sheet->setCellValue("A{$row}", $index + 1);
        $sheet->setCellValue("B{$row}", $item->user->nama ?? '-');
        $sheet->setCellValue("C{$row}", $item->tugas);
        $sheet->setCellValue("D{$row}", $item->tanggal_mulai);
        $sheet->setCellValue("E{$row}", $item->tanggal_selesai);

        $sheet->getStyle("A{$row}:E{$row}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ]
        ]);
        $row++;
    }

    // Autofit kolom
    foreach (range('A', 'E') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Autofit tinggi row
    $sheet->getDefaultRowDimension()->setRowHeight(-1);

    // Output
    $writer = new Xlsx($spreadsheet);
    $filename = 'Data_Tugas_' . now()->format('Ymd_His') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $writer->save("php://output");
    exit;
}
public function Pdf()
{
    $filename = 'Data_Tugas_' . now()->format('d-m-Y_H.i.s') . '.pdf';
    $data = [
        'tanggal' => now()->format('d-m-Y'),
        'jam' => now()->format('H:i:s'),
        'tugas' => Tugas::with('user')->get()
    ];

    $pdf = PDF::loadView('admin.tugas.pdf', compact('data'));
    return $pdf->download($filename);
}
}
