<?php

namespace App\Filament\Resources\InstallationPrices\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class InstallationPriceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('installation_type')
                    ->options([
                        'Residential' => 'Residential',
                        'Commercial' => 'Commercial/Mall',
                        'Industrial' => 'Industrial',
                    ])
                    ->required(),
                TextInput::make('hp_capacity')
                    ->required(),
                TextInput::make('price')
                    ->label('Installation Cost')
                    ->prefix('₱')
                    ->extraAlpineAttributes([
                        'x-mask:dynamic' => '$money($input, \'.\', \',\', 2)',
                    ])
                    ->dehydrateStateUsing(fn($state) => str_replace(',', '', $state))
                    ->formatStateUsing(fn($state) => number_format((float) $state, 2, '.', ','))
                    ->required()
                    ->extraInputAttributes([
                        'onfocus' => 'this.select()',
                    ])
                    ->required(),
            ])->columns(1);
    }
}
