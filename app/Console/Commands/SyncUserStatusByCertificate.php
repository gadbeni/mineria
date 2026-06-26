<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncUserStatusByCertificate extends Command
{
    protected $signature = 'users:sync-certificate-status';
    protected $description = 'Desactiva usuarios cuyo certificado venció y reactiva los que tienen certificado vigente';

    public function handle()
    {
        $today = Carbon::today();
        $deactivated = 0;
        $activated = 0;

        $users = User::whereNotNull('company_id')->get();

        foreach ($users as $user) {
            $hasActiveCert = Certificate::where('company_id', $user->company_id)
                ->whereNull('deleted_at')
                ->where('dateFinish', '>=', $today)
                ->exists();

            if ($hasActiveCert && $user->status == 0) {
                $user->update(['status' => 1]);
                $activated++;
            } elseif (!$hasActiveCert && $user->status != 0) {
                $user->update(['status' => 0]);
                $deactivated++;
            }
        }

        $this->info("Usuarios desactivados: {$deactivated}");
        $this->info("Usuarios reactivados: {$activated}");

        return 0;
    }
}
