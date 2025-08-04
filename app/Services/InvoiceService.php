<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceService
{
    public function generateInvoice(Order $order)
    {
        // Charger les relations nécessaires
        $order->load(['items.product', 'user']);
        
        // Générer le PDF
        $pdf = PDF::loadView('pdf.invoice', compact('order'));
        
        // Configuration du PDF
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Arial',
            'dpi' => 150,
        ]);
        
        return $pdf;
    }
    
    public function downloadInvoice(Order $order)
    {
        $pdf = $this->generateInvoice($order);
        
        $filename = 'facture-' . $order->order_number . '.pdf';
        
        return $pdf->download($filename);
    }
    
    public function streamInvoice(Order $order)
    {
        $pdf = $this->generateInvoice($order);
        
        return $pdf->stream('facture-' . $order->order_number . '.pdf');
    }
} 