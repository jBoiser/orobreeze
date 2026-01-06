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
use Filament\Forms\Components\Repeater;
use App\Models\Product;


class InstallationsForm
{

    protected static function updateGrandTotal(Get $get, Set $set): void
    {
        $selectedItems = collect($get('items') ?? []);
        $grandTotal = $selectedItems->sum(fn($item) => (float) ($item['price'] ?? 0));
        $set('total_price', $grandTotal);
    }

    // 2. Individual Row Calculation Function
    protected static function updateRowTotal(Get $get, Set $set): void
    {
        $srp = (float) $get('srp');
        $qty = (int) $get('quantity');
        $discountPercent = (float) ($get('discount') ?? 0);

        $subtotal = $srp * $qty;
        $discountAmount = $subtotal * ($discountPercent / 100);
        $finalPrice = $subtotal - $discountAmount;

        $set('price', $finalPrice);
        // Update grand total whenever a row price changes
        static::updateGrandTotal($get, $set);
    }


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

                                DatePicker::make('start_date')
                                    ->label('Start Date'),

                                DatePicker::make('end_date')
                                    ->label('End Date'),

                                // New fields
                                Select::make('service_by')
                                    ->label('Service By')
                                    ->options([
                                        'Team A' => 'Team A',
                                        'Team B' => 'Team B',
                                    ])
                                    ->required(),

                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'onHold' => 'On Hold',
                                        'onGoing' => 'On Going',
                                        'Cancelled' => 'Cancelled',
                                    ])
                                    ->default('onHold')
                                    ->required(),

                                TextInput::make('total_price')
                                    ->label('Total Price')
                                    ->numeric()
                                    ->prefix('₱')
                                    ->readOnly()
                                    ->dehydrated(),



                                Repeater::make('items')
                                    ->hiddenlabel()
                                    ->label('') // Make sure your Installation model has a 'items' relationship
                                    ->relationship()
                                    ->itemLabel('Unit Details')
                                    ->schema([ // 2. Brand

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
                                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
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

                                                    // Recalculate row total (uses current quantity/discount)
                                                    static::updateRowTotal($get, $set);
                                                }
                                            }),

                                        // --- THE FIXES ---
                                        TextInput::make('model_name')
                                            ->readOnly()
                                            ->dehydrated(),

                                        TextInput::make('outdoor_model')
                                            ->label('Outdoor Unit Model')
                                            ->hidden(fn(Get $get) => !$get('unit_type') || $get('unit_type') === 'Window Type') // default hidden; shown when unit_type exists and is not Window Type
                                            ->readOnly()
                                            ->dehydrated(),

                                        TextInput::make('unit_type')
                                            ->label('Unit Type')
                                            ->readOnly()
                                            ->dehydrated(), // Forces read-only fields to save

                                        TextInput::make('refrigerant_type')
                                            ->readOnly()
                                            ->dehydrated(),

                                        TextInput::make('hp_capacity')
                                            ->label('HP Capacity')
                                            ->readOnly()
                                            ->dehydrated(),

                                        Group::make()
                                            ->schema([

                                                TextInput::make('srp')
                                                    ->label('SRP')
                                                    ->numeric()
                                                    ->prefix('₱')
                                                    ->readOnly()
                                                    ->dehydrated()
                                                    ->live()
                                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                                        static::updateRowTotal($get, $set);
                                                    }),

                                                TextInput::make('quantity')
                                                    ->numeric()
                                                    ->default(1)
                                                    ->live() // Mandatory for live math
                                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                                        static::updateRowTotal($get, $set);
                                                    }),

                                                TextInput::make('discount')
                                                    ->numeric()
                                                    ->suffix('%')
                                                    ->default(0)
                                                    ->live()
                                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                                        static::updateRowTotal($get, $set);
                                                    }),

                                                TextInput::make('price')
                                                    ->label('Total Unit Price')
                                                    ->readOnly()
                                                    ->extraAlpineAttributes([
                                                        'x-mask:dynamic' => '$money($input, \'.\', \',\', 2)',
                                                    ])
                                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                                        // When a row total changes, update the grand total
                                                        static::updateGrandTotal($get, $set);
                                                    }),

                                            ])->columns(4)
                                            ->columnSpanFull(),

                                    ])
                                    ->columns(3) // Display items in a grid layout
                                    ->addActionLabel('Add Another Unit')
                                    ->collapsible()
                                    ->columnSpanFull(), // Useful if you have many fields per item

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
