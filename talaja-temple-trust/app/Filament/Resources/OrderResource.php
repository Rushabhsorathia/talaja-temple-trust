<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Shop';
    protected static ?string $navigationLabel = 'Orders';
    protected static ?string $recordTitleAttribute = 'order_no';
    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Order')->schema([
                Forms\Components\TextInput::make('order_no')->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('customer_name')->required(),
                Forms\Components\TextInput::make('customer_email')->email(),
                Forms\Components\TextInput::make('customer_mobile')->required()->tel(),
                Forms\Components\Textarea::make('shipping_address')->rows(2)->columnSpanFull(),
                Forms\Components\Select::make('user_id')->relationship('user', 'name')
                    ->label('Devotee')->searchable()->preload()->native(false),
            ])->columns(2),
            Forms\Components\Section::make('Amounts')->schema([
                Forms\Components\TextInput::make('subtotal')->numeric()->required()->prefix('₹'),
                Forms\Components\TextInput::make('shipping')->numeric()->default(0)->prefix('₹'),
                Forms\Components\TextInput::make('tax')->numeric()->default(0)->prefix('₹'),
                Forms\Components\TextInput::make('total')->numeric()->required()->prefix('₹'),
            ])->columns(4),
            Forms\Components\Section::make('Status')->schema([
                Forms\Components\Select::make('payment_status')->options([
                    'pending' => 'Pending', 'paid' => 'Paid', 'failed' => 'Failed', 'refunded' => 'Refunded',
                ])->default('pending')->required()->native(false),
                Forms\Components\Select::make('fulfilment_status')->options([
                    'new' => 'New', 'packed' => 'Packed', 'shipped' => 'Shipped',
                    'delivered' => 'Delivered', 'cancelled' => 'Cancelled',
                ])->default('new')->required()->native(false),
                Forms\Components\TextInput::make('gateway_transaction_id'),
                Forms\Components\TextInput::make('tracking_no'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->deferFilters()
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')->options([
                    'pending' => 'Pending', 'paid' => 'Paid', 'failed' => 'Failed', 'refunded' => 'Refunded',
                ])->multiple(),
                Tables\Filters\SelectFilter::make('fulfilment_status')->options([
                    'new' => 'New', 'packed' => 'Packed', 'shipped' => 'Shipped',
                    'delivered' => 'Delivered', 'cancelled' => 'Cancelled',
                ])->multiple(),
                Tables\Filters\Filter::make('created_at')
                    ->label('Placed between')
                    ->form([
                        Forms\Components\DatePicker::make('from')->native(false),
                        Forms\Components\DatePicker::make('to')->native(false),
                    ])
                    ->columns(2)
                    ->query(function (Builder $q, array $data) {
                        return $q->when($data['from'] ?? null, fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
                                 ->when($data['to'] ?? null, fn ($q, $d) => $q->whereDate('created_at', '<=', $d));
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->columns([
                Tables\Columns\TextColumn::make('order_no')->searchable()->sortable()->weight('bold')->copyable(),
                Tables\Columns\TextColumn::make('customer_name')->searchable()->sortable()->wrap()
                    ->description(fn (Order $r) => $r->customer_mobile),
                Tables\Columns\TextColumn::make('total')->money('INR')->sortable()->alignRight()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()->money('INR')->label('Total'),
                    ]),
                Tables\Columns\TextColumn::make('payment_status')->badge()->colors([
                    'warning' => 'pending', 'success' => 'paid', 'danger' => 'failed', 'info' => 'refunded',
                ]),
                Tables\Columns\TextColumn::make('fulfilment_status')->badge()->colors([
                    'gray' => 'new', 'info' => 'packed', 'primary' => 'shipped',
                    'success' => 'delivered', 'danger' => 'cancelled',
                ]),
                Tables\Columns\TextColumn::make('tracking_no')->toggleable()->placeholder('—'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d-m-Y H:i')->sortable(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('markPacked')->label('Mark packed')->icon('heroicon-o-archive-box')
                        ->visible(fn (Order $r) => $r->fulfilment_status === 'new')
                        ->action(fn (Order $r) => $r->update(['fulfilment_status' => 'packed']))
                        ->requiresConfirmation(),
                    Tables\Actions\Action::make('markShipped')->label('Mark shipped')->icon('heroicon-o-truck')
                        ->visible(fn (Order $r) => $r->fulfilment_status === 'packed')
                        ->action(fn (Order $r) => $r->update(['fulfilment_status' => 'shipped']))
                        ->requiresConfirmation(),
                    Tables\Actions\Action::make('markDelivered')->label('Mark delivered')->icon('heroicon-o-check-circle')
                        ->visible(fn (Order $r) => $r->fulfilment_status === 'shipped')
                        ->action(fn (Order $r) => $r->update(['fulfilment_status' => 'delivered']))
                        ->requiresConfirmation(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('markPacked')->label('Mark packed')
                    ->icon('heroicon-o-archive-box')
                    ->action(fn ($a) => $a->getRecords()->each->update(['fulfilment_status' => 'packed'])),
            ])])
            ->emptyStateHeading('No orders yet')
            ->emptyStateDescription('Shop orders will appear here once devotees start placing them.')
            ->emptyStateIcon('heroicon-o-shopping-bag')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Create order')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['order_no', 'customer_name', 'customer_email', 'customer_mobile', 'tracking_no'];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereIn('fulfilment_status', ['new', 'packed'])->count() ?: null;
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}