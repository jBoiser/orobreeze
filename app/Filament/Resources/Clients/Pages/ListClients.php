<?php

namespace App\Filament\Resources\Clients\Pages;

use App\Filament\Resources\Clients\ClientResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use PhpParser\Node\Stmt\Label;

class ListClients extends ListRecords
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Client')
                ->icon('heroicon-o-plus')
                ->modalHeading('Create New Client')
                ->createAnother(false),
        ];
    }
}
