<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>FR.AK.02 Rekaman Asesmen Kompetensi</title>
    <style>
        body { font-family: "DejaVu Sans", sans-serif; font-size: 11px; margin: 15px; }
        .bordered { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        .bordered td, .bordered th { border: 1px solid #333; padding: 8px; }
        .strikethrough { text-decoration: line-through; }
        .text-center { text-align: center; }
        .table-bordered { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        .table-bordered td, .table-bordered th { border: 1px solid #333; padding: 6px; }
        .bg-light { background-color: #f5f5f5; }
        .signature-img { max-width: 180px; max-height: 80px; border: 1px solid #ddd; }
        ol { margin: 5px 0; padding-left: 20px; }
    </style>
</head>
<body>

<div style="font-weight: bold; font-size: 14px;">FR.AK.02. REKAMAN ASESMEN KOMPETENSI</div>
<br>
<table class="bordered">
    <tr>
        <td rowspan="2" style="width: 25%; padding: 10px; vertical-align: top;">
            <strong>Skema Sertifikasi</strong><br>
            <span class="strikethrough">KKNI</span>/Okupasi/<span class="strikethrough">Klaster</span>
        </td>
        <td style="width: 20%; padding: 8px;">Judul</td>
        <td style="width: 3%; padding: 1px; text-align: center;">:</td>
        <td style="width: 55%; padding: 8px;">{{ $rekamanAsesmen->judul ?? '-' }}</td>
    </tr>
    <tr>
        <td style="padding: 8px;">Nomor</td>
        <td style="width: 3%; padding: 1px; text-align: center;">:</td>
        <td style="padding: 8px;">{{ $rekamanAsesmen->nomor_skema ?? '-' }}</td>
    </tr>
    <tr>
        <td style="padding: 8px;" colspan="2">TUK</td>
        <td style="width: 3%; padding: 1px; text-align: center;">:</td>
        <td style="padding: 8px;">
            @if($rekamanAsesmen->tuk == 'sewaktu')
                ☑ Sewaktu ☐ Tempat Kerja ☐ Mandiri
            @elseif($rekamanAsesmen->tuk == 'tempat_kerja')
                ☐ Sewaktu ☑ Tempat Kerja ☐ Mandiri
            @elseif($rekamanAsesmen->tuk == 'mandiri')
                ☐ Sewaktu ☐ Tempat Kerja ☑ Mandiri
            @else
                ☐ Sewaktu ☐ Tempat Kerja ☐ Mandiri
            @endif
        </td>
    </tr>
    <tr>
        <td style="padding: 8px;" colspan="2">Nama Asesor</td>
        <td style="width: 3%; padding: 1px; text-align: center;">:</td>
        <td style="padding: 8px;">{{ $rekamanAsesmen->nama_asesor ?? '-' }}</td>
    </tr>
    <tr>
        <td style="padding: 8px;" colspan="2">Nama Asesi</td>
        <td style="width: 3%; padding: 1px; text-align: center;">:</td>
        <td style="padding: 8px;">{{ $rekamanAsesmen->nama_asesi ?? '-' }}</td>
    </tr>
    <tr>
        <td rowspan="2" style="width: 25%; padding: 10px; vertical-align: top;">Tanggal Asesmen</td>
        <td style="width: 20%; padding: 8px;">Mulai</td>
        <td style="width: 3%; padding: 1px; text-align: center;">:</td>
        <td style="width: 55%; padding: 8px;">
            {{ $rekamanAsesmen->tanggal_mulai ? \Carbon\Carbon::parse($rekamanAsesmen->tanggal_mulai)->format('d F Y') : '-' }}
        </td>
    </tr>
    <tr>
        <td style="padding: 8px;">Selesai</td>
        <td style="width: 3%; padding: 1px; text-align: center;">:</td>
        <td style="padding: 8px;">
            {{ $rekamanAsesmen->tanggal_selesai ? \Carbon\Carbon::parse($rekamanAsesmen->tanggal_selesai)->format('d F Y') : '-' }}
        </td>
    </tr>
</table>

<p><strong>Beri tanda centang (√) di kolom yang sesuai untuk mencerminkan bukti yang sesuai untuk setiap Unit Kompetensi.</strong></p>

@if($rekamanAsesmen->unit_kompetensi_data && count($rekamanAsesmen->unit_kompetensi_data) > 0)
<table class="table-bordered">
    <thead>
        <tr>
            <th width="25%">Unit Kompetensi</th>
            <th width="9%" class="text-center">Observasi Demonstrasi</th>
            <th width="9%" class="text-center">Portofolio</th>
            <th width="9%" class="text-center">Pernyataan Pihak Ketiga</th>
            <!-- <th width="9%" class="text-center">Pertanyaan Wawancara</th> -->
            <th width="9%" class="text-center">Pertanyaan Lisan</th>
            <th width="9%" class="text-center">Pertanyaan Tertulis</th>
            <th width="9%" class="text-center">Proyek Kerja</th>
            <th width="9%" class="text-center">Lainnya</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekamanAsesmen->unit_kompetensi_data as $index => $unitData)
        <tr>
            <td><strong>{{ $unitData['judul_unit'] ?? 'Unit ' . ($index + 1) }}</strong></td>
            <td class="text-center">{{ (isset($unitData['observasi_demonstrasi']) && $unitData['observasi_demonstrasi']) ? '√' : '-' }}</td>
            <td class="text-center">{{ (isset($unitData['portofolio']) && $unitData['portofolio']) ? '√' : '-' }}</td>
            <td class="text-center">{{ (isset($unitData['pernyataan_pihak_ketiga']) && $unitData['pernyataan_pihak_ketiga']) ? '√' : '-' }}</td>
            <!-- <td class="text-center">{{ (isset($unitData['pertanyaan_wawancara']) && $unitData['pertanyaan_wawancara']) ? '√' : '-' }}</td> -->
            <td class="text-center">{{ (isset($unitData['pertanyaan_lisan']) && $unitData['pertanyaan_lisan']) ? '√' : '-' }}</td>
            <td class="text-center">{{ (isset($unitData['pertanyaan_tertulis']) && $unitData['pertanyaan_tertulis']) ? '√' : '-' }}</td>
            <td class="text-center">{{ (isset($unitData['proyek_kerja']) && $unitData['proyek_kerja']) ? '√' : '-' }}</td>
            <td class="text-center">{{ (isset($unitData['lainnya']) && $unitData['lainnya']) ? '√' : '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<table class="bordered">
    <tr>
        <td style="width: 30%; padding: 8px;">Rekomendasi hasil asesmen</td>
        <td style="padding: 8px;">
            @if($rekamanAsesmen->rekomendasi_hasil == 'kompeten')
                ☑ Kompeten &nbsp;/ ☐ Belum kompeten
            @elseif($rekamanAsesmen->rekomendasi_hasil == 'belum_kompeten')
                ☐ Kompeten &nbsp;/ ☑ Belum kompeten
            @else
                ☐ Kompeten / ☐ Belum kompeten
            @endif
        </td>
    </tr>
    <tr>
        <td style="padding: 8px;">Tindak lanjut yang dibutuhkan<br><small>(Masukkan pekerjaan tambahan dan asesmen yang diperlukan untuk mencapai kompetensi)</small></td>
        <td style="padding: 8px;">{{ $rekamanAsesmen->tindak_lanjut ?? '-' }}</td>
    </tr>
    <tr>
        <td style="padding: 8px;">Komentar/ Observasi oleh asesor</td>
        <td style="padding: 8px;">{{ $rekamanAsesmen->komentar_observasi ?? '-' }}</td>
    </tr>
</table>

<table class="bordered">
    <tr>
        <td style="padding: 8px;" colspan="4"><strong>Asesi:</strong></td>
    </tr>
    <tr>
        <td style="width: 20%; padding: 8px;">Nama</td>
        <td style="width: 3%; padding: 1px; text-align: center;">:</td>
        <td style="padding: 8px;" colspan="2">{{ $rekamanAsesmen->nama_asesi ?? '-' }}</td>
    </tr>
    <tr>
        <td style="padding: 8px;">Tanda tangan dan Tanggal</td>
        <td style="width: 3%; padding: 1px; text-align: center;">:</td>
        <td style="padding: 8px;" colspan="2">
            @if($rekamanAsesmen->mahasiswa_signature)
                <img src="{{ $rekamanAsesmen->mahasiswa_signature }}" alt="Tanda Tangan Asesi" class="signature-img"><br>
                {{ $rekamanAsesmen->tanggal_mahasiswa ? \Carbon\Carbon::parse($rekamanAsesmen->tanggal_mahasiswa)->format('d F Y') : '' }}
            @else
                <span style="color: #666;">Belum ada tanda tangan</span>
            @endif
        </td>
    </tr>
    <tr>
        <td style="padding: 8px;" colspan="4"><strong>Asesor:</strong></td>
    </tr>
    <tr>
        <td style="padding: 8px;">Nama</td>
        <td style="width: 3%; padding: 1px; text-align: center;">:</td>
        <td style="padding: 8px;" colspan="2">{{ $rekamanAsesmen->nama_asesor ?? '-' }}</td>
    </tr>
    <tr>
        <td style="padding: 8px;">No. Reg</td>
        <td style="width: 3%; padding: 1px; text-align: center;">:</td>
        <td style="padding: 8px;" colspan="2">{{ $rekamanAsesmen->no_reg_asesor ?? '-' }}</td>
    </tr>
    <tr>
        <td style="padding: 8px;">Tanda tangan dan Tanggal</td>
        <td style="width: 3%; padding: 1px; text-align: center;">:</td>
        <td style="padding: 8px;" colspan="2">
            @if($rekamanAsesmen->asesor_signature)
                <img src="{{ $rekamanAsesmen->asesor_signature }}" alt="Tanda Tangan Asesor" class="signature-img"><br>
                {{ $rekamanAsesmen->tanggal_asesor ? \Carbon\Carbon::parse($rekamanAsesmen->tanggal_asesor)->format('d F Y') : '' }}
            @else
                <span style="color: #666;">Belum ada tanda tangan</span>
            @endif
        </td>
    </tr>
</table>

<p style="margin-top: 25px;"><strong>LAMPIRAN DOKUMEN:</strong></p>
<ol>
    <li>Dokumen APL 01 peserta</li>
    <li>Dokumen APL 02 peserta</li>
    <li>Bukti-bukti berkualitas peserta</li>
    <li>Tinjauan proses asesmen</li>
</ol>

</body>
</html>
