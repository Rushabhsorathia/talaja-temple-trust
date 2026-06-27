<?php

namespace App\Filament\Resources\MeetingHallResource\Pages;

use App\Filament\Resources\MeetingHallResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMeetingHalls extends ListRecords
{
    protected static string $resource = MeetingHallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
