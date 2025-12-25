<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\SkemaSertifikasi;
use App\Models\Asesor;
use App\Models\Pendaftaran;
use App\Models\UnitKompetensi;
use App\Models\Elemen;
use App\Models\KriteriaUnjukKerja;
use App\Models\Tuk;
use App\Models\JadwalUji;
use App\Models\Penugasan;
use App\Models\User;
use App\Models\UnitKompetensiJudul;
use App\Models\ElemenJudul;
use App\Models\KriteriaUnjukKerjaJudul;
use App\Models\UserPersonalization;
use App\Models\PendaftaranVerification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalSkema = SkemaSertifikasi::count();
        $totalAsesor = Asesor::count();
        $totalPendaftaran = Pendaftaran::count();
        $totalUsers = User::count();
        $pendingApproval = Pendaftaran::where('status', 'pending')->count();
        $recentPendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalSkema',
            'totalAsesor', 
            'totalPendaftaran',
            'totalUsers',
            'pendingApproval',
            'recentPendaftaran'
        ));
    }

    // Skema Sertifikasi CRUD
    public function index()
    {
        $skemas = SkemaSertifikasi::latest()->paginate(10);
        return view('admin.skema.index', compact('skemas'));
    }

    public function create()
    {
        return view('admin.skema.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_skema' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kode_skema' => 'required|string|unique:skema_sertifikasi',
            'nomor_skema' => 'required|string|max:255',
            'level_kompetensi' => 'required|string',
            'standar_kompetensi' => 'required|string',
        ]);

        SkemaSertifikasi::create($request->all());

        return redirect()->route('admin.skema.index')
            ->with('success', 'Skema sertifikasi berhasil ditambahkan');
    }

    public function show(SkemaSertifikasi $skema)
    {
        return view('admin.skema.show', compact('skema'));
    }

    public function edit(SkemaSertifikasi $skema)
    {
        return view('admin.skema.edit', compact('skema'));
    }

    public function update(Request $request, SkemaSertifikasi $skema)
    {
        $request->validate([
            'nama_skema' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kode_skema' => 'required|string|unique:skema_sertifikasi,kode_skema,' . $skema->id,
            'nomor_skema' => 'required|string|max:255',
            'level_kompetensi' => 'required|string',
            'standar_kompetensi' => 'required|string',
        ]);

        $skema->update($request->all());

        return redirect()->route('admin.skema.index')
            ->with('success', 'Skema sertifikasi berhasil diupdate');
    }

    public function destroy(SkemaSertifikasi $skema)
    {
        $skema->delete();

        return redirect()->route('admin.skema.index')
            ->with('success', 'Skema sertifikasi berhasil dihapus');
    }

    // Unit Kompetensi
    public function unitKompetensi(Request $request)
    {
        $units = UnitKompetensi::with('skemaSertifikasi')->latest()->paginate(10);
        $skemas = SkemaSertifikasi::where('status', true)->orderBy('nama_skema')->get();
        
        // Get filter from request
        $filterJudul = $request->get('filter_judul');
        
        // Data untuk tab Unit Kompetensi per Judul
        $unitsJudulQuery = UnitKompetensiJudul::with('asesorKelompok.user')->orderBy('judul_sertifikasi')->orderBy('id');
        
        // Apply filter if exists
        if ($filterJudul) {
            $unitsJudulQuery->where('judul_sertifikasi', $filterJudul);
        }
        
        $unitsJudul = $unitsJudulQuery->get();
        
        // Group asesor by kelompok for each unit
        foreach ($unitsJudul as $unit) {
            if ($unit->ada_pembagian_kelompok && $unit->asesorKelompok && $unit->asesorKelompok->count() > 0) {
                $unit->kelompokAsesor = $unit->asesorKelompok->groupBy(function($asesor) {
                    return $asesor->pivot->kelompok;
                });
            }
        }
        
        // Get unique judul sertifikasi from database
        $judulOptions = UnitKompetensiJudul::select('judul_sertifikasi')
            ->distinct()
            ->orderBy('judul_sertifikasi')
            ->pluck('judul_sertifikasi')
            ->toArray();
        
        // Get asesor for pembagian kelompok - get all asesor with user relation
        // Always get all asesor - use DB query directly to ensure we get data
        try {
            $asesor = Asesor::with('user')->get();
            
            // If empty, try without eager loading
            if ($asesor->isEmpty()) {
                $asesor = Asesor::all();
                if ($asesor->isNotEmpty()) {
                    $asesor->load('user');
                }
            }
            
            // Ensure it's always a collection with numeric keys
            $asesor = $asesor->values();
        } catch (\Exception $e) {
            // If there's any error, get all asesor without relation
            $asesor = Asesor::all()->values();
        }
        
        // Final check - ensure it's always a collection
        if (!$asesor || !is_iterable($asesor)) {
            $asesor = collect([]);
        }
        
        return view('admin.unit-kompetensi', compact('units', 'skemas', 'unitsJudul', 'judulOptions', 'filterJudul', 'asesor'));
    }

    public function storeUnitKompetensi(Request $request)
    {
        $request->validate([
            'skema_sertifikasi_id' => 'required|exists:skema_sertifikasi,id',
            'kode_unit' => 'required|string',
            'nama_unit' => 'required|string',
            'deskripsi' => 'required|string',
            'kriteria_penilaian' => 'required|string',
        ]);

        UnitKompetensi::create($request->all());

        return redirect()->route('admin.unit-kompetensi')
            ->with('success', 'Unit kompetensi berhasil ditambahkan');
    }

    public function updateUnitKompetensi(Request $request, $id)
    {
        $request->validate([
            'skema_sertifikasi_id' => 'required|exists:skema_sertifikasi,id',
            'kode_unit' => 'required|string',
            'nama_unit' => 'required|string',
            'deskripsi' => 'required|string',
            'kriteria_penilaian' => 'required|string',
        ]);

        $unit = UnitKompetensi::findOrFail($id);
        $unit->update($request->all());

        return redirect()->route('admin.unit-kompetensi')
            ->with('success', 'Unit kompetensi berhasil diupdate');
    }

    public function deleteUnitKompetensi($id)
    {
        $unit = UnitKompetensi::findOrFail($id);
        $unit->delete();

        return redirect()->route('admin.unit-kompetensi')
            ->with('success', 'Unit kompetensi berhasil dihapus');
    }

    // Elemen
    public function elemen(Request $request)
    {
        $elemen = Elemen::with('unitKompetensi.skemaSertifikasi')->latest()->paginate(10);
        $units = UnitKompetensi::with('skemaSertifikasi')->get();
        
        // Get filter from request
        $filterJudul = $request->get('filter_judul');
        
        // Data untuk tab Elemen per Judul
        $elemenJudulQuery = ElemenJudul::with('kriteriaUnjukKerja')
            ->orderBy('judul_sertifikasi')
            ->orderBy('kode_unit')
            ->orderBy('kode_elemen');
        
        // Apply filter if exists
        if ($filterJudul) {
            $elemenJudulQuery->where('judul_sertifikasi', $filterJudul);
        }
        
        $elemenJudul = $elemenJudulQuery->get();
        
        // Get unique judul sertifikasi from database
        $judulOptions = ElemenJudul::select('judul_sertifikasi')
            ->distinct()
            ->orderBy('judul_sertifikasi')
            ->pluck('judul_sertifikasi')
            ->toArray();
        
        // Data unit kompetensi per judul untuk dropdown kode unit
        $unitKompetensiJudul = UnitKompetensiJudul::orderBy('judul_sertifikasi')->orderBy('kode_unit')->get();
        
        // Get all skema sertifikasi for dropdown
        $skemas = SkemaSertifikasi::where('status', true)->orderBy('nama_skema')->get();
        
        return view('admin.elemen', compact('elemen', 'units', 'elemenJudul', 'judulOptions', 'unitKompetensiJudul', 'filterJudul', 'skemas'));
    }

    public function storeElemen(Request $request)
    {
        $request->validate([
            'unit_kompetensi_id' => 'required|exists:unit_kompetensi,id',
            'kode_elemen' => 'required|string',
            'nama_elemen' => 'required|string',
            'deskripsi' => 'required|string',
        ]);

        Elemen::create($request->all());

        return redirect()->route('admin.elemen')
            ->with('success', 'Elemen berhasil ditambahkan');
    }

    public function updateElemen(Request $request, $id)
    {
        $request->validate([
            'unit_kompetensi_id' => 'required|exists:unit_kompetensi,id',
            'kode_elemen' => 'required|string',
            'nama_elemen' => 'required|string',
            'deskripsi' => 'required|string',
        ]);

        $elemen = Elemen::findOrFail($id);
        $elemen->update($request->all());

        return redirect()->route('admin.elemen')
            ->with('success', 'Elemen berhasil diupdate');
    }

    public function deleteElemen($id)
    {
        $elemen = Elemen::findOrFail($id);
        $elemen->delete();

        return redirect()->route('admin.elemen')
            ->with('success', 'Elemen berhasil dihapus');
    }

    // Elemen Judul CRUD
    public function storeElemenJudul(Request $request)
    {
        $request->validate([
            'judul_sertifikasi' => 'required|string|max:255',
            'kode_unit' => 'required|string|max:255|exists:unit_kompetensi_judul,kode_unit',
            'kode_elemen' => 'required|string|max:255',
            'nama_elemen' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        ElemenJudul::create($request->all());

        return redirect()->route('admin.elemen')
            ->with('success', 'Elemen judul berhasil ditambahkan');
    }

    public function updateElemenJudul(Request $request, $id)
    {
        $request->validate([
            'judul_sertifikasi' => 'required|string|max:255',
            'kode_unit' => 'required|string|max:255|exists:unit_kompetensi_judul,kode_unit',
            'kode_elemen' => 'required|string|max:255',
            'nama_elemen' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $elemen = ElemenJudul::findOrFail($id);
        $elemen->update($request->all());

        return redirect()->route('admin.elemen')
            ->with('success', 'Elemen judul berhasil diperbarui');
    }

    public function deleteElemenJudul($id)
    {
        $elemen = ElemenJudul::findOrFail($id);
        $elemen->delete();

        return redirect()->route('admin.elemen')
            ->with('success', 'Elemen judul berhasil dihapus');
    }

    // Kriteria Unjuk Kerja
    public function kriteriaUnjukKerja(Request $request)
    {
        $kriteria = KriteriaUnjukKerja::with('elemen.unitKompetensi.skemaSertifikasi')->latest()->paginate(10);
        $elemen = Elemen::with('unitKompetensi.skemaSertifikasi')->get();
        
        // Get filter from request
        $filterJudul = $request->get('filter_judul');
        
        // Data untuk tab Kriteria per Judul
        $kriteriaJudulQuery = KriteriaUnjukKerjaJudul::orderBy('judul_sertifikasi')
            ->orderBy('kode_unit')
            ->orderBy('kode_elemen')
            ->orderBy('nomor_kriteria');
        
        // Apply filter if exists
        if ($filterJudul) {
            $kriteriaJudulQuery->where('judul_sertifikasi', $filterJudul);
        }
        
        $kriteriaJudul = $kriteriaJudulQuery->get();
        
        // Get unique judul sertifikasi from database
        $judulOptions = KriteriaUnjukKerjaJudul::select('judul_sertifikasi')
            ->distinct()
            ->orderBy('judul_sertifikasi')
            ->pluck('judul_sertifikasi')
            ->toArray();
        
        // Data unit kompetensi per judul untuk dropdown kode unit
        $unitKompetensiJudul = UnitKompetensiJudul::orderBy('judul_sertifikasi')->orderBy('kode_unit')->get();
        
        // Data elemen per judul untuk autocomplete kode elemen
        $elemenJudul = ElemenJudul::orderBy('judul_sertifikasi')->orderBy('kode_unit')->orderBy('kode_elemen')->get();
        
        // Get all skema sertifikasi for dropdown
        $skemas = SkemaSertifikasi::where('status', true)->orderBy('nama_skema')->get();
        
        return view('admin.kriteria-unjuk-kerja', compact('kriteria', 'elemen', 'kriteriaJudul', 'judulOptions', 'unitKompetensiJudul', 'elemenJudul', 'filterJudul', 'skemas'));
    }

    public function storeKriteriaUnjukKerja(Request $request)
    {
        $request->validate([
            'elemen_id' => 'required|exists:elemen,id',
            'nomor_kriteria' => 'required|string',
            'deskripsi_kriteria' => 'required|string',
            'jenis_bukti' => 'required|string',
            'metode_asesmen' => 'required|string',
            'perangkat_asesmen' => 'required|string',
        ]);

        KriteriaUnjukKerja::create($request->all());

        return redirect()->route('admin.kriteria-unjuk-kerja')
            ->with('success', 'Kriteria unjuk kerja berhasil ditambahkan');
    }

    public function updateKriteriaUnjukKerja(Request $request, $id)
    {
        $request->validate([
            'elemen_id' => 'required|exists:elemen,id',
            'nomor_kriteria' => 'required|string',
            'deskripsi_kriteria' => 'required|string',
            'jenis_bukti' => 'required|string',
            'metode_asesmen' => 'required|string',
            'perangkat_asesmen' => 'required|string',
        ]);

        $kriteria = KriteriaUnjukKerja::findOrFail($id);
        $kriteria->update($request->all());

        return redirect()->route('admin.kriteria-unjuk-kerja')
            ->with('success', 'Kriteria unjuk kerja berhasil diupdate');
    }

    public function deleteKriteriaUnjukKerja($id)
    {
        $kriteria = KriteriaUnjukKerja::findOrFail($id);
        $kriteria->delete();

        return redirect()->route('admin.kriteria-unjuk-kerja')
            ->with('success', 'Kriteria unjuk kerja berhasil dihapus');
    }

    // Kriteria Judul CRUD
    public function storeKriteriaJudul(Request $request)
    {
        $request->validate([
            'judul_sertifikasi' => 'required|string|max:255',
            'kode_unit' => 'required|string|max:255|exists:unit_kompetensi_judul,kode_unit',
            'kode_elemen' => 'required|string|max:255',
            'nomor_kriteria' => 'required|string|max:255',
            'deskripsi_kriteria' => 'required|string',
            'jenis_bukti' => 'nullable|string|max:255',
            'metode_asesmen' => 'nullable|string|max:255',
            'perangkat_asesmen' => 'nullable|string|max:255',
        ]);

        KriteriaUnjukKerjaJudul::create($request->all());

        return redirect()->route('admin.kriteria-unjuk-kerja')
            ->with('success', 'Kriteria judul berhasil ditambahkan');
    }

    public function updateKriteriaJudul(Request $request, $id)
    {
        $request->validate([
            'judul_sertifikasi' => 'required|string|max:255',
            'kode_unit' => 'required|string|max:255|exists:unit_kompetensi_judul,kode_unit',
            'kode_elemen' => 'required|string|max:255',
            'nomor_kriteria' => 'required|string|max:255',
            'deskripsi_kriteria' => 'required|string',
            'jenis_bukti' => 'nullable|string|max:255',
            'metode_asesmen' => 'nullable|string|max:255',
            'perangkat_asesmen' => 'nullable|string|max:255',
        ]);

        $kriteria = KriteriaUnjukKerjaJudul::findOrFail($id);
        $kriteria->update($request->all());

        return redirect()->route('admin.kriteria-unjuk-kerja')
            ->with('success', 'Kriteria judul berhasil diperbarui');
    }

    public function deleteKriteriaJudul($id)
    {
        $kriteria = KriteriaUnjukKerjaJudul::findOrFail($id);
        $kriteria->delete();

        return redirect()->route('admin.kriteria-unjuk-kerja')
            ->with('success', 'Kriteria judul berhasil dihapus');
    }

    // Asesor
    public function asesor()
    {
        $asesor = Asesor::with('user')->latest()->paginate(10);
        $skemas = SkemaSertifikasi::where('status', true)->get();
        $users = User::where('role', 'asesor')->whereDoesntHave('asesor')->get();
        return view('admin.asesor', compact('asesor', 'skemas', 'users'));
    }


    public function updateAsesor(Request $request, $id)
    {
        $request->validate([
            'nama_lengkap' => 'required|string',
            'nip' => 'required|string',
            'jabatan' => 'required|string',
            'instansi' => 'required|string',
            'no_reg' => 'nullable|string',
            'no_sertifikat_asesor' => 'required|string',
            'tanggal_sertifikat' => 'required|date',
            'tanggal_expired' => 'required|date',
            'skema_kompetensi' => 'required|array',
            'no_telepon' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        $asesor = Asesor::findOrFail($id);
        
        // Update asesor data (excluding no_telepon and alamat)
        $asesorData = $request->except(['no_telepon', 'alamat']);
        $asesor->update($asesorData);
        
        // Update user's no_telepon and alamat if provided
        if ($asesor->user) {
            $userData = [];
            if ($request->has('no_telepon')) {
                $userData['no_telepon'] = $request->no_telepon;
            }
            if ($request->has('alamat')) {
                $userData['alamat'] = $request->alamat;
            }
            if (!empty($userData)) {
                $asesor->user->update($userData);
            }
        }

        return redirect()->route('admin.asesor')
            ->with('success', 'Asesor berhasil diupdate');
    }

    public function deleteAsesor($id)
    {
        try {
            DB::beginTransaction();

            $asesor = Asesor::with('user')->findOrFail($id);

            // Hapus user terkait terlebih dahulu agar tidak meninggalkan akun yatim
            if ($asesor->user) {
                $asesor->user->delete();
            }

            // Jika belum terhapus oleh cascade, hapus record asesor
            if ($asesor->exists) {
                $asesor->delete();
            }

            DB::commit();

            return redirect()->route('admin.asesor')
                ->with('success', 'Akun Asesor berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.asesor')
                ->with('error', 'Gagal menghapus asesor: ' . $e->getMessage());
        }
    }

    // Create Asesor Account
    public function createAsesorAccount()
    {
        $skemas = SkemaSertifikasi::where('status', true)->get();
        return view('admin.create-asesor-account', compact('skemas'));
    }

    public function storeAsesorAccount(Request $request)
    {
        $request->validate([
            // User validation
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            
            // Asesor validation
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'required|string|max:255|unique:asesor,nip',
            'jabatan' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'no_reg' => 'nullable|string|max:255',
            'no_sertifikat_asesor' => 'required|string|max:255|unique:asesor,no_sertifikat_asesor',
            'tanggal_sertifikat' => 'required|date',
            'tanggal_expired' => 'required|date|after:tanggal_sertifikat',
            'skema_kompetensi' => 'required|array|min:1',
            'skema_kompetensi.*' => 'exists:skema_sertifikasi,id',
            'no_telepon' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'asesor',
                'nama_lengkap' => $request->nama_lengkap,
                'no_telepon' => $request->no_telepon,
                'alamat' => $request->alamat,
            ]);

            // Create asesor profile
            Asesor::create([
                'user_id' => $user->id,
                'nama_lengkap' => $request->nama_lengkap,
                'nip' => $request->nip,
                'jabatan' => $request->jabatan,
                'instansi' => $request->instansi,
                'no_reg' => $request->no_reg,
                'no_sertifikat_asesor' => $request->no_sertifikat_asesor,
                'tanggal_sertifikat' => $request->tanggal_sertifikat,
                'tanggal_expired' => $request->tanggal_expired,
                'skema_kompetensi' => $request->skema_kompetensi,
                'status' => true,
            ]);

            DB::commit();

            return redirect()->route('admin.asesor')
                ->with('success', 'Akun asesor berhasil dibuat. User dapat login dengan email: ' . $user->email);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal membuat akun asesor: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Unit Kompetensi Judul Management

    public function storeUnitKompetensiJudul(Request $request)
    {
        $request->validate([
            'judul_sertifikasi' => 'required|string',
            'kode_unit' => 'required|string',
            'judul_unit' => 'required|string',
            'standar_kompetensi_kerja' => 'required|string',
            'status' => 'nullable|boolean',
            'ada_pembagian_kelompok' => 'nullable|boolean',
            'jumlah_kelompok' => 'nullable|integer|in:2,3',
            'kelompok_asesor' => 'nullable|array',
            'kelompok_asesor.*' => 'nullable|array',
            'kelompok_asesor.*.*' => 'exists:asesor,id',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? (bool)$request->status : true;
        $data['ada_pembagian_kelompok'] = $request->has('ada_pembagian_kelompok') ? (bool)$request->ada_pembagian_kelompok : false;
        
        // Jika tidak ada pembagian kelompok, set jumlah_kelompok ke null
        if (!$data['ada_pembagian_kelompok']) {
            $data['jumlah_kelompok'] = null;
        }

        $unit = UnitKompetensiJudul::create($data);

        // Simpan asesor per kelompok jika ada pembagian kelompok
        if ($data['ada_pembagian_kelompok'] && $request->has('kelompok_asesor')) {
            foreach ($request->kelompok_asesor as $kelompok => $asesorIds) {
                if (is_array($asesorIds)) {
                    foreach ($asesorIds as $asesorId) {
                        // Validasi: cek apakah unit kompetensi ini sudah dipilih di kelompok lain
                        $existing = DB::table('unit_kompetensi_judul_asesor_kelompok')
                            ->where('unit_kompetensi_judul_id', $unit->id)
                            ->where('asesor_id', $asesorId)
                            ->where('kelompok', '!=', $kelompok)
                            ->exists();
                        
                        if (!$existing) {
                            DB::table('unit_kompetensi_judul_asesor_kelompok')->insert([
                                'unit_kompetensi_judul_id' => $unit->id,
                                'asesor_id' => $asesorId,
                                'kelompok' => $kelompok,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.unit-kompetensi')
            ->with('success', 'Unit kompetensi berhasil ditambahkan');
    }

    public function updateUnitKompetensiJudul(Request $request, $id)
    {
        $request->validate([
            'judul_sertifikasi' => 'required|string',
            'kode_unit' => 'required|string',
            'judul_unit' => 'required|string',
            'standar_kompetensi_kerja' => 'required|string',
            'status' => 'nullable|boolean',
            'ada_pembagian_kelompok' => 'nullable',
            'jumlah_kelompok' => 'nullable|integer|in:2,3',
            'kelompok_asesor' => 'nullable|array',
            'kelompok_asesor.*' => 'nullable|array',
            'kelompok_asesor.*.*' => 'nullable|integer|exists:asesor,id',
        ]);

        $unit = UnitKompetensiJudul::findOrFail($id);
        $data = $request->all();
        $data['status'] = $request->has('status') ? (bool)$request->status : true;
        
        // Handle ada_pembagian_kelompok - bisa berupa string "1" atau "0" dari radio button
        if ($request->has('ada_pembagian_kelompok')) {
            $adaPembagian = $request->ada_pembagian_kelompok;
            $data['ada_pembagian_kelompok'] = ($adaPembagian === '1' || $adaPembagian === 1 || $adaPembagian === true);
        } else {
            $data['ada_pembagian_kelompok'] = false;
        }
        
        // Jika tidak ada pembagian kelompok, set jumlah_kelompok ke null dan hapus data kelompok
        if (!$data['ada_pembagian_kelompok']) {
            $data['jumlah_kelompok'] = null;
            // Hapus semua data kelompok yang ada
            DB::table('unit_kompetensi_judul_asesor_kelompok')
                ->where('unit_kompetensi_judul_id', $unit->id)
                ->delete();
        } else {
            // Hapus data kelompok lama
            DB::table('unit_kompetensi_judul_asesor_kelompok')
                ->where('unit_kompetensi_judul_id', $unit->id)
                ->delete();
            
            // Simpan data kelompok baru jika ada
            if ($request->has('kelompok_asesor') && is_array($request->kelompok_asesor)) {
                // Validasi: pastikan tidak ada asesor yang sama dipilih di lebih dari satu kelompok
                $allAsesorIds = [];
                foreach ($request->kelompok_asesor as $kelompok => $asesorIds) {
                    if (is_array($asesorIds)) {
                        foreach ($asesorIds as $asesorId) {
                            if (is_numeric($asesorId) && $asesorId > 0) {
                                $asesorIdInt = (int)$asesorId;
                                if (in_array($asesorIdInt, $allAsesorIds)) {
                                    return redirect()->back()
                                        ->withInput()
                                        ->withErrors(['kelompok_asesor' => 'Asesor dengan ID ' . $asesorIdInt . ' tidak dapat dipilih di lebih dari satu kelompok.']);
                                }
                                $allAsesorIds[] = $asesorIdInt;
                            }
                        }
                    }
                }
                
                // Jika validasi berhasil, simpan data
                foreach ($request->kelompok_asesor as $kelompok => $asesorIds) {
                    if (is_array($asesorIds)) {
                        foreach ($asesorIds as $asesorId) {
                            // Pastikan asesorId adalah integer yang valid
                            if (is_numeric($asesorId) && $asesorId > 0) {
                                DB::table('unit_kompetensi_judul_asesor_kelompok')->insert([
                                    'unit_kompetensi_judul_id' => $unit->id,
                                    'asesor_id' => (int)$asesorId,
                                    'kelompok' => $kelompok,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            }
                        }
                    }
                }
            }
        }

        $unit->update($data);

        return redirect()->route('admin.unit-kompetensi')
            ->with('success', 'Unit kompetensi berhasil diupdate');
    }

    public function deleteUnitKompetensiJudul($id)
    {
        try {
            $unit = UnitKompetensiJudul::findOrFail($id);
            $kodeUnit = $unit->kode_unit;
            
            // Hapus elemen judul yang terkait
            ElemenJudul::where('kode_unit', $kodeUnit)->delete();
            
            // Hapus kriteria unjuk kerja judul yang terkait
            KriteriaUnjukKerjaJudul::where('kode_unit', $kodeUnit)->delete();
            
            // Hapus unit kompetensi judul
            $unit->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Unit kompetensi dan data terkait berhasil dihapus'
                ]);
            }

            return redirect()->route('admin.unit-kompetensi')
                ->with('success', 'Unit kompetensi dan data terkait berhasil dihapus');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus unit kompetensi: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('admin.unit-kompetensi')
                ->with('error', 'Gagal menghapus unit kompetensi');
        }
    }

    // TUK
    public function tuk()
    {
        $tuks = Tuk::latest()->paginate(10);
        $skemas = SkemaSertifikasi::where('status', true)->get();
        return view('admin.tuk', compact('tuks', 'skemas'));
    }

    public function storeTuk(Request $request)
    {
        $request->validate([
            'nama_tuk' => 'required|string',
            'alamat' => 'required|string',
            'kota' => 'required|string',
            'provinsi' => 'required|string',
            'kode_pos' => 'required|string',
            'telepon' => 'required|string',
            'email' => 'required|email',
            'penanggung_jawab' => 'required|string',
            'skema_kompetensi' => 'required|array',
        ]);

        Tuk::create($request->all());

        return redirect()->route('admin.tuk')
            ->with('success', 'TUK berhasil ditambahkan');
    }

    public function updateTuk(Request $request, $id)
    {
        $request->validate([
            'nama_tuk' => 'required|string',
            'alamat' => 'required|string',
            'kota' => 'required|string',
            'provinsi' => 'required|string',
            'kode_pos' => 'required|string',
            'telepon' => 'required|string',
            'email' => 'required|email',
            'penanggung_jawab' => 'required|string',
            'skema_kompetensi' => 'required|array',
        ]);

        $tuk = Tuk::findOrFail($id);
        $tuk->update($request->all());

        return redirect()->route('admin.tuk')
            ->with('success', 'TUK berhasil diupdate');
    }

    public function deleteTuk($id)
    {
        $tuk = Tuk::findOrFail($id);
        $tuk->delete();

        return redirect()->route('admin.tuk')
            ->with('success', 'TUK berhasil dihapus');
    }

    // Jadwal Uji
    public function jadwalUji()
    {
        $jadwals = JadwalUji::with(['skemaSertifikasi', 'tuk'])->latest()->paginate(10);
        $skemas = SkemaSertifikasi::all();
        $tuks = Tuk::where('status', true)->get();
        return view('admin.jadwal-uji', compact('jadwals', 'skemas', 'tuks'));
    }

    public function storeJadwalUji(Request $request)
    {
        $request->validate([
            'skema_sertifikasi_id' => 'required|exists:skema_sertifikasi,id',
            'tuk_id' => 'required|exists:tuk,id',
            'nama_batch' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kuota_maksimal' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        JadwalUji::create($request->all());

        return redirect()->route('admin.jadwal-uji')
            ->with('success', 'Jadwal uji berhasil ditambahkan');
    }

    public function updateJadwalUji(Request $request, $id)
    {
        $request->validate([
            'skema_sertifikasi_id' => 'required|exists:skema_sertifikasi,id',
            'tuk_id' => 'required|exists:tuk,id',
            'nama_batch' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kuota_maksimal' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:draft,open,closed,completed',
        ]);

        $jadwal = JadwalUji::findOrFail($id);
        $jadwal->update($request->all());

        return redirect()->route('admin.jadwal-uji')
            ->with('success', 'Jadwal uji berhasil diupdate');
    }

    public function deleteJadwalUji($id)
    {
        $jadwal = JadwalUji::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('admin.jadwal-uji')
            ->with('success', 'Jadwal uji berhasil dihapus');
    }

    // Penugasan
    public function penugasan()
    {
        $penugasan = Penugasan::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk', 'asesor.user', 'pendaftaran.user'])->latest()->paginate(10);
        $jadwals = JadwalUji::with('skemaSertifikasi')->get();
        $asesor = Asesor::with('user')->where('status', true)->get();
        
        // Get IDs of pendaftaran that are already assigned to other penugasan
        $assignedPendaftaranIds = \DB::table('penugasan_pendaftaran')
            ->pluck('pendaftaran_id')
            ->unique()
            ->toArray();
        
        // Get approved pendaftaran (only those with status 'approved') that are not yet assigned
        $mahasiswa = Pendaftaran::with(['user', 'skemaSertifikasi'])
            ->where('status', 'approved')
            ->whereNotIn('id', $assignedPendaftaranIds)
            ->orderBy('no_pendaftaran')
            ->get();
        
        return view('admin.penugasan', compact('penugasan', 'jadwals', 'asesor', 'mahasiswa'));
    }

    public function storePenugasan(Request $request)
    {
        $request->validate([
            'jadwal_uji_id' => 'required|exists:jadwal_uji,id',
            'asesor_id' => 'required|exists:asesor,id',
            'jenis_penugasan' => 'required|in:asesor,mapa,ma,mkva',
            'keterangan' => 'nullable|string',
            'pendaftaran_id' => 'nullable|array',
            'pendaftaran_id.*' => 'exists:pendaftaran,id',
        ]);

        $request->merge(['tanggal_penugasan' => now()]);
        $penugasan = Penugasan::create($request->except('pendaftaran_id'));

        // Attach pendaftaran if provided
        if ($request->has('pendaftaran_id') && is_array($request->pendaftaran_id)) {
            $penugasan->pendaftaran()->attach($request->pendaftaran_id);
        }

        return redirect()->route('admin.penugasan')
            ->with('success', 'Penugasan berhasil ditambahkan');
    }

    public function updatePenugasan(Request $request, $id)
    {
        $request->validate([
            'jadwal_uji_id' => 'required|exists:jadwal_uji,id',
            'asesor_id' => 'required|exists:asesor,id',
            'jenis_penugasan' => 'required|in:asesor,mapa,ma,mkva',
            'keterangan' => 'nullable|string',
            'pendaftaran_id' => 'nullable|array',
            'pendaftaran_id.*' => 'nullable|exists:pendaftaran,id', // Allow null/empty values in array
        ]);

        $penugasan = Penugasan::findOrFail($id);
        $penugasan->update($request->except('pendaftaran_id'));

        // Sync pendaftaran based on jenis_penugasan
        if ($request->jenis_penugasan === 'asesor') {
            // For asesor type, always sync pendaftaran
            // Get selected pendaftaran_ids, filter out empty values
            $pendaftaranIds = [];
            
            // Always get pendaftaran_id from request
            // JavaScript ensures pendaftaran_id[] is always sent (even if empty)
            $requestIds = $request->input('pendaftaran_id', []);
            
            if (is_array($requestIds)) {
                // Filter out empty, null, or invalid values
                $pendaftaranIds = array_values(array_filter($requestIds, function($id) {
                    return !empty($id) && $id !== '' && $id !== null && is_numeric($id);
                }));
            }
            
            // Always sync with the filtered array (even if empty, this will remove all assignments)
            // This ensures that deselected items are removed from penugasan
            $penugasan->pendaftaran()->sync($pendaftaranIds);
        } else {
            // For non-asesor types, detach all pendaftaran
            $penugasan->pendaftaran()->detach();
        }

        return redirect()->route('admin.penugasan')
            ->with('success', 'Penugasan berhasil diupdate');
    }

    public function deletePenugasan($id)
    {
        try {
            $penugasan = Penugasan::findOrFail($id);
            $penugasan->delete();

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Penugasan berhasil dihapus'
                ]);
            }

            return redirect()->route('admin.penugasan')
                ->with('success', 'Penugasan berhasil dihapus');
        } catch (\Exception $e) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus penugasan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('admin.penugasan')
                ->with('error', 'Gagal menghapus penugasan: ' . $e->getMessage());
        }
    }

    public function getPenugasan($id)
    {
        $penugasan = Penugasan::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk', 'asesor.user', 'pendaftaran'])->findOrFail($id);
        $penugasan->pendaftaran_ids = $penugasan->pendaftaran->pluck('id')->toArray();
        
        // Get IDs of pendaftaran that are already assigned to other penugasan (excluding current penugasan)
        $assignedPendaftaranIds = \DB::table('penugasan_pendaftaran')
            ->where('penugasan_id', '!=', $id)
            ->pluck('pendaftaran_id')
            ->unique()
            ->toArray();
        
        // Get approved pendaftaran that are available for edit
        // Include those already assigned to this penugasan, exclude those assigned to other penugasan
        $currentPendaftaranIds = $penugasan->pendaftaran->pluck('id')->toArray();
        
        // Get all approved pendaftaran
        $allApproved = Pendaftaran::with(['user', 'skemaSertifikasi'])
            ->where('status', 'approved')
            ->get();
        
        // Filter: include current penugasan's pendaftaran OR pendaftaran not assigned to others
        $availableMahasiswa = $allApproved->filter(function($m) use ($assignedPendaftaranIds, $currentPendaftaranIds) {
            // Always include current penugasan's pendaftaran
            if (!empty($currentPendaftaranIds) && in_array($m->id, $currentPendaftaranIds)) {
                return true;
            }
            // Include if not assigned to other penugasan
            if (empty($assignedPendaftaranIds) || !in_array($m->id, $assignedPendaftaranIds)) {
                return true;
                }
            return false;
            })
        ->sortBy('no_pendaftaran')
            ->map(function($m) {
                return [
                    'id' => $m->id,
                    'text' => $m->no_pendaftaran . ' - ' . ($m->user->nama_lengkap ?? $m->user->name) . ' (' . ($m->skemaSertifikasi->nama_skema ?? '-') . ')'
                ];
        })
        ->values(); // Re-index array
        
        $penugasan->available_mahasiswa = $availableMahasiswa;
        
        return response()->json($penugasan);
    }

    // Pendaftaran
    public function pendaftaran()
    {
        $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi', 'jadwalUji', 'verifications'])
            ->latest()
            ->paginate(10);

        // Calculate summary statistics
        $totalPendaftaran = Pendaftaran::count();
        $pendingPendaftaran = Pendaftaran::where('status', 'pending')->count();
        $approvedPendaftaran = Pendaftaran::where('status', 'approved')->count();
        $rejectedPendaftaran = Pendaftaran::where('status', 'rejected')->count();

        return view('admin.pendaftaran', compact(
            'pendaftaran', 
            'totalPendaftaran', 
            'pendingPendaftaran', 
            'approvedPendaftaran', 
            'rejectedPendaftaran'
        ));
    }


    public function approvePendaftaran(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        
        // Temporary debug - remove after testing
        if (config('app.debug')) {
            Log::info('Admin Approval Request', [
                'request_all' => $request->all(),
                'signature_data' => $request->signature_data,
                'has_signature' => $request->has('signature_data')
            ]);
        }
        
        // Update pendaftaran status
        $pendaftaran->update([
            'status' => 'approved',
            'tanggal_verifikasi' => now()
        ]);

        // Update verification record
        $verification = PendaftaranVerification::where('pendaftaran_id', $id)
            ->where('type', 'admin_verification')
            ->first();
        
        if ($verification) {
            $updateData = [
                'verifier_id' => Auth::id(),
                'status' => 'approved',
                'verification_date' => now()
            ];
            
            // Add signature data if provided
            if ($request->has('signature_data') && $request->signature_data) {
                $updateData['signature_data'] = $request->signature_data;
            }
            
            $verification->update($updateData);
            
            // Temporary debug - remove after testing
            if (config('app.debug')) {
                Log::info('Verification Update Result', [
                    'verification_id' => $verification->id,
                    'update_data' => $updateData,
                    'final_signature' => $verification->fresh()->signature_data ? 'EXISTS' : 'NULL'
                ]);
            }
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function rejectPendaftaran(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|min:10'
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi.',
            'alasan_penolakan.min' => 'Alasan penolakan minimal 10 karakter.'
        ]);

        $pendaftaran = Pendaftaran::findOrFail($id);
        
        // Update pendaftaran status
        $pendaftaran->update([
            'status' => 'rejected',
            'alasan_penolakan' => $request->alasan_penolakan,
            'tanggal_verifikasi' => now()
        ]);

        // Update verification record
        $verification = PendaftaranVerification::where('pendaftaran_id', $id)
            ->where('type', 'admin_verification')
            ->first();
        
        if ($verification) {
            $verification->update([
                'verifier_id' => Auth::id(),
                'status' => 'rejected',
                'verification_date' => now()
            ]);
        }

        // Return JSON for AJAX requests, redirect for form submissions
        if ($request->expectsJson() || $request->isJson()) {
        return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil ditolak.'
        ]);
        }

        return redirect()->route('admin.pendaftaran')
            ->with('success', 'Pendaftaran berhasil ditolak.');
    }


    public function viewPendaftaranDetail($id)
    {
        $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi', 'jadwalUji'])
            ->findOrFail($id);
        
        return view('admin.pendaftaran-detail', compact('pendaftaran'));
    }


    // Laporan
    public function laporan()
    {
        $rekamanAsesmen = \App\Models\RekamanAsesmenKompetensi::with(['asesor', 'pendaftaran.user', 'pendaftaran.skemaSertifikasi'])
            ->latest()
            ->paginate(15);

        return view('admin.laporan', compact('rekamanAsesmen'));
    }

    /**
     * Show rekaman asesmen detail for admin
     */
    public function showRekamanAsesmen($id)
    {
        $rekamanAsesmen = \App\Models\RekamanAsesmenKompetensi::with(['asesor', 'pendaftaran.user', 'pendaftaran.skemaSertifikasi'])
            ->findOrFail($id);

        // Get unit kompetensi from database to get actual names
        $unitKompetensiList = \App\Models\UnitKompetensiJudul::where('judul_sertifikasi', $rekamanAsesmen->pendaftaran->skemaSertifikasi->nama_skema)
            ->get()
            ->keyBy(function($unit) {
                return $unit->judul_unit;
            });

        // Merge stored data with database data to ensure correct unit names
        if ($rekamanAsesmen->unit_kompetensi_data) {
            $unitData = collect($rekamanAsesmen->unit_kompetensi_data)->map(function($unit, $index) use ($unitKompetensiList) {
                // If judul_unit is missing or incorrect, get from database by index
                if (empty($unit['judul_unit']) || !$unitKompetensiList->has($unit['judul_unit'])) {
                    $unitFromDb = $unitKompetensiList->values()->get($index);
                    if ($unitFromDb) {
                        $unit['judul_unit'] = $unitFromDb->judul_unit;
                    } else {
                        $unit['judul_unit'] = 'Unit ' . ($index + 1);
                    }
                }
                return $unit;
            })->toArray();
            $rekamanAsesmen->unit_kompetensi_data = $unitData;
        }

        return view('admin.rekaman-asesmen-show', compact('rekamanAsesmen'));
    }

    // Manage Users
    public function manageUsers()
    {
        $users = User::with('asesor')->paginate(10);
        $skemas = SkemaSertifikasi::where('status', true)->get();
        return view('admin.manage-users', compact('users', 'skemas'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:mahasiswa,asesor',
            'nim' => 'required_if:role,mahasiswa|string|max:255',
            'program_studi' => 'required_if:role,mahasiswa|string|max:255',
            'fakultas' => 'required_if:role,mahasiswa|string|max:255',
            'no_telepon' => 'required_if:role,mahasiswa|string|max:20',
            'alamat' => 'required_if:role,mahasiswa|string',
            'nip' => 'required_if:role,asesor|string|max:255',
            'jabatan' => 'required_if:role,asesor|string|max:255',
            'instansi' => 'required_if:role,asesor|string|max:255',
            'no_sertifikat_asesor' => 'required_if:role,asesor|string|max:255',
            'tanggal_sertifikat' => 'required_if:role,asesor|date',
            'tanggal_expired' => 'required_if:role,asesor|date',
            'skema_kompetensi' => 'required_if:role,asesor|array',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'nama_lengkap' => $request->name,
            'nim' => $request->nim,
            'program_studi' => $request->program_studi,
            'fakultas' => $request->fakultas,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
        ]);

        // Create asesor profile if role is asesor
        if ($request->role === 'asesor') {
            Asesor::create([
                'user_id' => $user->id,
                'nama_lengkap' => $request->name,
                'nip' => $request->nip,
                'jabatan' => $request->jabatan,
                'instansi' => $request->instansi,
                'no_sertifikat_asesor' => $request->no_sertifikat_asesor,
                'tanggal_sertifikat' => $request->tanggal_sertifikat,
                'tanggal_expired' => $request->tanggal_expired,
                'skema_kompetensi' => $request->skema_kompetensi,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil dibuat');
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:mahasiswa,asesor',
            'nim' => 'required_if:role,mahasiswa|string|max:255',
            'program_studi' => 'required_if:role,mahasiswa|string|max:255',
            'fakultas' => 'required_if:role,mahasiswa|string|max:255',
            'no_telepon' => 'required_if:role,mahasiswa|string|max:20',
            'alamat' => 'required_if:role,mahasiswa|string',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'nama_lengkap' => $request->name,
            'nim' => $request->nim,
            'program_studi' => $request->program_studi,
            'fakultas' => $request->fakultas,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
        ];

        if ($request->password) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil diperbarui');
    }

    public function destroyUser(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.users.index')->with('error', 'Tidak dapat menghapus akun admin');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil dihapus');
    }

    public function generateAK05()
    {
        // Implementation for AK.05 report generation
        return response()->download(public_path('reports/ak05-report.pdf'));
    }

    public function personalization()
    {
        $personalization = UserPersonalization::where('user_id', Auth::id())->first();
        return view('admin.personalization', compact('personalization'));
    }

    public function storePersonalization(Request $request)
    {
        $request->validate([
            'signature_data' => 'required|string'
        ]);

        try {
            UserPersonalization::updateOrCreate(
                ['user_id' => Auth::id()],
                [
                    'signature_data' => $request->signature_data,
                    'updated_at' => now()
                ]
            );

            return redirect()->route('admin.personalization')
                ->with('success', 'Tanda tangan berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->route('admin.personalization')
                ->with('error', 'Gagal menyimpan tanda tangan: ' . $e->getMessage());
        }
    }

    public function getSignature()
    {
        $personalization = UserPersonalization::where('user_id', Auth::id())->first();
        
        if ($personalization && $personalization->signature_data) {
            return response()->json([
                'success' => true,
                'signature' => $personalization->signature_data
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Tanda tangan tidak ditemukan'
        ]);
    }

    // Persetujuan Asesmen
    public function persetujuanAsesmen()
    {
        $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi', 'jadwalUji'])
            ->where('status', 'in_progress')
            ->whereNotNull('asesmen_data')
            ->latest()
            ->paginate(10);

        return view('admin.persetujuan-asesmen', compact('pendaftaran'));
    }

    public function detailPersetujuanAsesmen($id)
    {
        $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi', 'jadwalUji'])
            ->where('id', $id)
            ->where('status', 'in_progress')
            ->whereNotNull('asesmen_data')
            ->firstOrFail();

        $asesmenData = is_string($pendaftaran->asesmen_data) ? 
            json_decode($pendaftaran->asesmen_data, true) : 
            $pendaftaran->asesmen_data;

        return view('admin.detail-persetujuan-asesmen', compact('pendaftaran', 'asesmenData'));
    }

    public function konfirmasiPersetujuanAsesmen(Request $request, $id)
    {
        $request->validate([
            'tanggal_asesmen' => 'required|date',
            'waktu_asesmen' => 'required|string',
            'tuk_asesmen' => 'required|string',
        ]);

        $pendaftaran = Pendaftaran::findOrFail($id);
        
        // Get existing asesmen data
        $asesmenData = null;
        if ($pendaftaran->asesmen_data) {
            $asesmenData = is_string($pendaftaran->asesmen_data) ? 
                json_decode($pendaftaran->asesmen_data, true) : 
                $pendaftaran->asesmen_data;
        }
        
        // Update asesmen data with admin input (preserve existing bukti from asesor)
        $asesmenData['tanggal_asesmen'] = $request->tanggal_asesmen;
        $asesmenData['waktu_asesmen'] = $request->waktu_asesmen;
        $asesmenData['tuk_asesmen'] = $request->tuk_asesmen;
        $asesmenData['confirmed_at'] = now();
        
        // Update pendaftaran
        $pendaftaran->update([
            'asesmen_data' => json_encode($asesmenData),
            'status' => 'completed',
            'tanggal_selesai' => now()
        ]);

        return redirect()->route('admin.persetujuan-asesmen')
            ->with('success', 'Persetujuan asesmen berhasil dikonfirmasi');
    }
}
