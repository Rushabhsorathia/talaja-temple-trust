<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrusteeResource\Pages;
use App\Models\Trustee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class TrusteeResource extends Resource
{
    protected static ?string $model = Trustee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Site Content';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 52;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Trustee')->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('designation')->required(),
                Forms\Components\TextInput::make('designation_gu')->label('Designation (Gujarati)'),
            ])->columns(3),
            Forms\Components\Section::make('Bio (English & Gujarati)')->schema([
                Forms\Components\Textarea::make('bio')->rows(3)->columnSpanFull(),
                Forms\Components\Textarea::make('bio_gu')->label('Bio (Gujarati)')->rows(3)->columnSpanFull(),
            ]),
            Forms\Components\Section::make('Photo & display')->schema([
                Forms\Components\FileUpload::make('photo_path')->image()
                    ->imagePreviewHeight('160')->directory('trustees')->circleCrop()->panelLayout('integrated')
                    ->helperText('Square photo recommended.'),
                Forms\Components\Select::make('temple_id')->relationship('temple', 'name')->native(false),
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
            ->filters([Tables\Filters\TernaryFilter::make('is_active')->label('Active')],
                layout: FiltersLayout::AboveContent)
            ->headerActions([
                Tables\Actions\Action::make('viewPublic')
                    ->label('View Trustees page')->icon('heroicon-o-arrow-top-right-on-square')
                    ->url('/trustees', shouldOpenInNewTab: true)->color('gray'),
            ])
            ->columns([
                Tables\Columns\ImageColumn::make('photo_path')->circular()->height(48)
                    ->defaultImageUrl(fn (Trustee $r) => 'https://ui-avatars.com/api/?name='.urlencode($r->name).'&background=fff7ed&color=c74808&size=128')
                    ->label(''),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('designation')->badge()->color('primary'),
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
            ->emptyStateHeading('No trustees yet')
            ->emptyStateDescription('Add board members and stewards shown on the public Trustees page.')
            ->emptyStateIcon('heroicon-o-user-group')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Add trustee')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'designation', 'bio'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTrustees::route('/'),
            'create' => Pages\CreateTrustee::route('/create'),
            'edit'   => Pages\EditTrustee::route('/{record}/edit'),
        ];
    }
}