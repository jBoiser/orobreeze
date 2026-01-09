<?php

namespace App\Filament\Resources\Brands;

use App\Filament\Clusters\AcProducts\AcProductsCluster;
use App\Filament\Resources\Brands\Pages\ListBrands;
use App\Filament\Resources\Brands\Schemas\BrandForm;
use App\Filament\Resources\Brands\Tables\BrandsTable;
use App\Models\Brand;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    // protected static string | UnitEnum | null $navigationGroup = 'Product Management';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquaresPlus;

       protected static ?string $navigationLabel = 'Brand List';

    protected static ?string $recordTitleAttribute = 'brand';

     protected static ?int $navigationSort = 1;

    protected static ?string $cluster = AcProductsCluster::class;

    public static function form(Schema $schema): Schema
    {
        return BrandForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BrandsTable::configure($table);
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
            'index' => ListBrands::route('/'),
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
