<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Models\News;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Support\Str;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Site Content';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?int $navigationSort = 40;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Article')
                ->description('Title and content shown on the public News page.')
                ->schema([
                    Forms\Components\TextInput::make('title')->required()->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),
                    Forms\Components\TextInput::make('title_gu')->label('Title (Gujarati)'),
                    Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true)
                        ->helperText('Auto-generated from title — used in the URL.'),
                    Forms\Components\Select::make('category')->options([
                        'event' => 'Event', 'announcement' => 'Announcement',
                        'festival' => 'Festival', 'general' => 'General',
                    ])->default('general')->native(false),
                    Forms\Components\TagsInput::make('tags')->separator(',')->placeholder('Add tag'),
                ])->columns(2),

            Forms\Components\Section::make('Content (English & Gujarati)')->schema([
                Forms\Components\RichEditor::make('excerpt')->columnSpanFull()
                    ->helperText('Short summary shown in the news cards.'),
                Forms\Components\RichEditor::make('content')->required()->columnSpanFull(),
                Forms\Components\RichEditor::make('content_gu')->label('Content (Gujarati)')->columnSpanFull(),
            ]),

            Forms\Components\Section::make('Featured image')->schema([
                Forms\Components\FileUpload::make('image_path')->image()
                    ->imagePreviewHeight('200')->directory('news')
                    ->panelAspectRatio('16:9')->panelLayout('integrated')
                    ->helperText('Recommended 16:9, ≥ 800px wide.'),
            ]),

            Forms\Components\Section::make('Publishing & SEO')->schema([
                Forms\Components\Toggle::make('is_published')->default(true)->inline(false)
                    ->helperText('Toggle off to hide this article from the public site.'),
                Forms\Components\DateTimePicker::make('published_at')->default(now()),
                Forms\Components\TextInput::make('meta_title')->helperText('Overrides the page <title>.'),
                Forms\Components\Textarea::make('meta_description')->rows(2),
            ])->columns(2)->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->deferFilters()
            ->filters([
                Tables\Filters\SelectFilter::make('category')->options([
                    'event' => 'Event', 'announcement' => 'Announcement',
                    'festival' => 'Festival', 'general' => 'General',
                ])->multiple(),
                Tables\Filters\TernaryFilter::make('is_published')->label('Published'),
                Tables\Filters\Filter::make('published_at')
                    ->label('Published between')
                    ->form([
                        Forms\Components\DatePicker::make('from')->native(false),
                        Forms\Components\DatePicker::make('to')->native(false),
                    ])
                    ->columns(2)
                    ->query(function ($q, array $data) {
                        return $q->when($data['from'] ?? null, fn ($q, $d) => $q->whereDate('published_at', '>=', $d))
                                 ->when($data['to'] ?? null, fn ($q, $d) => $q->whereDate('published_at', '<=', $d));
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->headerActions([
                Tables\Actions\Action::make('viewPublic')
                    ->label('View News page')->icon('heroicon-o-arrow-top-right-on-square')
                    ->url('/news-and-updates', shouldOpenInNewTab: true)->color('gray'),
            ])
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')->square()->label(''),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->limit(50)->wrap()->weight('bold')
                    ->description(fn (News $r) => $r->excerpt ? \Illuminate\Support\Str::limit(strip_tags($r->excerpt), 60) : null),
                Tables\Columns\TextColumn::make('category')->badge()->colors([
                    'primary' => 'event', 'success' => 'announcement',
                    'warning' => 'festival', 'gray' => 'general',
                ]),
                Tables\Columns\ToggleColumn::make('is_published')->label('Published'),
                Tables\Columns\TextColumn::make('published_at')->dateTime('d-m-Y H:i')->sortable()->placeholder('—'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('viewPublic')
                        ->label('Open on site')->icon('heroicon-o-arrow-top-right-on-square')
                        ->url(fn (News $record) => "/view-news/{$record->slug}", shouldOpenInNewTab: true),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('publish')->label('Publish')
                        ->icon('heroicon-o-eye')->color('success')
                        ->action(fn ($action) => $action->getRecords()->each->update(['is_published' => true])),
                    Tables\Actions\BulkAction::make('unpublish')->label('Unpublish')
                        ->icon('heroicon-o-eye-slash')->color('warning')
                        ->action(fn ($action) => $action->getRecords()->each->update(['is_published' => false])),
                ]),
            ])
            ->emptyStateHeading('No articles yet')
            ->emptyStateDescription('Articles you publish here appear on the public News & Updates page.')
            ->emptyStateIcon('heroicon-o-newspaper')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Write first article')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'title_gu', 'slug', 'excerpt', 'content'];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_published', false)->count() ?: null;
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit'   => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}