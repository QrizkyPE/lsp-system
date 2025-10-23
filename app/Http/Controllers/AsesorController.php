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
        
        return view('asesor.unit-kompetensi', compact('units', 'skemas', 'unitsJudul', 'judulOptions'));
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
        
        return view('asesor.elemen', compact('elemen', 'units', 'elemenJudul', 'judulOptions', 'unitKompetensiJudul'));
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
        
        return view('asesor.kriteria-unjuk-kerja', compact('kriteria', 'elemen', 'kriteriaJudul', 'judulOptions', 'unitKompetensiJudul'));
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
        $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi', 'jadwalUji'])
            ->whereIn('status', ['approved', 'in_progress'])
            ->whereNotNull('asesmen_data')
            ->latest()
            ->paginate(10);

        // Calculate summary statistics
        $totalAsesmen = Pendaftaran::whereIn('status', ['approved', 'in_progress'])->whereNotNull('asesmen_data')->count();
        $pendingAsesmen = Pendaftaran::where('status', 'approved')->whereNotNull('asesmen_data')->count();
        $verifiedAsesmen = Pendaftaran::where('status', 'in_progress')->whereNotNull('asesmen_data')->count();
        $rejectedAsesmen = Pendaftaran::where('status', 'rejected')->whereNotNull('asesmen_data')->count();
        

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
        
        // Get existing asesmen data (from mahasiswa)
        $existingAsesmenData = null;
        if ($pendaftaran->asesmen_data) {
            $existingAsesmenData = is_string($pendaftaran->asesmen_data) ? 
                json_decode($pendaftaran->asesmen_data, true) : 
                $pendaftaran->asesmen_data;
        }
        
        // Prepare verification data (from asesor)
        $verificationData = [
            'bukti' => $bukti,
            'signature_data' => $signatureData,
            'verified_at' => now()
        ];
        
        // Merge existing asesmen data with verification data (preserve original data)
        if ($existingAsesmenData) {
            // Preserve original asesmen data and add verification data
            $mergedAsesmenData = $existingAsesmenData;
            $mergedAsesmenData['bukti'] = $verificationData['bukti'];
            $mergedAsesmenData['signature_data'] = $verificationData['signature_data'];
            $mergedAsesmenData['verified_at'] = $verificationData['verified_at'];
        } else {
            $mergedAsesmenData = $verificationData;
        }
        
        // Update pendaftaran status and asesmen data
        $updateData = [
            'status' => 'in_progress',
            'tanggal_asesmen' => now(),
            'asesmen_data' => json_encode($mergedAsesmenData)
        ];
        
        $pendaftaran->update($updateData);

        // Update verification record with signature
        $verification = PendaftaranVerification::where('pendaftaran_id', $id)
            ->where('type', 'asesor_verification')
            ->first();
        
        if ($verification) {
            $updateVerificationData = [
                'verifier_id' => Auth::id(),
                'status' => 'verified',
                'verification_date' => now()
            ];
            
            // Add signature data if provided
            if ($request->has('signature_data')) {
                $updateVerificationData['signature_data'] = $request->signature_data;
            }
            
            $verification->update($updateVerificationData);
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
        ]);

        UnitKompetensiJudul::create($request->all());

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
        ]);

        $unit = UnitKompetensiJudul::findOrFail($id);
        $unit->update($request->all());

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

        $penugasan = \App\Models\Penugasan::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk'])
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
