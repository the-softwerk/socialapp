<?php

namespace App\Filament\Resources\UserResource\Pages;

use Illuminate\Support\Facades\Hash;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /**
     * Password input and verification were not part of the project requirements.
     * To avoid validation errors related to the missing password field,
     * password handling has been removed from the Filament user form.
     * A default password is automatically assigned when a user is created.
     */

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = Hash::make('password');
        return $data;
    }
}
