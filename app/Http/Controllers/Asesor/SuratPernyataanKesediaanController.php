<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\SuratPernyataanKesediaan;
use App\Models\UserPersonalization;
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
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        $surat = SuratPernyataanKesediaan::with(['tuk', 'creator'])
            ->where('asesor_id', $asesor->id)
            ->whereIn('status', ['sent', 'signed'])
            ->latest()
            ->get();

        return view('asesor.surat-pernyataan-kesediaan.index', compact('surat'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        $surat = SuratPernyataanKesediaan::with(['tuk', 'creator'])
            ->where('asesor_id', $asesor->id)
            ->findOrFail($id);

        // Get signature from personalization
        $signature = null;
        if ($asesor->user_id) {
            $personalization = UserPersonalization::where('user_id', $asesor->user_id)->first();
            if ($personalization && !empty($personalization->signature_data)) {
                $signature = $personalization->signature_data;
            }
        }

        return view('asesor.surat-pernyataan-kesediaan.show', compact('surat', 'signature'));
    }

    /**
     * Sign surat pernyataan kesediaan
     */
    public function sign(Request $request, $id)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        $surat = SuratPernyataanKesediaan::where('asesor_id', $asesor->id)
            ->findOrFail($id);

        if ($surat->status !== 'sent') {
            return redirect()->route('asesor.surat-pernyataan-kesediaan.index')
                ->with('error', 'Surat tidak dapat ditandatangani');
        }

        $request->validate([
            'signature' => 'required|string',
        ]);

        $surat->update([
            'status' => 'signed',
            'signature_data' => $request->signature,
            'tanggal_tanda_tangan' => now(),
        ]);

        return redirect()->route('asesor.surat-pernyataan-kesediaan.show', $surat->id)
            ->with('success', 'Surat pernyataan kesediaan berhasil ditandatangani');
    }

    /**
     * Generate PDF for surat pernyataan kesediaan
     */
    public function generatePDF($id)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        $surat = SuratPernyataanKesediaan::with(['asesor', 'tuk'])
            ->where('asesor_id', $asesor->id)
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

        $pdf = PDF::loadView('asesor.surat-pernyataan-kesediaan.pdf', $data);
        return $pdf->download('surat-pernyataan-kesediaan-' . $surat->id . '.pdf');
    }
}
