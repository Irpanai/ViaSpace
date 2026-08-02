<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Riwayat Logbook - {{ $user->name }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .header { text-align: center; margin-bottom: 30px; }
        .title { font-size: 20px; font-weight: bold; }
        .subtitle { color: #555; }
        img { max-width: 100px; max-height: 100px; object-fit: cover; }
    </style>
</head>
<body>

    <div class="header">
        <div class="title">Riwayat Logbook Magang</div>
        <div class="subtitle">Nama: {{ $user->name }} | Email: {{ $user->email }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Kategori Tugas</th>
                <th>Deskripsi Pekerjaan</th>
                <th>Bukti Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $att)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($att->date)->format('d M Y') }}</td>
                    <td>{{ $att->check_in_time ? \Carbon\Carbon::parse($att->check_in_time)->format('H:i') : '-' }}</td>
                    <td>{{ $att->check_out_time ? \Carbon\Carbon::parse($att->check_out_time)->format('H:i') : '-' }}</td>
                    <td>{{ $att->logbook ? $att->logbook->category : '-' }}</td>
                    <td>{{ $att->logbook ? $att->logbook->description : '-' }}</td>
                    <td>
                        @if($att->logbook && $att->logbook->photo_path)
                            @php
                                $path = storage_path('app/public/' . str_replace('public/', '', $att->logbook->photo_path));
                                // Make sure file exists before trying to display it
                                if(file_exists($path)) {
                                    $type = pathinfo($path, PATHINFO_EXTENSION);
                                    $data = file_get_contents($path);
                                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                    echo '<img src="'.$base64.'" alt="Foto" style="max-width:80px;">';
                                } else {
                                    echo 'Foto tidak ditemukan';
                                }
                            @endphp
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
