<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\SoalUpload;
use App\Models\SoalSubmission;
use App\Models\Pendaftaran;
use Carbon\Carbon;

class MahasiswaSoalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get all pendaftaran for this user that have jadwal
        $pendaftaran = Pendaftaran::with([
            'jadwalUji.skemaSertifikasi',
            'jadwalUji.soalUploads.asesor'
        ])
            ->where('user_id', $user->id)
            ->whereNotNull('jadwal_uji_id')
            ->whereIn('status', ['approved', 'in_progress', 'persetujuan_submitted'])
            ->latest()
            ->get();

        // Get available soal for each pendaftaran
        $pendaftaran = $pendaftaran->map(function ($p) use ($user) {
            $p->available_soal = $p->jadwalUji->soalUploads ?? collect();
            $p->submissions = SoalSubmission::where('pendaftaran_id', $p->id)->get()->keyBy('soal_upload_id');
            $p->is_deadline_passed = $p->jadwalUji && $p->jadwalUji->tanggal_selesai ? 
                Carbon::now()->greaterThan($p->jadwalUji->tanggal_selesai) : false;
            return $p;
        });

        return view('mahasiswa.soal.index', compact('pendaftaran'));
    }

    public function show($soalId)
    {
        $user = Auth::user();
        
        $soalUpload = SoalUpload::with(['jadwalUji.skemaSertifikasi', 'jadwalUji.tuk', 'asesor'])
            ->findOrFail($soalId);

        // Check if user has access to this soal through their pendaftaran
        $pendaftaran = Pendaftaran::where('user_id', $user->id)
            ->where('jadwal_uji_id', $soalUpload->jadwal_uji_id)
            ->whereIn('status', ['approved', 'in_progress', 'persetujuan_submitted'])
            ->first();

        if (!$pendaftaran) {
            return redirect()->route('mahasiswa.soal.index')
                ->with('error', 'Anda tidak memiliki akses ke soal ini');
        }

        // Check if already submitted
        $submission = SoalSubmission::where('soal_upload_id', $soalId)
            ->where('pendaftaran_id', $pendaftaran->id)
            ->first();

        // Check deadline
        $isDeadlinePassed = $soalUpload->jadwalUji && $soalUpload->jadwalUji->tanggal_selesai ? 
            Carbon::now()->greaterThan($soalUpload->jadwalUji->tanggal_selesai) : false;

        return view('mahasiswa.soal.show', compact('soalUpload', 'pendaftaran', 'submission', 'isDeadlinePassed'));
    }

    public function upload(Request $request, $soalId)
    {
        $user = Auth::user();
        
        $request->validate([
            'file' => 'required|file|mimes:doc,docx,pdf|max:10240', // 10MB max
        ]);

        $soalUpload = SoalUpload::findOrFail($soalId);

        // Check if user has access to this soal
        $pendaftaran = Pendaftaran::where('user_id', $user->id)
            ->where('jadwal_uji_id', $soalUpload->jadwal_uji_id)
            ->whereIn('status', ['approved', 'in_progress', 'persetujuan_submitted'])
            ->first();

        if (!$pendaftaran) {
            return redirect()->route('mahasiswa.soal.index')
                ->with('error', 'Anda tidak memiliki akses ke soal ini');
        }

        // Check deadline
        $isDeadlinePassed = $soalUpload->jadwalUji && $soalUpload->jadwalUji->tanggal_selesai ? 
            Carbon::now()->greaterThan($soalUpload->jadwalUji->tanggal_selesai) : false;

        if ($isDeadlinePassed) {
            return redirect()->back()
                ->with('error', 'Batas waktu pengumpulan sudah lewat');
        }

        // Check if already submitted
        $existingSubmission = SoalSubmission::where('soal_upload_id', $soalId)
            ->where('pendaftaran_id', $pendaftaran->id)
            ->first();

        if ($existingSubmission) {
            // Delete old file
            if (Storage::disk('public')->exists($existingSubmission->file_path)) {
                Storage::disk('public')->delete($existingSubmission->file_path);
            }
        }

        // Handle file upload
        $file = $request->file('file');
        $originalFilename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileType = in_array(strtolower($extension), ['doc', 'docx']) ? 'word' : 'pdf';
        $fileName = time() . '_' . uniqid() . '.' . $extension;
        $filePath = $file->storeAs('soal_submissions', $fileName, 'public');

        $data = [
            'soal_upload_id' => $soalId,
            'pendaftaran_id' => $pendaftaran->id,
            'file_path' => $filePath,
            'original_filename' => $originalFilename,
            'file_type' => $fileType,
            'file_size' => $file->getSize(),
            'submitted_at' => now(),
        ];

        if ($existingSubmission) {
            $existingSubmission->update($data);
        } else {
            SoalSubmission::create($data);
        }

        return redirect()->route('mahasiswa.soal.show', $soalId)
            ->with('success', 'Jawaban berhasil diupload');
    }
}
