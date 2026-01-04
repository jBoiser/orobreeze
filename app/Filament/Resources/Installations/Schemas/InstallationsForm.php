<?php

namespace App\Filament\Resources\Installations\Schemas;

use Filament\Schemas\Schema;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
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
                                Select::make('jo_number')
                                    ->relationship('jobOrder', 'jo_number')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('brand_id')
                                    ->label('Select Product')
                                    ->relationship('product', 'model_name') // Pulls from your products table
                                    ->searchable()
                                    ->preload()
                                    ->live() // Essential for reactivity
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        if (!$state) return;

                                        $product = Product::find($state);

                                        if ($product) {
                                            // 1. Set the values fetched from the Product database
                                            $set('unit_type', $product->unit_type); // Ensure your product table has a 'type' column
                                            // $set('unit_model', $product->model_name);
                                            // $set('model_indoor', $product->indoor_name);
                                            // $set('model_outdoor', $product->outdoor_name);
                                            // $set('total_price', $product->price);
                                            // $set('refregirant_type', $product->refregirant_type);
                                            // $set('unit_capacity', $product->capacity);
                                        }
                                    }),

                                // This field can be hidden or disabled since it's set automatically
                                TextInput::make('unit_type')
                                    ->label('Category')
                                    ->disabled()
                                    ->dehydrated() // Ensures it still saves to the installations table
                                    ->live(),

                                // --- CONDITIONAL FIELDS ---

                                // Visible ONLY if the selected product's type is "Window Type"
                                TextInput::make('unit_model')
                                    ->label('Unit Model')
                                    ->visible(fn(Get $get) => $get('unit_type') === 'Window Type')
                                    ->required(fn(Get $get) => $get('unit_type') === 'Window Type'),

                                // Visible ONLY if the selected product's type is "Split Type"
                                TextInput::make('model_indoor')
                                    ->label('Indoor Model')
                                    ->visible(fn(Get $get) => $get('unit_type') === 'Split Type')
                                    ->required(fn(Get $get) => $get('unit_type') === 'Split Type'),

                                TextInput::make('model_outdoor')
                                    ->label('Outdoor Model')
                                    ->visible(fn(Get $get) => $get('unit_type') === 'Split Type')
                                    ->required(fn(Get $get) => $get('unit_type') === 'Split Type'),

                                // --- COMMON FIELDS ---
                                TextInput::make('total_price')
                                    ->numeric()
                                    ->prefix('₱')
                                    ->required(),
                            ])

                    ])->columnSpanFull(),

            ]);
    }
}
