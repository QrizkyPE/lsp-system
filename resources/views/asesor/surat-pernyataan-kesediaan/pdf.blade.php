<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Surat Pernyataan Kesediaan</title>
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

        .fs-14 {
            font-size: 14px;
        }

        .fs-15 {
            font-size: 15px;
        }

        .fs-16 {
            font-size: 16px;
        }

        .signature-img {
            max-width: 200px;
            max-height: 100px;
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
            <td class="p-8" style="padding: 3px;">{{ $surat->no_dokumen ?? '-' }}</td>

        </tr>
        <tr>
            <td style="padding: 3px;">Tanggal Berlaku</td>
            <td style="padding: 3px;">{{ $tanggalBerlaku }}</td>
        </tr>
        <tr>
            <td rowspan="2" colspan="2" class="p-10 text-center fw-bold fs-12">SURAT PERNYATAAN KESEDIAAN ASESOR</td>
            <td class="p-8" style="padding: 3px;">Edisi/Revisi</td>
            <td class="p-8" style="padding: 3px;">{{ $surat->edisi_revisi ?? '-' }}</td>

        </tr>
        <tr>
            <td class="p-8" style="padding: 3px;">Halaman</td>
            <td class="p-8" style="padding: 3px;">1 dari 1</td>
        </tr>
    </table>

    <!-- Isi Surat -->
    <div style="margin-top: 20px;">
        <p style="margin-bottom: 15px;">Yang bertandatangan di bawah ini:</p>
        
        <table style="width: 100%; border: none; margin-bottom: 15px;">
            <tr>
                <td style="border: none; padding: 3px; width: 25%;">Nama</td>
                <td style="border: none; padding: 3px; width: 5%;">:</td>
                <td style="border: none; padding: 3px;">{{ $surat->asesor->nama_lengkap ?? '-' }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 3px;">Alamat</td>
                <td style="border: none; padding: 3px;">:</td>
                <td style="border: none; padding: 3px;">{{ $surat->alamat ?? '-' }}</td>
            </tr>
            <br>
            <tr>
                <td style="border: none; padding: 3px;">No. MET Sertifikat</td>
                <td style="border: none; padding: 3px;">:</td>
                <td style="border: none; padding: 3px;">{{ $surat->no_met_sertifikat ?? '-' }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 3px;">TUK</td>
                <td style="border: none; padding: 3px;">:</td>
                <td style="border: none; padding: 3px;">{{ $surat->tuk->nama_tuk ?? '-' }}</td>
            </tr>
        </table>

        <p style="margin-top: 15px; margin-bottom: 10px;">Dengan ini menyatakan:</p>
        <ol style= margin-bottom: 15px;">
            <li>Bersedia untuk menjadi asesor dalam proses pelaksanaan asesmen</li>
            <li>Menjaga kerahasiaan terhadap hasil asesmen selama proses asesmen maupun setelahnya.</li>
            <li>Hasil asesmen hanya akan diberitahukan kepada LSP Universitas Multi Data Palembang dan asesi.</li>
            <li>Menjaga kode etik profesi.</li>
        </ol>

        <p style="margin-top: 15px; margin-bottom: 20px;">
            Demikian pernyataan ini saya buat dalam keadaan sadar dan tanpa paksaan dari pihak manapun dan bilamana di kemudian hari ternyata pernyataan ini tidak benar maka saya bersedia untuk menerima sanksi yang diberikan dari pihak yang terkait.
        </p>
    </div>

    <!-- Footer -->
    <div style="margin-top: 40px;">
        <div style="text-align: right; margin-bottom: 20px;">
            <div>Palembang, {{ $surat->tanggal_tanda_tangan ? $tanggalTandaTangan : $tanggalSekarang }}</div>
            <div style="margin-right: 30px;">Yang menyatakan,</div>
        </div>
        
        <div style="text-align: right; margin-top: 10px;">
            @if($surat->signature_data)
                <div style="margin-bottom: 10px;">
                    <img src="{{ $surat->signature_data }}" alt="Tanda Tangan" class="signature-img">
                </div>
            @endif
            <div style="margin-right: 20px;">({{ $surat->asesor->nama_lengkap ?? '-' }})</div>
        </div>
    </div>
</body>

</html>

