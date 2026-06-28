<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Configuration';
    protected static ?string $navigationLabel = 'Site Settings';
    protected static ?string $modelLabel = 'Site setting';
    protected static ?string $recordTitleAttribute = 'key';
    protected static ?int $navigationSort = 50;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Setting')->schema([
                Forms\Components\Select::make('group')->required()
                    ->options([
                        'branding' => 'Branding',
                        'header'   => 'Header',
                        'footer'   => 'Footer',
                        'social'   => 'Social Links',
                        'contact'  => 'Contact',
                        'seo'      => 'SEO / Defaults',
                        'scripts'  => 'Custom scripts / head HTML',
                    ])->native(false)->searchable()->preload(),
                Forms\Components\TextInput::make('key')->required()->unique(ignoreRecord: true)
                    ->helperText('Identifier (e.g. site_tagline, footer_address, social_youtube).'),
                Forms\Components\Select::make('type')->options([
                    'text'     => 'Text',
                    'textarea' => 'Long text',
                    'boolean'  => 'Yes / No',
                    'json'     => 'JSON',
                    'image'    => 'Image path',
                ])->default('text')->live()->native(false),
            ])->columns(3),

            Forms\Components\Section::make('Value')->schema([
                Forms\Components\TextInput::make('value')
                    ->visible(fn (Forms\Get $get) => $get('type') === 'text'),
                Forms\Components\Textarea::make('value')->rows(3)
                    ->visible(fn (Forms\Get $get) => $get('type') === 'textarea'),
                Forms\Components\Toggle::make('value')
                    ->dehydrateStateUsing(fn ($state) => $state ? '1' : '0')
                    ->formatStateUsing(fn ($state) => (bool) $state)
                    ->visible(fn (Forms\Get $get) => $get('type') === 'boolean'),
                Forms\Components\Textarea::make('value')->rows(6)
                    ->helperText('JSON value (e.g. {"a":1})')
                    ->visible(fn (Forms\Get $get) => $get('type') === 'json'),
                Forms\Components\TextInput::make('value')
                    ->helperText('Path under /storage (e.g. temple/logo.jpg)')
                    ->visible(fn (Forms\Get $get) => $get('type') === 'image'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('group')
            ->deferFilters()
            ->filters([
                Tables\Filters\SelectFilter::make('group')->options([
                    'branding' => 'Branding', 'header' => 'Header', 'footer' => 'Footer',
                    'social' => 'Social Links', 'contact' => 'Contact', 'seo' => 'SEO / Defaults',
                    'scripts' => 'Custom scripts',
                ])->multiple(),
                Tables\Filters\SelectFilter::make('type')->options([
                    'text' => 'Text', 'textarea' => 'Long text', 'boolean' => 'Boolean', 'json' => 'JSON', 'image' => 'Image',
                ]),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(2)
            ->columns([
                Tables\Columns\TextColumn::make('group')->badge()->sortable()->searchable()->color(fn ($s) => match ($s) {
                    'branding' => 'primary', 'header' => 'info', 'footer' => 'gray',
                    'social' => 'warning', 'contact' => 'success', 'seo' => 'danger',
                    'scripts' => 'gray', default => 'gray',
                }),
                Tables\Columns\TextColumn::make('key')->searchable()->sortable()->weight('bold')->copyable(),
                Tables\Columns\TextColumn::make('value')->limit(60)->wrap()->searchable(),
                Tables\Columns\TextColumn::make('type')->badge()->color('gray'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->emptyStateHeading('No site settings yet')
            ->emptyStateDescription('Configure branding, header, footer, social links and contact info from one place.')
            ->emptyStateIcon('heroicon-o-cog-6-tooth')
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
            'index'  => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit'   => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}