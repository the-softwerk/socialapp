<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Password input and verification were not part of the project requirements.
     * To avoid validation errors related to the missing password field,
     * password handling has been removed from the Filament user form.
     * A default password is automatically assigned when a user is created.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['password']);
        return $data;
    }
}
