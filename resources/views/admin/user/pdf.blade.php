<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        thead th {
            background-color: #f2f2f2;
            border: 1px solid #000;
            text-align: center;
            padding: 5px;
        }
        tbody td {
            border: 1px solid #000;
            padding: 5px;
        }
        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="title">Data User</div>
    <p>Tanggal: {{ $data['tanggal'] }} </p>
    <p>Jam: {{ $data['jam'] }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Jabatan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['user'] as $item)
                <tr>
                    <td align="center">{{ $loop->iteration }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->email }}</td>
                    <td align="center">{{ $item->jabatan }}</td>
                    <td align="center">
                        {{ $item->is_tugas ? 'Sudah Ditugaskan' : 'Belum Ditugaskan' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
