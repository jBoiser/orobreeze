<?php

namespace App\Filament\Resources\InstallationPrices\Pages;

use App\Filament\Resources\InstallationPrices\InstallationPriceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInstallationPrices extends ListRecords
{
    protected static string $resource = InstallationPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Installation Price')
                ->icon('heroicon-o-plus')
                ->modalHeading('Create New Installation Prices')
                ->createAnother(false)
                ->modalWidth('xl'),
        ];
    }
}
