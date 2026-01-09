<?php

namespace App\Filament\Resources\Products\Schemas;

use Dom\Text;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;
use App\Models\Brand;

class ProductForm
{

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Product Information')
                            ->description('Basic details and information')
                            ->schema([
                                Select::make('brand_id')
                                    ->relationship('brand', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
 
                                TextInput::make('model_name')
                                    ->label('Model No.')
                                    ->unique()
                                    ->required()
                                    ->placeholder('e.g. Premium Inverter')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', \Illuminate\Support\Str::slug($state))),

                                // TextInput::make('slug')
                                //     ->unique()
                                //     ->readOnly()
                                //     ->dehydrated(),

                                TextInput::make('srp')
                                    ->label('Srp')
                                    ->prefix('₱')
                                    ->extraAlpineAttributes([
                                        'x-mask:dynamic' => '$money($input, \'.\', \',\', 2)',
                                    ])
                                    ->dehydrateStateUsing(fn($state) => str_replace(',', '', $state))
                                    ->formatStateUsing(fn($state) => number_format((float) $state, 2, '.', ','))
                                    ->extraInputAttributes([
                                        'onfocus' => 'this.select()',
                                    ])
                                    ->required(),

                                TextInput::make('description')
                                    ->label('Description')
                                    ->placeholder('Brief description about the product')
                                    ->columnSpanFull(),

                            ])->columns(2),

                        Section::make('Aircon Unit')
                            ->description('Aircon Unit Details')
                            ->schema([

                                Select::make('unit_type')
                                    ->label('Unit Type')
                                    ->options([
                                        'Window Type' => 'Window Type',
                                        'Split Type' => 'Split Type',
                                        'Floor Mounted' => 'Floor Mounted',
                                    ])
                                    ->required()
                                    ->live() // Essential: tells Filament to watch for changes
                                    ->afterStateUpdated(function (callable $set, $state) {
                                        // Clear logic: if Window Type is chosen, clear Split fields, and vice versa
                                        if ($state === 'Window Type') {
                                            $set('indoor_model', '-');
                                            $set('outdoor_model', '-');
                                        } else {
                                            $set('window_model', '-');
                                        }
                                    }),

                                Select::make('hp_capacity')
                                    ->label('Capacity')
                                    ->options([
                                        '1.0HP' => '1.0 HP',
                                        '1.5HP' => '1.5 HP',
                                        '2.0HP' => '2.0 HP',
                                        '2.5HP' => '2.5 HP',
                                    ])->required(),

                                TextInput::make('outdoor_model')
                                    ->label('Split Outdoor Model')
                                    ->placeholder('e.g. SO1200')
                                    ->visible(fn(callable $get) => $get('unit_type') !== 'Window Type' && filled($get('unit_type')))
                                    ->required(fn(callable $get) => $get('unit_type') !== 'Window Type'),


                                Select::make('is_inverter')
                                    ->label('Inverter Technology')
                                    ->options([
                                        '1' => 'Yes',
                                        '0' => 'No',
                                    ])
                                    ->default(true),

                                Select::make('refrigerant_type')
                                    ->label('Refrigerant Type')
                                    ->options([
                                        'R410A' => 'R410A',
                                        'R32' => 'R32',
                                    ])
                                    ->required(),

                            ])->columns(2),

                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
