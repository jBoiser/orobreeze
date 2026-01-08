<?php

namespace App\Filament\Resources\JobOrders;

// use App\Filament\Resources\JobOrders\Pages\CreateJobOrders;
// use App\Filament\Resources\JobOrders\Pages\EditJobOrders;
use App\Filament\Resources\JobOrders\Pages\ListJobOrders;
use App\Filament\Resources\JobOrders\Schemas\JobOrdersForm;
use App\Filament\Resources\JobOrders\Tables\JobOrdersTable;
use App\Models\JobOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Task\TaskCluster;
use UnitEnum;


class JobOrdersResource extends Resource
{
    protected static ?string $model = JobOrder::class;

    // protected static string | UnitEnum | null $navigationGroup = 'Task Management';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'JobOrders';

    protected static ?string $cluster = TaskCluster::class;

    public static function form(Schema $schema): Schema
    {
        return JobOrdersForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobOrdersTable::configure($table);
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
            'index' => ListJobOrders::route('/'),
            // 'create' => CreateJobOrders::route('/create'),
            // 'edit' => EditJobOrders::route('/{record}/edit'),
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
