<?php

namespace App\Filament\Resources\HousekeepingLogResource\Pages;

use App\Filament\Resources\HousekeepingLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHousekeepingLog extends EditRecord
{
    protected static string $resource = HousekeepingLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
