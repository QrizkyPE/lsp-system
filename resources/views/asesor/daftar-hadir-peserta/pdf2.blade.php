<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Daftar Hadir Peserta - Laporan</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table.bordered {
            border-collapse: collapse;
        }

        table.bordered td,
        table.bordered th {
            padding: 0;
            margin: 0;
            border: 1px solid #000;
            line-height: 1;
        }

        table.bordered-2 td,
        table.bordered-2 th {
            padding: 0;
            margin: 0;
            border: 1px solid #000;
        }

        .p-8 {
            padding: 8px;
        }

        .p-10 {
            padding: 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .fs-15 {
            font-size: 15px;
        }

        .fs-16 {
            font-size: 16px;
        }

        .signature-img {
            max-width: 150px;
            max-height: 60px;
            padding: 3px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <table class="bordered p-0 m-0">
        <tr>
            <td rowspan="3" style="width: 22%; text-align:center; vertical-align:middle;">
                @if(file_exists(public_path('assets/img/logo.png')))
                    <img src="{{ public_path('assets/img/logo.png') }}" alt="Logo"
                        style="width:50%; display:block; margin:auto;">
                @endif
            </td>
            <td rowspan="1" colspan="3" style="width: 38%; text-align: center;">
                <div class="fw-bold fs-16">LSP</div>
                <div class="fw-bold fs-16">Universitas Multi Data Palembang</div>
            </td>
        </tr>

        <tr>
            <td rowspan="2" class="text-center" style="font-weight: bold; font-size: 14px;">FORMULIR</td>
            <td class="p-8" style="padding: 3px;">No. Dokumen</td>
            <td class="p-8" style="padding: 3px;">{{ $daftarHadir->no_dokumen ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 3px;">Tanggal Berlaku</td>
            <td style="padding: 3px;">{{ $tanggalBerlaku }}</td>
        </tr>
        <tr>
            <td rowspan="2" colspan="2" class="p-10 text-center fw-bold fs-15">DAFTAR HADIR PESERTA UJI</td>
            <td class="p-8" style="padding: 3px;">Edisi/Revisi</td>
            <td class="p-8" style="padding: 3px;">{{ $daftarHadir->edisi_revisi ?? '-' }}</td>
        </tr>
        <tr>
            <td class="p-8" style="padding: 3px;">Halaman</td>
            <td class="p-8" style="padding: 3px;">1 dari 1</td>
        </tr>
    </table>

    <!-- Isi Laporan -->
    <div style="margin-top: 20px;">
        <p style="margin-bottom: 15px; text-align: justify;">
            Pada hari ini <strong>{{ $hari }}</strong>, tanggal <strong>{{ $tanggal }}</strong> bertempat di TUK <strong>{{ $daftarHadir->tuk->nama_tuk ?? ($daftarHadir->jadwalUji->tuk->nama_tuk ?? '-') }}</strong> telah dilaksanakan kegiatan Uji Kompetensi dengan Skema <strong>{{ $daftarHadir->skema ?? '-' }}</strong>, terhadap peserta uji/asesi:
        </p>

        <p style="margin-bottom: 15px;">
            Jumlah peserta sebanyak {{ $totalPeserta }} ({{ $daftarHadir->jumlah_peserta_huruf ?? $totalPeserta }}) orang
        </p>

        <ol style="margin-left: 20px; margin-bottom: 15px;">
            <li>Hadir sebanyak: {{ $hadirCount }} orang</li>
            <li>Tidak hadir sebanyak: {{ $tidakHadirCount }} orang</li>
        </ol>

        <p style="margin-bottom: 15px;">Adapun nama-nama peserta adalah sebagai berikut:</p>

         <!-- Tabel Peserta -->
         <table class="bordered-2" style="margin-top: 15px;">
             <thead>
                 <tr>
                     <th class="p-8 text-center" style="width: 5%;">No.</th>
                     <th class="p-8 text-center" style="width: 30%;">Nama Peserta</th>
                     <th class="p-8 text-center" style="width: 15%;">NPM</th>
                     <th colspan="2" class="p-8 text-center" style="width: 20%;">Hasil</th>
                     <th class="p-8 text-center" style="width: 30%;">Keterangan</th>
                 </tr>
                 <tr>
                     <th></th>
                     <th></th>
                     <th></th>
                     <th class="p-8 text-center" style="width: 10%;">K</th>
                     <th class="p-8 text-center" style="width: 10%;">BK</th>
                     <th></th>
                 </tr>
             </thead>
            <tbody>
                @foreach($asesiList as $index => $asesi)
                    <tr>
                        <td class="p-8 text-center">{{ $index + 1 }}</td>
                        <td class="p-8" style="padding: 3px;">{{ $asesi['nama'] }}</td>
                        <td class="p-8 text-center" style="padding: 3px;">{{ $asesi['npm'] }}</td>
                        <td class="p-8 text-center">
                            @if($asesi['k'])
                                <span style="font-family: 'DejaVu Sans', sans-serif; font-size: 18px; font-weight: bold;">✓</span>
                            @else
                                &nbsp;
                            @endif
                        </td>
                        <td class="p-8 text-center">
                            @if($asesi['bk'])
                                <span style="font-family: 'DejaVu Sans', sans-serif; font-size: 18px; font-weight: bold;">✓</span>
                            @else
                                &nbsp;
                            @endif
                        </td>
                        <td class="p-8" style="padding: 3px;">{{ $asesi['keterangan'] ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Hasil -->
        <div style="margin-top: 20px;">
            <p>Hasil:</p>
            <p>
                Kompeten&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $countK }} orang<br>
                Belum Kompeten&nbsp;: {{ $countBK }} orang
            </p>
        </div>
    </div>

    <!-- Footer -->
    <div style="margin-top: 40px; clear: both;">
        <table style="width: 100%; border: none;">
            <thead>
                <th style="text-align: left; width: 32%; font-weight: normal;">Kepala TUK,</th>
                <th style="text-align: right; width: 36%; font-weight: normal;">Palembang, {{ $tanggalDateOnly }}
                    <br> Asesor Kompetensi,
                </th>
            </thead>
            <tbody>
                <tr>
                    <td style="border: none; padding: 8px 2px; text-align: left; vertical-align: bottom;">
                        <div style="margin-top: 10px;">
                            ({{ $daftarHadir->kepala_tuk ?? '-' }})
                        </div>
                    </td>
                    <td style="border: none; padding: 8px 2px; text-align: right; vertical-align: bottom;">
                        <div style="margin-top: 10px;">
                            @if($asesorData && $asesorData['signature'])
                                <div style="margin-bottom: 10px;">
                                    <img src="{{ $asesorData['signature'] }}" alt="Tanda Tangan" class="signature-img">
                                </div>
                            @endif
                            <div>({{ $asesorData['nama'] ?? '-' }})</div>
                            <div>No. Reg : {{ $asesorData['no_reg'] ?? '-' }}</div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>

