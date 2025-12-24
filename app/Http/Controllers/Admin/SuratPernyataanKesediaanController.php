<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratPernyataanKesediaan;
use App\Models\Asesor;
use App\Models\Tuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class SuratPernyataanKesediaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surat = SuratPernyataanKesediaan::with(['asesor', 'tuk', 'creator'])
            ->latest()
            ->get();

        return view('admin.surat-pernyataan-kesediaan.index', compact('surat'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $asesorList = Asesor::with('user')->get();
        $tukList = Tuk::where('status', true)->get();

        return view('admin.surat-pernyataan-kesediaan.create', compact('asesorList', 'tukList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_dokumen' => 'nullable|string|max:255',
            'edisi_revisi' => 'nullable|string|max:255',
            'tanggal_berlaku' => 'nullable|date',
            'asesor_id' => 'required|exists:asesor,id',
            'alamat' => 'nullable|string',
            'no_met_sertifikat' => 'nullable|string|max:255',
            'tuk_id' => 'nullable|exists:tuk,id',
        ]);

        $asesor = Asesor::findOrFail($request->asesor_id);

        $data = $request->all();
        $data['created_by'] = Auth::id();
        $data['alamat'] = $request->alamat ?? ($asesor->instansi ?? '');
        $data['no_met_sertifikat'] = $request->no_met_sertifikat ?? $asesor->no_reg;
        $data['status'] = 'draft';

        SuratPernyataanKesediaan::create($data);

        return redirect()->route('admin.surat-pernyataan-kesediaan.index')
            ->with('success', 'Surat pernyataan kesediaan berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $surat = SuratPernyataanKesediaan::with(['asesor', 'tuk', 'creator'])
            ->findOrFail($id);

        return view('admin.surat-pernyataan-kesediaan.show', compact('surat'));
    }

    /**
     * Send surat to asesor
     */
    public function send($id)
    {
        $surat = SuratPernyataanKesediaan::findOrFail($id);
        
        if ($surat->status !== 'draft') {
            return redirect()->route('admin.surat-pernyataan-kesediaan.index')
                ->with('error', 'Surat sudah dikirim sebelumnya');
        }

        $surat->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return redirect()->route('admin.surat-pernyataan-kesediaan.index')
            ->with('success', 'Surat berhasil dikirim ke asesor');
    }

    /**
     * Generate PDF for surat pernyataan kesediaan
     */
    public function generatePDF($id)
    {
        $surat = SuratPernyataanKesediaan::with(['asesor', 'tuk'])
            ->findOrFail($id);

        // Format tanggal untuk PDF
        $tanggalBerlaku = $surat->tanggal_berlaku 
            ? \Carbon\Carbon::parse($surat->tanggal_berlaku)->locale('id')->isoFormat('D MMMM YYYY')
            : '';
        
        $tanggalTandaTangan = $surat->tanggal_tanda_tangan 
            ? \Carbon\Carbon::parse($surat->tanggal_tanda_tangan)->locale('id')->isoFormat('D MMMM YYYY')
            : '';
        
        $tanggalSekarang = \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY');

        $data = [
            'surat' => $surat,
            'tanggalBerlaku' => $tanggalBerlaku,
            'tanggalTandaTangan' => $tanggalTandaTangan,
            'tanggalSekarang' => $tanggalSekarang,
        ];

        $pdf = PDF::loadView('admin.surat-pernyataan-kesediaan.pdf', $data);
        return $pdf->download('surat-pernyataan-kesediaan-' . $surat->id . '.pdf');
    }
}
