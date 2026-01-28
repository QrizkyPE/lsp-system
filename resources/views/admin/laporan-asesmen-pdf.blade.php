<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>FR.AK.05 Laporan Asesmen</title>
    <style>
        body { font-family: "DejaVu Sans", sans-serif; font-size: 11px; margin: 15px; }
        .bordered { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        .bordered td, .bordered th { border: 1px solid #333; padding: 8px; }
        .table-bordered { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        .table-bordered td, .table-bordered th { border: 1px solid #333; padding: 6px; }
        .strikethrough { text-decoration: line-through; }
        .text-center { text-align: center; }
        .signature-img { max-width: 180px; max-height: 80px; }
    </style>
</head>
<body>

<div style="font-weight: bold; font-size: 14px;">
    FR.AK.05. LAPORAN ASESMEN
</div>
<br>
@php
    $jadwal = $laporan->jadwalUji;
    $skema = $jadwal->skemaSertifikasi ?? null;
    $tukModel = $jadwal->tuk ?? null;
    $asesor = $laporan->asesor;
    $tukType = $laporan->tuk_type ?? null; // Ambil dari laporan, bukan dari jadwal
@endphp

<table class="bordered" style="width: 100%; margin-bottom: 20px;">
    <tr>
        <td rowspan="2" style="width: 25%; padding: 10px; vertical-align: top;">
            <strong>Skema Sertifikasi</strong><br>
            <span class="strikethrough">KKNI</span>/Okupasi/<span class="strikethrough">Klaster</span>
        </td>
        <td style="width: 20%; padding: 8px;">
            Judul
        </td>
        <td style="width: 3%; padding: 1px; text-align: center;">
            :
        </td>
        <td style="width: 55%; padding: 8px;" >
            {{ $skema->nama_skema ?? '-' }}
        </td>
    </tr>
    <tr>
        <td style="padding: 8px;">
            Nomor
        </td>
        <td style="width: 3%; padding: 1px; text-align: center;">
            :
        </td>
        <td style="padding: 8px;" >
            {{ $skema->kode_skema ?? '-' }}
        </td>
    </tr>
    <tr>
        <td style="padding: 8px;" colspan="2">
            TUK
        </td>
        <td style="width: 3%; padding: 1px; text-align: center;">
            :
        </td>
        <td style="padding: 8px;" >
            @if($tukType === 'sewaktu')
                Sewaktu/<span class="strikethrough">Tempat Kerja</span>/<span class="strikethrough">Mandiri</span>
            @elseif($tukType === 'tempat_kerja')
                <span class="strikethrough">Sewaktu</span>/Tempat Kerja/<span class="strikethrough">Mandiri</span>
            @elseif($tukType === 'mandiri')
                <span class="strikethrough">Sewaktu</span>/<span class="strikethrough">Tempat Kerja</span>/Mandiri
            @else
                Sewaktu/Tempat Kerja/Mandiri
            @endif
            <!-- <br>
            {{ $tukModel->nama_tuk ?? '' }} -->
        </td>
    </tr>
    <tr>
        <td style="padding: 8px;" colspan="2">
            Nama Asesor
        </td>
        <td style="width: 3%; padding: 1px; text-align: center;">
            :
        </td>
        <td style="padding: 8px;" >
            {{ $asesor->nama_lengkap ?? '-' }}
        </td>
    </tr>
    
    <tr>
        <td style="padding: 8px;" colspan="2">
            Tanggal
        </td>
        <td style="width: 3%; padding: 1px; text-align: center;">
            :
        </td>
        <td style="padding: 8px;" >
            {{ $laporan->tanggal ? \Carbon\Carbon::parse($laporan->tanggal)->format('d F Y') : '-' }}
        </td>
    </tr>
</table>

@if($asesiList && count($asesiList) > 0)
<table class="table-bordered">
    <thead>
        <tr>
            <th  style="width:5%;">No.</th>
            <th  style="width:35%;">Nama Asesi</th>
            <th colspan="2" style="width:20%;" class="text-center">Rekomendasi</th>
            <th style="width:40%;">Keterangan</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th class="text-center">K</th>
            <th class="text-center">BK</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($asesiList as $index => $asesi)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $asesi['nama'] ?? '-' }}</td>
                <td class="text-center">{{ !empty($asesi['k']) ? '☑' : '☐' }}</td>
                <td class="text-center">{{ !empty($asesi['bk']) ? '☑' : '☐' }}</td>
                <td>{{ $asesi['keterangan'] ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif
<p>** tuliskan Kode dan Judul Unit Kompetensi yang dinyatakan BK bila mengases satu skema</p>
<table class="bordered">
    <tr>
        <td style="width: 28%; padding: 10px; vertical-align: top;">
            Aspek Negatif dan<br>
            Positif dalam Asesemen
        </td>
        <td style="width: 72%; padding: 10px;">
            {{ $laporan->aspek_positif_negatif ?? '-' }}
        </td>
    </tr>
    <tr>
        <td style="padding: 10px; vertical-align: top;">
            Pencatatan Penolakan<br>
            Hasil Asesmen
        </td>
        <td style="padding: 10px;">
            {{ $laporan->penolakan_hasil ?? '-' }}
        </td>
    </tr>
    <tr>
        <td style="padding: 10px; vertical-align: top;">
            Saran Perbaikan :<br>
            (Asesor/Personil Terkait)
        </td>
        <td style="padding: 10px;">
            {{ $laporan->saran_perbaikan ?? '-' }}
        </td>
    </tr>
</table>

<table class="bordered">
    <tr>
        <td style="width: 30%; padding: 8px; vertical-align: top;" rowspan="4">
            Catatan :
            <br>
            {{ $laporan->catatan ?? '-' }}
        </td>
        <td style="width: 20%; padding: 8px;" colspan="2">
            Asesor :
        </td>
    </tr>
    <tr>
        <td style="padding: 8px;">Nama</td>
        <td style="padding: 8px;">{{ $asesor->nama_lengkap ?? '-' }}</td>
    </tr>
    <tr>
        <td style="padding: 8px;">No. Reg</td>
        <td style="padding: 8px;">{{ $asesor->no_reg ?? '-' }}</td>
    </tr>
    <tr>
        <td style="padding: 8px; text-align: center;">Tanda tangan/Tanggal</td>
        <td style="padding: 8px; text-align: center;">
            @if($asesorSignature ?? false)
                <img src="{{ $asesorSignature }}" alt="Tanda Tangan Asesor" class="signature-img"><br>
            @endif
            {{ $laporan->tanggal ? \Carbon\Carbon::parse($laporan->tanggal)->format('d F Y') : '' }}
        </td>
    </tr>
</table>

</body>
</html>

