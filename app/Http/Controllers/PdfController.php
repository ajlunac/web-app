<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function ServerRecords(Server $record){
        $pdf = Pdf::loadView('pdf.server', ['server' => $record]);
        return $pdf->download();
    }
}
