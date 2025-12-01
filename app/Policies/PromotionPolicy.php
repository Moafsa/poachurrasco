<?php

namespace App\Policies;

use App\Models\Promotion;
use App\Models\User;

class PromotionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Promotion $promotion): bool
    {
        return $promotion->establishment && $promotion->establishment->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Promotion $promotion): bool
    {
        return $promotion->establishment && $promotion->establishment->user_id === $user->id;
    }

    public function delete(User $user, Promotion $promotion): bool
    {
        return $promotion->establishment && $promotion->establishment->user_id === $user->id;
    }

    public function restore(User $user, Promotion $promotion): bool
    {
        return $this->delete($user, $promotion);
    }

    public function forceDelete(User $user, Promotion $promotion): bool
    {
        return $this->delete($user, $promotion);
    }
}












