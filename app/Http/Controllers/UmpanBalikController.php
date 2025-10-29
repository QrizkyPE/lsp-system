<?php

namespace App\Http\Controllers;

use App\Models\UmpanBalikAsesmen;
use App\Models\RekamanAsesmenKompetensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UmpanBalikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $umpanBalik = UmpanBalikAsesmen::with(['rekamanAsesmen'])
            ->where('mahasiswa_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('mahasiswa.umpan-balik.index', compact('umpanBalik'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rekamanAsesmen = RekamanAsesmenKompetensi::with(['asesor', 'pendaftaran.skemaSertifikasi'])
            ->whereHas('pendaftaran', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->whereNotNull('mahasiswa_signature')
            ->get();

        return view('mahasiswa.umpan-balik.create', compact('rekamanAsesmen'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rekaman_asesmen_id' => 'required|exists:rekaman_asesmen_kompetensi,id',
            'tuk' => 'required|in:sewaktu,tempat_kerja,mandiri',
            'umpan_balik_data' => 'required|array',
            'catatan_lainnya' => 'nullable|string'
        ]);

        $rekamanAsesmen = RekamanAsesmenKompetensi::with(['asesor', 'pendaftaran.skemaSertifikasi'])
            ->whereHas('pendaftaran', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->findOrFail($request->rekaman_asesmen_id);

        $umpanBalik = UmpanBalikAsesmen::create([
            'judul' => $rekamanAsesmen->judul,
            'nomor_skema' => $rekamanAsesmen->nomor_skema,
            'tuk' => $request->tuk,
            'nama_asesor' => $rekamanAsesmen->nama_asesor,
            'nama_asesi' => $rekamanAsesmen->nama_asesi,
            'tanggal_mulai' => $rekamanAsesmen->tanggal_mulai,
            'waktu_mulai' => $rekamanAsesmen->waktu_mulai,
            'tanggal_selesai' => $rekamanAsesmen->tanggal_selesai,
            'waktu_selesai' => $rekamanAsesmen->waktu_selesai,
            'umpan_balik_data' => $request->umpan_balik_data,
            'catatan_lainnya' => $request->catatan_lainnya,
            'mahasiswa_id' => Auth::id(),
            'rekaman_asesmen_id' => $request->rekaman_asesmen_id
        ]);

        return redirect()->route('mahasiswa.umpan-balik.index')
            ->with('success', 'Umpan balik asesmen berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $umpanBalik = UmpanBalikAsesmen::with(['rekamanAsesmen'])
            ->where('mahasiswa_id', Auth::id())
            ->findOrFail($id);

        return view('mahasiswa.umpan-balik.show', compact('umpanBalik'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $umpanBalik = UmpanBalikAsesmen::where('mahasiswa_id', Auth::id())->findOrFail($id);
        $rekamanAsesmen = RekamanAsesmenKompetensi::with(['asesor', 'pendaftaran.skemaSertifikasi'])
            ->whereHas('pendaftaran', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->whereNotNull('mahasiswa_signature')
            ->get();

        return view('mahasiswa.umpan-balik.edit', compact('umpanBalik', 'rekamanAsesmen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $umpanBalik = UmpanBalikAsesmen::where('mahasiswa_id', Auth::id())->findOrFail($id);

        $request->validate([
            'rekaman_asesmen_id' => 'required|exists:rekaman_asesmen_kompetensi,id',
            'tuk' => 'required|in:sewaktu,tempat_kerja,mandiri',
            'umpan_balik_data' => 'required|array',
            'catatan_lainnya' => 'nullable|string'
        ]);

        $rekamanAsesmen = RekamanAsesmenKompetensi::with(['asesor', 'pendaftaran.skemaSertifikasi'])
            ->whereHas('pendaftaran', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->findOrFail($request->rekaman_asesmen_id);

        $umpanBalik->update([
            'judul' => $rekamanAsesmen->judul,
            'nomor_skema' => $rekamanAsesmen->nomor_skema,
            'tuk' => $request->tuk,
            'nama_asesor' => $rekamanAsesmen->nama_asesor,
            'nama_asesi' => $rekamanAsesmen->nama_asesi,
            'tanggal_mulai' => $rekamanAsesmen->tanggal_mulai,
            'waktu_mulai' => $rekamanAsesmen->waktu_mulai,
            'tanggal_selesai' => $rekamanAsesmen->tanggal_selesai,
            'waktu_selesai' => $rekamanAsesmen->waktu_selesai,
            'umpan_balik_data' => $request->umpan_balik_data,
            'catatan_lainnya' => $request->catatan_lainnya,
            'rekaman_asesmen_id' => $request->rekaman_asesmen_id
        ]);

        return redirect()->route('mahasiswa.umpan-balik.index')
            ->with('success', 'Umpan balik asesmen berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $umpanBalik = UmpanBalikAsesmen::where('mahasiswa_id', Auth::id())->findOrFail($id);
        $umpanBalik->delete();

        return redirect()->route('mahasiswa.umpan-balik.index')
            ->with('success', 'Umpan balik asesmen berhasil dihapus');
    }
}
