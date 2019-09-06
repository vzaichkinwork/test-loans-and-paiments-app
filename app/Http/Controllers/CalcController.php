<?php

namespace App\Http\Controllers;

use App\Helpers\RateConverter;
use Illuminate\Http\Request;

class CalcController extends Controller
{
    public function getView()
    {
        return view('loans.calc');
    }

    /*
     * Skipped request validation that is required for sure
     */
    public function getConversion(Request $request)
    {
        $amount = $request->input('amount');
        $currencyFrom = $request->input('currency_from');
        $currencyTo = $request->input('currency_to');

        $converted = RateConverter::convert($amount, $currencyFrom, $currencyTo);

        if (is_null($converted)) {
            // It's better to use exceptions to generate errors here.
            $result = ['error' => 'Selected currencies unsupported or currency rate server is unavailable.'];
            $status = 400;
        }
        else {
            $result = [
                'orig_amount' => $amount,
                'orig_currency' => $currencyFrom,
                'conv_amount' => $converted,
                'conv_currency' => $currencyTo
            ];
            $status = 200;
        }

        return response()->json($result, $status);
    }
}
