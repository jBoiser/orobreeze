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
use App\Models\JobOrder;



class InstallationsForm
{

    public static function updatePriceFromDiscount(Get $get, Set $set)
    {
        $srp = (float) ($get('srp') ?? 0);
        $discount = (float) ($get('discount') ?? 0);
        $qty = (float) ($get('quantity') ?? 1);

        // Formula: (SRP - (SRP * Discount%)) * Qty
        $unitPriceAfterDiscount = $srp - ($srp * ($discount / 100));
        $totalRowPrice = $unitPriceAfterDiscount * $qty;

        $set('price', number_format($totalRowPrice, 2, '.', ''));

        static::updateGrandTotal($get, $set);
    }

    public static function updateDiscountFromPrice(Get $get, Set $set)
    {
        $srp = (float) ($get('srp') ?? 0);
        $totalPrice = (float) ($get('price') ?? 0);
        $qty = (float) ($get('quantity') ?? 1);

        if ($srp > 0 && $qty > 0) {
            $unitPrice = $totalPrice / $qty;
            // Formula: ((SRP - UnitPrice) / SRP) * 100
            $discountPercentage = (($srp - $unitPrice) / $srp) * 100;

            $set('discount', round($discountPercentage, 2));
        }

        static::updateGrandTotal($get, $set);
    }

    public static function updateGrandTotal(Get $get, Set $set)
    {
        // Retrieve all items from the repeater
        $items = $get('../../items') ?? [];
        $grandTotal = 0;

        foreach ($items as $item) {
            $grandTotal += (float) ($item['price'] ?? 0);
        }

        // Set the value to the total_price field outside the repeater
        $set('../../total_price', number_format($grandTotal, 2, '.', ''));
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
                                // Top Level Fields
                                Select::make('job_order_id')
                                    ->relationship('jobOrder', 'jo_number')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, $state) {
                                        $jobOrder = JobOrder::with('client')->find($state);
                                        $set('client_name', $jobOrder?->client?->name ?? '');
                                    }),

                                TextInput::make('client_name')
                                    ->label('Client Name')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->afterStateHydrated(function (Set $set, Get $get) {
                                        $jobOrderId = $get('job_order_id');
                                        if ($jobOrderId) {
                                            $jobOrder = JobOrder::with('client')->find($jobOrderId);
                                            $set('client_name', $jobOrder?->client?->name ?? '');
                                        }
                                    }),

                                DatePicker::make('start_date')->label('Start Date'),
                                DatePicker::make('end_date')->label('End Date'),

                                Select::make('service_by')
                                    ->options(['Team A' => 'Team A', 'Team B' => 'Team B'])
                                    ->required(),

                                Select::make('status')
                                    ->options(['onHold' => 'On Hold', 'onGoing' => 'On Going', 'Cancelled' => 'Cancelled'])
                                    ->default('onHold')
                                    ->required(),

                                TextInput::make('total_price')
                                    ->label('Total Price')
                                    ->numeric()
                                    ->prefix('₱')
                                    ->readOnly()
                                    ->dehydrated(),

                                TextInput::make('remarks')
                                    ->label('Remarks'),

                                // REPEATER START
                                Repeater::make('items')
                                    ->relationship()
                                    ->itemLabel('Unit Details')
                                    ->schema([
                                        // 1. Brand Selection (MUST BE INSIDE REPEATER)
                                        Select::make('brand_id')
                                            ->relationship('brand', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->afterStateUpdated(fn(Set $set) => $set('product_id', null)),

                                        // 2. Product Selection (MUST BE INSIDE REPEATER)
                                        Select::make('product_id')
                                            ->label('Select Model')
                                            ->options(function (Get $get) {
                                                $brandId = $get('brand_id');
                                                return $brandId ? Product::where('brand_id', $brandId)->pluck('model_name', 'id') : [];
                                            })
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->required()
                                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                                if (!$state) return;
                                                $product = Product::find($state);
                                                if ($product) {
                                                    $set('model_name', $product->model_name);
                                                    $set('unit_type', $product->unit_type);
                                                    $set('srp', $product->srp);
                                                    $set('refrigerant_type', $product->refrigerant_type);
                                                    $set('hp_capacity', $product->hp_capacity);
                                                    $set('outdoor_model', in_array($product->unit_type, ['Split Type', 'Floor Mounted', 'Floor City']) ? $product->outdoor_model : '-');

                                                    static::updatePriceFromDiscount($get, $set);
                                                }
                                            }),

                                        TextInput::make('model_name')->readOnly()->dehydrated(),
                                        TextInput::make('unit_type')->readOnly()->dehydrated(),

                                        TextInput::make('outdoor_model')
                                            ->hidden(fn(Get $get) => !$get('unit_type') || $get('unit_type') === 'Window Type')
                                            ->readOnly()
                                            ->dehydrated(),

                                        TextInput::make('refrigerant_type')
                                            ->readOnly()
                                            ->dehydrated(),

                                        TextInput::make('hp_capacity')
                                            ->label('HP Capacity')
                                            ->readOnly()
                                            ->dehydrated(),
                                        // Calculation Row
                                        Group::make()
                                            ->schema([
                                                TextInput::make('srp')
                                                    ->numeric()->prefix('₱')->live()
                                                    ->afterStateUpdated(fn(Get $get, Set $set) => static::updatePriceFromDiscount($get, $set)),

                                                TextInput::make('quantity')
                                                    ->numeric()->default(1)->live()
                                                    ->afterStateUpdated(fn(Get $get, Set $set) => static::updatePriceFromDiscount($get, $set)),

                                                TextInput::make('discount')
                                                    ->numeric()
                                                    ->suffix('%')
                                                    ->default(0)
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn(Get $get, Set $set) => static::updatePriceFromDiscount($get, $set))
                                                    // Use extraInputAttributes to target the actual HTML input element
                                                    ->extraInputAttributes([
                                                        'onfocus' => 'setTimeout(() => this.select(), 10)',
                                                        'onkeydown' => "if (event.key === 'Enter') { event.preventDefault(); }",
                                                    ]),

                                                TextInput::make('price')
                                                    ->label('Total Unit Price')
                                                    ->numeric()->prefix('₱')->live(onBlur: true)
                                                    ->extraAttributes([
                                                        'onkeydown' => "if (event.key === 'Enter') { event.preventDefault(); }"
                                                    ])
                                                    ->afterStateUpdated(fn(Get $get, Set $set) => static::updateDiscountFromPrice($get, $set)),
                                            ])->columns(4)->columnSpanFull(),
                                    ])
                                    ->columns(4)
                                    ->addActionLabel('Add Another Unit')
                                    ->collapsible()
                                    ->columnSpanFull(),
                                // REPEATER END
                            ])->columns(4),
                    ])->columnSpanFull(),
            ]);
    }
}
