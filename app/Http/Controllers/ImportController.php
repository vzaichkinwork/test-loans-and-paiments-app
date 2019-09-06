<?php

namespace App\Http\Controllers;

use App\Helpers\CSVReader;
use App\Helpers\RateConverter;
use App\Models\Loan;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

/**
 * Class ImportController
 * @package App\Http\Controllers
 */
class ImportController extends Controller
{
    public function getLoansView()
    {
        return view('loans.import_loans');
    }

    /**
     * Skipped request validation
     * In case of big CSV files this controller method should
     * start job to process files async.
     * It's better to place all import logic into some service.
     * Certainly we need some CSV validation.
     */
    public function storeLoans(Request $request)
    {
        if (!$request->hasFile('csv') || !$request->file('csv')->isValid()) {
            return redirect()
                ->route('import.loans.form')
                ->with('error', 'CSV file is not provided.');
        }

        $csv = $request->file('csv')->get();
        $data = CSVReader::readString($csv);

        if (!is_array($data) || empty($data)) {
            return redirect()
                ->route('import.loans.form')
                ->with('error', 'CSV file is invalid or empty.');
        }

        // Remove headers
        unset($data[0]);

        foreach ($data as $rec) {
            Loan::updateOrCreate([
                'loan_number' => trim($rec[0])
            ], [
                'amount' => trim($rec[1]),
                'imported' => 1,
            ]);
        }

        // This console command must be called asynchronously
        // But for test & show purposes inserted here
        Artisan::call('update:loans:statuses');

        return redirect()
            ->route('import.loans.form')
            ->with('success', 'CSV file is imported successfully.');
    }

    public function getPaymentsView()
    {
        return view('loans.import_payments');
    }

    /**
     * Skipped request validation
     * In case of big CSV files this controller method should
     * start job to process files async.
     * It's better to place all import logic into some service.
     * Certainly we need some CSV validation.
     */
    public function storePayments(Request $request)
    {
        if (!$request->hasFile('csv') || !$request->file('csv')->isValid()) {
            return redirect()
                ->route('import.payments.form')
                ->with('error', 'CSV file is not provided.');
        }

        $csv = $request->file('csv')->get();
        $data = CSVReader::readString($csv);

        if (!is_array($data) || empty($data)) {
            return redirect()
                ->route('import.payments.form')
                ->with('error', 'CSV file is invalid or empty.');
        }

        // Remove headers
        unset($data[0]);

        $loanNumbers = [];

        foreach ($data as $rec) {
            $origAmount = trim($rec[1]);
            $currency = mb_strtoupper(trim($rec[2]));

            // No check for result success here
            $amount = RateConverter::convert($origAmount, $currency, 'USD');

            $loanNumber =  trim($rec[3]);
            $loanNumbers[] = $loanNumber;

            Payment::updateOrCreate([
                'loan_number' => $loanNumber,
                'payment_number' => trim($rec[0]),
            ], [
                'amount' => $amount,
                'orig_amount' => $origAmount,
                'orig_currency' => $currency,
                'imported' => 1,
            ]);
        }

        if (count($loanNumbers)) {
            Loan::whereIn('loan_number', $loanNumbers)->update(['imported' => 1]);
        }

        // This console command must be called asynchronously
        // But for test & show purposes inserted here
        Artisan::call('update:loans:statuses');

        return redirect()
            ->route('import.payments.form')
            ->with('success', 'CSV file is imported successfully.');
    }
}
