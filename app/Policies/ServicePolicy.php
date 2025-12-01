<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Service $service): bool
    {
        return $service->establishment && $service->establishment->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Service $service): bool
    {
        return $service->establishment && $service->establishment->user_id === $user->id;
    }

    public function delete(User $user, Service $service): bool
    {
        return $service->establishment && $service->establishment->user_id === $user->id;
    }

    public function restore(User $user, Service $service): bool
    {
        return $this->delete($user, $service);
    }

    public function forceDelete(User $user, Service $service): bool
    {
        return $this->delete($user, $service);
    }
}












