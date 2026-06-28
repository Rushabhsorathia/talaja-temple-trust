<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeedbackResource\Pages;
use App\Models\Feedback;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Communication';
    protected static ?string $navigationLabel = 'Feedback';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 30;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Submission')->schema([
                Forms\Components\Select::make('type')->options([
                    'suggestion' => 'Suggestion', 'feedback' => 'Feedback', 'complaint' => 'Complaint',
                ])->required()->native(false),
                Forms\Components\TextInput::make('category'),
                Forms\Components\TextInput::make('rating')->numeric()->minValue(1)->maxValue(5)->suffix('★'),
            ])->columns(3),
            Forms\Components\Section::make('Contact')->schema([
                Forms\Components\TextInput::make('name'),
                Forms\Components\TextInput::make('email')->email(),
                Forms\Components\TextInput::make('mobile'),
            ])->columns(3),
            Forms\Components\Section::make('Message')->schema([
                Forms\Components\Textarea::make('message')->required()->rows(4)->columnSpanFull(),
            ]),
            Forms\Components\Section::make('Admin response & status')->schema([
                Forms\Components\Select::make('status')->options([
                    'open' => 'Open', 'in_progress' => 'In Progress', 'closed' => 'Closed', 'spam' => 'Spam',
                ])->required()->default('open')->native(false),
                Forms\Components\Select::make('assigned_to')->relationship('assignee', 'name')
                    ->label('Assign to')->searchable()->preload()->native(false),
                Forms\Components\Textarea::make('admin_reply')->rows(3)->columnSpanFull()
                    ->helperText('Reply shown to the user on the public site.'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->deferFilters()
            ->filters([
                Tables\Filters\SelectFilter::make('type')->options([
                    'suggestion' => 'Suggestion', 'feedback' => 'Feedback', 'complaint' => 'Complaint',
                ])->multiple(),
                Tables\Filters\SelectFilter::make('status')->options([
                    'open' => 'Open', 'in_progress' => 'In Progress', 'closed' => 'Closed', 'spam' => 'Spam',
                ])->multiple(),
                Tables\Filters\SelectFilter::make('assigned_to')->relationship('assignee', 'name')->searchable()->preload(),
                Tables\Filters\Filter::make('rating')
                    ->form([Forms\Components\TextInput::make('rating')->numeric()])
                    ->query(fn ($q, $data) => $data['rating'] ? $q->where('rating', $data['rating']) : $q),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(4)
            ->columns([
                Tables\Columns\TextColumn::make('type')->badge()->color(fn ($s) => match ($s) {
                    'suggestion' => 'info', 'feedback' => 'success', 'complaint' => 'danger', default => 'gray',
                }),
                Tables\Columns\TextColumn::make('name')->searchable()->placeholder('—')->wrap()
                    ->description(fn (Feedback $r) => $r->email ?: $r->mobile),
                Tables\Columns\TextColumn::make('category')->badge()->color('gray')->placeholder('—'),
                Tables\Columns\TextColumn::make('rating')->suffix('★')->alignCenter()->badge()->color('warning')->placeholder('—'),
                Tables\Columns\TextColumn::make('message')->limit(60)->wrap()->toggleable(),
                Tables\Columns\TextColumn::make('assignee.name')->label('Assigned to')->placeholder('—')->toggleable(),
                Tables\Columns\TextColumn::make('status')->badge()->colors([
                    'warning' => 'open', 'primary' => 'in_progress', 'success' => 'closed', 'danger' => 'spam',
                ]),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d-m-Y H:i')->sortable(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('markClosed')->label('Close')->icon('heroicon-o-check-circle')
                        ->visible(fn (Feedback $r) => $r->status !== 'closed')
                        ->action(fn (Feedback $r) => $r->update(['status' => 'closed']))
                        ->requiresConfirmation(),
                    Tables\Actions\Action::make('markSpam')->label('Mark spam')->icon('heroicon-o-shield-exclamation')
                        ->visible(fn (Feedback $r) => $r->status !== 'spam')
                        ->action(fn (Feedback $r) => $r->update(['status' => 'spam']))
                        ->requiresConfirmation()->color('danger'),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('markClosed')->label('Close selected')
                    ->icon('heroicon-o-check-circle')->color('success')
                    ->action(fn ($a) => $a->getRecords()->each->update(['status' => 'closed'])),
            ])])
            ->emptyStateHeading('No feedback yet')
            ->emptyStateDescription('Suggestions, feedback and complaints from the public site appear here.')
            ->emptyStateIcon('heroicon-o-chat-bubble-left-right')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Log feedback manually')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'mobile', 'message', 'admin_reply'];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'open')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFeedback::route('/'),
            'create' => Pages\CreateFeedback::route('/create'),
            'edit'   => Pages\EditFeedback::route('/{record}/edit'),
        ];
    }
}