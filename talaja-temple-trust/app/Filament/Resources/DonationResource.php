<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationResource\Pages;
use App\Filament\Resources\DonationResource\RelationManagers;
use App\Models\Donation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Donations';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('donation_category_id')->relationship('category','name'),
            Forms\Components\TextInput::make('amount')->numeric()->required(),
            Forms\Components\Select::make('payment_mode')->options(['online'=>'Online','upi'=>'UPI','cash'=>'Cash','cheque'=>'Cheque','bank_transfer'=>'Bank Transfer','qr'=>'QR']),
            Forms\Components\Select::make('status')->options(['pending'=>'Pending','success'=>'Success','failed'=>'Failed','refunded'=>'Refunded']),
            Forms\Components\TextInput::make('gateway_transaction_id'),
            Forms\Components\Toggle::make('is_80g'),
            Forms\Components\TextInput::make('donor_name'),
            Forms\Components\TextInput::make('donor_email')->email(),
            Forms\Components\TextInput::make('donor_mobile'),
            Forms\Components\TextInput::make('donor_pan'),
            Forms\Components\Textarea::make('donor_address')->rows(2),
            Forms\Components\Textarea::make('note')->rows(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
                Tables\Columns\TextColumn::make('receipt_no')->searchable(),
                Tables\Columns\TextColumn::make('donor_name')->searchable()->default('-'),
                Tables\Columns\TextColumn::make('amount')->money('INR')->sortable(),
                Tables\Columns\TextColumn::make('payment_mode')->badge(),
                Tables\Columns\TextColumn::make('status')->badge()->colors(['warning'=>'pending','success'=>'success','danger'=>'failed']),
                Tables\Columns\IconColumn::make('is_80g')->boolean(),
                Tables\Columns\TextColumn::make('paid_at')->dateTime('d-m-Y')->sortable(),
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
            'index' => Pages\ListDonations::route('/'),
            'create' => Pages\CreateDonation::route('/create'),
            'edit' => Pages\EditDonation::route('/{record}/edit'),
        ];
    }
}
