@extends('layouts.app')

@section('title', 'Detail Pendaftaran')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-file-alt me-2"></i>Detail Pendaftaran</h2>
                <a href="{{ route('mahasiswa.riwayat-pendaftaran') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
                </a>
            </div>

            <!-- Status Card -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Status Pendaftaran</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Status:</strong>
                                    @switch($pendaftaran->status)
                                        @case('pending')
                                            <span class="badge bg-warning">Menunggu Persetujuan</span>
                                            @break
                                        @case('approved')
                                            <span class="badge bg-success">Disetujui</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-danger">Ditolak</span>
                                            @break
                                        @case('in_progress')
                                            <span class="badge bg-info">Sedang Berlangsung</span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-success">Selesai</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $pendaftaran->status }}</span>
                                    @endswitch
                                </div>
                                <div class="col-md-3">
                                    <strong>Tanggal Pendaftaran:</strong>
                                    {{ $pendaftaran->tanggal_pendaftaran ? \Carbon\Carbon::parse($pendaftaran->tanggal_pendaftaran)->format('d/m/Y H:i') : '-' }}
                                </div>
                                <div class="col-md-3">
                                    <strong>Skema Sertifikasi:</strong>
                                    {{ $pendaftaran->skemaSertifikasi->nama_skema ?? '-' }}
                                </div>
                                <div class="col-md-3">
                                    <strong>Jadwal Uji:</strong>
                                    @if($pendaftaran->jadwalUji)
                                        {{ $pendaftaran->jadwalUji->nama_batch }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Pendaftaran -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Mahasiswa</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Nama:</strong></td>
                                    <td>{{ $pendaftaran->user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $pendaftaran->user->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>NIM:</strong></td>
                                    <td>{{ $pendaftaran->user->nim ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Program Studi:</strong></td>
                                    <td>{{ $pendaftaran->user->program_studi ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-calendar me-2"></i>Jadwal Uji</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Nama Batch:</strong></td>
                                    <td>
                                        @if($pendaftaran->jadwalUji)
                                            {{ $pendaftaran->jadwalUji->nama_batch }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal:</strong></td>
                                    <td>
                                        @if($pendaftaran->jadwalUji)
                                            {{ $pendaftaran->jadwalUji->tanggal_mulai ? \Carbon\Carbon::parse($pendaftaran->jadwalUji->tanggal_mulai)->format('d/m/Y') : '-' }}
                                            - {{ $pendaftaran->jadwalUji->tanggal_selesai ? \Carbon\Carbon::parse($pendaftaran->jadwalUji->tanggal_selesai)->format('d/m/Y') : '-' }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Jam:</strong></td>
                                    <td>
                                        @if($pendaftaran->jadwalUji)
                                            {{ $pendaftaran->jadwalUji->jam_mulai ? \Carbon\Carbon::parse($pendaftaran->jadwalUji->jam_mulai)->format('H:i') : '-' }}
                                            - {{ $pendaftaran->jadwalUji->jam_selesai ? \Carbon\Carbon::parse($pendaftaran->jadwalUji->jam_selesai)->format('H:i') : '-' }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Lokasi:</strong></td>
                                    <td>
                                        @if($pendaftaran->jadwalUji && $pendaftaran->jadwalUji->tuk)
                                            {{ $pendaftaran->jadwalUji->tuk->alamat }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Profil -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Data Profil</h5>
                        </div>
                        <div class="card-body">
                            @if($profilData && is_array($profilData))
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-user me-2"></i>Data Pribadi</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Nama Lengkap:</strong></td>
                                                <td>{{ $profilData['nama_lengkap'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tempat Lahir:</strong></td>
                                                <td>{{ $profilData['tempat_lahir'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tanggal Lahir:</strong></td>
                                                <td>{{ $profilData['tanggal_lahir'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Jenis Kelamin:</strong></td>
                                                <td>{{ $profilData['jenis_kelamin'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>No. KTP:</strong></td>
                                                <td>{{ $profilData['no_ktp'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>No. Telepon:</strong></td>
                                                <td>{{ $profilData['no_telp'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Email:</strong></td>
                                                <td>{{ $profilData['email'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Kebangsaan:</strong></td>
                                                <td>{{ $profilData['kebangsaan'] ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-home me-2"></i>Alamat</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Alamat Rumah:</strong></td>
                                                <td>{{ $profilData['alamat_rumah'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Kode Pos:</strong></td>
                                                <td>{{ $profilData['kode_pos'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Pekerjaan:</strong></td>
                                                <td>{{ $profilData['pekerjaan'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Jabatan:</strong></td>
                                                <td>{{ $profilData['jabatan'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Kualifikasi Pendidikan:</strong></td>
                                                <td>{{ $profilData['kualifikasi_pendidikan'] ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Data Institusi/Lembaga -->
                                @if(isset($profilData['nama_institusi']) && $profilData['nama_institusi'])
                                <div class="mt-3">
                                    <h6><i class="fas fa-university me-2"></i>Data Institusi/Lembaga</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>Nama Institusi:</strong></td>
                                                    <td>{{ $profilData['nama_institusi'] ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Email Lembaga:</strong></td>
                                                    <td>{{ $profilData['email_lembaga'] ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Alamat Lembaga:</strong></td>
                                                    <td>{{ $profilData['alamat_lembaga'] ?? '-' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>No. Telepon Lembaga:</strong></td>
                                                    <td>{{ $profilData['no_telp_lembaga'] ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>No. Fax Lembaga:</strong></td>
                                                    <td>{{ $profilData['no_fax_lembaga'] ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Kode Pos Lembaga:</strong></td>
                                                    <td>{{ $profilData['kode_pos_lembaga'] ?? '-' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Data profil belum tersedia atau belum diisi.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Sertifikasi -->
            @if($sertifikasiData && is_object($unitKompetensiJudul) && $unitKompetensiJudul->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-certificate me-2"></i>Data Sertifikasi</h5>
                        </div>
                        <div class="card-body">
                            @foreach($unitKompetensiJudul as $unit)
                            <div class="mb-4">
                                <h6 class="text-primary">{{ $unit->unitKompetensi->nama_unit ?? 'Unit Kompetensi' }}</h6>
                                
                                @php
                                    $elemenForUnit = is_object($elemenJudul) ? $elemenJudul->where('unit_kompetensi_judul_id', $unit->id) : collect();
                                @endphp
                                
                                @if(is_object($elemenForUnit) && $elemenForUnit->count() > 0)
                                    @foreach($elemenForUnit as $elemen)
                                    <div class="ms-3 mb-3">
                                        <h6 class="text-secondary">{{ $elemen->elemen->nama_elemen ?? 'Elemen' }}</h6>
                                        
                                        @php
                                            $kriteriaForElemen = is_object($kriteriaUnjukKerjaJudul) ? $kriteriaUnjukKerjaJudul->where('elemen_judul_id', $elemen->id) : collect();
                                        @endphp
                                        
                                        @if(is_object($kriteriaForElemen) && $kriteriaForElemen->count() > 0)
                                        <div class="ms-3">
                                            <table class="table table-sm table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="10%">No</th>
                                                        <th width="70%">Kriteria Unjuk Kerja</th>
                                                        <th width="20%">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($kriteriaForElemen as $kriteria)
                                                    <tr>
                                                        <td>{{ $kriteria->kriteriaUnjukKerja->nomor_kriteria ?? '-' }}</td>
                                                        <td>{{ $kriteria->kriteriaUnjukKerja->deskripsi ?? '-' }}</td>
                                                        <td>
                                                            @if($asesmenData && isset($asesmenData[$kriteria->id]))
                                                                @if($asesmenData[$kriteria->id]['kompeten'] ?? false)
                                                                    <span class="badge bg-success">Kompeten</span>
                                                                @elseif($asesmenData[$kriteria->id]['belum_kompeten'] ?? false)
                                                                    <span class="badge bg-danger">Belum Kompeten</span>
                                                                @else
                                                                    <span class="badge bg-warning">Belum Dinilai</span>
                                                                @endif
                                                            @else
                                                                <span class="badge bg-secondary">Tidak Ada Data</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Hasil Asesmen Mandiri -->
            @if($asesmenData)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Hasil Asesmen Mandiri</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $totalKriteria = 0;
                                $kompetenCount = 0;
                                $belumKompetenCount = 0;
                                
                                foreach($asesmenData as $key => $value) {
                                    if($key !== 'signature_data') {
                                        $totalKriteria++;
                                        $kompeten = $value['kompeten'] ?? false;
                                        $belumKompeten = $value['belum_kompeten'] ?? false;
                                        
                                        if($kompeten) $kompetenCount++;
                                        if($belumKompeten) $belumKompetenCount++;
                                    }
                                }
                            @endphp
                            
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h4 class="text-primary">{{ $totalKriteria }}</h4>
                                            <small>Total Kriteria</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h4>{{ $kompetenCount }}</h4>
                                            <small>Kompeten</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-danger text-white">
                                        <div class="card-body text-center">
                                            <h4>{{ $belumKompetenCount }}</h4>
                                            <small>Belum Kompeten</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body text-center">
                                            <h4>{{ $totalKriteria - $kompetenCount - $belumKompetenCount }}</h4>
                                            <small>Belum Dinilai</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if(isset($asesmenData['signature_data']))
                            <div class="mt-3">
                                <h6><i class="fas fa-signature me-2"></i>Tanda Tangan Mahasiswa</h6>
                                <div class="text-center">
                                    <img src="{{ $asesmenData['signature_data'] }}" alt="Tanda Tangan Mahasiswa" 
                                         style="max-width: 300px; border: 1px solid #ddd; border-radius: 4px; background: white;">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Verifikasi Status -->
            @if(is_object($pendaftaran->verifications) && $pendaftaran->verifications->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Status Verifikasi</h5>
                        </div>
                        <div class="card-body">
                            @foreach($pendaftaran->verifications as $verification)
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="fas fa-user-shield me-2"></i>
                                                {{ $verification->verifier->name ?? 'Unknown' }}
                                                @if($verification->type === 'admin_approval')
                                                    <span class="badge bg-primary">Admin</span>
                                                @elseif($verification->type === 'asesor_verification')
                                                    <span class="badge bg-info">Asesor</span>
                                                @endif
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Status:</strong> 
                                                @switch($verification->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning">Menunggu</span>
                                                        @break
                                                    @case('approved')
                                                        <span class="badge bg-success">Disetujui</span>
                                                        @break
                                                    @case('rejected')
                                                        <span class="badge bg-danger">Ditolak</span>
                                                        @break
                                                    @case('verified')
                                                        <span class="badge bg-info">Terverifikasi</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ $verification->status }}</span>
                                                @endswitch
                                            </p>
                                            <p><strong>Tanggal:</strong> {{ $verification->verification_date ? \Carbon\Carbon::parse($verification->verification_date)->format('d/m/Y H:i') : '-' }}</p>
                                            @if($verification->notes)
                                                <p><strong>Catatan:</strong> {{ $verification->notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="fas fa-signature me-2"></i>
                                                Tanda Tangan {{ $verification->type === 'admin_approval' ? 'Admin' : 'Asesor' }}
                                            </h6>
                                        </div>
                                        <div class="card-body text-center">
                                            @if($verification->signature_data)
                                                <img src="{{ $verification->signature_data }}" 
                                                     alt="Tanda Tangan {{ $verification->verifier->name ?? 'Verifikator' }}" 
                                                     style="max-width: 100%; max-height: 150px; border: 1px solid #ddd; border-radius: 4px; background: white;">
                                                <p class="mt-2 text-muted">
                                                    <small>Tanda tangan {{ $verification->verifier->name ?? 'verifikator' }}</small>
                                                </p>
                                            @else
                                                <div class="text-muted">
                                                    <i class="fas fa-signature fa-3x mb-2"></i>
                                                    <p>Tanda tangan tidak tersedia</p>
                                                    @if(config('app.debug'))
                                                        <small class="text-danger">Debug: signature_data is null or empty</small>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Alasan Penolakan -->
            @if($pendaftaran->status == 'rejected' && $pendaftaran->alasan_penolakan)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0"><i class="fas fa-times-circle me-2"></i>Alasan Penolakan</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $pendaftaran->alasan_penolakan }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
