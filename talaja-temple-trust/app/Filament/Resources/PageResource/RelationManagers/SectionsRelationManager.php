<?php

namespace App\Filament\Resources\PageResource\RelationManagers;

use App\Models\PageSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'sections';
    protected static ?string $title = 'Page sections';
    protected static ?string $icon = 'heroicon-o-squares-2x2';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Hidden::make('page_id')->default(fn () => $this->ownerRecord->id),

            Forms\Components\Section::make('Section identity')->schema([
                Forms\Components\TextInput::make('section_key')->required()
                    ->helperText('Unique key on this page (hero, intro, values, ...)'),
                Forms\Components\Select::make('type')
                    ->options(PageSection::TYPES)
                    ->required()->live()
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('data', []))
                    ->native(false),
            ])->columns(2),

            Forms\Components\Section::make('Heading')->schema([
                Forms\Components\TextInput::make('title'),
                Forms\Components\TextInput::make('subtitle'),
            ])->columns(2),

            Forms\Components\Section::make('Content')->schema([
                Forms\Components\RichEditor::make('content')
                    ->helperText('Free-form rich text for richtext, cta, contact_block & timings sections.')
                    ->columnSpanFull(),
            ]),

            // Cards / stats / values / timeline payload
            Forms\Components\Section::make('Items (cards / stats / values / timeline)')
                ->description('Add as many rows as needed. Keys are internal labels; values become the entry.')
                ->schema([
                    Forms\Components\KeyValue::make('data.items')->columnSpanFull(),
                ])->visible(fn (Forms\Get $get) => in_array($get('type'), ['cards', 'stats', 'values', 'timeline'])),

            Forms\Components\Section::make('Slides')->schema([
                Forms\Components\Repeater::make('data.slides')
                    ->schema([
                        Forms\Components\TextInput::make('title')->required(),
                        Forms\Components\TextInput::make('subtitle'),
                        Forms\Components\TextInput::make('tag')->helperText('e.g. || Jay Mataji ||'),
                        Forms\Components\FileUpload::make('image_path')->image()
                            ->imagePreviewHeight('100')->directory('slides')->panelLayout('integrated'),
                        Forms\Components\TextInput::make('button_label'),
                        Forms\Components\TextInput::make('button_href'),
                    ])->columns(2)->columnSpanFull()
                    ->addActionLabel('Add slide'),
            ])->visible(fn (Forms\Get $get) => $get('type') === 'hero_slider'),

            Forms\Components\Section::make('CTA')->schema([
                Forms\Components\TextInput::make('data.cta_label'),
                Forms\Components\TextInput::make('data.cta_href'),
            ])->columns(2)->visible(fn (Forms\Get $get) => $get('type') === 'cta'),

            Forms\Components\Section::make('Display')->schema([
                Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                Forms\Components\Toggle::make('is_active')->default(true)->inline(false),
            ])->columns(2),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('section_key')
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')->label('#')->sortable()->alignCenter(),
                Tables\Columns\TextColumn::make('section_key')->badge()->color('primary')->weight('bold'),
                Tables\Columns\TextColumn::make('type')->badge()->color('info'),
                Tables\Columns\TextColumn::make('title')->limit(50)->placeholder('—')->searchable()->wrap(),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Add section')->icon('heroicon-o-plus'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('moveUp')->icon('heroicon-o-arrow-up')
                        ->action(fn (PageSection $record) => $this->move($record, -1)),
                    Tables\Actions\Action::make('moveDown')->icon('heroicon-o-arrow-down')
                        ->action(fn (PageSection $record) => $this->move($record, 1)),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->emptyStateHeading('No sections yet')
            ->emptyStateDescription('Add sections (hero, intro, values, timeline, CTA) to control this page.')
            ->emptyStateIcon('heroicon-o-squares-2x2')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Add first section')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    protected function move(PageSection $record, int $delta): void
    {
        $record->sort_order = max(0, $record->sort_order + $delta);
        $record->save();
    }
}