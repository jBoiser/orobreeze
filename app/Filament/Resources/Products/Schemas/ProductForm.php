<?php

namespace App\Filament\Resources\Products\Schemas;


use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

use App\Models\Product;

use function Laravel\Prompts\select;

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

                                Select::make('hp_capacity')
                                    ->label('Capacity')
                                    ->options([
                                        '1.0HP' => '1.0 HP',
                                        '1.5HP' => '1.5 HP',
                                        '2.0HP' => '2.0 HP',
                                        '2.5HP' => '2.5 HP',
                                    ])->required(),

                                Select::make('type')
                                    ->label('Type')
                                    ->options([
                                        'Window Type' => 'Window Type',
                                        'Split Type' => 'Split Type',
                                        'Floor Mounted' => 'Floor Mounted',
                                    ])->required(),

                                Select::make('is_inverter')
                                    ->label('Inverter Technology')
                                    ->options([
                                        '1' => 'Yes',
                                        '0' => 'No',
                                    ])
                                    ->default(true),

                                TextInput::make('srp')
                                    ->label('Srp')
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

                                TextInput::make('description')
                                    ->label('Description')
                                    ->placeholder('Brief description about the product')
                                    ->columnSpanFull(),
                                    
                            ])->columns(2),

                    ])
                    ->columnSpanFull(),
            ]);
    }
}
