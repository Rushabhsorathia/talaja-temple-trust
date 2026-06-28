<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationGroup = 'Configuration';
    protected static ?string $navigationLabel = 'Raw Settings (key/value)';
    protected static ?string $recordTitleAttribute = 'key';
    protected static ?int $navigationSort = 40;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Setting')->schema([
                Forms\Components\TextInput::make('key')->required()->unique(ignoreRecord: true)
                    ->helperText('Identifier (e.g. site_tagline). For typed site-wide settings use Site Settings instead.'),
                Forms\Components\Textarea::make('value')->rows(3)->columnSpanFull(),
                Forms\Components\TextInput::make('group')->default('general'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('group')
            ->deferFilters()
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options(fn () => Setting::query()->distinct()->pluck('group', 'group')->sort())
                    ->multiple(),
            ], layout: FiltersLayout::AboveContent)
            ->columns([
                Tables\Columns\TextColumn::make('group')->badge()->sortable()->searchable()->color('gray'),
                Tables\Columns\TextColumn::make('key')->searchable()->sortable()->weight('bold')->copyable(),
                Tables\Columns\TextColumn::make('value')->limit(80)->wrap()->searchable(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->emptyStateHeading('No raw settings')
            ->emptyStateDescription('Generic key/value settings. Prefer Site Settings for typed configuration.')
            ->emptyStateIcon('heroicon-o-adjustments-horizontal')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Add setting')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['key', 'value', 'group'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit'   => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}