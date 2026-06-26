<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HTML2PDF extends Controller
{
    public function viewCompiledReport($id) 
    {
        ob_start();
        include('view.php');
        $content = ob_get_clean();
        $html2pdf_path = base_path() . '\vendor\spipu\html2pdf\html2pdf.class.php';
        File::requireOnce($html2pdf_path);
        
        try {
            $html2pdf = new \HTML2PDF('P', 'Legal', 'en', true, 'UTF-8', array(25.4, 20.4, 25.4, 20.4));
            $html2pdf->pdf->SetTitle('HTML2PDF Sample');
            $html2pdf->WriteHTML($content);
            $html2pdf->Output('example.pdf');

            ob_flush();
            ob_end_clean();
        }
        catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }
}
