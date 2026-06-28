<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationLogResource\Pages;
use App\Models\NotificationLog;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;

class NotificationLogResource extends Resource
{
    protected static ?string $model = NotificationLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Communication';
    protected static ?string $navigationLabel = 'Notification Log';
    protected static ?string $recordTitleAttribute = 'recipient';
    protected static ?int $navigationSort = 20;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->deferFilters()
            ->filters([
                Tables\Filters\SelectFilter::make('channel')->options([
                    'sms' => 'SMS', 'email' => 'Email', 'whatsapp' => 'WhatsApp',
                ])->multiple(),
                Tables\Filters\SelectFilter::make('status')->options([
                    'queued' => 'Queued', 'sent' => 'Sent', 'delivered' => 'Delivered', 'failed' => 'Failed',
                ])->multiple(),
                Tables\Filters\SelectFilter::make('template_id')->relationship('template', 'code')->searchable()->preload(),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')->native(false),
                        \Filament\Forms\Components\DatePicker::make('to')->native(false),
                    ])
                    ->columns(2)
                    ->query(function (Builder $q, array $data) {
                        return $q->when($data['from'] ?? null, fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
                                 ->when($data['to'] ?? null, fn ($q, $d) => $q->whereDate('created_at', '<=', $d));
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(4)
            ->columns([
                Tables\Columns\TextColumn::make('channel')->badge()->color(fn ($s) => match ($s) {
                    'sms' => 'info', 'email' => 'primary', 'whatsapp' => 'success', default => 'gray',
                }),
                Tables\Columns\TextColumn::make('recipient')->searchable()->sortable()->copyable(),
                Tables\Columns\TextColumn::make('template.code')->label('Template')->placeholder('—')->badge()->color('gray'),
                Tables\Columns\TextColumn::make('content')->limit(60)->wrap()->toggleable(),
                Tables\Columns\TextColumn::make('status')->badge()->colors([
                    'gray' => 'queued', 'info' => 'sent', 'success' => 'delivered', 'danger' => 'failed',
                ]),
                Tables\Columns\TextColumn::make('sent_at')->dateTime('d-m-Y H:i')->sortable()->placeholder('—'),
                Tables\Columns\TextColumn::make('error')->limit(40)->wrap()->toggleable()->placeholder('—'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('markDelivered')->label('Mark delivered')->icon('heroicon-o-check')
                        ->visible(fn (NotificationLog $r) => $r->status === 'sent')
                        ->action(fn (NotificationLog $r) => $r->update(['status' => 'delivered'])),
                    Tables\Actions\Action::make('retry')->label('Retry')->icon('heroicon-o-arrow-path')
                        ->visible(fn (NotificationLog $r) => $r->status === 'failed')
                        ->action(fn (NotificationLog $r) => $r->update(['status' => 'queued', 'error' => null])),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->emptyStateHeading('No notifications yet')
            ->emptyStateDescription('System notifications (SMS/email/WhatsApp) will appear here.')
            ->emptyStateIcon('heroicon-o-clipboard-document-list')
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['recipient', 'content', 'error'];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListNotificationLogs::route('/'),
        ];
    }
}