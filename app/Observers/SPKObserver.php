<?php

namespace App\Observers;

use App\Models\Spk;
use App\Models\User;
use App\Notifications\NewSPK;

class SPKObserver
{
    public function created(Spk $spk)
    {
        $invoice = $spk->nomor_invoice;
        $users = User::where('role', 'Produksi')->get();
        foreach ($users as $user) {
            $user->notify(new NewSPK($spk, $user));
        }
    }
}
