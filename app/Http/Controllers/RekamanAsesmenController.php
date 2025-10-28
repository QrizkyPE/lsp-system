<?php

namespace App\Http\Controllers;

use App\Models\RekamanAsesmenKompetensi;
use App\Models\Pendaftaran;
use App\Models\UnitKompetensiJudul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekamanAsesmenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rekamanAsesmen = RekamanAsesmenKompetensi::with(['asesor', 'pendaftaran.skemaSertifikasi'])
            ->where('asesor_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('asesor.rekaman-asesmen.index', compact('rekamanAsesmen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi'])
            ->whereIn('status', ['approved', 'in_progress', 'persetujuan_submitted'])
            ->whereNotNull('persetujuan_data')
            ->get();

        return view('asesor.rekaman-asesmen.create', compact('pendaftaran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran,id',
            'tuk' => 'required|in:sewaktu,tempat_kerja,mandiri',
            'tanggal_mulai' => 'required|date',
            'waktu_mulai' => 'required',
            'tanggal_selesai' => 'required|date',
            'waktu_selesai' => 'required',
            'unit_kompetensi_data' => 'nullable|array',
            'rekomendasi_hasil' => 'nullable|in:kompeten,belum_kompeten',
            'tindak_lanjut' => 'nullable|string',
            'komentar_observasi' => 'nullable|string',
            'no_reg_asesor' => 'nullable|string',
            'asesor_signature' => 'nullable|string',
            'tanggal_asesor' => 'nullable|date'
        ]);

        $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi'])->findOrFail($request->pendaftaran_id);

        $rekamanAsesmen = RekamanAsesmenKompetensi::create([
            'judul' => $pendaftaran->skemaSertifikasi->nama_skema,
            'nomor_skema' => $pendaftaran->skemaSertifikasi->nomor_skema,
            'tuk' => $request->tuk,
            'nama_asesor' => Auth::user()->name,
            'nama_asesi' => $pendaftaran->user->name,
            'tanggal_mulai' => $request->tanggal_mulai,
            'waktu_mulai' => $request->waktu_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'waktu_selesai' => $request->waktu_selesai,
            'unit_kompetensi_data' => $request->unit_kompetensi_data,
            'rekomendasi_hasil' => $request->rekomendasi_hasil,
            'tindak_lanjut' => $request->tindak_lanjut,
            'komentar_observasi' => $request->komentar_observasi,
            'no_reg_asesor' => $request->no_reg_asesor,
            'asesor_signature' => $request->asesor_signature,
            'tanggal_asesor' => $request->tanggal_asesor,
            'asesor_id' => Auth::id(),
            'pendaftaran_id' => $request->pendaftaran_id
        ]);

        return redirect()->route('asesor.rekaman-asesmen.index')
            ->with('success', 'Rekaman asesmen kompetensi berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $rekamanAsesmen = RekamanAsesmenKompetensi::with(['asesor', 'pendaftaran.skemaSertifikasi'])
            ->where('asesor_id', Auth::id())
            ->findOrFail($id);

        return view('asesor.rekaman-asesmen.show', compact('rekamanAsesmen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $rekamanAsesmen = RekamanAsesmenKompetensi::where('asesor_id', Auth::id())->findOrFail($id);
        $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi'])
            ->whereIn('status', ['approved', 'in_progress', 'persetujuan_submitted'])
            ->whereNotNull('persetujuan_data')
            ->get();

        return view('asesor.rekaman-asesmen.edit', compact('rekamanAsesmen', 'pendaftaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rekamanAsesmen = RekamanAsesmenKompetensi::where('asesor_id', Auth::id())->findOrFail($id);

        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran,id',
            'tuk' => 'required|in:sewaktu,tempat_kerja,mandiri',
            'tanggal_mulai' => 'required|date',
            'waktu_mulai' => 'required',
            'tanggal_selesai' => 'required|date',
            'waktu_selesai' => 'required',
            'unit_kompetensi_data' => 'nullable|array',
            'rekomendasi_hasil' => 'nullable|in:kompeten,belum_kompeten',
            'tindak_lanjut' => 'nullable|string',
            'komentar_observasi' => 'nullable|string',
            'no_reg_asesor' => 'nullable|string',
            'asesor_signature' => 'nullable|string',
            'tanggal_asesor' => 'nullable|date'
        ]);

        $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi'])->findOrFail($request->pendaftaran_id);

        $rekamanAsesmen->update([
            'judul' => $pendaftaran->skemaSertifikasi->nama_skema,
            'nomor_skema' => $pendaftaran->skemaSertifikasi->nomor_skema,
            'tuk' => $request->tuk,
            'nama_asesor' => Auth::user()->name,
            'nama_asesi' => $pendaftaran->user->name,
            'tanggal_mulai' => $request->tanggal_mulai,
            'waktu_mulai' => $request->waktu_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'waktu_selesai' => $request->waktu_selesai,
            'unit_kompetensi_data' => $request->unit_kompetensi_data,
            'rekomendasi_hasil' => $request->rekomendasi_hasil,
            'tindak_lanjut' => $request->tindak_lanjut,
            'komentar_observasi' => $request->komentar_observasi,
            'no_reg_asesor' => $request->no_reg_asesor,
            'asesor_signature' => $request->asesor_signature,
            'tanggal_asesor' => $request->tanggal_asesor,
            'pendaftaran_id' => $request->pendaftaran_id
        ]);

        return redirect()->route('asesor.rekaman-asesmen.index')
            ->with('success', 'Rekaman asesmen kompetensi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $rekamanAsesmen = RekamanAsesmenKompetensi::where('asesor_id', Auth::id())->findOrFail($id);
        $rekamanAsesmen->delete();

        return redirect()->route('asesor.rekaman-asesmen.index')
            ->with('success', 'Rekaman asesmen kompetensi berhasil dihapus');
    }

    /**
     * Get unit kompetensi data for selected pendaftaran
     */
    public function getUnitKompetensi($pendaftaranId)
    {
        $pendaftaran = Pendaftaran::with(['skemaSertifikasi'])->findOrFail($pendaftaranId);
        
        $unitKompetensi = UnitKompetensiJudul::where('judul_sertifikasi', $pendaftaran->skemaSertifikasi->nama_skema)
            ->get();

        return response()->json($unitKompetensi);
    }
}
