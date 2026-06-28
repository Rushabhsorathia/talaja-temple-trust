<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CmsPageResource\Pages;
use App\Models\CmsPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Support\Str;

class CmsPageResource extends Resource
{
    protected static ?string $model = CmsPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Site Content';
    protected static ?string $navigationLabel = 'CMS Pages';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?int $navigationSort = 70;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Page')->schema([
                Forms\Components\TextInput::make('title')->required()->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),
                Forms\Components\TextInput::make('title_gu')->label('Title (Gujarati)'),
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true)
                    ->helperText('URL key, e.g. privacy, terms.'),
                Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                Forms\Components\Toggle::make('is_published')->default(true)->inline(false),
            ])->columns(2),
            Forms\Components\Section::make('Content (English & Gujarati)')->schema([
                Forms\Components\RichEditor::make('content')->columnSpanFull(),
                Forms\Components\RichEditor::make('content_gu')->label('Content (Gujarati)')->columnSpanFull(),
            ]),
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
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->deferFilters()
            ->filters([Tables\Filters\TernaryFilter::make('is_published')->label('Published')],
                layout: FiltersLayout::AboveContent)
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->weight('bold')->wrap()
                    ->description(fn (CmsPage $r) => \Illuminate\Support\Str::limit(strip_tags($r->content ?? ''), 80)),
                Tables\Columns\TextColumn::make('slug')->badge()->color('primary')->copyable(),
                Tables\Columns\TextColumn::make('sort_order')->sortable()->alignCenter(),
                Tables\Columns\ToggleColumn::make('is_published'),
                Tables\Columns\TextColumn::make('updated_at')->dateTime('d-m-Y')->sortable()->placeholder('—')->toggleable(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('viewPublic')->label('View on site')->icon('heroicon-o-arrow-top-right-on-square')
                        ->url(fn (CmsPage $r) => "/page/{$r->slug}", shouldOpenInNewTab: true)
                        ->visible(fn (CmsPage $r) => $r->is_published),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('publish')->label('Publish selected')
                    ->icon('heroicon-o-eye')->color('success')
                    ->action(fn ($a) => $a->getRecords()->each->update(['is_published' => true])),
            ])])
            ->emptyStateHeading('No CMS pages yet')
            ->emptyStateDescription('Add standalone pages (privacy, terms, etc.) accessible via /page/{slug}.')
            ->emptyStateIcon('heroicon-o-document-text')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Create page')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug', 'content'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCmsPages::route('/'),
            'create' => Pages\CreateCmsPage::route('/create'),
            'edit'   => Pages\EditCmsPage::route('/{record}/edit'),
        ];
    }
}