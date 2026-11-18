<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create';
    protected $description = 'Buat (atau jadikan admin) sebuah akun. Kata sandi diketik langsung, tidak disimpan di kode.';

    public function handle(): int
    {
        $name = $this->ask('Nama');
        $email = $this->ask('Email');
        $password = $this->secret('Kata sandi (min. 8 karakter)');

        $v = Validator::make(compact('name', 'email', 'password'), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ]);

        if ($v->fails()) {
            foreach ($v->errors()->all() as $e) { $this->error($e); }
            return self::FAILURE;
        }

        $user = User::firstOrNew(['email' => $email]);
        $user->name = $name;
        $user->password = $password; // di-hash otomatis oleh cast 'hashed'
        $user->forceFill(['is_admin' => true])->save();

        $this->info("Admin siap: {$user->email}");
        return self::SUCCESS;
    }
}
