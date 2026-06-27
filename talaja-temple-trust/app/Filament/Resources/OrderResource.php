<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('customer_name')->required(),
            Forms\Components\TextInput::make('customer_mobile')->required(),
            Forms\Components\Textarea::make('shipping_address')->rows(2),
            Forms\Components\TextInput::make('total')->numeric(),
            Forms\Components\Select::make('payment_status')->options(['pending'=>'Pending','paid'=>'Paid','failed'=>'Failed','refunded'=>'Refunded']),
            Forms\Components\Select::make('fulfilment_status')->options(['new'=>'New','packed'=>'Packed','shipped'=>'Shipped','delivered'=>'Delivered','cancelled'=>'Cancelled']),
            Forms\Components\TextInput::make('tracking_no'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
                Tables\Columns\TextColumn::make('order_no')->searchable(),
                Tables\Columns\TextColumn::make('customer_name')->searchable(),
                Tables\Columns\TextColumn::make('total')->money('INR')->sortable(),
                Tables\Columns\TextColumn::make('payment_status')->badge()->colors(['warning'=>'pending','success'=>'paid','danger'=>'failed']),
                Tables\Columns\TextColumn::make('fulfilment_status')->badge(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d-m-Y')->sortable(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
