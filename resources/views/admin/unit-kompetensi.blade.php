@extends('layouts.app')

@section('title', 'Unit Kompetensi')
@section('page-title', 'Unit Kompetensi')

@section('content')
<!-- Main Content -->
<div class="container-fluid">
    <!-- Unit Kompetensi Section -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Daftar Unit Kompetensi</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUnitJudulModal">
                <i class="fas fa-plus me-2"></i>Tambah Unit
            </button>
        </div>

        <!-- Filter and Search Box -->
        <div class="row mb-3">
            <div class="col-md-4">
                <form method="GET" action="{{ route('admin.unit-kompetensi') }}" id="filterForm">
                    <label for="filter_judul" class="form-label"><strong>Filter Judul Sertifikasi:</strong></label>
                    <select name="filter_judul" id="filter_judul" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Judul Sertifikasi</option>
                        @foreach($judulOptions as $judul)
                            <option value="{{ $judul }}" {{ $filterJudul == $judul ? 'selected' : '' }}>
                                {{ $judul }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="col-md-8">
                <label for="searchUnit" class="form-label"><strong>Pencarian:</strong></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="searchUnit" placeholder="Cari berdasarkan judul sertifikasi, kode unit, atau nama unit...">
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="unitJudulTable" class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Judul Sertifikasi</th>
                                <th>Kode Unit</th>
                                <th>Judul Unit</th>
                                <th>Standar Kompetensi Kerja</th>
                                <th>Kelompok</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($unitsJudul as $index => $unit)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $unit->judul_sertifikasi }}</td>
                                <td>{{ $unit->kode_unit }}</td>
                                <td>{{ $unit->judul_unit }}</td>
                                <td>{{ $unit->standar_kompetensi_kerja }}</td>
                                <td>
                                    @if($unit->ada_pembagian_kelompok && isset($unit->kelompokAsesor))
                                        <div class="small">
                                            <strong>{{ $unit->jumlah_kelompok }} Kelompok</strong>
                                            @for($i = 1; $i <= $unit->jumlah_kelompok; $i++)
                                                @php
                                                    $kelompokAsesor = $unit->kelompokAsesor->get($i) ?? collect();
                                                @endphp
                                                @if($kelompokAsesor->count() > 0)
                                                    <div class="mt-1">
                                                        <strong>Kelompok {{ $i }}:</strong>
                                                        <ul class="list-unstyled mb-0 ms-2">
                                                            @foreach($kelompokAsesor as $asesor)
                                                                <li>• {{ $asesor->user->nama_lengkap ?? $asesor->user->name }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            @endfor
                                        </div>
                                    @else
                                        <span class="text-muted">Tidak ada</span>
                                    @endif
                                </td>
                                <td>
                                    @if($unit->status)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-warning btn-sm" 
                                                data-unit-id="{{ $unit->id }}"
                                                data-judul="{{ $unit->judul_sertifikasi }}"
                                                data-kode="{{ $unit->kode_unit }}"
                                                data-nama="{{ $unit->judul_unit }}"
                                                data-standar="{{ $unit->standar_kompetensi_kerja }}"
                                                data-status="{{ $unit->status ? '1' : '0' }}"
                                                data-ada-pembagian="{{ $unit->ada_pembagian_kelompok ? '1' : '0' }}"
                                                data-jumlah-kelompok="{{ $unit->jumlah_kelompok ?? '' }}"
@php
    $kelompokAsesorData = [];
    if (isset($unit->kelompokAsesor) && is_object($unit->kelompokAsesor)) {
        $kelompokAsesorData = $unit->kelompokAsesor->mapWithKeys(function($collection, $key) {
            return [strval($key) => $collection->map(function($item) {
                return is_object($item) && method_exists($item, 'toArray') ? $item->toArray() : (is_array($item) ? $item : []);
            })->values()->toArray()];
        })->toArray();
    }
@endphp
                                                data-kelompok-asesor="{{ htmlspecialchars(json_encode($kelompokAsesorData), ENT_QUOTES, 'UTF-8') }}"
                                                onclick="editUnitJudulFromButton(this)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                onclick="deleteUnitJudul({{ $unit->id }}, '{{ addslashes($unit->judul_unit) }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data unit kompetensi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</div>

<!-- Add Unit Modal -->
<div class="modal fade" id="addUnitJudulModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Unit Kompetensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.unit-kompetensi-judul.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="judul_sertifikasi" class="form-label">Judul Sertifikasi <span class="text-danger">*</span></label>
                        <select class="form-select @error('judul_sertifikasi') is-invalid @enderror" 
                                id="judul_sertifikasi" name="judul_sertifikasi" required>
                            <option value="">Pilih Judul Sertifikasi</option>
                            @foreach($judulOptions as $judul)
                                <option value="{{ $judul }}">{{ $judul }}</option>
                            @endforeach
                        </select>
                        @error('judul_sertifikasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kode_unit_judul" class="form-label">Kode Unit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode_unit') is-invalid @enderror" 
                                   id="kode_unit_judul" name="kode_unit" value="{{ old('kode_unit') }}" required>
                            @error('kode_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="judul_unit_judul" class="form-label">Judul Unit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('judul_unit') is-invalid @enderror" 
                                   id="judul_unit_judul" name="judul_unit" value="{{ old('judul_unit') }}" required>
                            @error('judul_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="standar_kompetensi_kerja" class="form-label">Standar Kompetensi Kerja <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('standar_kompetensi_kerja') is-invalid @enderror" 
                               id="standar_kompetensi_kerja" name="standar_kompetensi_kerja" value="{{ old('standar_kompetensi_kerja') }}" required>
                        @error('standar_kompetensi_kerja')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status">
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Unit Kompetensi yang tidak aktif tidak akan muncul di halaman pendaftaran mahasiswa</small>
                    </div>

                    <!-- Pembagian Kelompok Pekerjaan -->
                    <div class="mb-3">
                        <label class="form-label">Adakah pembagian kelompok pekerjaan?</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ada_pembagian_kelompok" id="ada_pembagian_ya" value="1" onchange="togglePembagianKelompok(this)">
                            <label class="form-check-label" for="ada_pembagian_ya">Ya</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ada_pembagian_kelompok" id="ada_pembagian_tidak" value="0" checked onchange="togglePembagianKelompok(this)">
                            <label class="form-check-label" for="ada_pembagian_tidak">Tidak</label>
                        </div>
                    </div>

                    <div id="pembagian_kelompok_container" style="display: none;">
                        <div class="mb-3">
                            <label for="jumlah_kelompok" class="form-label">Jumlah Kelompok <span class="text-danger">*</span></label>
                            <select class="form-select" id="jumlah_kelompok" name="jumlah_kelompok" onchange="renderKelompokAsesor()">
                                <option value="">Pilih Jumlah Kelompok</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>

                        <div id="kelompok_asesor_container"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Unit Modal (Original) -->
<div class="modal fade" id="editUnitModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Unit Kompetensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUnitForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_skema_sertifikasi_id" class="form-label">Skema Sertifikasi <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_skema_sertifikasi_id" name="skema_sertifikasi_id" required>
                            @foreach($skemas as $skema)
                                <option value="{{ $skema->id }}">{{ $skema->nama_skema }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_kode_unit" class="form-label">Kode Unit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_kode_unit" name="kode_unit" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_nama_unit" class="form-label">Nama Unit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_nama_unit" name="nama_unit" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_kriteria_penilaian" class="form-label">Kriteria Penilaian <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="edit_kriteria_penilaian" name="kriteria_penilaian" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Unit Judul Modal -->
<div class="modal fade" id="editUnitJudulModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Unit Kompetensi per Judul</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUnitJudulForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_judul_sertifikasi" class="form-label">Judul Sertifikasi <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_judul_sertifikasi" name="judul_sertifikasi" required>
                            @foreach($judulOptions as $judul)
                                <option value="{{ $judul }}">{{ $judul }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_kode_unit_judul" class="form-label">Kode Unit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_kode_unit_judul" name="kode_unit" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_judul_unit_judul" class="form-label">Judul Unit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_judul_unit_judul" name="judul_unit" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_standar_kompetensi_kerja" class="form-label">Standar Kompetensi Kerja <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_standar_kompetensi_kerja" name="standar_kompetensi_kerja" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                        <small class="form-text text-muted">Unit Kompetensi yang tidak aktif tidak akan muncul di halaman pendaftaran mahasiswa</small>
                    </div>

                    <!-- Pembagian Kelompok Pekerjaan -->
                    <div class="mb-3">
                        <label class="form-label">Adakah pembagian kelompok pekerjaan?</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ada_pembagian_kelompok" id="edit_ada_pembagian_ya" value="1" onchange="toggleEditPembagianKelompok(this)">
                            <label class="form-check-label" for="edit_ada_pembagian_ya">Ya</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ada_pembagian_kelompok" id="edit_ada_pembagian_tidak" value="0" checked onchange="toggleEditPembagianKelompok(this)">
                            <label class="form-check-label" for="edit_ada_pembagian_tidak">Tidak</label>
                        </div>
                    </div>

                    <div id="edit_pembagian_kelompok_container" style="display: none;">
                        <div class="mb-3">
                            <label for="edit_jumlah_kelompok" class="form-label">Jumlah Kelompok <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_jumlah_kelompok" name="jumlah_kelompok" onchange="renderEditKelompokAsesor()">
                                <option value="">Pilih Jumlah Kelompok</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>

                        <div id="edit_kelompok_asesor_container"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Define asesor data globally
@php
    $asesorArray = [];
    $originalCount = 0;
    $asesorType = 'unknown';
    
    // If $asesor is not set or empty, fetch directly from database
    $shouldFetch = false;
    $fetchReason = '';
    
    // Calculate count first - try multiple methods
    $calculatedCount = -1;
    if (isset($asesor)) {
        try {
            // Try is_countable() first
            if (is_countable($asesor)) {
                $calculatedCount = count($asesor);
            }
            // Try method count() on object
            if ($calculatedCount < 0 && is_object($asesor) && method_exists($asesor, 'count')) {
                try {
                    $calculatedCount = $asesor->count();
                } catch (\Exception $e) {
                    // Ignore
                }
            }
            // Try count() on array
            if ($calculatedCount < 0 && is_array($asesor)) {
                $calculatedCount = count($asesor);
            }
            // Try isEmpty() - if empty, count is 0
            if ($calculatedCount < 0 && is_object($asesor) && method_exists($asesor, 'isEmpty')) {
                try {
                    if ($asesor->isEmpty()) {
                        $calculatedCount = 0;
                    }
                } catch (\Exception $e) {
                    // Ignore
                }
            }
        } catch (\Exception $e) {
            $calculatedCount = -1;
        }
    }
    
    // Check if we need to fetch - use calculated count
    if (!isset($asesor)) {
        $shouldFetch = true;
        $fetchReason = 'not_set';
    } elseif ($calculatedCount === 0) {
        // If count is 0, we need to fetch
        $shouldFetch = true;
        $fetchReason = 'count_zero';
    } elseif (is_object($asesor) && method_exists($asesor, 'isEmpty')) {
        // Also check isEmpty() for Collections (as additional check)
        try {
            if ($asesor->isEmpty()) {
                $shouldFetch = true;
                $fetchReason = 'collection_empty';
            }
        } catch (\Exception $e) {
            // Ignore
        }
    } elseif (is_array($asesor) && empty($asesor)) {
        // Check if it's an empty array
        $shouldFetch = true;
        $fetchReason = 'array_empty';
    } elseif (!$asesor) {
        // Check if it's falsy
        $shouldFetch = true;
        $fetchReason = 'empty_value';
    }
    
    
    if ($shouldFetch) {
        try {
            $asesor = \App\Models\Asesor::with('user')->get();
            // If still empty, try without eager loading
            if ($asesor->isEmpty()) {
                $asesor = \App\Models\Asesor::all();
                if ($asesor->isNotEmpty()) {
                    $asesor->load('user');
                }
            }
            // Verify we got data
            if ($asesor->isEmpty()) {
                // Last resort: use DB query
                $asesor = \Illuminate\Support\Facades\DB::table('asesor')
                    ->join('users', 'asesor.user_id', '=', 'users.id')
                    ->select('asesor.*', 'users.name', 'users.email', 'users.nama_lengkap')
                    ->get()
                    ->map(function($item) {
                        return (object)[
                            'id' => $item->id,
                            'user_id' => $item->user_id,
                            'nama_lengkap' => $item->nama_lengkap,
                            'user' => (object)[
                                'id' => $item->user_id,
                                'name' => $item->name,
                                'email' => $item->email,
                                'nama_lengkap' => $item->nama_lengkap ?? $item->name
                            ]
                        ];
                    });
            }
        } catch (\Exception $e) {
            try {
                $asesor = \Illuminate\Support\Facades\DB::table('asesor')
                    ->join('users', 'asesor.user_id', '=', 'users.id')
                    ->select('asesor.*', 'users.name', 'users.email', 'users.nama_lengkap')
                    ->get()
                    ->map(function($item) {
                        return (object)[
                            'id' => $item->id,
                            'user_id' => $item->user_id,
                            'nama_lengkap' => $item->nama_lengkap,
                            'user' => (object)[
                                'id' => $item->user_id,
                                'name' => $item->name,
                                'email' => $item->email,
                                'nama_lengkap' => $item->nama_lengkap ?? $item->name
                            ]
                        ];
                    });
            } catch (\Exception $e2) {
                $asesor = collect([]);
            }
        }
    }
    
    
    // After fetch, verify we have data - if still empty, force fetch again
    if ($shouldFetch && isset($asesor) && is_object($asesor) && method_exists($asesor, 'isEmpty') && $asesor->isEmpty()) {
        // Fetch failed, try one more time with DB query
        try {
            $asesor = \Illuminate\Support\Facades\DB::table('asesor')
                ->join('users', 'asesor.user_id', '=', 'users.id')
                ->select('asesor.*', 'users.name', 'users.email', 'users.nama_lengkap')
                ->get()
                ->map(function($item) {
                    return (object)[
                        'id' => $item->id,
                        'user_id' => $item->user_id,
                        'nama_lengkap' => $item->nama_lengkap,
                        'user' => (object)[
                            'id' => $item->user_id,
                            'name' => $item->name,
                            'email' => $item->email,
                            'nama_lengkap' => $item->nama_lengkap ?? $item->name
                        ]
                    ];
                });
        } catch (\Exception $e) {
            // Keep empty collection if all fails
        }
    }
    
    if (isset($asesor) && $asesor) {
        $asesorType = gettype($asesor);
        try {
            // Check if it's countable - use same logic as $originalCount
            if (is_countable($asesor)) {
                $originalCount = count($asesor);
                // If count is 0, ALWAYS re-fetch regardless of $shouldFetch
                if ($originalCount === 0) {
                    // Re-fetch data - try multiple methods
                    try {
                        $asesor = \App\Models\Asesor::with('user')->get();
                        if ($asesor->isEmpty()) {
                            $asesor = \App\Models\Asesor::all();
                            if ($asesor->isNotEmpty()) {
                                $asesor->load('user');
                            }
                        }
                        // If still empty, try DB query
                        if ($asesor->isEmpty()) {
                            $asesor = \Illuminate\Support\Facades\DB::table('asesor')
                                ->join('users', 'asesor.user_id', '=', 'users.id')
                                ->select('asesor.*', 'users.name', 'users.email', 'users.nama_lengkap')
                                ->get()
                                ->map(function($item) {
                                    return (object)[
                                        'id' => $item->id,
                                        'user_id' => $item->user_id,
                                        'nama_lengkap' => $item->nama_lengkap,
                                        'user' => (object)[
                                            'id' => $item->user_id,
                                            'name' => $item->name,
                                            'email' => $item->email,
                                            'nama_lengkap' => $item->nama_lengkap ?? $item->name
                                        ]
                                    ];
                                });
                        }
                        // Recalculate count after fetch
                        if (is_countable($asesor)) {
                            $originalCount = count($asesor);
                        } elseif (is_object($asesor) && method_exists($asesor, 'count')) {
                            $originalCount = $asesor->count();
                        }
                    } catch (\Exception $e) {
                        // Keep original count
                    }
                }
            } else {
                $originalCount = 0;
                // If not countable but exists, try to fetch anyway
                try {
                    $asesor = \App\Models\Asesor::with('user')->get();
                    if (is_countable($asesor)) {
                        $originalCount = count($asesor);
                    }
                } catch (\Exception $e) {
                    // Keep original
                }
            }
            
            // Always loop through collection/array to create numeric array
            if (is_iterable($asesor)) {
                foreach ($asesor as $item) {
                    if (is_object($item) && method_exists($item, 'toArray')) {
                        $itemArray = $item->toArray();
                        $asesorArray[] = $itemArray;
                    } elseif (is_array($item)) {
                        $asesorArray[] = $item;
                    } else {
                        $asesorArray[] = (array)$item;
                    }
                }
            }
        } catch (\Exception $e) {
            $asesorArray = [];
        }
    }
    
    // Final check: ensure it's always a numeric array (not associative)
    $asesorArray = array_values($asesorArray);
    
    // Debug: Log count in PHP
    $count = count($asesorArray);
@endphp
const asesorData = @json($asesorArray);

function editUnit(id, kode, nama, deskripsi, kriteria, skemaId) {
    document.getElementById('editUnitForm').action = '{{ route("admin.unit-kompetensi") }}/' + id;
    document.getElementById('edit_kode_unit').value = kode;
    document.getElementById('edit_nama_unit').value = nama;
    document.getElementById('edit_deskripsi').value = deskripsi;
    document.getElementById('edit_kriteria_penilaian').value = kriteria;
    document.getElementById('edit_skema_sertifikasi_id').value = skemaId;
    
    new bootstrap.Modal(document.getElementById('editUnitModal')).show();
}

// Store kelompokAsesor data globally for edit function
let storedKelompokAsesor = {};

// Define function globally to ensure it's accessible
window.editUnitJudulFromButton = function(button) {
    const id = button.getAttribute('data-unit-id');
    const judul = button.getAttribute('data-judul');
    const kode = button.getAttribute('data-kode');
    const nama = button.getAttribute('data-nama');
    const standar = button.getAttribute('data-standar');
    const status = button.getAttribute('data-status');
    const adaPembagianKelompok = button.getAttribute('data-ada-pembagian') === '1';
    const jumlahKelompok = button.getAttribute('data-jumlah-kelompok');
    const kelompokAsesorJson = button.getAttribute('data-kelompok-asesor');
    
    // Parse and store kelompokAsesor data
    storedKelompokAsesor = {};
    try {
        storedKelompokAsesor = JSON.parse(kelompokAsesorJson || '{}');
    } catch (e) {
        storedKelompokAsesor = {};
    }
    
    document.getElementById('editUnitJudulForm').action = '{{ url("admin/unit-kompetensi-judul") }}/' + id;
    document.getElementById('edit_judul_sertifikasi').value = judul;
    document.getElementById('edit_kode_unit_judul').value = kode;
    document.getElementById('edit_judul_unit_judul').value = nama;
    document.getElementById('edit_standar_kompetensi_kerja').value = standar;
    document.getElementById('edit_status').value = status;
    
    // Set pembagian kelompok
    if (adaPembagianKelompok) {
        document.getElementById('edit_ada_pembagian_ya').checked = true;
        document.getElementById('edit_ada_pembagian_tidak').checked = false;
        document.getElementById('edit_pembagian_kelompok_container').style.display = 'block';
        
        if (jumlahKelompok) {
            document.getElementById('edit_jumlah_kelompok').value = jumlahKelompok;
            // Render first, then set selected values
            renderEditKelompokAsesor();
        } else {
            document.getElementById('edit_jumlah_kelompok').value = '';
            document.getElementById('edit_kelompok_asesor_container').innerHTML = '';
        }
    } else {
        document.getElementById('edit_ada_pembagian_ya').checked = false;
        document.getElementById('edit_ada_pembagian_tidak').checked = true;
        document.getElementById('edit_pembagian_kelompok_container').style.display = 'none';
        document.getElementById('edit_jumlah_kelompok').value = '';
        document.getElementById('edit_kelompok_asesor_container').innerHTML = '';
    }
    
    new bootstrap.Modal(document.getElementById('editUnitJudulModal')).show();
};

// Also define as regular function for compatibility
function editUnitJudulFromButton(button) {
    window.editUnitJudulFromButton(button);
}

function deleteUnitJudul(id, judulUnit) {
    if (confirm('Apakah Anda yakin ingin menghapus unit "' + judulUnit + '"?')) {
        // Show loading state
        const deleteBtn = event.target.closest('button');
        const originalHTML = deleteBtn.innerHTML;
        deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        deleteBtn.disabled = true;
        
        // Create form data
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'DELETE');
        
        // Send AJAX request
        fetch('{{ url("admin/unit-kompetensi-judul") }}/' + id, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove row from table
                const row = deleteBtn.closest('tr');
                row.remove();
                
                // Show success message
                showAlert('success', data.message || 'Unit kompetensi berhasil dihapus');
                
                // Update row numbers
                updateRowNumbers();
            } else {
                showAlert('error', data.message || 'Gagal menghapus unit kompetensi');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat menghapus unit kompetensi');
        })
        .finally(() => {
            // Restore button state
            deleteBtn.innerHTML = originalHTML;
            deleteBtn.disabled = false;
        });
    }
}

function updateRowNumbers() {
    const rows = document.querySelectorAll('#unitJudulTable tbody tr');
    rows.forEach((row, index) => {
        const numberCell = row.querySelector('td:first-child');
        if (numberCell) {
            numberCell.textContent = index + 1;
        }
    });
}

function showAlert(type, message) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create new alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insert alert at the top of the content
    const content = document.querySelector('.container-fluid');
    content.insertBefore(alertDiv, content.firstChild);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Search functionality
document.getElementById('searchUnit').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const table = document.getElementById('unitJudulTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;
        
        // Search in judul sertifikasi (index 1), kode unit (index 2), and judul unit (index 3)
        for (let j = 1; j <= 3; j++) {
            if (cells[j] && cells[j].textContent.toLowerCase().includes(searchTerm)) {
                found = true;
                break;
            }
        }
        
        row.style.display = found ? '' : 'none';
    }
});

// Pembagian Kelompok Pekerjaan
function togglePembagianKelompok(radio) {
    const container = document.getElementById('pembagian_kelompok_container');
    if (radio.value === '1') {
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
        // Reset form
        document.getElementById('jumlah_kelompok').value = '';
        document.getElementById('kelompok_asesor_container').innerHTML = '';
    }
}

function renderKelompokAsesor() {
    const jumlahKelompok = document.getElementById('jumlah_kelompok').value;
    const container = document.getElementById('kelompok_asesor_container');
    
    if (!jumlahKelompok) {
        container.innerHTML = '';
        return;
    }
    
    // Use global asesor data
    const asesor = asesorData || [];
    
    if (!Array.isArray(asesor) || asesor.length === 0) {
        container.innerHTML = '<div class="alert alert-warning">Tidak ada asesor yang tersedia.</div>';
        return;
    }
    
    let html = '';
    
    for (let i = 1; i <= parseInt(jumlahKelompok); i++) {
        let optionsHtml = '';
        asesor.forEach(function(a) {
            if (a && a.id && a.user) {
                const nama = a.user.nama_lengkap || a.user.name || '';
                const email = a.user.email || '';
                optionsHtml += `<option value="${a.id}" data-nama="${nama}">${nama} - ${email}</option>`;
            }
        });
        
        html += `
            <div class="card mb-3">
                <div class="card-header">
                    <strong>Kelompok Pekerjaan ${i}</strong>
                </div>
                <div class="card-body">
                    <label class="form-label">Pilih Asesor untuk Kelompok ${i}</label>
                    <select class="form-select kelompok-asesor-select" name="kelompok_asesor[${i}][]" multiple size="5" data-kelompok="${i}" onchange="validateUnitKompetensi(this, ${i})">
                        ${optionsHtml}
                    </select>
                    <small class="form-text text-muted">Gunakan Ctrl+Click (Windows) atau Cmd+Click (Mac) untuk memilih beberapa asesor</small>
                </div>
            </div>
        `;
    }
    
    container.innerHTML = html;
    
    // Add event listeners after DOM is updated
    // Use longer timeout to ensure DOM is fully ready
    setTimeout(() => {
        // First enable all options
        const allSelects = document.querySelectorAll('.kelompok-asesor-select');
        allSelects.forEach(select => {
            Array.from(select.options).forEach(option => {
                option.disabled = false;
                option.style.color = '';
                option.style.backgroundColor = '';
            });
        });
        // Then update disabled options based on selections
        updateDisabledAsesorOptions();
    }, 200);
}

// Update disabled options untuk mencegah asesor yang sama dipilih di lebih dari satu kelompok
function updateDisabledAsesorOptions() {
    const allSelects = document.querySelectorAll('.kelompok-asesor-select');
    
    if (allSelects.length === 0) {
        return;
    }
    
    // First, enable all options in all selects
    allSelects.forEach(select => {
        Array.from(select.options).forEach(option => {
            option.disabled = false;
            option.style.color = '';
            option.style.backgroundColor = '';
        });
    });
    
    // Collect all selected asesor IDs from all groups
    const selectedAsesorIds = new Set();
    allSelects.forEach(select => {
        Array.from(select.selectedOptions).forEach(option => {
            selectedAsesorIds.add(parseInt(option.value));
        });
    });
    
    // Disable options that are selected in other groups
    allSelects.forEach(select => {
        const currentGroup = select.getAttribute('data-kelompok');
        const currentSelected = new Set();
        
        // Get currently selected in this group
        Array.from(select.selectedOptions).forEach(option => {
            currentSelected.add(parseInt(option.value));
        });
        
        // Update disabled state for all options
        Array.from(select.options).forEach(option => {
            const optionId = parseInt(option.value);
            const isSelectedInThisGroup = currentSelected.has(optionId);
            const isSelectedInOtherGroup = selectedAsesorIds.has(optionId) && !isSelectedInThisGroup;
            
            // Disable if selected in other group, keep enabled if selected in this group or not selected anywhere
            if (isSelectedInOtherGroup) {
                option.disabled = true;
                option.style.color = '#999';
                option.style.backgroundColor = '#f5f5f5';
            } else {
                option.disabled = false;
                option.style.color = '';
                option.style.backgroundColor = '';
            }
        });
    });
}

// Validasi: mencegah asesor yang sama dipilih di lebih dari satu kelompok
function validateUnitKompetensi(select, kelompok) {
    // Update disabled options first
    updateDisabledAsesorOptions();
    
    // Check if user is trying to select an asesor that's already selected in another group
    const selectedOptions = Array.from(select.selectedOptions);
    const allSelects = document.querySelectorAll('.kelompok-asesor-select');
    let hasConflict = false;
    
    selectedOptions.forEach(option => {
        const optionId = parseInt(option.value);
        let foundInOtherGroup = false;
        
        allSelects.forEach(otherSelect => {
            if (otherSelect !== select) {
                const otherSelected = Array.from(otherSelect.selectedOptions);
                otherSelected.forEach(otherOption => {
                    if (parseInt(otherOption.value) === optionId) {
                        foundInOtherGroup = true;
                    }
                });
            }
        });
        
        if (foundInOtherGroup) {
            // Deselect if already selected in another group
            option.selected = false;
            hasConflict = true;
        }
    });
    
    if (hasConflict) {
        alert('Asesor ini sudah dipilih di kelompok lain. Satu asesor tidak dapat berada di lebih dari satu kelompok.');
    }
    
    // Update disabled options again after validation
    updateDisabledAsesorOptions();
}

// Edit Pembagian Kelompok Functions
function toggleEditPembagianKelompok(radio) {
    const container = document.getElementById('edit_pembagian_kelompok_container');
    if (radio.value === '1') {
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
        // Reset form
        document.getElementById('edit_jumlah_kelompok').value = '';
        document.getElementById('edit_kelompok_asesor_container').innerHTML = '';
    }
}

function renderEditKelompokAsesor() {
    const jumlahKelompok = document.getElementById('edit_jumlah_kelompok').value;
    const container = document.getElementById('edit_kelompok_asesor_container');
    
    if (!jumlahKelompok) {
        container.innerHTML = '';
        return;
    }
    
    // Use global asesor data - ensure it's always an array
    let asesor = [];
    if (Array.isArray(asesorData)) {
        asesor = asesorData;
    } else if (asesorData && typeof asesorData === 'object') {
        // If it's an object, convert to array
        asesor = Object.values(asesorData);
    }
    
    if (!Array.isArray(asesor) || asesor.length === 0) {
        container.innerHTML = '<div class="alert alert-warning">Tidak ada asesor yang tersedia. Silakan refresh halaman.</div>';
        return;
    }
    
    let html = '';
    
    for (let i = 1; i <= parseInt(jumlahKelompok); i++) {
        let optionsHtml = '';
        asesor.forEach(function(a) {
            if (a && a.id) {
                // Handle both cases: with user and without user
                let nama = '';
                let email = '';
                
                if (a.user) {
                    nama = a.user.nama_lengkap || a.user.name || a.nama_lengkap || '';
                    email = a.user.email || '';
                } else {
                    // Fallback to asesor data if user is not available
                    nama = a.nama_lengkap || '';
                    email = '';
                }
                
                if (nama) {
                    const displayText = email ? `${nama} - ${email}` : nama;
                    optionsHtml += `<option value="${a.id}" data-nama="${nama}">${displayText}</option>`;
                }
            }
        });
        
        html += `
            <div class="card mb-3">
                <div class="card-header">
                    <strong>Kelompok Pekerjaan ${i}</strong>
                </div>
                <div class="card-body">
                    <label class="form-label">Pilih Asesor untuk Kelompok ${i}</label>
                    <select class="form-select kelompok-asesor-select" name="kelompok_asesor[${i}][]" multiple size="5" data-kelompok="${i}" onchange="validateUnitKompetensi(this, ${i})">
                        ${optionsHtml}
                    </select>
                    <small class="form-text text-muted">Gunakan Ctrl+Click (Windows) atau Cmd+Click (Mac) untuk memilih beberapa asesor</small>
                </div>
            </div>
        `;
    }
    
    container.innerHTML = html;
    
    // Add event listeners after DOM is updated
    // Use longer timeout to ensure DOM is fully ready
    setTimeout(() => {
        const allSelects = document.querySelectorAll('.kelompok-asesor-select');
        
        if (allSelects.length === 0) {
            return;
        }
        
        // First enable all options
        allSelects.forEach(select => {
            Array.from(select.options).forEach(option => {
                option.disabled = false;
                option.style.color = '';
                option.style.backgroundColor = '';
            });
        });
        
        // Set selected asesor from storedKelompokAsesor
        if (storedKelompokAsesor && typeof storedKelompokAsesor === 'object' && Object.keys(storedKelompokAsesor).length > 0) {
            Object.keys(storedKelompokAsesor).forEach(kelompok => {
                // Convert kelompok key to string to match select name
                const kelompokKey = String(kelompok);
                const select = document.querySelector(`select[name="kelompok_asesor[${kelompokKey}][]"]`);
                if (select && storedKelompokAsesor[kelompok] && Array.isArray(storedKelompokAsesor[kelompok])) {
                    const asesorIds = storedKelompokAsesor[kelompok].map(a => {
                        // Handle both direct id and pivot structure
                        if (typeof a === 'object' && a !== null) {
                            // If it's a direct object with id
                            if (a.id) {
                                return parseInt(a.id);
                            }
                            // If it has pivot structure
                            if (a.pivot && a.pivot.asesor_id) {
                                return parseInt(a.pivot.asesor_id);
                            }
                            // If asesor_id is directly in the object
                            if (a.asesor_id) {
                                return parseInt(a.asesor_id);
                            }
                        }
                        // If it's already a number
                        if (typeof a === 'number') {
                            return a;
                        }
                        return null;
                    }).filter(id => id !== null && id !== undefined && !isNaN(id));
                    
                    Array.from(select.options).forEach(option => {
                        if (asesorIds.includes(parseInt(option.value))) {
                            option.selected = true;
                        }
                    });
                }
            });
        }
        
        // Then update disabled options based on selections
        updateDisabledAsesorOptions();
    }, 300);
}
</script>
@endsection