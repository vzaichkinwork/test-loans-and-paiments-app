<?php

namespace App\Console\Commands;

use App\Models\Loan;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class UpdateLoanStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:loans:statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update loan statuses after loading.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Loan::query()
            ->where('imported', 1)
            ->chunk(100, function (Collection $loans) {
                $loans->each(function (Loan $loan) {
                    $loan->calcStatus();
                });
            });
    }
}
