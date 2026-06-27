<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomBookingResource\Pages;
use App\Filament\Resources\RoomBookingResource\RelationManagers;
use App\Models\RoomBooking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoomBookingResource extends Resource
{
    protected static ?string $model = RoomBooking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Accommodation';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('room_id')->relationship('room','number')->required(),
            Forms\Components\TextInput::make('guest_name')->required(),
            Forms\Components\TextInput::make('guest_mobile')->required(),
            Forms\Components\TextInput::make('guests')->numeric()->default(1),
            Forms\Components\DatePicker::make('check_in')->required(),
            Forms\Components\DatePicker::make('check_out')->required(),
            Forms\Components\TextInput::make('amount')->numeric(),
            Forms\Components\Select::make('status')->options(['pending'=>'Pending','confirmed'=>'Confirmed','checked_in'=>'Checked In','checked_out'=>'Checked Out','cancelled'=>'Cancelled']),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
                Tables\Columns\TextColumn::make('booking_no')->searchable(),
                Tables\Columns\TextColumn::make('guest_name')->searchable(),
                Tables\Columns\TextColumn::make('room.number')->label('Room'),
                Tables\Columns\TextColumn::make('check_in')->date('d-m-Y'),
                Tables\Columns\TextColumn::make('check_out')->date('d-m-Y'),
                Tables\Columns\TextColumn::make('status')->badge()->colors(['warning'=>'pending','success'=>'confirmed','info'=>'checked_in','gray'=>'checked_out','danger'=>'cancelled']),
            ])
            ->filters([Tables\Filters\Filter::make('placeholder')])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoomBookings::route('/'),
            'create' => Pages\CreateRoomBooking::route('/create'),
            'edit' => Pages\EditRoomBooking::route('/{record}/edit'),
        ];
    }
}
