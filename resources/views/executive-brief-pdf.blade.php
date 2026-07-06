<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 25px 35px;
        }

        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 11px;
            color: #2A2E37;
        }

        .header {
            background: #082A5E;
            color: #fff;
            padding: 14px 18px;
            border-radius: 6px;
            margin-bottom: 16px;
        }

        .header .logo {
            font-size: 18px;
            font-weight: bold;
        }

        .header .logo span {
            color: #49A5FF;
        }

        .header .meeting {
            font-size: 13px;
            font-weight: bold;
            margin-top: 6px;
        }

        .header .tanggal {
            font-size: 10px;
            opacity: .85;
        }

        .initiative {
            border: 1px solid #E5E9F1;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 10px;
        }

        .kode {
            display: inline-block;
            background: #EAF1FF;
            color: #1E5EFF;
            font-size: 9px;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 4px;
        }

        .judul {
            font-size: 12px;
            font-weight: bold;
            margin: 4px 0 2px;
        }

        .status {
            display: inline-block;
            font-size: 9px;
            font-weight: bold;
            color: #fff;
            padding: 2px 8px;
            border-radius: 4px;
        }

        .status-selesai { background: #198754; }
        .status-on-track { background: #0d6efd; }
        .status-perlu-perhatian { background: #ffc107; color: #2A2E37; }
        .status-terlambat { background: #dc3545; }
        .status-belum-mulai { background: #6c757d; }

        .progress-text {
            font-size: 11px;
            font-weight: bold;
        }

        table.row-top {
            width: 100%;
        }

        table.row-top td {
            vertical-align: top;
        }

        .dukungan-title {
            font-size: 9px;
            color: #6c757d;
            font-weight: bold;
            margin-top: 6px;
        }

        ul.dukungan {
            margin: 3px 0 0;
            padding-left: 16px;
        }

        ul.dukungan li {
            margin-bottom: 2px;
        }

        .empty {
            text-align: center;
            color: #999;
            padding: 20px 0;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo"><span>R</span>EDIS &mdash; Executive Brief</div>
        <div class="meeting">{{ $meeting->judul }}</div>
        @if ($meeting->tanggal)
            <div class="tanggal">{{ $meeting->tanggal->format('d F Y') }}</div>
        @endif
    </div>

    @php
        $statusSlug = fn ($label) => 'status-'.\Illuminate\Support\Str::slug($label);
    @endphp

    @forelse ($initiatives as $initiative)

        <div class="initiative">

            <table class="row-top">
                <tr>
                    <td>
                        <span class="kode">{{ $initiative->kode }}</span>
                        <div class="judul">{{ $initiative->judul }}</div>
                    </td>
                    <td style="text-align:right; width:140px;">
                        <span class="status {{ $statusSlug($initiative->status) }}">{{ $initiative->status_label }}</span>
                        <div class="progress-text">{{ $initiative->progress_percent }}%</div>
                    </td>
                </tr>
            </table>

            @php
                $dukungan = $initiative->actionPlans->pluck('dukungan_direktur')->filter()->unique();
            @endphp

            @if ($dukungan->isNotEmpty())
                <div class="dukungan-title">DUKUNGAN DIREKTUR YANG DIPERLUKAN</div>
                <ul class="dukungan">
                    @foreach ($dukungan as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            @endif

        </div>

    @empty
        <p class="empty">Belum ada Strategic Initiative yang dibahas di meeting ini.</p>
    @endforelse

</body>
</html>
