<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PublicationResource\Pages;
use App\Models\Publication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class PublicationResource extends Resource
{
    protected static ?string $model = Publication::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Site Content';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?int $navigationSort = 61;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Publication')->schema([
                Forms\Components\TextInput::make('title')->required(),
                Forms\Components\TextInput::make('title_gu')->label('Title (Gujarati)'),
                Forms\Components\Select::make('category')->options([
                    'Reports' => 'Reports', 'Guides' => 'Guides',
                    'Magazines' => 'Magazines', 'Forms' => 'Forms', 'Other' => 'Other',
                ])->default('Reports')->native(false)->createOptionUsing(fn ($v) => $v),
                Forms\Components\Toggle::make('is_active')->default(true)->inline(false),
            ])->columns(2),
            Forms\Components\Section::make('File')->schema([
                Forms\Components\FileUpload::make('file_path')->required()
                    ->acceptedFileTypes(['application/pdf'])
                    ->directory('publications')->preserveFilenames()
                    ->downloadable()->openable()
                    ->helperText('PDF only. Max 25 MB recommended.'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('title')
            ->deferFilters()
            ->filters([
                Tables\Filters\SelectFilter::make('category')->options([
                    'Reports' => 'Reports', 'Guides' => 'Guides',
                    'Magazines' => 'Magazines', 'Forms' => 'Forms', 'Other' => 'Other',
                ])->multiple(),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(2)
            ->headerActions([
                Tables\Actions\Action::make('viewPublic')
                    ->label('View Downloads')->icon('heroicon-o-arrow-top-right-on-square')
                    ->url('/downloads', shouldOpenInNewTab: true)->color('gray'),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->weight('bold')->wrap(),
                Tables\Columns\TextColumn::make('category')->badge()->color('info'),
                Tables\Columns\TextColumn::make('file_path')->label('File')->limit(30)->toggleable(),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('download')->label('Download')->icon('heroicon-o-arrow-down-tray')
                        ->url(fn (Publication $r) => asset('storage/'.$r->file_path), shouldOpenInNewTab: true),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->emptyStateHeading('No publications yet')
            ->emptyStateDescription('Upload reports, guides and other downloadable PDFs.')
            ->emptyStateIcon('heroicon-o-book-open')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Upload first PDF')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'category'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPublications::route('/'),
            'create' => Pages\CreatePublication::route('/create'),
            'edit'   => Pages\EditPublication::route('/{record}/edit'),
        ];
    }
}