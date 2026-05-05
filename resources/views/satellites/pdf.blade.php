<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Satelit</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; }
        .header { background: #1e40af; color: white; padding: 20px; text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 18px; font-weight: bold; }
        .header p { font-size: 10px; margin-top: 5px; opacity: 0.8; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #1e40af; color: white; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; }
        td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) { background: #f9fafb; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 9px; font-weight: bold; }
        .badge-active { background: #d1fae5; color: #065f46; }
        .badge-inactive { background: #fee2e2; color: #991b1b; }
        .footer { margin-top: 20px; text-align: right; font-size: 9px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🛰️ PERSATELITAN — Data Satelit</h1>
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }} | Total: {{ $satellites->count() }} satelit</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Satelit</th>
                <th>Negara</th>
                <th>Orbit</th>
                <th>Launch Date</th>
                <th>Ground Station</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($satellites as $i => $satellite)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $satellite->name }}</td>
                <td>{{ $satellite->country }}</td>
                <td>{{ $satellite->orbit_type }}</td>
                <td>{{ $satellite->launch_date ? $satellite->launch_date->format('d/m/Y') : '-' }}</td>
                <td>{{ $satellite->groundStation?->name ?? '-' }}</td>
                <td>
                    <span class="badge {{ $satellite->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $satellite->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">Dokumen ini digenerate secara otomatis oleh Sistem Persatelitan</div>
</body>
</html>