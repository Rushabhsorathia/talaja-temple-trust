<?php

namespace App\Filament\Resources\MeetingHallResource\Pages;

use App\Filament\Resources\MeetingHallResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMeetingHall extends EditRecord
{
    protected static string $resource = MeetingHallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
