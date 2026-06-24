<?php

namespace App\Console\Commands;

use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Console\Command;

class VoucherActiveUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:voucher-active-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update Voucher active to actived';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //

         $now = Carbon::now();
        $vouchers = Voucher::where('actived_at' , '<=' , $now)->where('active' , 0)->get();

        foreach($vouchers as $voucher){
            
            $voucher->update([
                'active' => 1
            ]);

        }

        $this->info('Update voucher actived success');
    }
}
