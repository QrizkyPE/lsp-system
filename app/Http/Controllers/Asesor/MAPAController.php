<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MerencanakanAktivitasDanProsesAsesmen;
use App\Models\SkemaSertifikasi;
use App\Models\UnitKompetensiJudul;
use App\Models\ElemenJudul;
use App\Models\KriteriaUnjukKerjaJudul;

class MAPAController extends Controller
{
    /**
     * Display a listing of MAPA for asesor's assigned skema
     */
    public function index()
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        // Get skema kompetensi dari asesor
        $skemaKompetensiIds = $asesor->skema_kompetensi ?? [];
        
        // Get skema sertifikasi berdasarkan skema kompetensi asesor
        $skemas = SkemaSertifikasi::whereIn('id', $skemaKompetensiIds)
            ->where('status', true)
            ->get();

        // Get existing MAPA
        $mapas = MerencanakanAktivitasDanProsesAsesmen::where('asesor_id', $asesor->id)
            ->with('skemaSertifikasi')
            ->latest()
            ->get();

        return view('asesor.mapa.index', compact('skemas', 'mapas'));
    }

    /**
     * Show the form for creating a new MAPA
     */
    public function create($skemaId)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        // Verify that the skema is in asesor's kompetensi
        $skemaKompetensiIds = $asesor->skema_kompetensi ?? [];
        if (!in_array($skemaId, $skemaKompetensiIds)) {
            return redirect()->route('asesor.mapa.index')
                ->with('error', 'Anda tidak memiliki akses ke skema ini');
        }

        $skema = SkemaSertifikasi::findOrFail($skemaId);
        
        // Get unit kompetensi judul berdasarkan nama skema
        $unitKompetensiJudul = UnitKompetensiJudul::where('judul_sertifikasi', $skema->nama_skema)
            ->where('status', true)
            ->with('asesorKelompok.user')
            ->get();
        
        // Load elemen and kriteria for each unit
        foreach ($unitKompetensiJudul as $unit) {
            $unit->elemenJudul = ElemenJudul::where('judul_sertifikasi', $skema->nama_skema)
                ->where('kode_unit', $unit->kode_unit)
                ->get();
            
            foreach ($unit->elemenJudul as $elemen) {
                $elemen->kriteriaUnjukKerja = KriteriaUnjukKerjaJudul::where('judul_sertifikasi', $skema->nama_skema)
                    ->where('kode_unit', $unit->kode_unit)
                    ->where('kode_elemen', $elemen->kode_elemen)
                    ->get();
            }
            
            // Group asesor by kelompok
            if ($unit->ada_pembagian_kelompok && $unit->asesorKelompok && $unit->asesorKelompok->count() > 0) {
                $unit->kelompokAsesor = $unit->asesorKelompok->groupBy(function($asesor) {
                    return $asesor->pivot->kelompok;
                });
            }
        }

        return view('asesor.mapa.create', compact('skema', 'unitKompetensiJudul'));
    }

    /**
     * Store a newly created MAPA
     */
    public function store(Request $request, $skemaId)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        // Verify that the skema is in asesor's kompetensi
        $skemaKompetensiIds = $asesor->skema_kompetensi ?? [];
        if (!in_array($skemaId, $skemaKompetensiIds)) {
            return redirect()->route('asesor.mapa.index')
                ->with('error', 'Anda tidak memiliki akses ke skema ini');
        }

        $validated = $request->validate([
            'peserta' => 'nullable|string',
            'tujuan_asesmen' => 'nullable|string',
            'lingkungan' => 'nullable|string',
            'peluang_bukti' => 'nullable|string',
            'hubungan_standar_kompetensi' => 'nullable|array',
            'pelaku_asesmen' => 'nullable|string',
            'konfirmasi_orang_relevan_1' => 'nullable|string',
            'konfirmasi_orang_relevan_1_lainnya' => 'nullable|string',
            'tolok_ukur_asesmen' => 'nullable|string',
            'rencana_asesmen' => 'nullable|array',
            'karakteristik_kandidat' => 'nullable|string',
            'kebutuhan_kontekstualisasi_tempat_kerja' => 'nullable|string',
            'saran_paket_pelatihan' => 'nullable|string',
            'penyesuaian_perangkat_asesmen' => 'nullable|string',
            'peluang_kegiatan_terintegrasi' => 'nullable|string',
            'kegiatan_terintegrasi_units' => 'nullable|array',
            'konfirmasi_orang_relevan_2' => 'nullable|string',
            'konfirmasi_orang_relevan_2_lainnya' => 'nullable|string',
            'penyusun_nama' => 'nullable|string',
            'penyusun_tandatangan' => 'nullable|string',
            'penyusun_tanggal' => 'nullable|date',
            'validator_nama' => 'nullable|string',
            'validator_tandatangan' => 'nullable|string',
            'validator_tanggal' => 'nullable|date',
        ]);

        $validated['skema_sertifikasi_id'] = $skemaId;
        $validated['asesor_id'] = $asesor->id;

        MerencanakanAktivitasDanProsesAsesmen::create($validated);

        return redirect()->route('asesor.mapa.index')
            ->with('success', 'MAPA berhasil dibuat');
    }

    /**
     * Display the specified MAPA
     */
    public function show($id)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        $mapa = MerencanakanAktivitasDanProsesAsesmen::where('asesor_id', $asesor->id)
            ->with(['skemaSertifikasi'])
            ->findOrFail($id);

        // Get unit kompetensi judul
        $unitKompetensiJudul = UnitKompetensiJudul::where('judul_sertifikasi', $mapa->skemaSertifikasi->nama_skema)
            ->where('status', true)
            ->get();
        
        // Load elemen and kriteria for each unit
        foreach ($unitKompetensiJudul as $unit) {
            $unit->elemenJudul = ElemenJudul::where('judul_sertifikasi', $mapa->skemaSertifikasi->nama_skema)
                ->where('kode_unit', $unit->kode_unit)
                ->get();
            
            foreach ($unit->elemenJudul as $elemen) {
                $elemen->kriteriaUnjukKerja = KriteriaUnjukKerjaJudul::where('judul_sertifikasi', $mapa->skemaSertifikasi->nama_skema)
                    ->where('kode_unit', $unit->kode_unit)
                    ->where('kode_elemen', $elemen->kode_elemen)
                    ->get();
            }
        }

        return view('asesor.mapa.show', compact('mapa', 'unitKompetensiJudul'));
    }

    /**
     * Show the form for editing the specified MAPA
     */
    public function edit($id)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        $mapa = MerencanakanAktivitasDanProsesAsesmen::where('asesor_id', $asesor->id)
            ->with(['skemaSertifikasi'])
            ->findOrFail($id);

        // Get unit kompetensi judul
        $unitKompetensiJudul = UnitKompetensiJudul::where('judul_sertifikasi', $mapa->skemaSertifikasi->nama_skema)
            ->where('status', true)
            ->get();
        
        // Load elemen and kriteria for each unit
        foreach ($unitKompetensiJudul as $unit) {
            $unit->elemenJudul = ElemenJudul::where('judul_sertifikasi', $mapa->skemaSertifikasi->nama_skema)
                ->where('kode_unit', $unit->kode_unit)
                ->get();
            
            foreach ($unit->elemenJudul as $elemen) {
                $elemen->kriteriaUnjukKerja = KriteriaUnjukKerjaJudul::where('judul_sertifikasi', $mapa->skemaSertifikasi->nama_skema)
                    ->where('kode_unit', $unit->kode_unit)
                    ->where('kode_elemen', $elemen->kode_elemen)
                    ->get();
            }
        }

        return view('asesor.mapa.edit', compact('mapa', 'unitKompetensiJudul'));
    }

    /**
     * Update the specified MAPA
     */
    public function update(Request $request, $id)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        $mapa = MerencanakanAktivitasDanProsesAsesmen::where('asesor_id', $asesor->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'peserta' => 'nullable|string',
            'tujuan_asesmen' => 'nullable|string',
            'lingkungan' => 'nullable|string',
            'peluang_bukti' => 'nullable|string',
            'hubungan_standar_kompetensi' => 'nullable|array',
            'pelaku_asesmen' => 'nullable|string',
            'konfirmasi_orang_relevan_1' => 'nullable|string',
            'konfirmasi_orang_relevan_1_lainnya' => 'nullable|string',
            'tolok_ukur_asesmen' => 'nullable|string',
            'rencana_asesmen' => 'nullable|array',
            'karakteristik_kandidat' => 'nullable|string',
            'kebutuhan_kontekstualisasi_tempat_kerja' => 'nullable|string',
            'saran_paket_pelatihan' => 'nullable|string',
            'penyesuaian_perangkat_asesmen' => 'nullable|string',
            'peluang_kegiatan_terintegrasi' => 'nullable|string',
            'kegiatan_terintegrasi_units' => 'nullable|array',
            'konfirmasi_orang_relevan_2' => 'nullable|string',
            'konfirmasi_orang_relevan_2_lainnya' => 'nullable|string',
            'penyusun_nama' => 'nullable|string',
            'penyusun_tandatangan' => 'nullable|string',
            'penyusun_tanggal' => 'nullable|date',
            'validator_nama' => 'nullable|string',
            'validator_tandatangan' => 'nullable|string',
            'validator_tanggal' => 'nullable|date',
        ]);

        $mapa->update($validated);

        return redirect()->route('asesor.mapa.index')
            ->with('success', 'MAPA berhasil diperbarui');
    }

    /**
     * Remove the specified MAPA
     */
    public function destroy($id)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        $mapa = MerencanakanAktivitasDanProsesAsesmen::where('asesor_id', $asesor->id)
            ->findOrFail($id);

        $mapa->delete();

        return redirect()->route('asesor.mapa.index')
            ->with('success', 'MAPA berhasil dihapus');
    }
}
