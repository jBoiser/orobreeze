<?php

namespace App\Filament\Resources\Installations\Schemas;

use Filament\Schemas\Schema;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use App\Models\Product;


class InstallationsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Installation')
                            ->description('Installation Details')
                            ->schema([
                                // 1. Job Order
                                Select::make('job_order_id')
                                    ->relationship('jobOrder', 'jo_number')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                // 2. Brand
                                Select::make('brand_id')
                                    ->relationship('brand', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set) {
                                        $set('product_id', null);
                                        $set('model_name', null);
                                        $set('unit_type', null);
                                    }),

                                // 3. Product Selection
                                Select::make('product_id')
                                    ->label('Select Model')
                                    ->options(function (Get $get) {
                                        $brandId = $get('brand_id');
                                        if (!$brandId) return [];
                                        return Product::where('brand_id', $brandId)->pluck('model_name', 'id');
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->required()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        if (!$state) return;

                                        $product = Product::find($state);

                                        if ($product) {
                                            // We set the values here
                                            $set('model_name', $product->model_name);
                                            $set('unit_type', $product->unit_type);
                                            $set('srp', $product->srp);
                                            $set('refrigerant_type', $product->refrigerant_type);
                                            $set('hp_capacity', $product->hp_capacity);

                                            // Treat Floor Standing like Split Type for outdoor_model
                                            if (in_array($product->unit_type, ['Split Type', 'Floor Mounted'], true)) {
                                                $set('outdoor_model', $product->outdoor_model);
                                            } else {
                                                $set('outdoor_model', '-');
                                            }
                                        }
                                    }),

                                // --- THE FIXES ---
                                TextInput::make('model_name')
                                    ->hidden()
                                    ->dehydrated(true),

                                TextInput::make('unit_type')
                                    ->label('Unit Type')
                                    ->readOnly()
                                    ->dehydrated(), // Forces read-only fields to save

                                  TextInput::make('outdoor_model')
                                    ->label('Outdoor Unit Model')
                                    ->hidden(fn(Get $get) => !$get('unit_type') || $get('unit_type') === 'Window Type') // default hidden; shown when unit_type exists and is not Window Type
                                    ->readOnly()
                                    ->dehydrated(),

                                TextInput::make('srp')
                                    ->numeric()
                                    ->prefix('₱')
                                    ->readOnly()
                                    ->dehydrated(),

                                TextInput::make('refrigerant_type')
                                    ->readOnly()
                                    ->dehydrated(),

                                TextInput::make('hp_capacity')
                                    ->label('HP Capacity')
                                    ->readOnly()
                                    ->dehydrated(),

                                // New fields
                                Select::make('service_by')
                                    ->label('Service By')
                                    ->options([
                                        'Team A' => 'Team A',
                                        'Team B' => 'Team B',
                                    ])
                                    ->required(),

                                DatePicker::make('start_date')
                                    ->label('Start Date'),

                                DatePicker::make('end_date')
                                    ->label('End Date'),

                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'onHold' => 'On Hold',
                                        'onGoing' => 'On Going',
                                        'Cancelled' => 'Cancelled',
                                    ])
                                    ->default('onHold')
                                    ->required(),

                                Textarea::make('remarks')
                                    ->label('Remarks')
                                    ->rows(3)
                                    ->nullable()
                                    ->columnSpanFull(),
                            ])->columns(3),

                    ])->columnSpanFull(),
            ]);
    }
}
