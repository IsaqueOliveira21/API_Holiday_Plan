<?php

namespace App\Services;

use App\Models\Plan;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class PdfService {
    public function generatePlanPdf(Plan $plan) {
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $pdf = new Dompdf($options);

        $logo = storage_path('app/public/img/buzzvel-logo-dark.png');
        $type = pathinfo($logo, PATHINFO_EXTENSION);
        $data = file_get_contents($logo);
        $base64Image = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $pdfView = view('pdf.planPdf', ['plan' => $plan, 'base64Image' => $base64Image])->render();

        $pdf->loadHtml($pdfView);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return $pdf->output();
    }
}
