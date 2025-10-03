<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class MakeAdmin extends Command
{
    /**
     * Komandas nosaukums.
     *
     * @var string
     */
    protected $signature = 'make:admin {email}';

    /**
     * Komandas apraksts.
     *
     * @var string
     */
    protected $description = 'Piešķirt lietotājam admin tiesības pēc e-pasta';

    /**
     * Izpilde.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("Lietotājs ar e-pastu {$email} nav atrasts.");
            return 1;
        }

        $user->is_admin = true;
        $user->save();

        $this->info("Lietotājam {$email} piešķirtas admin tiesības!");
        return 0;
    }
}
