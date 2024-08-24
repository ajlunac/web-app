<?php

use App\Http\Controllers\DownloadPdfController;
use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return redirect('/dashboard');
});

Route::get('/{record}/pdf}/ServerRecords', [PdfController::class, 'ServerRecords'])->name('pdf.server');