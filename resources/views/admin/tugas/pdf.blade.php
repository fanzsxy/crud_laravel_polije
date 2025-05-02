<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            text-align: center;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .sub-title {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            text-align: center;
        }

        thead th {
            background-color: #f2f2f2;
            border: 1px solid #000;
            padding: 5px;
        }

        tbody td {
            border: 1px solid #000;
            padding: 5px;
        }
    </style>
</head>
<body>

    <div class="title">Data Tugas</div>
    <div class="sub-title">
        <p>Tanggal: {{ $data['tanggal'] }}</p>
        <p>Jam: {{ $data['jam'] }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Tugas</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['tugas'] as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->user->nama }}</td>
                    <td>{{ $item->tugas }}</td>
                    <td>{{ $item->tanggal_mulai }}</td>
                    <td>{{ $item->tanggal_selesai }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
