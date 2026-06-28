<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BankStatementResource\Pages;
use App\Models\BankStatement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;

class BankStatementResource extends Resource
{
    protected static ?string $model = BankStatement::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Bank Reconciliation';
    protected static ?string $recordTitleAttribute = 'description';
    protected static ?int $navigationSort = 30;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Transaction')->schema([
                Forms\Components\DatePicker::make('date')->required()->native(false),
                Forms\Components\TextInput::make('description')->required()->columnSpanFull(),
                Forms\Components\TextInput::make('reference')->placeholder('e.g. CHQ-1234, UPI/1234'),
            ])->columns(2),
            Forms\Components\Section::make('Amounts')->schema([
                Forms\Components\TextInput::make('debit')->numeric()->default(0)->prefix('₹'),
                Forms\Components\TextInput::make('credit')->numeric()->default(0)->prefix('₹'),
                Forms\Components\TextInput::make('balance')->numeric()->required()->prefix('₹'),
            ])->columns(3),
            Forms\Components\Section::make('Reconciliation')->schema([
                Forms\Components\Select::make('reconciliation_status')->options([
                    'unmatched' => 'Unmatched', 'matched' => 'Matched', 'ignored' => 'Ignored',
                ])->default('unmatched')->native(false),
                Forms\Components\Select::make('temple_id')->relationship('temple', 'name')->native(false),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->deferFilters()
            ->filters([
                Tables\Filters\SelectFilter::make('reconciliation_status')->options([
                    'unmatched' => 'Unmatched', 'matched' => 'Matched', 'ignored' => 'Ignored',
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
            ->filtersFormColumns(2)
            ->columns([
                Tables\Columns\TextColumn::make('date')->date('d-m-Y')->sortable(),
                Tables\Columns\TextColumn::make('description')->searchable()->limit(50)->wrap(),
                Tables\Columns\TextColumn::make('reference')->toggleable()->placeholder('—'),
                Tables\Columns\TextColumn::make('debit')->money('INR')->color('danger')->alignRight(),
                Tables\Columns\TextColumn::make('credit')->money('INR')->color('success')->alignRight(),
                Tables\Columns\TextColumn::make('balance')->money('INR')->sortable()->alignRight(),
                Tables\Columns\TextColumn::make('reconciliation_status')->badge()->colors([
                    'warning' => 'unmatched', 'success' => 'matched', 'gray' => 'ignored',
                ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('markMatched')->label('Mark matched')->icon('heroicon-o-check')
                        ->visible(fn (BankStatement $r) => $r->reconciliation_status !== 'matched')
                        ->action(fn (BankStatement $r) => $r->update(['reconciliation_status' => 'matched'])),
                    Tables\Actions\Action::make('markIgnored')->label('Ignore')->icon('heroicon-o-minus-circle')
                        ->visible(fn (BankStatement $r) => $r->reconciliation_status !== 'ignored')
                        ->action(fn (BankStatement $r) => $r->update(['reconciliation_status' => 'ignored'])),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('markMatched')->label('Mark matched')
                    ->icon('heroicon-o-check')->color('success')
                    ->action(fn ($a) => $a->getRecords()->each->update(['reconciliation_status' => 'matched'])),
            ])])
            ->emptyStateHeading('No bank transactions yet')
            ->emptyStateDescription('Import or record bank statement entries to reconcile against receipts and payments.')
            ->emptyStateIcon('heroicon-o-building-library')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Add transaction')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['description', 'reference'];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('reconciliation_status', 'unmatched')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBankStatements::route('/'),
            'create' => Pages\CreateBankStatement::route('/create'),
            'edit'   => Pages\EditBankStatement::route('/{record}/edit'),
        ];
    }
}