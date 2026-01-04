<?php

namespace App\Filament\Resources\Installations;

use App\Filament\Resources\Installations\Pages\CreateInstallations;
use App\Filament\Resources\Installations\Pages\EditInstallations;
use App\Filament\Resources\Installations\Pages\ListInstallations;
use App\Filament\Resources\Installations\Schemas\InstallationsForm;
use App\Filament\Resources\Installations\Tables\InstallationsTable;
use App\Models\Installation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InstallationsResource extends Resource
{
    protected static ?string $model = Installation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Installation';

    public static function form(Schema $schema): Schema
    {
        return InstallationsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InstallationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInstallations::route('/'),
            'create' => CreateInstallations::route('/create'),
            'edit' => EditInstallations::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
