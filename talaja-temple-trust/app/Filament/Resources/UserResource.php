<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Users & Access';
    protected static ?string $navigationLabel = 'Admin Users';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 10;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereIn('type', ['admin', 'staff', 'trustee']);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Profile')
                ->description('Name and contact info shown across the admin.')
                ->schema([
                    Forms\Components\TextInput::make('name')->required()->maxLength(120),
                    Forms\Components\TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('mobile')->tel()->helperText('Login + SMS notifications.'),
                ])->columns(2),

            Forms\Components\Section::make('Access')
                ->description('Type drives sidebar visibility, roles drive granular permissions.')
                ->schema([
                    Forms\Components\Select::make('type')->required()
                        ->options([
                            'admin'   => 'Admin',
                            'staff'   => 'Staff',
                            'trustee' => 'Trustee',
                        ])->default('staff')->native(false),
                    Forms\Components\Select::make('roles')
                        ->multiple()->relationship('roles', 'name')
                        ->options(Role::pluck('name', 'id'))->preload()->searchable()
                        ->helperText('Spatie permission roles. Super Admin bypasses all gates.'),
                    Forms\Components\Toggle::make('is_active')->default(true)
                        ->inline(false)
                        ->helperText('Inactive users cannot log in to the admin panel.'),
                ])->columns(2),

            Forms\Components\Section::make('Security')->schema([
                Forms\Components\TextInput::make('password')
                    ->password()->revealable()
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context) => $context === 'create')
                    ->helperText(fn (string $context) => $context === 'edit' ? 'Leave blank to keep current password.' : null),
            ])->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->deferFilters()
            ->filters([
                SelectFilter::make('type')->options([
                    'admin' => 'Admin', 'staff' => 'Staff', 'trustee' => 'Trustee',
                ])->multiple(),
                SelectFilter::make('roles')->relationship('roles', 'name')->multiple(),
                TernaryFilter::make('is_active')->label('Active'),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->weight('bold')
                    ->description(fn (User $r) => $r->email),
                Tables\Columns\TextColumn::make('mobile')->placeholder('—'),
                Tables\Columns\TextColumn::make('type')->badge()->color(fn ($s) => match ($s) {
                    'admin' => 'success', 'trustee' => 'warning', 'staff' => 'info', default => 'gray',
                }),
                Tables\Columns\TextColumn::make('roles.name')->badge()->separator(',')->wrap()->placeholder('—'),
                Tables\Columns\ToggleColumn::make('is_active'),
                Tables\Columns\TextColumn::make('last_login_at')->dateTime('d-m-Y H:i')->placeholder('Never')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d-m-Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('toggleActive')
                        ->icon('heroicon-o-power')
                        ->label(fn (User $r) => $r->is_active ? 'Deactivate' : 'Activate')
                        ->color(fn (User $r) => $r->is_active ? 'warning' : 'success')
                        ->action(fn (User $record) => $record->update(['is_active' => ! $record->is_active]))
                        ->requiresConfirmation(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('activate')->label('Activate selected')
                    ->icon('heroicon-o-check')->color('success')
                    ->action(fn ($a) => $a->getRecords()->each->update(['is_active' => true])),
            ])])
            ->emptyStateHeading('No admin users yet')
            ->emptyStateDescription('Create the first user to give your team access to the admin panel.')
            ->emptyStateIcon('heroicon-o-users')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Add first user')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'mobile'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}