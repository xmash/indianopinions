<?php

namespace App\Http\Controllers\Concerns;

use App\Models\User;

trait ResolvesStaffLogin
{
    protected function resolveStaffUser(string $login): ?User
    {
        $login = trim($login);

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            return User::where('email', $login)->first();
        }

        $needle = strtolower($login);

        return User::query()
            ->whereRaw('LOWER(name) = ?', [$needle])
            ->orWhereRaw('LOWER(email) LIKE ?', [$needle.'@%'])
            ->first();
    }
}
