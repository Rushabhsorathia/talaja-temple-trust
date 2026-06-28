<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationResource\Pages;
use App\Models\Donation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Donations';
    protected static ?string $recordTitleAttribute = 'receipt_no';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Donation')
                ->description('Receipt, amount and payment status.')
                ->icon('heroicon-o-banknotes')
                ->schema([
                    Forms\Components\TextInput::make('receipt_no')->required()->unique(ignoreRecord: true)
                        ->helperText('Auto-generated if left blank.')
                        ->prefixIcon('heroicon-o-receipt-percent'),
                    Forms\Components\Select::make('donation_category_id')->relationship('category', 'name')
                        ->required()->searchable()->preload()->native(false)
                        ->prefixIcon('heroicon-o-rectangle-stack'),
                    Forms\Components\TextInput::make('amount')->numeric()->required()->prefix('₹')
                        ->extraInputAttributes(['class' => 'text-lg font-semibold'])
                        ->helperText('Donation amount in INR.'),
                    Forms\Components\Select::make('payment_mode')->options([
                        'online' => 'Online', 'upi' => 'UPI', 'cash' => 'Cash',
                        'cheque' => 'Cheque', 'bank_transfer' => 'Bank Transfer', 'qr' => 'QR',
                    ])->required()->native(false)->prefixIcon('heroicon-o-credit-card'),
                    Forms\Components\Select::make('status')->options([
                        'pending' => 'Pending', 'success' => 'Success',
                        'failed' => 'Failed', 'refunded' => 'Refunded',
                    ])->required()->native(false)->default('pending'),
                    Forms\Components\DateTimePicker::make('paid_at')->native(false)
                        ->helperText('Leave blank for pending donations.'),
                ])->columns(2),

            Forms\Components\Section::make('Donor')
                ->description('Who made this donation. Optional for cash/anonymous gifts.')
                ->icon('heroicon-o-user')
                ->schema([
                    Forms\Components\TextInput::make('donor_name')->prefixIcon('heroicon-o-user'),
                    Forms\Components\TextInput::make('donor_email')->email()->prefixIcon('heroicon-o-envelope'),
                    Forms\Components\TextInput::make('donor_mobile')->tel()->prefixIcon('heroicon-o-phone'),
                    Forms\Components\TextInput::make('donor_pan')->helperText('Required for 80-G tax receipt.'),
                    Forms\Components\Textarea::make('donor_address')->rows(2)->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Settings')
                ->icon('heroicon-o-cog-6-tooth')
                ->schema([
                    Forms\Components\Toggle::make('is_80g')->inline(false)
                        ->helperText('Generates 80-G tax receipt on success.'),
                    Forms\Components\Toggle::make('is_anonymous')->inline(false)
                        ->helperText('Hide donor name on public lists.'),
                    Forms\Components\Select::make('donor_id')->relationship('donor', 'name')
                        ->label('Linked devotee')->searchable()->preload()->native(false)
                        ->helperText('Optional — link to a registered devotee account.'),
                    Forms\Components\TextInput::make('gateway_transaction_id')
                        ->label('Gateway Txn ID')
                        ->helperText('Razorpay / bank reference for online payments.'),
                    Forms\Components\Textarea::make('note')->rows(2)->columnSpanFull()
                        ->helperText('Internal notes — not shown to donor.'),
                ])->columns(2)->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->deferFilters()
            ->filters([
                // Primary filter — status — visible by default
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'success' => 'Success',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ])
                    ->placeholder('All statuses')
                    ->native(false),

                // Primary filter — date — visible by default
                Tables\Filters\Filter::make('paid_at')
                    ->label('Paid between')
                    ->form([
                        Forms\Components\DatePicker::make('from')->native(false)->placeholder('From'),
                        Forms\Components\DatePicker::make('to')->native(false)->placeholder('To'),
                    ])
                    ->columns(2)
                    ->query(function (Builder $q, array $data) {
                        return $q->when($data['from'] ?? null, fn ($q, $d) => $q->whereDate('paid_at', '>=', $d))
                                 ->when($data['to'] ?? null, fn ($q, $d) => $q->whereDate('paid_at', '<=', $d));
                    })
                    ->indicateUsing(function (array $data): ?string {
                        $bits = [];
                        if ($data['from'] ?? null) $bits[] = 'from '.date('d-m-Y', strtotime($data['from']));
                        if ($data['to'] ?? null) $bits[] = 'to '.date('d-m-Y', strtotime($data['to']));
                        return $bits ? 'Paid '.implode(' ', $bits) : null;
                    }),

                // Advanced filters — collapsed by default
                Tables\Filters\Filter::make('advanced')
                    ->label('More filters')
                    ->form([
                        Forms\Components\Select::make('payment_mode')
                            ->label('Payment mode')
                            ->options([
                                'online' => 'Online', 'upi' => 'UPI', 'cash' => 'Cash',
                                'cheque' => 'Cheque', 'bank_transfer' => 'Bank Transfer', 'qr' => 'QR',
                            ])->multiple()->native(false),
                        Forms\Components\Select::make('donation_category_id')
                            ->label('Category')->relationship('category', 'name')
                            ->searchable()->preload()->multiple(),
                        Forms\Components\Toggle::make('is_80g')->label('80-G eligible'),
                        Forms\Components\Toggle::make('is_anonymous')->label('Anonymous'),
                    ])
                    ->columns(2)
                    ->query(function (Builder $q, array $data) {
                        return $q
                            ->when(! empty($data['payment_mode']), fn ($q) => $q->whereIn('payment_mode', $data['payment_mode']))
                            ->when(! empty($data['donation_category_id']), fn ($q) => $q->whereIn('donation_category_id', $data['donation_category_id']))
                            ->when($data['is_80g'] ?? null, fn ($q) => $q->where('is_80g', true))
                            ->when($data['is_anonymous'] ?? null, fn ($q) => $q->where('is_anonymous', true));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if (! empty($data['payment_mode'])) {
                            $indicators[] = 'Mode: '.implode(', ', $data['payment_mode']);
                        }
                        if (! empty($data['donation_category_id'])) {
                            $cats = \App\Models\DonationCategory::whereIn('id', $data['donation_category_id'])->pluck('name')->all();
                            $indicators[] = 'Category: '.implode(', ', $cats);
                        }
                        if ($data['is_80g'] ?? null) $indicators[] = '80-G only';
                        if ($data['is_anonymous'] ?? null) $indicators[] = 'Anonymous only';
                        return $indicators;
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->headerActions([
                Tables\Actions\Action::make('viewPublic')
                    ->label('View Donate page')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->url('/donate', shouldOpenInNewTab: true),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('receipt_no')
                    ->label('Receipt')
                    ->searchable()->sortable()->weight('bold')
                    ->copyable()->copyMessage('Receipt no. copied')
                    ->icon('heroicon-o-receipt-percent')
                    ->iconColor('gray')
                    ->description(fn (Donation $r) => $r->donor_name ?: ($r->is_anonymous ? 'Anonymous donor' : '—')),

                Tables\Columns\TextColumn::make('donor_mobile')
                    ->label('Mobile')
                    ->placeholder('—')
                    ->icon('heroicon-o-phone')
                    ->iconColor('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->color('primary')
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('amount')
                    ->money('INR')
                    ->sortable()
                    ->alignEnd()
                    ->weight('semibold')
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()->money('INR')->label('Total'),
                        Tables\Columns\Summarizers\Average::make()->money('INR')->label('Average'),
                    ]),

                Tables\Columns\TextColumn::make('payment_mode')
                    ->label('Mode')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'success',
                        'danger'  => 'failed',
                        'info'    => 'refunded',
                    ])
                    ->icons([
                        'heroicon-o-clock'        => 'pending',
                        'heroicon-o-check-circle'  => 'success',
                        'heroicon-o-x-circle'      => 'failed',
                        'heroicon-o-arrow-uturn-left' => 'refunded',
                    ]),

                Tables\Columns\IconColumn::make('is_80g')
                    ->label('80-G')
                    ->boolean()
                    ->trueIcon('heroicon-o-document-check')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Paid')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->placeholder('—')
                    ->icon('heroicon-o-calendar')
                    ->iconColor('gray'),
            ])
            ->actions([
                // Inline quick actions visible on every row
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil-square')
                        ->color('primary'),
                    Tables\Actions\Action::make('markSuccess')
                        ->label('Mark success')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Donation $r) => $r->status !== 'success')
                        ->action(fn (Donation $r) => $r->update(['status' => 'success', 'paid_at' => now()]))
                        ->requiresConfirmation(),
                    Tables\Actions\Action::make('markFailed')
                        ->label('Mark failed')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (Donation $r) => $r->status !== 'failed')
                        ->action(fn (Donation $r) => $r->update(['status' => 'failed']))
                        ->requiresConfirmation(),
                    Tables\Actions\Action::make('downloadReceipt')
                        ->label('Download receipt')
                        ->icon('heroicon-o-document-arrow-down')
                        ->url(fn (Donation $r) => $r->receipts()->first()?->pdf_path
                            ? asset('storage/'.$r->receipts()->first()->pdf_path)
                            : '#', shouldOpenInNewTab: true)
                        ->visible(fn (Donation $r) => $r->receipts()->exists()),
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->color('danger'),
                ])
                ->icon('heroicon-o-ellipsis-vertical')
                ->tooltip('Actions'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('markSuccess')
                        ->label('Mark success')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($action) => $action->getRecords()->each->update(['status' => 'success', 'paid_at' => now()])),
                    Tables\Actions\BulkAction::make('markPending')
                        ->label('Mark pending')
                        ->icon('heroicon-o-clock')
                        ->color('warning')
                        ->action(fn ($action) => $action->getRecords()->each->update(['status' => 'pending', 'paid_at' => null])),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No donations yet')
            ->emptyStateDescription('Donations made on the public site will appear here. You can also record one manually.')
            ->emptyStateIcon('heroicon-o-banknotes')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Record donation')
                    ->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['receipt_no', 'donor_name', 'donor_email', 'donor_mobile', 'gateway_transaction_id'];
    }

    public static function getNavigationBadge(): ?string
    {
        $pending = static::getModel()::where('status', 'pending')->count();
        return $pending ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDonations::route('/'),
            'create' => Pages\CreateDonation::route('/create'),
            'edit'   => Pages\EditDonation::route('/{record}/edit'),
        ];
    }
}