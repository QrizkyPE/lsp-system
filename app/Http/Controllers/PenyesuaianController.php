<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenyesuaianChecklist;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Auth;

class PenyesuaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penyesuaianChecklists = PenyesuaianChecklist::with(['asesor', 'pendaftaran.skemaSertifikasi'])
            ->where('asesor_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('asesor.penyesuaian.index', compact('penyesuaianChecklists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi'])
            ->whereIn('status', ['approved', 'in_progress', 'persetujuan_submitted', 'persetujuan_confirmed', 'completed'])
            ->whereNotNull('persetujuan_data') // Sudah melakukan persetujuan asesmen
            ->get();

        return view('asesor.penyesuaian.create', compact('pendaftaran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran,id',
            'tuk' => 'required|in:sewaktu,tempat_kerja,mandiri',
            'tanggal' => 'required|date',
            'potensi_asesi' => 'nullable|array',
            'modifikasi_data' => 'nullable|array',
            'asesor_signature' => 'nullable|string',
            'tanggal_asesor' => 'nullable|date'
        ]);

        $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi'])->findOrFail($request->pendaftaran_id);

        $penyesuaianChecklist = PenyesuaianChecklist::create([
            'judul' => $pendaftaran->skemaSertifikasi->nama_skema,
            'nomor_skema' => $pendaftaran->skemaSertifikasi->nomor_skema,
            'tuk' => $request->tuk,
            'nama_asesor' => Auth::user()->name,
            'nama_asesi' => $pendaftaran->user->name,
            'tanggal' => $request->tanggal,
            'potensi_asesi' => $request->potensi_asesi,
            'modifikasi_data' => $request->modifikasi_data,
            'asesor_signature' => $request->asesor_signature,
            'tanggal_asesor' => $request->tanggal_asesor,
            'asesor_id' => Auth::id(),
            'pendaftaran_id' => $request->pendaftaran_id
        ]);

        return redirect()->route('asesor.penyesuaian.index')
            ->with('success', 'Ceklis penyesuaian berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $penyesuaianChecklist = PenyesuaianChecklist::with(['asesor', 'pendaftaran.skemaSertifikasi'])
            ->where('asesor_id', Auth::id())
            ->findOrFail($id);

        return view('asesor.penyesuaian.show', compact('penyesuaianChecklist'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $penyesuaianChecklist = PenyesuaianChecklist::where('asesor_id', Auth::id())->findOrFail($id);
        $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi'])
            ->whereIn('status', ['approved', 'in_progress', 'persetujuan_submitted', 'persetujuan_confirmed', 'completed'])
            ->whereNotNull('persetujuan_data') // Sudah melakukan persetujuan asesmen
            ->get();

        return view('asesor.penyesuaian.edit', compact('penyesuaianChecklist', 'pendaftaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $penyesuaianChecklist = PenyesuaianChecklist::where('asesor_id', Auth::id())->findOrFail($id);

        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran,id',
            'tuk' => 'required|in:sewaktu,tempat_kerja,mandiri',
            'tanggal' => 'required|date',
            'potensi_asesi' => 'nullable|array',
            'modifikasi_data' => 'nullable|array',
            'asesor_signature' => 'nullable|string',
            'tanggal_asesor' => 'nullable|date'
        ]);

        $pendaftaran = Pendaftaran::with(['user', 'skemaSertifikasi'])->findOrFail($request->pendaftaran_id);

        $penyesuaianChecklist->update([
            'judul' => $pendaftaran->skemaSertifikasi->nama_skema,
            'nomor_skema' => $pendaftaran->skemaSertifikasi->nomor_skema,
            'tuk' => $request->tuk,
            'nama_asesor' => Auth::user()->name,
            'nama_asesi' => $pendaftaran->user->name,
            'tanggal' => $request->tanggal,
            'potensi_asesi' => $request->potensi_asesi,
            'modifikasi_data' => $request->modifikasi_data,
            'asesor_signature' => $request->asesor_signature,
            'tanggal_asesor' => $request->tanggal_asesor,
            'pendaftaran_id' => $request->pendaftaran_id
        ]);

        return redirect()->route('asesor.penyesuaian.index')
            ->with('success', 'Ceklis penyesuaian berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $penyesuaianChecklist = PenyesuaianChecklist::where('asesor_id', Auth::id())->findOrFail($id);
        $penyesuaianChecklist->delete();

        return redirect()->route('asesor.penyesuaian.index')
            ->with('success', 'Ceklis penyesuaian berhasil dihapus');
    }
}
