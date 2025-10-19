<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
    public function unitKompetensi()
    {
        $units = UnitKompetensi::with('skemaSertifikasi')->latest()->paginate(10);
        $skemas = SkemaSertifikasi::all();
        
        // Data untuk tab Unit Kompetensi per Judul
        $unitsJudul = UnitKompetensiJudul::orderBy('judul_sertifikasi')->orderBy('id')->get();
        $judulOptions = [
            'PENGEMBANG WEB (WEB DEVELOPER)',
            'TEKNISI PERPAJAKAN (PAJAK PENGHASILAN ORANG PRIBADI)',
            'System Analyst',
            'Junior Web Programmer',
            'Database Administrator',
            'Analis Senior Hubungan Industrial'
        ];
        
        return view('admin.unit-kompetensi', compact('units', 'skemas', 'unitsJudul', 'judulOptions'));
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
    public function elemen()
    {
        $elemen = Elemen::with('unitKompetensi.skemaSertifikasi')->latest()->paginate(10);
        $units = UnitKompetensi::with('skemaSertifikasi')->get();
        
        // Data untuk tab Elemen per Judul
        $elemenJudul = ElemenJudul::with('kriteriaUnjukKerja')->orderBy('judul_sertifikasi')->orderBy('kode_unit')->orderBy('kode_elemen')->get();
        $judulOptions = [
            'PENGEMBANG WEB (WEB DEVELOPER)',
            'TEKNISI PERPAJAKAN (PAJAK PENGHASILAN ORANG PRIBADI)',
            'System Analyst',
            'Junior Web Programmer',
            'Database Administrator',
            'Analis Senior Hubungan Industrial'
        ];
        
        // Data unit kompetensi per judul untuk dropdown kode unit
        $unitKompetensiJudul = UnitKompetensiJudul::orderBy('judul_sertifikasi')->orderBy('kode_unit')->get();
        
        return view('admin.elemen', compact('elemen', 'units', 'elemenJudul', 'judulOptions', 'unitKompetensiJudul'));
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
    public function kriteriaUnjukKerja()
    {
        $kriteria = KriteriaUnjukKerja::with('elemen.unitKompetensi.skemaSertifikasi')->latest()->paginate(10);
        $elemen = Elemen::with('unitKompetensi.skemaSertifikasi')->get();
        
        // Data untuk tab Kriteria per Judul
        $kriteriaJudul = KriteriaUnjukKerjaJudul::orderBy('judul_sertifikasi')->orderBy('kode_unit')->orderBy('kode_elemen')->orderBy('nomor_kriteria')->get();
        $judulOptions = [
            'PENGEMBANG WEB (WEB DEVELOPER)',
            'TEKNISI PERPAJAKAN (PAJAK PENGHASILAN ORANG PRIBADI)',
            'System Analyst',
            'Junior Web Programmer',
            'Database Administrator',
            'Analis Senior Hubungan Industrial'
        ];
        
        // Data unit kompetensi per judul untuk dropdown kode unit
        $unitKompetensiJudul = UnitKompetensiJudul::orderBy('judul_sertifikasi')->orderBy('kode_unit')->get();
        
        return view('admin.kriteria-unjuk-kerja', compact('kriteria', 'elemen', 'kriteriaJudul', 'judulOptions', 'unitKompetensiJudul'));
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
            'user_id' => 'required|exists:users,id',
            'nama_lengkap' => 'required|string',
            'nip' => 'required|string',
            'jabatan' => 'required|string',
            'instansi' => 'required|string',
            'no_sertifikat_asesor' => 'required|string',
            'tanggal_sertifikat' => 'required|date',
            'tanggal_expired' => 'required|date',
            'skema_kompetensi' => 'required|array',
        ]);

        $asesor = Asesor::findOrFail($id);
        $asesor->update($request->all());

        return redirect()->route('admin.asesor')
            ->with('success', 'Asesor berhasil diupdate');
    }

    public function deleteAsesor($id)
    {
        $asesor = Asesor::findOrFail($id);
        $asesor->delete();

        return redirect()->route('admin.asesor')
            ->with('success', 'Asesor berhasil dihapus');
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
        ]);

        UnitKompetensiJudul::create($request->all());

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
        ]);

        $unit = UnitKompetensiJudul::findOrFail($id);
        $unit->update($request->all());

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
        $penugasan = Penugasan::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk', 'asesor.user'])->latest()->paginate(10);
        $jadwals = JadwalUji::with('skemaSertifikasi')->get();
        $asesor = Asesor::with('user')->where('status', true)->get();
        return view('admin.penugasan', compact('penugasan', 'jadwals', 'asesor'));
    }

    public function storePenugasan(Request $request)
    {
        $request->validate([
            'jadwal_uji_id' => 'required|exists:jadwal_uji,id',
            'asesor_id' => 'required|exists:asesor,id',
            'jenis_penugasan' => 'required|in:asesor,mapa,ma,mkva',
            'keterangan' => 'nullable|string',
        ]);

        $request->merge(['tanggal_penugasan' => now()]);
        Penugasan::create($request->all());

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
        ]);

        $penugasan = Penugasan::findOrFail($id);
        $penugasan->update($request->all());

        return redirect()->route('admin.penugasan')
            ->with('success', 'Penugasan berhasil diupdate');
    }

    public function deletePenugasan($id)
    {
        $penugasan = Penugasan::findOrFail($id);
        $penugasan->delete();

        return redirect()->route('admin.penugasan')
            ->with('success', 'Penugasan berhasil dihapus');
    }

    public function getPenugasan($id)
    {
        $penugasan = Penugasan::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk', 'asesor.user'])->findOrFail($id);
        return response()->json($penugasan);
    }

    // Pendaftaran
    public function pendaftaran()
    {
        $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi', 'jadwalUji'])
            ->latest()
            ->paginate(10);
        return view('admin.pendaftaran', compact('pendaftaran'));
    }

    public function approvePendaftaran($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update([
            'status' => 'approved',
            'tanggal_verifikasi' => now()
        ]);

        return redirect()->route('admin.pendaftaran')
            ->with('success', 'Pendaftaran berhasil disetujui');
    }

    public function viewPendaftaranDetail($id)
    {
        $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi', 'jadwalUji'])
            ->findOrFail($id);
        
        return view('admin.pendaftaran-detail', compact('pendaftaran'));
    }

    public function rejectPendaftaran(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string'
        ]);

        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update([
            'status' => 'rejected',
            'alasan_penolakan' => $request->alasan_penolakan,
            'tanggal_verifikasi' => now()
        ]);

        return redirect()->route('admin.pendaftaran')
            ->with('success', 'Pendaftaran berhasil ditolak');
    }

    // Laporan
    public function laporan()
    {
        return view('admin.laporan');
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
}
