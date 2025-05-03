<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .title {
            text-align: center;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .sub-title {
            text-align: center;
            margin-bottom: 15px;
        }

        .sub-title p {
            margin: 2px 0;
        }

        .line {
            border-top: 1px solid #000;
            margin: 10px auto;
            width: 80%;
        }

        .content {
            width: 80%;
            margin: 0 auto;
        }

        .info-table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px;
            vertical-align: top;
            font-weight: normal;
        }

        .label {
            width: 200px;
        }

        .separator {
            width: 10px;
        }

        .value {
            padding-left: 20px;
        }
    </style>
</head>
<body>

    <div class="title">Data Tugas</div>
    <div class="sub-title">
        <p>Tanggal : {{ $data['tanggal'] }}</p>
        <p>Pukul : {{ $data['jam'] }}</p>
    </div>

    <div class="line"></div>

    <div class="content">
        @foreach ($data['tugas'] as $item)
        <table class="info-table">
            <tr>
                <td class="label">Nama</td>
                <td class="separator">:</td>
                <td class="value">{{ $item->user->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Email</td>
                <td class="separator">:</td>
                <td class="value">{{ $item->user->email ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tugas</td>
                <td class="separator">:</td>
                <td class="value">{{ $item->tugas }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Mulai</td>
                <td class="separator">:</td>
                <td class="value">{{ $item->tanggal_mulai }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Selesai</td>
                <td class="separator">:</td>
                <td class="value">{{ $item->tanggal_selesai }}</td>
            </tr>
        </table>
        <div class="line"></div> <!-- garis bawah tambahan -->
        @endforeach
    </div>

</body>
</html>
