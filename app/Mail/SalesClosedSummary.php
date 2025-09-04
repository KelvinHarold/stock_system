<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\StockTransaction;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesClosedSummary extends Mailable
{
    use Queueable, SerializesModels;

    public $transactions;
    public $totalSales;
    public $totalDiscount;
    public $totalProfit;

    /**
     * Create a new message instance.
     */
    public function __construct($transactions)
    {
        $this->transactions = $transactions;

        // Compute totals
        $this->totalSales    = $transactions->sum(fn($t) => $t->unit_price * $t->quantity);
        $this->totalDiscount = $transactions->sum('discount');
        $this->totalProfit   = $transactions->sum(fn($t) => ($t->unit_price - $t->unit_cost) * $t->quantity - $t->discount);
    }

    /**
     * Build the message.
     */

public function build()
{
    // Generate the PDF from the same Blade view
    $pdf = Pdf::loadView('emails.sales_closed_summary', [
        'transactions'  => $this->transactions,
        'totalSales'    => $this->totalSales,
        'totalDiscount' => $this->totalDiscount,
        'totalProfit'   => $this->totalProfit,
    ]);

    return $this->subject('Daily Sales Closed Summary')
                ->view('emails.sales_closed_summary') // HTML email
                ->with([
                    'transactions'  => $this->transactions,
                    'totalSales'    => $this->totalSales,
                    'totalDiscount' => $this->totalDiscount,
                    'totalProfit'   => $this->totalProfit,
                ])
                ->attachData($pdf->output(), 'daily_sales_summary.pdf'); // attach PDF
}

}
