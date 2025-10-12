@extends('layouts.app')

@section('title', 'Jadwal Uji')
@section('page-title', 'Jadwal Uji')

@section('content')
<!-- Main Content -->
<div class="container-fluid">
    <!-- Jadwal Uji Section -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Daftar Jadwal Uji Kompetensi</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addJadwalModal">
                <i class="fas fa-plus me-2"></i>Tambah Jadwal Uji
            </button>
        </div>

        <!-- Search Box -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="searchJadwal" placeholder="Cari berdasarkan nama batch, skema, atau TUK...">
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
                    <table class="table table-bordered table-hover" id="jadwalTable">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Batch</th>
                                <th>Skema Sertifikasi</th>
                                <th>TUK</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Jam</th>
                                <th>Kuota</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwals as $index => $jadwal)
                            <tr>
                                <td>{{ $jadwals->firstItem() + $index }}</td>
                                <td>{{ $jadwal->nama_batch }}</td>
                                <td>{{ $jadwal->skemaSertifikasi->nama_skema }}</td>
                                <td>{{ $jadwal->tuk->nama_tuk }}</td>
                                <td>{{ $jadwal->tanggal_mulai->format('d F Y') }}</td>
                                <td>{{ $jadwal->tanggal_selesai->format('d F Y') }}</td>
                                <td>{{ $jadwal->jam_mulai->format('H:i') }} - {{ $jadwal->jam_selesai->format('H:i') }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $jadwal->kuota_terisi ?? 0 }}/{{ $jadwal->kuota_maksimal }}</span>
                                </td>
                                <td>
                                    @if($jadwal->status == 'open')
                                        <span class="badge bg-success">Open</span>
                                    @elseif($jadwal->status == 'closed')
                                        <span class="badge bg-warning">Closed</span>
                                    @elseif($jadwal->status == 'completed')
                                        <span class="badge bg-secondary">Completed</span>
                                    @else
                                        <span class="badge bg-info">Draft</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-warning btn-sm" 
                                                onclick="editJadwal({{ $jadwal->id }}, '{{ $jadwal->nama_batch }}', {{ $jadwal->skema_sertifikasi_id }}, {{ $jadwal->tuk_id }}, '{{ $jadwal->tanggal_mulai->format('Y-m-d') }}', '{{ $jadwal->tanggal_selesai->format('Y-m-d') }}', '{{ $jadwal->jam_mulai->format('H:i') }}', '{{ $jadwal->jam_selesai->format('H:i') }}', {{ $jadwal->kuota_maksimal }}, '{{ $jadwal->keterangan }}', '{{ $jadwal->status }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.jadwal-uji') }}/{{ $jadwal->id }}" method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')" 
                                              style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada data jadwal uji</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center">
                    {{ $jadwals->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Jadwal Modal -->
<div class="modal fade" id="addJadwalModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jadwal Uji Kompetensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.jadwal-uji') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama_batch" class="form-label">Nama Batch <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_batch') is-invalid @enderror" 
                                   id="nama_batch" name="nama_batch" value="{{ old('nama_batch') }}" required>
                            @error('nama_batch')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="skema_sertifikasi_id" class="form-label">Skema Sertifikasi <span class="text-danger">*</span></label>
                            <select class="form-select @error('skema_sertifikasi_id') is-invalid @enderror" 
                                    id="skema_sertifikasi_id" name="skema_sertifikasi_id" required>
                                <option value="">Pilih Skema Sertifikasi</option>
                                @foreach($skemas as $skema)
                                    <option value="{{ $skema->id }}" {{ old('skema_sertifikasi_id') == $skema->id ? 'selected' : '' }}>
                                        {{ $skema->nama_skema }}
                                    </option>
                                @endforeach
                            </select>
                            @error('skema_sertifikasi_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tuk_id" class="form-label">TUK (Tempat Uji Kompetensi) <span class="text-danger">*</span></label>
                            <select class="form-select @error('tuk_id') is-invalid @enderror" 
                                    id="tuk_id" name="tuk_id" required>
                                <option value="">Pilih TUK</option>
                                @foreach($tuks as $tuk)
                                    <option value="{{ $tuk->id }}" {{ old('tuk_id') == $tuk->id ? 'selected' : '' }}>
                                        {{ $tuk->nama_tuk }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tuk_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="kuota_maksimal" class="form-label">Kuota Maksimal <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('kuota_maksimal') is-invalid @enderror" 
                                   id="kuota_maksimal" name="kuota_maksimal" value="{{ old('kuota_maksimal') }}" min="1" required>
                            @error('kuota_maksimal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                   id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                   id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required>
                            @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="jam_mulai" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" 
                                   id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}" required>
                            @error('jam_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="jam_selesai" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('jam_selesai') is-invalid @enderror" 
                                   id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai') }}" required>
                            @error('jam_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                  id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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

<!-- Edit Jadwal Modal -->
<div class="modal fade" id="editJadwalModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Jadwal Uji Kompetensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editJadwalForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_nama_batch" class="form-label">Nama Batch <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_nama_batch" name="nama_batch" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_skema_sertifikasi_id" class="form-label">Skema Sertifikasi <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_skema_sertifikasi_id" name="skema_sertifikasi_id" required>
                                <option value="">Pilih Skema Sertifikasi</option>
                                @foreach($skemas as $skema)
                                    <option value="{{ $skema->id }}">{{ $skema->nama_skema }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_tuk_id" class="form-label">TUK (Tempat Uji Kompetensi) <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_tuk_id" name="tuk_id" required>
                                <option value="">Pilih TUK</option>
                                @foreach($tuks as $tuk)
                                    <option value="{{ $tuk->id }}">{{ $tuk->nama_tuk }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_kuota_maksimal" class="form-label">Kuota Maksimal <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_kuota_maksimal" name="kuota_maksimal" min="1" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_tanggal_mulai" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_tanggal_mulai" name="tanggal_mulai" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_tanggal_selesai" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_tanggal_selesai" name="tanggal_selesai" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_jam_mulai" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="edit_jam_mulai" name="jam_mulai" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_jam_selesai" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="edit_jam_selesai" name="jam_selesai" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-select" id="edit_status" name="status">
                                <option value="draft">Draft</option>
                                <option value="open">Open</option>
                                <option value="closed">Closed</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="edit_keterangan" name="keterangan" rows="3"></textarea>
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

<script>
// Search functionality
document.getElementById('searchJadwal').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const table = document.getElementById('jadwalTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;
        
        // Search in nama batch (index 1), skema (index 2), and TUK (index 3)
        for (let j = 1; j <= 3; j++) {
            if (cells[j] && cells[j].textContent.toLowerCase().includes(searchTerm)) {
                found = true;
                break;
            }
        }
        
        row.style.display = found ? '' : 'none';
    }
});

// Edit function
function editJadwal(id, namaBatch, skemaId, tukId, tanggalMulai, tanggalSelesai, jamMulai, jamSelesai, kuotaMaksimal, keterangan, status) {
    document.getElementById('editJadwalForm').action = "{{ route('admin.jadwal-uji') }}/" + id;
    document.getElementById('edit_nama_batch').value = namaBatch;
    document.getElementById('edit_skema_sertifikasi_id').value = skemaId;
    document.getElementById('edit_tuk_id').value = tukId;
    document.getElementById('edit_tanggal_mulai').value = tanggalMulai;
    document.getElementById('edit_tanggal_selesai').value = tanggalSelesai;
    document.getElementById('edit_jam_mulai').value = jamMulai;
    document.getElementById('edit_jam_selesai').value = jamSelesai;
    document.getElementById('edit_kuota_maksimal').value = kuotaMaksimal;
    document.getElementById('edit_keterangan').value = keterangan;
    document.getElementById('edit_status').value = status;
    
    new bootstrap.Modal(document.getElementById('editJadwalModal')).show();
}
</script>
@endsection
