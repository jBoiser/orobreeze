<?php

namespace App\Filament\Resources\JobOrders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;

class JobOrdersForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Job Order')
                            ->description('Generate Job Order Details')
                            ->schema([
                                TextInput::make('jo_number')
                                    ->label('JO Number')
                                    ->placeholder('Generated automatically')
                                    ->disabled()
                                    ->dehydrated(false), // Handled by Model booting logic

                                Select::make('client_id')
                                    ->relationship('client', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                DatePicker::make('receive_date')
                                    ->required()
                                    ->default(now())
                                    ->native(false),

                                TextInput::make('project_cost')
                                    ->label('Total Project Cost')
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

                            ])->columns(2),

                    ])->columnSpanFull(),

            ]);
    }
}
