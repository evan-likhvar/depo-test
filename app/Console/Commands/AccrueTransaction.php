<?php

namespace App\Console\Commands;

use App\Models\Deposit;
use App\Services\UserTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AccrueTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:accrue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'transaction:accrue';

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
     * @param UserTransaction $userTransaction
     * @return int
     */
    public function handle(UserTransaction $userTransaction)
    {
        $deposits = Deposit::where('active',1)->where('accrue_times','<', config('site-param.max_accrue_times'))->get();

        foreach ($deposits as $deposit) {
            try {
                $userTransaction->createAccrue($deposit);
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
            }
        }

        return 0;
    }
}
