<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReceiptResource\Pages;
use App\Models\Receipt;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;

class ReceiptResource extends Resource
{
    protected static ?string $model = Receipt::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-circle';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Receipts (In)';
    protected static ?string $recordTitleAttribute = 'receipt_no';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Receipt')->schema([
                Forms\Components\TextInput::make('receipt_no')->required()->unique(ignoreRecord: true),
                Forms\Components\Select::make('source')->options([
                    'donation' => 'Donation', 'booking' => 'Booking', 'shop' => 'Shop', 'other' => 'Other',
                ])->required()->native(false),
                Forms\Components\Select::make('temple_id')->relationship('temple', 'name')->native(false),
                Forms\Components\TextInput::make('amount')->numeric()->required()->prefix('₹'),
                Forms\Components\Select::make('payment_mode')->options([
                    'online' => 'Online', 'upi' => 'UPI', 'cash' => 'Cash',
                    'cheque' => 'Cheque', 'bank_transfer' => 'Bank Transfer',
                ])->required()->native(false),
                Forms\Components\DatePicker::make('date')->required()->native(false),
                Forms\Components\Textarea::make('note')->rows(2)->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->deferFilters()
            ->filters([
                Tables\Filters\SelectFilter::make('source')->options([
                    'donation' => 'Donation', 'booking' => 'Booking', 'shop' => 'Shop', 'other' => 'Other',
                ])->multiple(),
                Tables\Filters\SelectFilter::make('payment_mode')->options([
                    'online' => 'Online', 'upi' => 'UPI', 'cash' => 'Cash',
                    'cheque' => 'Cheque', 'bank_transfer' => 'Bank Transfer',
                ])->multiple(),
                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('from')->native(false),
                        Forms\Components\DatePicker::make('to')->native(false),
                    ])
                    ->columns(2)
                    ->query(function (Builder $q, array $data) {
                        return $q->when($data['from'] ?? null, fn ($q, $d) => $q->whereDate('date', '>=', $d))
                                 ->when($data['to'] ?? null, fn ($q, $d) => $q->whereDate('date', '<=', $d));
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->columns([
                Tables\Columns\TextColumn::make('receipt_no')->searchable()->sortable()->weight('bold')->copyable(),
                Tables\Columns\TextColumn::make('source')->badge()->color('primary'),
                Tables\Columns\TextColumn::make('amount')->money('INR')->sortable()->alignRight()
                    ->summarize([Tables\Columns\Summarizers\Sum::make()->money('INR')->label('Total received')]),
                Tables\Columns\TextColumn::make('payment_mode')->badge()->color('gray'),
                Tables\Columns\TextColumn::make('date')->date('d-m-Y')->sortable(),
                Tables\Columns\TextColumn::make('note')->limit(30)->toggleable()->placeholder('—'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->emptyStateHeading('No receipts yet')
            ->emptyStateDescription('Inflow receipts from donations, bookings and shop will appear here.')
            ->emptyStateIcon('heroicon-o-arrow-down-circle')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Record receipt')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['receipt_no', 'note'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListReceipts::route('/'),
            'create' => Pages\CreateReceipt::route('/create'),
            'edit'   => Pages\EditReceipt::route('/{record}/edit'),
        ];
    }
}