<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AsesorController;
use App\Http\Controllers\MahasiswaController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Skema Sertifikasi
    Route::resource('skema', AdminController::class);
    
    // Unit Kompetensi
    Route::get('/unit-kompetensi', [AdminController::class, 'unitKompetensi'])->name('unit-kompetensi');
    Route::post('/unit-kompetensi', [AdminController::class, 'storeUnitKompetensi']);
    Route::put('/unit-kompetensi/{id}', [AdminController::class, 'updateUnitKompetensi']);
    Route::delete('/unit-kompetensi/{id}', [AdminController::class, 'deleteUnitKompetensi']);
    
    // Elemen
    Route::get('/elemen', [AdminController::class, 'elemen'])->name('elemen');
    Route::post('/elemen', [AdminController::class, 'storeElemen']);
    Route::put('/elemen/{id}', [AdminController::class, 'updateElemen']);
    Route::delete('/elemen/{id}', [AdminController::class, 'deleteElemen']);
    
    // Elemen Judul
    Route::post('/elemen-judul', [AdminController::class, 'storeElemenJudul'])->name('elemen-judul');
    Route::put('/elemen-judul/{id}', [AdminController::class, 'updateElemenJudul']);
    Route::delete('/elemen-judul/{id}', [AdminController::class, 'deleteElemenJudul']);
    
    // Kriteria Unjuk Kerja
    Route::get('/kriteria-unjuk-kerja', [AdminController::class, 'kriteriaUnjukKerja'])->name('kriteria-unjuk-kerja');
    Route::post('/kriteria-unjuk-kerja', [AdminController::class, 'storeKriteriaUnjukKerja']);
    Route::put('/kriteria-unjuk-kerja/{id}', [AdminController::class, 'updateKriteriaUnjukKerja']);
    Route::delete('/kriteria-unjuk-kerja/{id}', [AdminController::class, 'deleteKriteriaUnjukKerja']);
    
    // Kriteria Unjuk Kerja Judul
    Route::post('/kriteria-judul', [AdminController::class, 'storeKriteriaJudul'])->name('kriteria-judul');
    Route::put('/kriteria-judul/{id}', [AdminController::class, 'updateKriteriaJudul']);
    Route::delete('/kriteria-judul/{id}', [AdminController::class, 'deleteKriteriaJudul']);
    
    // Asesor
    Route::get('/asesor', [AdminController::class, 'asesor'])->name('asesor');
    Route::put('/asesor/{id}', [AdminController::class, 'updateAsesor']);
    Route::delete('/asesor/{id}', [AdminController::class, 'deleteAsesor']);
    
    // Create Asesor Account
    Route::get('/create-asesor-account', [AdminController::class, 'createAsesorAccount'])->name('create-asesor-account');
    Route::post('/create-asesor-account', [AdminController::class, 'storeAsesorAccount']);
    
    // Personalization
    Route::get('/personalization', [AdminController::class, 'personalization'])->name('personalization');
    Route::post('/personalization', [AdminController::class, 'storePersonalization']);
    
    // Verifications
    Route::get('/verifications', [AdminController::class, 'verifications'])->name('verifications');
    Route::post('/verifications/{id}/verify', [AdminController::class, 'verifyPendaftaran'])->name('verifications.verify');
    
    // TUK
    Route::get('/tuk', [AdminController::class, 'tuk'])->name('tuk');
    Route::post('/tuk', [AdminController::class, 'storeTuk']);
    Route::put('/tuk/{id}', [AdminController::class, 'updateTuk']);
    Route::delete('/tuk/{id}', [AdminController::class, 'deleteTuk']);
    
    // Jadwal Uji
    Route::get('/jadwal-uji', [AdminController::class, 'jadwalUji'])->name('jadwal-uji');
    Route::post('/jadwal-uji', [AdminController::class, 'storeJadwalUji']);
    Route::put('/jadwal-uji/{id}', [AdminController::class, 'updateJadwalUji']);
    Route::delete('/jadwal-uji/{id}', [AdminController::class, 'deleteJadwalUji']);
    
    // Penugasan
    Route::get('/penugasan', [AdminController::class, 'penugasan'])->name('penugasan');
    Route::post('/penugasan', [AdminController::class, 'storePenugasan'])->name('penugasan.store');
    Route::get('/penugasan/{id}', [AdminController::class, 'getPenugasan'])->name('penugasan.show');
    Route::put('/penugasan/{id}', [AdminController::class, 'updatePenugasan'])->name('penugasan.update');
    Route::delete('/penugasan/{id}', [AdminController::class, 'deletePenugasan'])->name('penugasan.delete');
    
    // Pendaftaran
    Route::get('/pendaftaran', [AdminController::class, 'pendaftaran'])->name('pendaftaran');
    Route::get('/pendaftaran/{id}/detail', [AdminController::class, 'viewPendaftaranDetail'])->name('pendaftaran.detail');
    
    // Verifikasi Pendaftaran (integrated into pendaftaran page)
    Route::post('/pendaftaran/{id}/approved', [AdminController::class, 'approvePendaftaran'])->name('pendaftaran.approved');
    Route::post('/pendaftaran/{id}/rejected', [AdminController::class, 'rejectPendaftaran'])->name('pendaftaran.rejected');
    Route::put('/pendaftaran/{id}/approve', [AdminController::class, 'approvePendaftaran'])->name('pendaftaran.approve');
    Route::put('/pendaftaran/{id}/reject', [AdminController::class, 'rejectPendaftaran'])->name('pendaftaran.reject');
    
    // Laporan
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
    Route::get('/laporan/ak05', [AdminController::class, 'generateAK05'])->name('laporan.ak05');
    
    // Manage Users
    Route::get('/users', [AdminController::class, 'manageUsers'])->name('users.index');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    
    // Unit Kompetensi Judul (integrated into unit-kompetensi page)
    Route::post('/unit-kompetensi-judul', [AdminController::class, 'storeUnitKompetensiJudul'])->name('unit-kompetensi-judul.store');
    Route::put('/unit-kompetensi-judul/{id}', [AdminController::class, 'updateUnitKompetensiJudul'])->name('unit-kompetensi-judul.update');
    Route::delete('/unit-kompetensi-judul/{id}', [AdminController::class, 'deleteUnitKompetensiJudul'])->name('unit-kompetensi-judul.delete');
    
    // Personalization
    Route::get('/personalization', [AdminController::class, 'personalization'])->name('personalization');
    Route::post('/personalization', [AdminController::class, 'storePersonalization'])->name('personalization.store');
    Route::get('/personalization/get-signature', [AdminController::class, 'getSignature'])->name('personalization.get-signature');
    
    // Persetujuan Asesmen
    Route::get('/persetujuan-asesmen', [AdminController::class, 'persetujuanAsesmen'])->name('persetujuan-asesmen');
    Route::get('/persetujuan-asesmen/{id}/detail', [AdminController::class, 'detailPersetujuanAsesmen'])->name('persetujuan-asesmen.detail');
    Route::post('/persetujuan-asesmen/{id}/konfirmasi', [AdminController::class, 'konfirmasiPersetujuanAsesmen'])->name('persetujuan-asesmen.konfirmasi');
});

// Asesor routes
Route::middleware(['auth', 'role:asesor'])->prefix('asesor')->name('asesor.')->group(function () {
    Route::get('/dashboard', [AsesorController::class, 'dashboard'])->name('dashboard');
    Route::get('/pendaftaran', [AsesorController::class, 'pendaftaran'])->name('pendaftaran');
    Route::get('/penugasan', [AsesorController::class, 'penugasan'])->name('penugasan');
    Route::get('/dokumen', [AsesorController::class, 'dokumen'])->name('dokumen');
    Route::post('/dokumen/{id}/approve', [AsesorController::class, 'approveDokumen']);
    Route::post('/dokumen/{id}/reject', [AsesorController::class, 'rejectDokumen']);
    Route::get('/asesmen', [AsesorController::class, 'asesmen'])->name('asesmen');
    Route::post('/asesmen/{id}/submit', [AsesorController::class, 'submitAsesmen']);
    
    // Personalization
    Route::get('/personalization', [AsesorController::class, 'personalization'])->name('personalization');
    Route::post('/personalization', [AsesorController::class, 'storePersonalization']);
    
    // Verifications
    Route::get('/verifications', [AsesorController::class, 'verifications'])->name('verifications');
    Route::post('/verifications/{id}/verify', [AsesorController::class, 'verifyPendaftaran'])->name('verifications.verify');
    
    // Observasi Checklist
    Route::resource('observasi', App\Http\Controllers\ObservasiController::class);
    Route::get('/observasi/get-elemen-kriteria/{pendaftaranId}', [App\Http\Controllers\ObservasiController::class, 'getElemenKriteria']);
    Route::get('/observasi/get-unit-kompetensi/{pendaftaranId}', [App\Http\Controllers\ObservasiController::class, 'getUnitKompetensi']);
    
    // Penyesuaian Checklist
    Route::resource('penyesuaian', App\Http\Controllers\PenyesuaianController::class);
    
    // Rekaman Asesmen Kompetensi
    Route::resource('rekaman-asesmen', App\Http\Controllers\RekamanAsesmenController::class);
    Route::get('/rekaman-asesmen/get-unit-kompetensi/{pendaftaranId}', [App\Http\Controllers\RekamanAsesmenController::class, 'getUnitKompetensi']);
    
    // Umpan Balik Asesmen (view-only for asesor)
    Route::get('/umpan-balik', [App\Http\Controllers\AsesorUmpanBalikController::class, 'index'])->name('umpan-balik.index');
    Route::get('/umpan-balik/{id}', [App\Http\Controllers\AsesorUmpanBalikController::class, 'show'])->name('umpan-balik.show');
    
    // Banding Asesmen (view-only for asesor)
    Route::get('/banding-asesmen', [App\Http\Controllers\AsesorBandingAsesmenController::class, 'index'])->name('banding-asesmen.index');
    Route::get('/banding-asesmen/{id}', [App\Http\Controllers\AsesorBandingAsesmenController::class, 'show'])->name('banding-asesmen.show');
    
    // Soal Upload
    Route::get('/soal-upload', [App\Http\Controllers\SoalUploadController::class, 'index'])->name('soal-upload.index');
    Route::get('/soal-upload/create/{jadwalId}', [App\Http\Controllers\SoalUploadController::class, 'create'])->name('soal-upload.create');
    Route::post('/soal-upload/{jadwalId}', [App\Http\Controllers\SoalUploadController::class, 'store'])->name('soal-upload.store');
    Route::get('/soal-upload/{id}', [App\Http\Controllers\SoalUploadController::class, 'show'])->name('soal-upload.show');
    Route::delete('/soal-upload/{id}', [App\Http\Controllers\SoalUploadController::class, 'destroy'])->name('soal-upload.destroy');
    
        // Unit Kompetensi
        Route::get('/unit-kompetensi', [AsesorController::class, 'unitKompetensi'])->name('unit-kompetensi');
        Route::post('/unit-kompetensi', [AsesorController::class, 'storeUnitKompetensi']);
        Route::put('/unit-kompetensi/{id}', [AsesorController::class, 'updateUnitKompetensi']);
        Route::delete('/unit-kompetensi/{id}', [AsesorController::class, 'deleteUnitKompetensi']);
        
        // Unit Kompetensi Judul (integrated into unit-kompetensi page)
        Route::post('/unit-kompetensi-judul', [AsesorController::class, 'storeUnitKompetensiJudul'])->name('unit-kompetensi-judul.store');
        Route::put('/unit-kompetensi-judul/{id}', [AsesorController::class, 'updateUnitKompetensiJudul'])->name('unit-kompetensi-judul.update');
        Route::delete('/unit-kompetensi-judul/{id}', [AsesorController::class, 'deleteUnitKompetensiJudul'])->name('unit-kompetensi-judul.delete');
        
        // Elemen
        Route::get('/elemen', [AsesorController::class, 'elemen'])->name('elemen');
        Route::post('/elemen', [AsesorController::class, 'storeElemen']);
        Route::put('/elemen/{id}', [AsesorController::class, 'updateElemen']);
        Route::delete('/elemen/{id}', [AsesorController::class, 'deleteElemen']);
        
        // Elemen Judul
        Route::post('/elemen-judul', [AsesorController::class, 'storeElemenJudul'])->name('elemen-judul');
        Route::put('/elemen-judul/{id}', [AsesorController::class, 'updateElemenJudul']);
        Route::delete('/elemen-judul/{id}', [AsesorController::class, 'deleteElemenJudul']);
        
        // Kriteria Unjuk Kerja
        Route::get('/kriteria-unjuk-kerja', [AsesorController::class, 'kriteriaUnjukKerja'])->name('kriteria-unjuk-kerja');
        Route::post('/kriteria-unjuk-kerja', [AsesorController::class, 'storeKriteriaUnjukKerja']);
        Route::put('/kriteria-unjuk-kerja/{id}', [AsesorController::class, 'updateKriteriaUnjukKerja']);
        Route::delete('/kriteria-unjuk-kerja/{id}', [AsesorController::class, 'deleteKriteriaUnjukKerja']);
        
        // Kriteria Unjuk Kerja Judul
        Route::post('/kriteria-judul', [AsesorController::class, 'storeKriteriaJudul'])->name('kriteria-judul');
        Route::put('/kriteria-judul/{id}', [AsesorController::class, 'updateKriteriaJudul']);
        Route::delete('/kriteria-judul/{id}', [AsesorController::class, 'deleteKriteriaJudul']);
        
    // Personalization
    Route::get('/personalization', [AsesorController::class, 'personalization'])->name('personalization');
    Route::post('/personalization', [AsesorController::class, 'storePersonalization'])->name('personalization.store');
    Route::get('/personalization/get-signature', [AsesorController::class, 'getSignature'])->name('personalization.get-signature');
    
    // Penugasan
    Route::get('/penugasan', [AsesorController::class, 'penugasan'])->name('penugasan');
    Route::get('/penugasan/{id}', [AsesorController::class, 'getPenugasan'])->name('penugasan.show');
    Route::post('/penugasan/{id}/status', [AsesorController::class, 'updatePenugasanStatus'])->name('penugasan.status');
    
    // Verifikasi Asesmen (integrated into asesmen page)
    Route::post('/asesmen/{id}/verified', [AsesorController::class, 'verifyAsesmen'])->name('asesmen.verified');
    Route::post('/asesmen/{id}/rejected', [AsesorController::class, 'rejectAsesmen'])->name('asesmen.rejected');
    
    // Persetujuan Asesmen
});

// Mahasiswa routes
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('dashboard');
    Route::get('/pendaftaran', [MahasiswaController::class, 'pendaftaran'])->name('pendaftaran');
    
    // Continue draft pendaftaran
    Route::get('/pendaftaran/{id}/continue', [MahasiswaController::class, 'continuePendaftaran'])->name('pendaftaran.continue');
    
    // Multi-step pendaftaran
    Route::get('/pendaftaran/step1', [MahasiswaController::class, 'pendaftaranStep1'])->name('pendaftaran.step1');
    Route::post('/pendaftaran/step1', [MahasiswaController::class, 'storePendaftaranStep1'])->name('pendaftaran.step1.store');
    Route::get('/pendaftaran/step2', [MahasiswaController::class, 'pendaftaranStep2'])->name('pendaftaran.step2');
    Route::post('/pendaftaran/step2', [MahasiswaController::class, 'storePendaftaranStep2'])->name('pendaftaran.step2.store');
    Route::get('/pendaftaran/step3', [MahasiswaController::class, 'pendaftaranStep3'])->name('pendaftaran.step3');
    Route::post('/pendaftaran/step3', [MahasiswaController::class, 'storePendaftaranStep3'])->name('pendaftaran.step3.store');
    Route::get('/pendaftaran/step4', [MahasiswaController::class, 'pendaftaranStep4'])->name('pendaftaran.step4');
    Route::post('/pendaftaran/step4', [MahasiswaController::class, 'storePendaftaranStep4'])->name('pendaftaran.step4.store');
    Route::post('/pendaftaran/clear', [MahasiswaController::class, 'clearPendaftaranSession'])->name('pendaftaran.clear');
    
    Route::get('/jadwal', [MahasiswaController::class, 'jadwal'])->name('jadwal');
    Route::get('/dokumen', [MahasiswaController::class, 'dokumen'])->name('dokumen');
    Route::post('/dokumen', [MahasiswaController::class, 'storeDokumen']);
    Route::get('/hasil', [MahasiswaController::class, 'hasil'])->name('hasil');
    Route::post('/banding', [MahasiswaController::class, 'submitBanding'])->name('banding');
    
    // Riwayat Pendaftaran
    Route::get('/riwayat-pendaftaran', [MahasiswaController::class, 'riwayatPendaftaran'])->name('riwayat-pendaftaran');
    Route::get('/pendaftaran/{id}/detail', [MahasiswaController::class, 'detailPendaftaran'])->name('pendaftaran.detail');
    
    // Persetujuan Asesmen
    Route::get('/pendaftaran/{id}/persetujuan', [MahasiswaController::class, 'persetujuanAsesmen'])->name('persetujuan');
    Route::post('/persetujuan', [MahasiswaController::class, 'storePersetujuan'])->name('persetujuan.store');
    
    // Observasi Checklist
    Route::get('/observasi-checklist', [MahasiswaController::class, 'observasiChecklist'])->name('observasi-checklist');
    Route::get('/observasi-checklist/{id}', [MahasiswaController::class, 'showObservasiChecklist'])->name('observasi-checklist.show');
    Route::post('/observasi-checklist/{id}/signature', [MahasiswaController::class, 'updateObservasiChecklist'])->name('observasi-checklist.signature');
    
    // Penyesuaian Checklist
    Route::get('/penyesuaian-checklist', [MahasiswaController::class, 'penyesuaianChecklist'])->name('penyesuaian-checklist');
    Route::get('/penyesuaian-checklist/{id}', [MahasiswaController::class, 'showPenyesuaianChecklist'])->name('penyesuaian-checklist.show');
    Route::post('/penyesuaian-checklist/{id}/signature', [MahasiswaController::class, 'updatePenyesuaianChecklist'])->name('penyesuaian-checklist.signature');
    
    // Rekaman Asesmen Kompetensi
    Route::get('/rekaman-asesmen', [MahasiswaController::class, 'rekamanAsesmen'])->name('rekaman-asesmen');
    Route::get('/rekaman-asesmen/{id}', [MahasiswaController::class, 'showRekamanAsesmen'])->name('rekaman-asesmen.show');
    Route::get('/rekaman-asesmen/{id}/signature', [MahasiswaController::class, 'rekamanAsesmenSignature'])->name('rekaman-asesmen.signature');
    Route::post('/rekaman-asesmen/{id}/signature', [MahasiswaController::class, 'updateRekamanAsesmen'])->name('rekaman-asesmen.signature');
    
    // Umpan Balik Asesmen (read-only after creation)
    Route::get('/umpan-balik', [App\Http\Controllers\UmpanBalikController::class, 'index'])->name('umpan-balik.index');
    Route::get('/umpan-balik/create', [App\Http\Controllers\UmpanBalikController::class, 'create'])->name('umpan-balik.create');
    Route::post('/umpan-balik', [App\Http\Controllers\UmpanBalikController::class, 'store'])->name('umpan-balik.store');
    Route::get('/umpan-balik/{id}', [App\Http\Controllers\UmpanBalikController::class, 'show'])->name('umpan-balik.show');
    
    // Banding Asesmen
    Route::get('/banding-asesmen', [App\Http\Controllers\Mahasiswa\BandingAsesmenController::class, 'index'])->name('banding-asesmen.index');
    Route::get('/banding-asesmen/select-rekaman', [App\Http\Controllers\Mahasiswa\BandingAsesmenController::class, 'selectRekaman'])->name('banding-asesmen.select-rekaman');
    Route::get('/banding-asesmen/create/{rekamanAsesmenId}', [App\Http\Controllers\Mahasiswa\BandingAsesmenController::class, 'create'])->name('banding-asesmen.create');
    Route::post('/banding-asesmen/{rekamanAsesmenId}', [App\Http\Controllers\Mahasiswa\BandingAsesmenController::class, 'store'])->name('banding-asesmen.store');
    Route::get('/banding-asesmen/{id}', [App\Http\Controllers\Mahasiswa\BandingAsesmenController::class, 'show'])->name('banding-asesmen.show');
    
    // Soal
    Route::get('/soal', [App\Http\Controllers\MahasiswaSoalController::class, 'index'])->name('soal.index');
    Route::get('/soal/{id}', [App\Http\Controllers\MahasiswaSoalController::class, 'show'])->name('soal.show');
    Route::post('/soal/{id}/upload', [App\Http\Controllers\MahasiswaSoalController::class, 'upload'])->name('soal.upload');
});
