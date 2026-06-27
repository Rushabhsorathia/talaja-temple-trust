<?php

namespace App\Filament\Resources\HousekeepingLogResource\Pages;

use App\Filament\Resources\HousekeepingLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHousekeepingLogs extends ListRecords
{
    protected static string $resource = HousekeepingLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
