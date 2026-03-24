<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BookingInvoiceController extends Controller
{
    public function download(Request $request, Booking $booking)
    {
      
        if ((int)$booking->user_id !== (int)$request->user()->id) {
            abort(403);
        }

       
        $booking->load(['rooms.roomType']);

        $pdf = Pdf::loadView('pdf.booking-invoice', [
            'booking' => $booking,
        ])->setPaper('a4');

        $fileName = "invoice-{$booking->booking_code}.pdf";

        return $pdf->download($fileName);
    }
}
