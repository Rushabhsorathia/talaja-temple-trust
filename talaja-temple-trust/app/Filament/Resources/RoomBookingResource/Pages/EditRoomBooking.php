<?php

namespace App\Filament\Resources\RoomBookingResource\Pages;

use App\Filament\Resources\RoomBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRoomBooking extends EditRecord
{
    protected static string $resource = RoomBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('check_in')
                ->label('Check In')
                ->icon('heroicon-o-arrow-right-on-rectangle')
                ->color('success')
                ->visible(fn () => $this->getRecord()->status === 'confirmed')
                ->requiresConfirmation()
                ->action(function () {
                    $this->getRecord()->update(['status' => 'checked_in', 'checked_in_at' => now()]);
                    $this->refreshFormData(['status', 'checked_in_at']);
                }),
            Actions\Action::make('check_out')
                ->label('Check Out')
                ->icon('heroicon-o-arrow-left-on-rectangle')
                ->color('warning')
                ->visible(fn () => $this->getRecord()->status === 'checked_in')
                ->requiresConfirmation()
                ->action(function () {
                    $booking = $this->getRecord();
                    $booking->update(['status' => 'checked_out', 'checked_out_at' => now()]);
                    if ($booking->room) {
                        $booking->room->update(['housekeeping_status' => 'dirty']);
                        \App\Models\HousekeepingLog::create([
                            'room_id' => $booking->room_id,
                            'user_id' => auth()->id(),
                            'status' => 'dirty',
                            'note' => 'Auto-set on checkout of '.$booking->booking_no,
                        ]);
                    }
                    $this->refreshFormData(['status', 'checked_out_at']);
                }),
            Actions\Action::make('cancel')
                ->label('Cancel')
                ->icon('heroicon-o-x-mark')
                ->color('danger')
                ->visible(fn () => ! in_array($this->getRecord()->status, ['cancelled', 'checked_out']))
                ->requiresConfirmation()
                ->action(function () {
                    $this->getRecord()->update(['status' => 'cancelled']);
                    $this->refreshFormData(['status']);
                }),
            Actions\DeleteAction::make(),
        ];
    }
}
