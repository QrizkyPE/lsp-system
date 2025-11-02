<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\SoalUpload;
use App\Models\JadwalUji;
use App\Models\Penugasan;

class SoalUploadController extends Controller
{
    public function index()
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        // Get all jadwals where asesor is assigned (include assigned, accepted, and completed statuses)
        $penugasan = Penugasan::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk', 'jadwalUji.soalUploads'])
            ->where('asesor_id', $asesor->id)
            ->whereIn('status', ['assigned', 'accepted', 'completed'])
            ->latest()
            ->get();

        // Get unique jadwals (filter out null jadwals)
        $jadwals = $penugasan->map(function ($p) {
            return $p->jadwalUji;
        })->filter(function ($jadwal) {
            return $jadwal !== null;
        })->unique('id')->values();

        // Check which jadwals have soal uploaded
        $jadwals = $jadwals->map(function ($jadwal) use ($asesor) {
            $soalUploads = SoalUpload::where('jadwal_uji_id', $jadwal->id)
                ->where('asesor_id', $asesor->id)
                ->get();
            $jadwal->has_soal = $soalUploads->isNotEmpty();
            $jadwal->soalUploads = $soalUploads;
            return $jadwal;
        });

        return view('asesor.soal-upload.index', compact('jadwals'));
    }

    public function create($jadwalId)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        $jadwal = JadwalUji::with(['skemaSertifikasi', 'tuk'])
            ->findOrFail($jadwalId);

        // Check if asesor is assigned to this jadwal (include assigned, accepted, and completed statuses)
        $penugasan = Penugasan::where('jadwal_uji_id', $jadwalId)
            ->where('asesor_id', $asesor->id)
            ->whereIn('status', ['assigned', 'accepted', 'completed'])
            ->first();

        if (!$penugasan) {
            return redirect()->route('asesor.soal-upload.index')
                ->with('error', 'Anda tidak memiliki akses ke jadwal ini');
        }

        // Instrumen options
        $instrumenOptions = [
            'FR.IA.02' => 'FR.IA.02. TPD - Tugas Praktik Demonstrasi',
            'FR.IA.03' => 'FR.IA.03. PMO - Pertanyaan Untuk Mendukung Observasi',
            'FR.IA.04A' => 'FR.IA.04. A DIT - Daftar Instruksi Terstruktur (Penjelasan Proyek Singkat/ Kegiatan Terstruktur Lainnya*)',
            'FR.IA.04B' => 'FR.IA.04B. DIT - Penilaian Proyek Singkat atau Kegiatan Terstruktur Lainnya',
            'FR.IA.05' => 'FR.IA.05. DPT - Pertanyaan Tertulis Pilihan Ganda',
            'FR.IA.05B' => 'FR.IA.05 B Lembar Kunci Jawaban Pilihan Ganda',
            'FR.IA.06A' => 'FR.IA.06. A. DPT - Pertanyaan Tertulis Esai',
            'FR.IA.06B' => 'FR.IA.06. B. Lembar Kunci Jawaban Pertanyaan Tertulis Esai',
            'FR.IA.07' => 'FR.IA.07. DPL - Pertanyaan Lisan',
            'FR.IA.09' => 'FR.IA.09. PW - Pertanyaan Wawancara',
        ];

        return view('asesor.soal-upload.create', compact('jadwal', 'instrumenOptions'));
    }

    public function store(Request $request, $jadwalId)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        $request->validate([
            'jenis_instrumen' => 'required|string|in:FR.IA.02,FR.IA.03,FR.IA.04A,FR.IA.04B,FR.IA.05,FR.IA.05B,FR.IA.06A,FR.IA.06B,FR.IA.07,FR.IA.09',
            'file' => 'required|file|mimes:doc,docx,pdf|max:10240', // 10MB max
        ]);

        $jadwal = JadwalUji::findOrFail($jadwalId);

        // Check if asesor is assigned to this jadwal (include assigned, accepted, and completed statuses)
        $penugasan = Penugasan::where('jadwal_uji_id', $jadwalId)
            ->where('asesor_id', $asesor->id)
            ->whereIn('status', ['assigned', 'accepted', 'completed'])
            ->first();

        if (!$penugasan) {
            return redirect()->route('asesor.soal-upload.index')
                ->with('error', 'Anda tidak memiliki akses ke jadwal ini');
        }

        // Handle file upload
        $file = $request->file('file');
        $originalFilename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileType = in_array(strtolower($extension), ['doc', 'docx']) ? 'word' : 'pdf';
        $fileName = time() . '_' . uniqid() . '.' . $extension;
        $filePath = $file->storeAs('soal_uploads', $fileName, 'public');

        SoalUpload::create([
            'jadwal_uji_id' => $jadwalId,
            'asesor_id' => $asesor->id,
            'jenis_instrumen' => $request->jenis_instrumen,
            'file_path' => $filePath,
            'original_filename' => $originalFilename,
            'file_type' => $fileType,
            'file_size' => $file->getSize(),
        ]);

        return redirect()->route('asesor.soal-upload.index')
            ->with('success', 'Soal berhasil diupload');
    }

    public function show($id)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        $soalUpload = SoalUpload::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk', 'submissions.pendaftaran.user'])
            ->where('asesor_id', $asesor->id)
            ->findOrFail($id);

        return view('asesor.soal-upload.show', compact('soalUpload'));
    }

    public function destroy($id)
    {
        $asesor = Auth::user()->asesor;
        if (!$asesor) {
            return redirect()->route('login')->with('error', 'Anda bukan asesor');
        }

        $soalUpload = SoalUpload::where('asesor_id', $asesor->id)->findOrFail($id);

        // Delete file from storage
        if (Storage::disk('public')->exists($soalUpload->file_path)) {
            Storage::disk('public')->delete($soalUpload->file_path);
        }

        // Delete submissions files
        foreach ($soalUpload->submissions as $submission) {
            if (Storage::disk('public')->exists($submission->file_path)) {
                Storage::disk('public')->delete($submission->file_path);
            }
        }

        $soalUpload->delete();

        return redirect()->route('asesor.soal-upload.index')
            ->with('success', 'Soal berhasil dihapus');
    }
}
