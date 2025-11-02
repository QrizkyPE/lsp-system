<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LSP System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #2c3e50;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
        }
        .sidebar .nav-link:hover {
            background-color: #34495e;
            color: #fff;
        }
        .sidebar .nav-link.active {
            background-color: #3498db;
            color: #fff;
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            @auth
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white">LSP System</h4>
                        <small class="text-muted">{{ auth()->user()->role }}</small>
                    </div>
                    
                    <ul class="nav flex-column">
                        @if(auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.skema.*') ? 'active' : '' }}" href="{{ route('admin.skema.index') }}">
                                    <i class="fas fa-certificate me-2"></i>Skema Sertifikasi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.unit-kompetensi') ? 'active' : '' }}" href="{{ route('admin.unit-kompetensi') }}">
                                    <i class="fas fa-list me-2"></i>Unit Kompetensi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.elemen') ? 'active' : '' }}" href="{{ route('admin.elemen') }}">
                                    <i class="fas fa-tasks me-2"></i>Elemen
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.kriteria-unjuk-kerja') ? 'active' : '' }}" href="{{ route('admin.kriteria-unjuk-kerja') }}">
                                    <i class="fas fa-check-circle me-2"></i>Kriteria Unjuk Kerja
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.asesor') ? 'active' : '' }}" href="{{ route('admin.asesor') }}">
                                    <i class="fas fa-user-tie me-2"></i>Asesor
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.tuk') ? 'active' : '' }}" href="{{ route('admin.tuk') }}">
                                    <i class="fas fa-building me-2"></i>TUK
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.jadwal-uji') ? 'active' : '' }}" href="{{ route('admin.jadwal-uji') }}">
                                    <i class="fas fa-calendar me-2"></i>Jadwal Uji
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.penugasan') ? 'active' : '' }}" href="{{ route('admin.penugasan') }}">
                                    <i class="fas fa-user-check me-2"></i>Penugasan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.pendaftaran') ? 'active' : '' }}" href="{{ route('admin.pendaftaran') }}">
                                    <i class="fas fa-file-alt me-2"></i>Pendaftaran
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.persetujuan-asesmen*') ? 'active' : '' }}" href="{{ route('admin.persetujuan-asesmen') }}">
                                    <i class="fas fa-clipboard-check me-2"></i>Persetujuan Asesmen
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}" href="{{ route('admin.laporan') }}">
                                    <i class="fas fa-chart-bar me-2"></i>Laporan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.personalization') ? 'active' : '' }}" href="{{ route('admin.personalization') }}">
                                    <i class="fas fa-signature me-2"></i>Personalisasi
                                </a>
                            </li>
                            
                        @elseif(auth()->user()->role === 'asesor')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('asesor.dashboard') ? 'active' : '' }}" href="{{ route('asesor.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('asesor.penugasan') ? 'active' : '' }}" href="{{ route('asesor.penugasan') }}">
                                    <i class="fas fa-user-check me-2"></i>Penugasan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('asesor.unit-kompetensi') ? 'active' : '' }}" href="{{ route('asesor.unit-kompetensi') }}">
                                    <i class="fas fa-list me-2"></i>Unit Kompetensi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('asesor.elemen') ? 'active' : '' }}" href="{{ route('asesor.elemen') }}">
                                    <i class="fas fa-tasks me-2"></i>Elemen
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('asesor.kriteria-unjuk-kerja') ? 'active' : '' }}" href="{{ route('asesor.kriteria-unjuk-kerja') }}">
                                    <i class="fas fa-check-circle me-2"></i>Kriteria Unjuk Kerja
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('asesor.dokumen') ? 'active' : '' }}" href="{{ route('asesor.dokumen') }}">
                                    <i class="fas fa-file-alt me-2"></i>Dokumen
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('asesor.asesmen') ? 'active' : '' }}" href="{{ route('asesor.asesmen') }}">
                                    <i class="fas fa-clipboard-check me-2"></i>Asesmen
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('asesor.observasi*') ? 'active' : '' }}" href="{{ route('asesor.observasi.index') }}">
                                    <i class="fas fa-eye me-2"></i>Observasi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('asesor.penyesuaian*') ? 'active' : '' }}" href="{{ route('asesor.penyesuaian.index') }}">
                                    <i class="fas fa-adjust me-2"></i>Penyesuaian
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('asesor.soal-upload*') ? 'active' : '' }}" href="{{ route('asesor.soal-upload.index') }}">
                                    <i class="fas fa-file-upload me-2"></i>Upload Soal
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('asesor.rekaman-asesmen*') ? 'active' : '' }}" href="{{ route('asesor.rekaman-asesmen.index') }}">
                                    <i class="fas fa-file-alt me-2"></i>Rekaman Asesmen
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('asesor.umpan-balik*') ? 'active' : '' }}" href="{{ route('asesor.umpan-balik.index') }}">
                                    <i class="fas fa-comments me-2"></i>Umpan Balik Asesmen
                                </a>
                            </li>
                            
                            
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('asesor.personalization') ? 'active' : '' }}" href="{{ route('asesor.personalization') }}">
                                    <i class="fas fa-signature me-2"></i>Personalisasi
                                </a>
                            </li>
                        @elseif(auth()->user()->role === 'mahasiswa')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}" href="{{ route('mahasiswa.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('mahasiswa.pendaftaran') ? 'active' : '' }}" href="{{ route('mahasiswa.pendaftaran') }}">
                                    <i class="fas fa-user-plus me-2"></i>Pendaftaran
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('mahasiswa.riwayat-pendaftaran') ? 'active' : '' }}" href="{{ route('mahasiswa.riwayat-pendaftaran') }}">
                                    <i class="fas fa-history me-2"></i>Riwayat Pendaftaran
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('mahasiswa.jadwal') ? 'active' : '' }}" href="{{ route('mahasiswa.jadwal') }}">
                                    <i class="fas fa-calendar me-2"></i>Jadwal
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('mahasiswa.observasi-checklist*') ? 'active' : '' }}" href="{{ route('mahasiswa.observasi-checklist') }}">
                                    <i class="fas fa-clipboard-list me-2"></i>Observasi Checklist
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('mahasiswa.penyesuaian-checklist*') ? 'active' : '' }}" href="{{ route('mahasiswa.penyesuaian-checklist') }}">
                                    <i class="fas fa-adjust me-2"></i>Penyesuaian Checklist
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('mahasiswa.soal*') ? 'active' : '' }}" href="{{ route('mahasiswa.soal.index') }}">
                                    <i class="fas fa-file-alt me-2"></i>Soal
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('mahasiswa.rekaman-asesmen*') ? 'active' : '' }}" href="{{ route('mahasiswa.rekaman-asesmen') }}">
                                    <i class="fas fa-file-alt me-2"></i>Rekaman Asesmen
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('mahasiswa.umpan-balik*') ? 'active' : '' }}" href="{{ route('mahasiswa.umpan-balik.index') }}">
                                    <i class="fas fa-comments me-2"></i>Umpan Balik Asesmen
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('mahasiswa.dokumen') ? 'active' : '' }}" href="{{ route('mahasiswa.dokumen') }}">
                                    <i class="fas fa-file-alt me-2"></i>Dokumen
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('mahasiswa.hasil') ? 'active' : '' }}" href="{{ route('mahasiswa.hasil') }}">
                                    <i class="fas fa-chart-line me-2"></i>Hasil
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </nav>
            @endauth

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('page-title', 'Dashboard')</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        @auth
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-2"></i>{{ auth()->user()->name }}
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a></li>
                            </ul>
                        </div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        @endauth
                    </div>
                </div>

                @if(session('success') && !request()->routeIs('mahasiswa.pendaftaran') && !request()->routeIs('asesor.soal-upload.*'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error') && !request()->routeIs('mahasiswa.pendaftaran') && !request()->routeIs('asesor.soal-upload.*'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
