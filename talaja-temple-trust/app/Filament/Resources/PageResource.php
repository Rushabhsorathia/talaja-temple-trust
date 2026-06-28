<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationGroup = 'Site Content';
    protected static ?string $navigationLabel = 'Pages & Sections';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Page identity')
                ->description('Slug must match the public route (home, about, history, ...).')
                ->schema([
                    Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true)
                        ->helperText('Routing key — matches the public route (home, about, history, ...)'),
                    Forms\Components\TextInput::make('route_name')->helperText('Optional Laravel route name (e.g. about).'),
                    Forms\Components\TextInput::make('title')->required(),
                    Forms\Components\TextInput::make('title_gu')->label('Title (Gujarati)'),
                    Forms\Components\Toggle::make('is_published')->default(true)->inline(false),
                ])->columns(2),

            Forms\Components\Section::make('SEO')->schema([
                Forms\Components\TextInput::make('meta_title'),
                Forms\Components\Textarea::make('meta_description')->rows(2),
                Forms\Components\TextInput::make('meta_image'),
            ])->columns(2)->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('title')
            ->deferFilters()
            ->filters([Tables\Filters\TernaryFilter::make('is_published')->label('Published')],
                layout: FiltersLayout::AboveContent)
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->weight('bold')->wrap()
                    ->description(fn (Page $r) => $r->slug),
                Tables\Columns\TextColumn::make('slug')->badge()->color('primary')->copyable(),
                Tables\Columns\TextColumn::make('sections_count')->counts('sections')->label('Sections')->badge()->color('info')->alignCenter(),
                Tables\Columns\ToggleColumn::make('is_published'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()->label('Edit & manage sections'),
                    Tables\Actions\Action::make('viewPublic')
                        ->label('Open on site')->icon('heroicon-o-arrow-top-right-on-square')
                        ->url(fn (Page $r) => $r->slug === 'home' ? '/' : ($r->route_name ? route($r->route_name) : url($r->slug)))
                        ->openUrlInNewTab()
                        ->visible(fn (Page $r) => $r->is_published),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('publish')->label('Publish selected')
                    ->icon('heroicon-o-eye')->color('success')
                    ->action(fn ($a) => $a->getRecords()->each->update(['is_published' => true])),
            ])])
            ->emptyStateHeading('No pages yet')
            ->emptyStateDescription('Add pages with editable sections (hero, intro, values, timeline, CTA, ...).')
            ->emptyStateIcon('heroicon-o-document-duplicate')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Create page')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SectionsRelationManager::class,
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'title_gu', 'slug'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit'   => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}