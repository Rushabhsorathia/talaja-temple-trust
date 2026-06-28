<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationTemplateResource\Pages;
use App\Models\NotificationTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class NotificationTemplateResource extends Resource
{
    protected static ?string $model = NotificationTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationGroup = 'Communication';
    protected static ?string $navigationLabel = 'Templates';
    protected static ?string $recordTitleAttribute = 'code';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Template')->schema([
                Forms\Components\TextInput::make('code')->required()->unique(ignoreRecord: true)
                    ->helperText('Stable identifier, e.g. donation_success, booking_confirm.'),
                Forms\Components\Select::make('channel')->options([
                    'sms' => 'SMS', 'email' => 'Email', 'whatsapp' => 'WhatsApp',
                ])->required()->native(false),
                Forms\Components\TextInput::make('subject')
                    ->helperText('Used for email subject. Leave blank for SMS/WhatsApp.'),
            ])->columns(3),
            Forms\Components\Section::make('Body (English & Gujarati)')->schema([
                Forms\Components\Textarea::make('body')->required()->rows(5)->columnSpanFull()
                    ->helperText('Use {placeholders} like {name}, {amount}, {receipt}.'),
                Forms\Components\Textarea::make('body_gu')->label('Body (Gujarati)')->rows(5)->columnSpanFull(),
            ]),
            Forms\Components\Section::make('Variables & settings')->schema([
                Forms\Components\TagsInput::make('variables')->placeholder('{name}')->helperText('List of variables used in the body.'),
                Forms\Components\Toggle::make('is_active')->default(true)->inline(false),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('code')
            ->deferFilters()
            ->filters([
                Tables\Filters\SelectFilter::make('channel')->options([
                    'sms' => 'SMS', 'email' => 'Email', 'whatsapp' => 'WhatsApp',
                ])->multiple(),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(2)
            ->columns([
                Tables\Columns\TextColumn::make('code')->searchable()->sortable()->weight('bold')->copyable(),
                Tables\Columns\TextColumn::make('channel')->badge()->color(fn ($s) => match ($s) {
                    'sms' => 'info', 'email' => 'primary', 'whatsapp' => 'success', default => 'gray',
                }),
                Tables\Columns\TextColumn::make('subject')->limit(40)->placeholder('—'),
                Tables\Columns\TextColumn::make('body')->limit(60)->wrap()->toggleable(),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->emptyStateHeading('No templates yet')
            ->emptyStateDescription('Add message templates for SMS, email and WhatsApp notifications.')
            ->emptyStateIcon('heroicon-o-envelope')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Create template')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['code', 'subject', 'body'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListNotificationTemplates::route('/'),
            'create' => Pages\CreateNotificationTemplate::route('/create'),
            'edit'   => Pages\EditNotificationTemplate::route('/{record}/edit'),
        ];
    }
}