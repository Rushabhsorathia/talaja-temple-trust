<?php

namespace App\Filament\Resources\Concerns;

use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;

/**
 * Provides a consistent row-action group (Edit + Delete) plus a helper for
 * a single "toggle boolean" action used across resources. Keeps every table
 * in the admin consistent without repeating boilerplate.
 */
trait HasStandardTableActions
{
    protected static function standardActions(): ActionGroup
    {
        return ActionGroup::make([
            EditAction::make(),
            DeleteAction::make(),
        ]);
    }

    protected static function standardBulkActions(): array
    {
        return [
            \Filament\Tables\Actions\DeleteBulkAction::make(),
        ];
    }
}