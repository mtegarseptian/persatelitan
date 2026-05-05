<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Ground Station</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; }
        .header { background: #7c3aed; color: white; padding: 20px; text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 18px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #7c3aed; color: white; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; }
        td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) { background: #f9fafb; }
        .footer { margin-top: 20px; text-align: right; font-size: 9px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📡 PERSATELITAN — Data Ground Station</h1>
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }} | Total: {{ $groundStations->count() }} stasiun</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th><th>Nama Stasiun</th><th>Lokasi</th><th>Negara</th>
                <th>Latitude</th><th>Longitude</th><th>Total Satelit</th><th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groundStations as $i => $gs)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $gs->name }}</td>
                <td>{{ $gs->location }}</td>
                <td>{{ $gs->country }}</td>
                <td>{{ $gs->latitude }}</td>
                <td>{{ $gs->longitude }}</td>
                <td>{{ $gs->satellites_count }}</td>
                <td>{{ $gs->is_active ? 'Aktif' : 'Nonaktif' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">Dokumen ini digenerate secara otomatis oleh Sistem Persatelitan</div>
</body>
</html>