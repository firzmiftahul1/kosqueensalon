<?php

namespace App\Policies;

use App\Models\KontrakSewa;
use App\Models\User;

class KontrakSewaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, KontrakSewa $kontrakSewa): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, KontrakSewa $kontrakSewa): bool
    {
        return true;
    }

    public function delete(User $user, KontrakSewa $kontrakSewa): bool
    {
        return true;
    }

    public function restore(User $user, KontrakSewa $kontrakSewa): bool
    {
        return true;
    }

    public function forceDelete(User $user, KontrakSewa $kontrakSewa): bool
    {
        return true;
    }
}
