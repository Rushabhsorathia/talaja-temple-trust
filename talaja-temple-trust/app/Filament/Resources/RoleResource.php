<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static ?string $model = \Spatie\Permission\Models\Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'Users & Access';
    protected static ?string $navigationLabel = 'Roles & Permissions';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Role')->schema([
                Forms\Components\TextInput::make('name')->required()->unique(ignoreRecord: true)
                    ->helperText('e.g. "Content Editor", "Donation Officer".'),
                Forms\Components\Hidden::make('guard_name')->default('web'),
            ])->columns(1),
            Forms\Components\Section::make('Permissions')
                ->description('Pick the actions this role can perform. Use search to find permissions quickly.')
                ->schema([
                    Forms\Components\CheckboxList::make('permissions')
                        ->relationship('permissions', 'name')
                        ->columns(3)->searchable()->bulkToggleable()
                        ->helperText('Toggle a row to grant/deny a permission for this role.'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('permissions_count')->counts('permissions')->label('Permissions')->badge()->color('info'),
                Tables\Columns\TextColumn::make('users_count')->counts('users')->label('Users')->badge()->color('primary'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d-m-Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->before(function (\Spatie\Permission\Models\Role $record) {
                            if ($record->name === 'Super Admin') {
                                throw new \Exception('Cannot delete the Super Admin role.');
                            }
                        }),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->emptyStateHeading('No roles yet')
            ->emptyStateDescription('Create roles to grant granular permissions to admin users.')
            ->emptyStateIcon('heroicon-o-shield-check')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Create role')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit'   => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}