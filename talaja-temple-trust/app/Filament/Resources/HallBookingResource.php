<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HallBookingResource\Pages;
use App\Filament\Resources\HallBookingResource\RelationManagers;
use App\Models\HallBooking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HallBookingResource extends Resource
{
    protected static ?string $model = HallBooking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Accommodation';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('meeting_hall_id')->relationship('hall','name')->required(),
            Forms\Components\TextInput::make('guest_name')->required(),
            Forms\Components\TextInput::make('guest_mobile')->required(),
            Forms\Components\DatePicker::make('event_date')->required(),
            Forms\Components\TimePicker::make('start_time'),
            Forms\Components\TimePicker::make('end_time'),
            Forms\Components\TextInput::make('attendees')->numeric()->default(1),
            Forms\Components\TextInput::make('amount')->numeric(),
            Forms\Components\Select::make('status')->options(['pending'=>'Pending','confirmed'=>'Confirmed','cancelled'=>'Cancelled']),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
                Tables\Columns\TextColumn::make('booking_no')->searchable(),
                Tables\Columns\TextColumn::make('hall.name')->label('Hall'),
                Tables\Columns\TextColumn::make('guest_name')->searchable(),
                Tables\Columns\TextColumn::make('event_date')->date('d-m-Y')->sortable(),
                Tables\Columns\TextColumn::make('attendees'),
                Tables\Columns\TextColumn::make('amount')->money('INR')->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->colors(['warning'=>'pending','success'=>'confirmed','danger'=>'cancelled']),
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
            'index' => Pages\ListHallBookings::route('/'),
            'create' => Pages\CreateHallBooking::route('/create'),
            'edit' => Pages\EditHallBooking::route('/{record}/edit'),
        ];
    }
}
