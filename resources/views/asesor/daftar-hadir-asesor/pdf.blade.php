<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Hadir Asesor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        .bordered td, .bordered th {
            border: 1px solid #000;
        }
        .p-8 { padding: 8px; }
        .p-10 { padding: 10px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .fs-14 { font-size: 14px; }
        .fs-16 { font-size: 16px; }
        .fs-18 { font-size: 18px; }
        .signature-img {
            max-width: 150px;
            max-height: 60px;
        }
        .info-table td { padding: 5px; }
        .info-table td:first-child { width: 22%; }
        .info-table td:nth-child(3) { width: 12%; }
        
        .info-container {
            width: 100%;
            margin-bottom: 15px;
        }
        .info-left {
            float: left;
            width: 50%;
        }
        .info-right {
            float: right;
            width: 50%;
        }
    </style>
</head>
<body>
    <!-- Header utama mengikuti format contoh -->
    <table class="bordered">
        <tr>
            <td rowspan="3" style="width: 22%; padding: 6px;">
                @if(file_exists(public_path('assets/img/logo.png')))
                    <img src="{{ public_path('assets/img/logo.png') }}" alt="Logo" style="width: 100%; height: auto;">
                @endif
            </td>
            <td rowspan="1" colspan="3" style="width: 38%; text-align: center; padding: 6px;">
                <div class="fw-bold fs-16">LSP</div>
                <div class="fw-bold fs-16">Universitas Multi Data Palembang</div>
            </td>
            
        </tr>
        
        <tr>
            <td rowspan="2" style="width: 30%;" class="p-10 text-center fw-bold fs-10">FORMULIR</td>
            <td class="p-8" style="width: 20%;">No. Dokumen</td>
            <td class="p-8" style="width: 20%;">{{ $daftarHadir->no_dokumen ?? '-' }}</td>
            
        </tr>
        <tr>
        <td class="p-8">Tanggal Berlaku</td>
        <td class="p-8">{{ $tanggalBerlaku }}</td>
        </tr>
        <tr>
            <td rowspan="2" colspan="2" class="p-10 text-center fw-bold fs-10">DAFTAR HADIR ASESOR</td>
            <td class="p-8">Edisi/Revisi</td>
            <td class="p-8">{{ $daftarHadir->edisi_revisi ?? '-' }}</td>
            
        </tr>
        <tr>
        <td class="p-8">Halaman</td>
        <td class="p-8">{{ $halaman ?? 1 }} dari {{ $halamanTotal ?? 1 }}</td>
        </tr>
    </table>

    <!-- Informasi Jadwal -->
    <div class="info-container" style="margin-top: 6px;">
        <div class="info-left">
            <p style="font-size: 12px; margin: 5px 0;">Skema&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $daftarHadir->skema ?? '-' }}</p>
            <p style="font-size: 12px; margin: 5px 0;">Hari/Tanggal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $hariTanggalWithDay }}</p>
        </div>
        
        <div class="info-right">
            <p style="font-size: 12px; margin: 5px 0; margin-left:50px;">Pukul&nbsp;&nbsp;: 
                @if($daftarHadir->pukul_mulai && $daftarHadir->pukul_selesai)
                    {{ \Carbon\Carbon::parse($daftarHadir->pukul_mulai)->format('H:i') }} s/d {{ \Carbon\Carbon::parse($daftarHadir->pukul_selesai)->format('H:i') }}
                @else
                    -
                @endif
            </p>
            <p style="font-size: 12px; margin: 5px 0; margin-left:50px;">TUK&nbsp;&nbsp;&nbsp;&nbsp;: {{ $daftarHadir->tuk->nama_tuk ?? ($daftarHadir->jadwalUji->tuk->nama_tuk ?? '-') }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <!-- Tabel Daftar Asesor -->
    <table class="bordered" style="margin-top: 10px;">
        <thead>
            <tr>
                <th class="p-8 text-center" style="width: 6%;">No.</th>
                <th class="p-8 text-center" style="width: 44%;">Nama Asesor</th>
                <th class="p-8 text-center" style="width: 50%;">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asesorList as $index => $asesor)
                <tr>
                    <td class="p-8 text-center">{{ $index + 1 }}</td>
                    <td class="p-8">{{ $asesor['nama'] }}</td>
                    <td class="p-8 text-center">
                        @if($asesor['signature'])
                            <img src="{{ $asesor['signature'] }}" alt="Tanda Tangan" class="signature-img">
                        @else
                            &nbsp;
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div style="margin-top: 30px;">
        <div>Palembang, {{ $hariTanggalDateOnly }}</div>
        <div style="margin-top: 20px;">Mengetahui,</div>
        <div>Penanggung jawab TUK</div>
        <div style="margin-top: 80px; font-weight: bold;">({{ $daftarHadir->penanggung_jawab_tuk ?? '-' }})</div>
    </div>
</body>
</html>

