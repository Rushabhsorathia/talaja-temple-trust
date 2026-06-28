<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LiveDarshanConfigResource\Pages;
use App\Models\LiveDarshanConfig;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LiveDarshanConfigResource extends Resource
{
    protected static ?string $model = LiveDarshanConfig::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationGroup = 'Configuration';
    protected static ?string $navigationLabel = 'Live Darshan';
    protected static ?string $recordTitleAttribute = 'stream_url';
    protected static ?int $navigationSort = 30;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Stream')->schema([
                Forms\Components\TextInput::make('stream_url')->required()->url()
                    ->helperText('YouTube / Vimeo embed URL.'),
                Forms\Components\FileUpload::make('poster_path')->image()
                    ->imagePreviewHeight('160')->directory('live-darshan')
                    ->panelAspectRatio('16:9')->panelLayout('integrated'),
            ])->columns(1),
            Forms\Components\Section::make('Schedule & visibility')->schema([
                Forms\Components\TimePicker::make('start_time')->native(false)->helperText('When the stream goes live (optional).'),
                Forms\Components\TimePicker::make('end_time')->native(false)->helperText('When the stream goes offline (optional).'),
                Forms\Components\Toggle::make('is_live')->default(false)->inline(false)
                    ->helperText('Set ON to show the LIVE indicator on the public site.'),
                Forms\Components\Select::make('temple_id')->relationship('temple', 'name')->native(false),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('poster_path')->square()->height(40)->label(''),
                Tables\Columns\TextColumn::make('stream_url')->limit(50)->wrap()->searchable(),
                Tables\Columns\ToggleColumn::make('is_live')->label('Live now'),
                Tables\Columns\TextColumn::make('start_time')->time('H:i')->placeholder('—')->alignCenter(),
                Tables\Columns\TextColumn::make('end_time')->time('H:i')->placeholder('—')->alignCenter(),
                Tables\Columns\TextColumn::make('temple.name')->placeholder('—')->toggleable(),
            ])
            ->filters([Tables\Filters\TernaryFilter::make('is_live')->label('Live now')])
            ->headerActions([
                Tables\Actions\Action::make('viewPublic')
                    ->label('View Live Darshan')->icon('heroicon-o-arrow-top-right-on-square')
                    ->url('/live-darshan', shouldOpenInNewTab: true)->color('gray'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('goLive')->label('Go live')->icon('heroicon-o-broadcast')
                        ->visible(fn (LiveDarshanConfig $r) => ! $r->is_live)
                        ->action(fn (LiveDarshanConfig $r) => $r->update(['is_live' => true]))
                        ->requiresConfirmation(),
                    Tables\Actions\Action::make('stopLive')->label('Stop live')->icon('heroicon-o-stop')
                        ->visible(fn (LiveDarshanConfig $r) => $r->is_live)
                        ->action(fn (LiveDarshanConfig $r) => $r->update(['is_live' => false]))
                        ->requiresConfirmation()->color('danger'),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->emptyStateHeading('No stream configured')
            ->emptyStateDescription('Configure the live darshan stream URL and poster.')
            ->emptyStateIcon('heroicon-o-video-camera')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Configure stream')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLiveDarshanConfigs::route('/'),
            'create' => Pages\CreateLiveDarshanConfig::route('/create'),
            'edit'   => Pages\EditLiveDarshanConfig::route('/{record}/edit'),
        ];
    }
}