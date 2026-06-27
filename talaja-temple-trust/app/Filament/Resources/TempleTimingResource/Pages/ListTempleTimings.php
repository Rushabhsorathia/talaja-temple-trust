<?php

namespace App\Filament\Resources\TempleTimingResource\Pages;

use App\Filament\Resources\TempleTimingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTempleTimings extends ListRecords
{
    protected static string $resource = TempleTimingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
