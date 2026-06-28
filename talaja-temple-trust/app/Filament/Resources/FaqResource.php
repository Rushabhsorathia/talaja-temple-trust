<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationGroup = 'Site Content';
    protected static ?string $recordTitleAttribute = 'question';
    protected static ?int $navigationSort = 62;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Question & Answer')->schema([
                Forms\Components\Select::make('category')->options([
                    'General' => 'General', 'Donations' => 'Donations',
                    'Accommodation' => 'Accommodation', 'Darshan' => 'Darshan', 'Other' => 'Other',
                ])->default('General')->native(false)->createOptionUsing(fn ($v) => $v),
                Forms\Components\TextInput::make('question')->required()->columnSpanFull(),
                Forms\Components\Textarea::make('answer')->required()->rows(3)->columnSpanFull(),
                Forms\Components\TextInput::make('question_gu')->label('Question (Gujarati)')->columnSpanFull(),
                Forms\Components\Textarea::make('answer_gu')->label('Answer (Gujarati)')->rows(3)->columnSpanFull(),
            ])->columns(2),
            Forms\Components\Section::make('Display')->schema([
                Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                Forms\Components\Toggle::make('is_active')->default(true)->inline(false),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->deferFilters()
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(fn () => Faq::query()->whereNotNull('category')->distinct()->pluck('category', 'category')->sort())
                    ->multiple(),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(2)
            ->headerActions([
                Tables\Actions\Action::make('viewPublic')
                    ->label('View FAQs page')->icon('heroicon-o-arrow-top-right-on-square')
                    ->url('/faqs', shouldOpenInNewTab: true)->color('gray'),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('question')->searchable()->sortable()->wrap()->weight('medium')
                    ->limit(60),
                Tables\Columns\TextColumn::make('category')->badge()->color('info'),
                Tables\Columns\TextColumn::make('sort_order')->sortable()->alignCenter(),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->emptyStateHeading('No FAQs yet')
            ->emptyStateDescription('Add frequently asked questions shown on the public FAQs page.')
            ->emptyStateIcon('heroicon-o-question-mark-circle')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Add first FAQ')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['question', 'answer', 'category'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit'   => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}