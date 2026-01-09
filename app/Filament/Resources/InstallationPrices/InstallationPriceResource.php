<?php

namespace App\Filament\Resources\InstallationPrices;
use App\Filament\Clusters\AcProducts\AcProductsCluster;
use App\Filament\Resources\InstallationPrices\Pages\ListInstallationPrices;
use App\Filament\Resources\InstallationPrices\Schemas\InstallationPriceForm;
use App\Filament\Resources\InstallationPrices\Tables\InstallationPricesTable;
use App\Models\InstallationPrice;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InstallationPriceResource extends Resource
{
    protected static ?string $model = InstallationPrice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    protected static ?string $navigationLabel = 'Installation Price List';

    protected static ?string $cluster = AcProductsCluster::class;

     protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return InstallationPriceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InstallationPricesTable::configure($table);
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
            'index' => ListInstallationPrices::route('/'),
            // 'create' => CreateInstallationPrice::route('/create'),
            // 'edit' => EditInstallationPrice::route('/{record}/edit'),
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
