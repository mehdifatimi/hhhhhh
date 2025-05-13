<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UpdateAdminRole extends Command
{
    protected $signature = 'user:make-admin {email}';
    protected $description = 'Met à jour le rôle d\'un utilisateur en admin';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("Utilisateur avec l'email {$email} non trouvé.");
            return 1;
        }

        $user->role_id = 1; // 1 = admin
        $user->save();

        $this->info("L'utilisateur {$email} est maintenant admin.");
        return 0;
    }
} 