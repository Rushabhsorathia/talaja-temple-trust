<?php

namespace App\Filament\Resources\LiveDarshanConfigResource\Pages;

use App\Filament\Resources\LiveDarshanConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLiveDarshanConfig extends EditRecord
{
    protected static string $resource = LiveDarshanConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
