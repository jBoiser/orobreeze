<?php

namespace App\Filament\Resources\JobOrders\Pages;

use App\Filament\Resources\JobOrders\JobOrdersResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJobOrders extends ListRecords
{
    protected static string $resource = JobOrdersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
             ->label('New Job Order')
                ->icon('heroicon-o-plus')
                ->modalHeading('Create New Job Order')
                ->createAnother(false),
        ];
    }
}
