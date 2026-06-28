<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-circle';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Payments (Out)';
    protected static ?string $recordTitleAttribute = 'voucher_no';
    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Voucher')->schema([
                Forms\Components\TextInput::make('voucher_no')->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('payee')->required(),
                Forms\Components\Select::make('category')->options([
                    'vendor' => 'Vendor', 'salary' => 'Salary', 'expense' => 'Expense',
                    'utility' => 'Utility', 'maintenance' => 'Maintenance',
                    'supplies' => 'Supplies', 'service' => 'Service', 'construction' => 'Construction',
                ])->required()->native(false)->createOptionUsing(fn ($v) => $v),
                Forms\Components\Select::make('temple_id')->relationship('temple', 'name')->native(false),
            ])->columns(2),
            Forms\Components\Section::make('Amount & mode')->schema([
                Forms\Components\TextInput::make('amount')->numeric()->required()->prefix('₹'),
                Forms\Components\Select::make('payment_mode')->options([
                    'online' => 'Online', 'cash' => 'Cash',
                    'cheque' => 'Cheque', 'bank_transfer' => 'Bank Transfer',
                ])->required()->native(false),
                Forms\Components\DatePicker::make('date')->required()->native(false),
            ])->columns(2),
            Forms\Components\Section::make('Approval & notes')->schema([
                Forms\Components\TextInput::make('approved_by'),
                Forms\Components\Textarea::make('note')->rows(2)->columnSpanFull(),
            ])->columns(2)->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->deferFilters()
            ->filters([
                Tables\Filters\SelectFilter::make('category')->options([
                    'vendor' => 'Vendor', 'salary' => 'Salary', 'expense' => 'Expense',
                    'utility' => 'Utility', 'maintenance' => 'Maintenance',
                    'supplies' => 'Supplies', 'service' => 'Service', 'construction' => 'Construction',
                ])->multiple(),
                Tables\Filters\SelectFilter::make('payment_mode')->options([
                    'online' => 'Online', 'cash' => 'Cash', 'cheque' => 'Cheque', 'bank_transfer' => 'Bank Transfer',
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
                Tables\Columns\TextColumn::make('voucher_no')->searchable()->sortable()->weight('bold')->copyable(),
                Tables\Columns\TextColumn::make('payee')->searchable()->sortable()->wrap(),
                Tables\Columns\TextColumn::make('category')->badge()->color('warning'),
                Tables\Columns\TextColumn::make('amount')->money('INR')->sortable()->alignRight()
                    ->summarize([Tables\Columns\Summarizers\Sum::make()->money('INR')->label('Total paid')]),
                Tables\Columns\TextColumn::make('payment_mode')->badge()->color('gray'),
                Tables\Columns\TextColumn::make('date')->date('d-m-Y')->sortable(),
                Tables\Columns\TextColumn::make('approved_by')->toggleable()->placeholder('—'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->emptyStateHeading('No payments yet')
            ->emptyStateDescription('Outflow payments (vendors, salary, expenses) will appear here.')
            ->emptyStateIcon('heroicon-o-arrow-up-circle')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Record payment')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['voucher_no', 'payee', 'note'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit'   => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}