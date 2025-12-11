<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Penugasan;
use App\Models\Dokumen;
use App\Models\Pendaftaran;
use App\Models\UnitKompetensiJudul;
use App\Models\UnitKompetensi;
use App\Models\SkemaSertifikasi;
use App\Models\Elemen;
use App\Models\ElemenJudul;
use App\Models\KriteriaUnjukKerja;
use App\Models\KriteriaUnjukKerjaJudul;
use App\Models\UserPersonalization;
use App\Models\PendaftaranVerification;

class AsesorController extends Controller
{
    public function dashboard()
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        $totalPenugasan = Penugasan::where('asesor_id', $asesor->id)->count();
        $acceptedPenugasan = Penugasan::where('asesor_id', $asesor->id)->where('status', 'accepted')->count();
        $pendingDokumen = Dokumen::where('status', 'submitted')->count();
        $completedAsesmen = Pendaftaran::where('status', 'completed')->count();
        $recentPenugasan = Penugasan::with(['jadwalUji.skemaSertifikasi'])
            ->where('asesor_id', $asesor->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('asesor.dashboard', compact(
            'totalPenugasan',
            'acceptedPenugasan',
            'pendingDokumen',
            'completedAsesmen',
            'recentPenugasan'
        ));
    }

    public function pendaftaran()
    {
        $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi', 'jadwalUji'])
            ->latest()
            ->paginate(10);
        return view('asesor.pendaftaran', compact('pendaftaran'));
    }

    // Unit Kompetensi
    public function unitKompetensi(Request $request)
    {
        $units = UnitKompetensi::with('skemaSertifikasi')->latest()->paginate(10);
        $skemas = SkemaSertifikasi::all();
        
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
            $asesor = \App\Models\Asesor::with('user')->get();
            
            // If empty, try without eager loading
            if ($asesor->isEmpty()) {
                $asesor = \App\Models\Asesor::all();
                if ($asesor->isNotEmpty()) {
                    $asesor->load('user');
                }
            }
            
            // Ensure it's always a collection with numeric keys
            $asesor = $asesor->values();
        } catch (\Exception $e) {
            // If there's any error, get all asesor without relation
            $asesor = \App\Models\Asesor::all()->values();
        }
        
        // Final check - ensure it's always a collection
        if (!$asesor || !is_iterable($asesor)) {
            $asesor = collect([]);
        }
        
        return view('asesor.unit-kompetensi', compact('units', 'skemas', 'unitsJudul', 'judulOptions', 'filterJudul', 'asesor'));
    }

    public function storeUnitKompetensi(Request $request)
    {
        $request->validate([
            'skema_sertifikasi_id' => 'required|exists:skema_sertifikasi,id',
            'kode_unit' => 'required|string|max:255',
            'nama_unit' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kriteria_penilaian' => 'required|string',
        ]);

        UnitKompetensi::create($request->all());

        return redirect()->route('asesor.unit-kompetensi')
            ->with('success', 'Unit kompetensi berhasil ditambahkan');
    }

    public function updateUnitKompetensi(Request $request, $id)
    {
        $request->validate([
            'skema_sertifikasi_id' => 'required|exists:skema_sertifikasi,id',
            'kode_unit' => 'required|string|max:255',
            'nama_unit' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kriteria_penilaian' => 'required|string',
        ]);

        $unit = UnitKompetensi::findOrFail($id);
        $unit->update($request->all());

        return redirect()->route('asesor.unit-kompetensi')
            ->with('success', 'Unit kompetensi berhasil diperbarui');
    }

    public function deleteUnitKompetensi($id)
    {
        $unit = UnitKompetensi::findOrFail($id);
        $unit->delete();

        return redirect()->route('asesor.unit-kompetensi')
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
        
        return view('asesor.elemen', compact('elemen', 'units', 'elemenJudul', 'judulOptions', 'unitKompetensiJudul', 'filterJudul'));
    }

    public function storeElemen(Request $request)
    {
        $request->validate([
            'unit_kompetensi_id' => 'required|exists:unit_kompetensi,id',
            'kode_elemen' => 'required|string|max:255',
            'nama_elemen' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        Elemen::create($request->all());

        return redirect()->route('asesor.elemen')
            ->with('success', 'Elemen berhasil ditambahkan');
    }

    public function updateElemen(Request $request, $id)
    {
        $request->validate([
            'unit_kompetensi_id' => 'required|exists:unit_kompetensi,id',
            'kode_elemen' => 'required|string|max:255',
            'nama_elemen' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        $elemen = Elemen::findOrFail($id);
        $elemen->update($request->all());

        return redirect()->route('asesor.elemen')
            ->with('success', 'Elemen berhasil diperbarui');
    }

    public function deleteElemen($id)
    {
        $elemen = Elemen::findOrFail($id);
        $elemen->delete();

        return redirect()->route('asesor.elemen')
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

        return redirect()->route('asesor.elemen')
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

        return redirect()->route('asesor.elemen')
            ->with('success', 'Elemen judul berhasil diperbarui');
    }

    public function deleteElemenJudul($id)
    {
        $elemen = ElemenJudul::findOrFail($id);
        $elemen->delete();

        return redirect()->route('asesor.elemen')
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
        
        return view('asesor.kriteria-unjuk-kerja', compact('kriteria', 'elemen', 'kriteriaJudul', 'judulOptions', 'unitKompetensiJudul', 'elemenJudul', 'filterJudul'));
    }

    public function storeKriteriaUnjukKerja(Request $request)
    {
        $request->validate([
            'elemen_id' => 'required|exists:elemen,id',
            'nomor_kriteria' => 'required|string|max:255',
            'deskripsi_kriteria' => 'required|string',
            'jenis_bukti' => 'nullable|string|max:255',
            'metode_asesmen' => 'nullable|string|max:255',
            'perangkat_asesmen' => 'nullable|string|max:255',
        ]);

        KriteriaUnjukKerja::create($request->all());

        return redirect()->route('asesor.kriteria-unjuk-kerja')
            ->with('success', 'Kriteria unjuk kerja berhasil ditambahkan');
    }

    public function updateKriteriaUnjukKerja(Request $request, $id)
    {
        $request->validate([
            'elemen_id' => 'required|exists:elemen,id',
            'nomor_kriteria' => 'required|string|max:255',
            'deskripsi_kriteria' => 'required|string',
            'jenis_bukti' => 'nullable|string|max:255',
            'metode_asesmen' => 'nullable|string|max:255',
            'perangkat_asesmen' => 'nullable|string|max:255',
        ]);

        $kriteria = KriteriaUnjukKerja::findOrFail($id);
        $kriteria->update($request->all());

        return redirect()->route('asesor.kriteria-unjuk-kerja')
            ->with('success', 'Kriteria unjuk kerja berhasil diperbarui');
    }

    public function deleteKriteriaUnjukKerja($id)
    {
        $kriteria = KriteriaUnjukKerja::findOrFail($id);
        $kriteria->delete();

        return redirect()->route('asesor.kriteria-unjuk-kerja')
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

        return redirect()->route('asesor.kriteria-unjuk-kerja')
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

        return redirect()->route('asesor.kriteria-unjuk-kerja')
            ->with('success', 'Kriteria judul berhasil diperbarui');
    }

    public function deleteKriteriaJudul($id)
    {
        $kriteria = KriteriaUnjukKerjaJudul::findOrFail($id);
        $kriteria->delete();

        return redirect()->route('asesor.kriteria-unjuk-kerja')
            ->with('success', 'Kriteria judul berhasil dihapus');
    }

    public function penugasan()
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        $penugasan = \App\Models\Penugasan::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk'])
            ->where('asesor_id', $asesor->id)
            ->latest()
            ->paginate(10);

        // Check soal upload status for accepted penugasan
        $penugasan->getCollection()->transform(function ($p) use ($asesor) {
            if ($p->status === 'accepted' && $p->jadwalUji) {
                $p->has_soal = \App\Models\SoalUpload::where('jadwal_uji_id', $p->jadwalUji->id)
                    ->where('asesor_id', $asesor->id)
                    ->exists();
            } else {
                $p->has_soal = null;
            }
            return $p;
        });

        // Calculate summary statistics
        $totalPenugasan = \App\Models\Penugasan::where('asesor_id', $asesor->id)->count();
        $pendingPenugasan = \App\Models\Penugasan::where('asesor_id', $asesor->id)->where('status', 'pending')->count();
        $acceptedPenugasan = \App\Models\Penugasan::where('asesor_id', $asesor->id)->where('status', 'accepted')->count();
        $rejectedPenugasan = \App\Models\Penugasan::where('asesor_id', $asesor->id)->where('status', 'rejected')->count();

        return view('asesor.penugasan', compact(
            'penugasan', 
            'totalPenugasan', 
            'pendingPenugasan', 
            'acceptedPenugasan', 
            'rejectedPenugasan'
        ));
    }

    public function dokumen()
    {
        $dokumen = Dokumen::with(['pendaftaran.user', 'pendaftaran.skemaSertifikasi'])
            ->where('status', 'submitted')
            ->latest()
            ->paginate(10);

        return view('asesor.dokumen', compact('dokumen'));
    }

    public function approveDokumen($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        $dokumen->update([
            'status' => 'approved',
            'tanggal_approve' => now()
        ]);

        return redirect()->route('asesor.dokumen')
            ->with('success', 'Dokumen berhasil disetujui');
    }

    public function rejectDokumen(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string'
        ]);

        $dokumen = Dokumen::findOrFail($id);
        $dokumen->update([
            'status' => 'rejected',
            'catatan' => $request->catatan
        ]);

        return redirect()->route('asesor.dokumen')
            ->with('success', 'Dokumen berhasil ditolak');
    }

    public function asesmen()
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        // Get pendaftaran IDs that are assigned to this asesor through penugasan
        $assignedPendaftaranIds = \App\Models\Penugasan::where('asesor_id', $asesor->id)
            ->whereIn('status', ['assigned', 'accepted', 'completed'])
            ->with('pendaftaran')
            ->get()
            ->pluck('pendaftaran')
            ->flatten()
            ->pluck('id')
            ->unique()
            ->filter()
            ->toArray();

        // If no pendaftaran assigned, return empty result
        if (empty($assignedPendaftaranIds)) {
            $pendaftaran = Pendaftaran::whereRaw('1 = 0')->paginate(10);
            $totalAsesmen = 0;
            $pendingAsesmen = 0;
            $verifiedAsesmen = 0;
            $rejectedAsesmen = 0;
        } else {
            // Filter pendaftaran based on assignment
            // Include approved, in_progress, persetujuan_submitted, and persetujuan_confirmed
            $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi', 'jadwalUji', 'verifications'])
                ->whereIn('status', ['approved', 'in_progress', 'persetujuan_submitted', 'persetujuan_confirmed'])
                ->whereNotNull('asesmen_data')
                ->whereIn('id', $assignedPendaftaranIds)
                ->latest()
                ->paginate(10);

            // Calculate summary statistics (only for assigned pendaftaran)
            $totalAsesmen = Pendaftaran::whereIn('status', ['approved', 'in_progress', 'persetujuan_submitted', 'persetujuan_confirmed'])
                ->whereNotNull('asesmen_data')
                ->whereIn('id', $assignedPendaftaranIds)
                ->count();
            $pendingAsesmen = Pendaftaran::where('status', 'approved')
                ->whereNotNull('asesmen_data')
                ->whereIn('id', $assignedPendaftaranIds)
                ->count();
            $verifiedAsesmen = Pendaftaran::whereIn('status', ['in_progress', 'persetujuan_submitted', 'persetujuan_confirmed'])
                ->whereNotNull('asesmen_data')
                ->whereIn('id', $assignedPendaftaranIds)
                ->count();
            $rejectedAsesmen = Pendaftaran::where('status', 'rejected')
                ->whereNotNull('asesmen_data')
                ->whereIn('id', $assignedPendaftaranIds)
                ->count();
        }
        

        // Ambil data elemen dan kriteria untuk setiap pendaftaran
        foreach ($pendaftaran as $p) {
            if ($p->sertifikasi_data) {
                $sertifikasiData = is_string($p->sertifikasi_data) ? json_decode($p->sertifikasi_data, true) : $p->sertifikasi_data;
                if (isset($sertifikasiData['judul'])) {
                    $judul = $sertifikasiData['judul'];
                    
                    // Ambil unit kompetensi per judul
                    $p->unitKompetensiJudul = UnitKompetensiJudul::where('judul_sertifikasi', $judul)->get();
                    
                    // Ambil elemen per judul
                    $p->elemenJudul = ElemenJudul::where('judul_sertifikasi', $judul)->get();
                    
                    // Ambil kriteria per judul
                    $p->kriteriaJudul = KriteriaUnjukKerjaJudul::where('judul_sertifikasi', $judul)->get();
                }
            }
        }

        return view('asesor.asesmen', compact(
            'pendaftaran', 
            'totalAsesmen', 
            'pendingAsesmen', 
            'verifiedAsesmen', 
            'rejectedAsesmen'
        ));
    }

    /**
     * Show detail pendaftaran with asesmen mandiri for asesor
     */
    public function detailPendaftaran($id)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        // Get pendaftaran IDs that are assigned to this asesor through penugasan
        $assignedPendaftaranIds = \App\Models\Penugasan::where('asesor_id', $asesor->id)
            ->whereIn('status', ['assigned', 'accepted', 'completed'])
            ->with('pendaftaran')
            ->get()
            ->pluck('pendaftaran')
            ->flatten()
            ->pluck('id')
            ->unique()
            ->filter()
            ->toArray();

        // Get pendaftaran with all related data
        $pendaftaran = Pendaftaran::with([
            'user', 
            'skemaSertifikasi', 
            'jadwalUji.tuk',
            'verifications' => function($query) {
                $query->with(['verifier.asesor']);
            }
        ])
        ->where('id', $id)
        ->whereIn('id', $assignedPendaftaranIds)
        ->firstOrFail();

        // Decode JSON data safely
        $profilData = null;
        $sertifikasiData = null;
        $asesmenData = null;

        if ($pendaftaran->profil_data) {
            if (is_string($pendaftaran->profil_data)) {
                $decoded = json_decode($pendaftaran->profil_data, true);
                $profilData = (json_last_error() === JSON_ERROR_NONE) ? $decoded : null;
            } else {
                $profilData = $pendaftaran->profil_data;
            }
        }

        if ($pendaftaran->sertifikasi_data) {
            if (is_string($pendaftaran->sertifikasi_data)) {
                $decoded = json_decode($pendaftaran->sertifikasi_data, true);
                $sertifikasiData = (json_last_error() === JSON_ERROR_NONE) ? $decoded : null;
            } else {
                $sertifikasiData = $pendaftaran->sertifikasi_data;
            }
        }

        if ($pendaftaran->asesmen_data) {
            if (is_string($pendaftaran->asesmen_data)) {
                $decoded = json_decode($pendaftaran->asesmen_data, true);
                $asesmenData = (json_last_error() === JSON_ERROR_NONE) ? $decoded : null;
            } else {
                $asesmenData = $pendaftaran->asesmen_data;
            }
        }

        // Get unit kompetensi, elemen, dan kriteria if sertifikasi data exists
        // Similar structure to asesmen method
        $unitKompetensiJudul = collect();
        $elemenJudul = collect();
        $kriteriaJudul = collect();

        if ($sertifikasiData && isset($sertifikasiData['judul'])) {
            $judul = $sertifikasiData['judul'];
            
            // Get judul sertifikasi name from first unit or from skema
            $judulSertifikasi = null;
            if (is_array($judul) && !empty($judul)) {
                $firstUnit = \App\Models\UnitKompetensiJudul::whereIn('id', $judul)->first();
                if ($firstUnit) {
                    $judulSertifikasi = $firstUnit->judul_sertifikasi;
                }
            } elseif (is_string($judul)) {
                $judulSertifikasi = $judul;
            }
            
            if ($judulSertifikasi) {
                // Ambil unit kompetensi per judul (semua, termasuk yang tidak aktif)
                // Menggunakan urutan yang sama seperti saat data disimpan
                $unitKompetensiJudul = \App\Models\UnitKompetensiJudul::where('judul_sertifikasi', $judulSertifikasi)
                    ->orderBy('id')
                    ->get();

                // Ambil elemen per judul
                $elemenJudul = \App\Models\ElemenJudul::where('judul_sertifikasi', $judulSertifikasi)
                    ->orderBy('kode_unit')
                    ->orderBy('kode_elemen')
                    ->get();

                // Ambil kriteria per judul
                $kriteriaJudul = \App\Models\KriteriaUnjukKerjaJudul::where('judul_sertifikasi', $judulSertifikasi)
                    ->orderBy('kode_unit')
                    ->orderBy('kode_elemen')
                    ->orderBy('nomor_kriteria')
                    ->get();
            }
        }

        return view('asesor.detail-pendaftaran', compact(
            'pendaftaran',
            'profilData',
            'sertifikasiData', 
            'asesmenData',
            'unitKompetensiJudul',
            'elemenJudul',
            'kriteriaJudul'
        ));
    }

    public function verifyAsesmen(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        
        // Get signature and bukti data from request
        $signatureData = $request->input('signature_data');
        $bukti = $request->input('bukti', []);
        
        // Validate bukti
        if (empty($bukti)) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan pilih minimal satu bukti yang dikumpulkan'
            ], 400);
        }
        
        // Get existing asesmen data (from mahasiswa) - preserve all original data
        $existingAsesmenData = null;
        if ($pendaftaran->asesmen_data) {
            $existingAsesmenData = is_string($pendaftaran->asesmen_data) ? 
                json_decode($pendaftaran->asesmen_data, true) : 
                $pendaftaran->asesmen_data;
        }
        
        // Prepare verification data from asesor (only bukti, keep mahasiswa signature separate)
        $verificationData = [
            'bukti' => $bukti,
            'verified_at' => now()->toDateTimeString()
        ];
        
        // Merge existing asesmen data with verification data (preserve ALL original data)
        if ($existingAsesmenData) {
            // Preserve original asesmen data completely, only update bukti and verified_at
            $mergedAsesmenData = $existingAsesmenData;
            $mergedAsesmenData['bukti'] = $verificationData['bukti'];
            $mergedAsesmenData['verified_at'] = $verificationData['verified_at'];
            // Keep original signature_data from mahasiswa if exists
            // Don't overwrite mahasiswa signature with asesor signature
        } else {
            $mergedAsesmenData = $verificationData;
        }
        
        // Update pendaftaran status and asesmen data (preserve mahasiswa data)
        $updateData = [
            'status' => 'in_progress',
            'tanggal_asesmen' => now(),
            'asesmen_data' => json_encode($mergedAsesmenData)
        ];
        
        $pendaftaran->update($updateData);

        // Update verification record with asesor signature (separate from asesmen_data)
        $verification = PendaftaranVerification::where('pendaftaran_id', $id)
            ->where('type', 'asesor_verification')
            ->first();
        
        if ($verification) {
            $updateVerificationData = [
                'verifier_id' => Auth::id(),
                'status' => 'approved',
                'verification_date' => now()
            ];
            
            // Add signature data if provided (this is asesor signature, stored in verification record)
            if ($request->has('signature_data') && $request->signature_data) {
                $updateVerificationData['signature_data'] = $request->signature_data;
            }
            
            $verification->update($updateVerificationData);
        } else {
            // If verification record doesn't exist, create it
            PendaftaranVerification::create([
                'pendaftaran_id' => $id,
                'verifier_id' => Auth::id(),
                'type' => 'asesor_verification',
                'status' => 'approved',
                'verification_date' => now(),
                'signature_data' => $request->has('signature_data') && $request->signature_data ? $request->signature_data : null
            ]);
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function rejectAsesmen($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        
        // Update pendaftaran status
        $pendaftaran->update([
            'status' => 'rejected',
            'tanggal_asesmen' => now()
        ]);

        // Update verification record
        $verification = PendaftaranVerification::where('pendaftaran_id', $id)
            ->where('type', 'asesor_verification')
            ->first();
        
        if ($verification) {
            $verification->update([
                'verifier_id' => Auth::id(),
                'status' => 'rejected',
                'verification_date' => now()
            ]);
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function submitAsesmen(Request $request, $id)
    {
        $request->validate([
            'hasil_asesmen' => 'required|in:kompeten,belum_kompeten',
            'catatan_asesmen' => 'required|string'
        ]);

        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update([
            'status' => 'completed',
            'hasil_asesmen' => $request->hasil_asesmen,
            'catatan_asesmen' => $request->catatan_asesmen,
            'tanggal_selesai' => now()
        ]);

        return redirect()->route('asesor.asesmen')
            ->with('success', 'Hasil asesmen berhasil disimpan');
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
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? (bool)$request->status : true;

        UnitKompetensiJudul::create($data);

        return redirect()->route('asesor.unit-kompetensi')
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
            \Illuminate\Support\Facades\DB::table('unit_kompetensi_judul_asesor_kelompok')
                ->where('unit_kompetensi_judul_id', $unit->id)
                ->delete();
        } else {
            // Hapus data kelompok lama
            \Illuminate\Support\Facades\DB::table('unit_kompetensi_judul_asesor_kelompok')
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
                                \Illuminate\Support\Facades\DB::table('unit_kompetensi_judul_asesor_kelompok')->insert([
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

        return redirect()->route('asesor.unit-kompetensi')
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

            return redirect()->route('asesor.unit-kompetensi')
                ->with('success', 'Unit kompetensi dan data terkait berhasil dihapus');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus unit kompetensi: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('asesor.unit-kompetensi')
                ->with('error', 'Gagal menghapus unit kompetensi');
        }
    }

    public function personalization()
    {
        $personalization = UserPersonalization::where('user_id', Auth::id())->first();
        return view('asesor.personalization', compact('personalization'));
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

            return redirect()->route('asesor.personalization')
                ->with('success', 'Tanda tangan berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->route('asesor.personalization')
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

    public function getPenugasan($id)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $penugasan = \App\Models\Penugasan::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk', 'pendaftaran.user', 'pendaftaran.skemaSertifikasi'])
            ->where('asesor_id', $asesor->id)
            ->findOrFail($id);
        
        return response()->json($penugasan);
    }

    public function updatePenugasanStatus(Request $request, $id)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'status' => 'required|in:accepted,rejected'
        ]);

        $penugasan = \App\Models\Penugasan::where('asesor_id', $asesor->id)->findOrFail($id);
        $penugasan->update([
            'status' => $request->status,
            'tanggal_respon' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status penugasan berhasil diupdate'
        ]);
    }

}
