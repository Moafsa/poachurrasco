<?php

namespace App\Policies;

use App\Models\Recipe;
use App\Models\User;

class RecipePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Recipe $recipe): bool
    {
        return $recipe->establishment && $recipe->establishment->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Recipe $recipe): bool
    {
        return $recipe->establishment && $recipe->establishment->user_id === $user->id;
    }

    public function delete(User $user, Recipe $recipe): bool
    {
        return $recipe->establishment && $recipe->establishment->user_id === $user->id;
    }

    public function restore(User $user, Recipe $recipe): bool
    {
        return $this->delete($user, $recipe);
    }

    public function forceDelete(User $user, Recipe $recipe): bool
    {
        return $this->delete($user, $recipe);
    }
}












