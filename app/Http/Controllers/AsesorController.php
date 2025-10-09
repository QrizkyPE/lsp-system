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

    public function penugasan()
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        $penugasan = Penugasan::with(['jadwalUji.skemaSertifikasi'])
            ->where('asesor_id', $asesor->id)
            ->latest()
            ->paginate(10);

        return view('asesor.penugasan', compact('penugasan'));
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
        $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi', 'dokumen'])
            ->where('status', 'approved')
            ->latest()
            ->paginate(10);

        return view('asesor.asesmen', compact('pendaftaran'));
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
    public function unitKompetensiJudul()
    {
        $units = UnitKompetensiJudul::orderBy('judul_sertifikasi')->orderBy('id')->get();
        $judulOptions = [
            'PENGEMBANG WEB (WEB DEVELOPER)',
            'TEKNISI PERPAJAKAN (PAJAK PENGHASILAN ORANG PRIBADI)',
            'System Analyst',
            'Junior Web Programmer',
            'Database Administrator',
            'Analis Senior Hubungan Industrial'
        ];
        return view('asesor.unit-kompetensi-judul', compact('units', 'judulOptions'));
    }

    public function storeUnitKompetensiJudul(Request $request)
    {
        $request->validate([
            'judul_sertifikasi' => 'required|string',
            'kode_unit' => 'required|string',
            'judul_unit' => 'required|string',
            'standar_kompetensi_kerja' => 'required|string',
        ]);

        UnitKompetensiJudul::create($request->all());

        return redirect()->route('asesor.unit-kompetensi-judul')
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

        return redirect()->route('asesor.unit-kompetensi-judul')
            ->with('success', 'Unit kompetensi berhasil diupdate');
    }

    public function deleteUnitKompetensiJudul($id)
    {
        $unit = UnitKompetensiJudul::findOrFail($id);
        $unit->delete();

        return redirect()->route('asesor.unit-kompetensi-judul')
            ->with('success', 'Unit kompetensi berhasil dihapus');
    }
}
