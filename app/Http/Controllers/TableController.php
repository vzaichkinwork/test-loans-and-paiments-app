<?php

namespace App\Http\Controllers;

use App\Models\Loan;

class TableController extends Controller
{
    public function getTable()
    {
        $loans = Loan::with('payments')->get();

        return view('loans.table', ['loans' => $loans]);
    }
}
