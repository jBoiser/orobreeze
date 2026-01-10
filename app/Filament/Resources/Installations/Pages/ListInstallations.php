<?php

namespace App\Filament\Resources\Installations\Pages;

use App\Filament\Resources\Installations\InstallationsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInstallations extends ListRecords
{
    protected static string $resource = InstallationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Installation')
                ->icon('heroicon-o-plus')
                ->modalHeading('Create New Installation')
                ->createAnother(false)
                ->slideOver()
        ];
    }
}
