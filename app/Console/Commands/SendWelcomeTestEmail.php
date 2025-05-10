<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use App\Models\User;

class SendWelcomeTestEmail extends Command
{
    protected $signature = 'mail:test-welcome {email}';
    protected $description = 'Enviar email de bienvenida a un correo manualmente';

    public function handle(): void
    {
        $email = $this->argument('email');

        // Puedes usar un usuario falso
        $user = new User([
            'name' => 'Carlos Test',
            'email' => $email,
        ]);

        Mail::to($email)->send(new WelcomeMail($user));

        $this->info("Email de bienvenida enviado a {$email}");
    }
}
