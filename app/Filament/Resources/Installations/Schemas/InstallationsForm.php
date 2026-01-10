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
        $srp = (float) str_replace(',', '', $get('srp') ?? 0);
        $discount = (float) ($get('discount') ?? 0);
        $qty = (float) ($get('quantity') ?? 1);

        // Formula: (SRP - (SRP * Discount%)) * Qty
        $unitPriceAfterDiscount = $srp - ($srp * ($discount / 100));
        $totalRowPrice = $unitPriceAfterDiscount * $qty;

        $set('price', number_format($totalRowPrice, 2, '.', ','));

        static::updateGrandTotal($get, $set);
    }

    public static function updateDiscountFromPrice(Get $get, Set $set)
    {
        $srp = (float) str_replace(',', '', $get('srp') ?? 0);
        $totalPrice = (float) str_replace(',', '', $get('price') ?? 0);
        $qty = (float) ($get('quantity') ?? 1);

        if ($srp > 0 && $qty > 0) {
            $unitPrice = $totalPrice / $qty;
            $discountPercentage = (($srp - $unitPrice) / $srp) * 100;

            $set('discount', round($discountPercentage, 2));
            // Re-format the price field to ensure commas stay
            $set('price', number_format($totalPrice, 2, '.', ','));
        }

        static::updateGrandTotal($get, $set);
    }

    public static function updateGrandTotal(Get $get, Set $set)
    {
        $items = $get('../../items') ?? [];
        $grandTotal = 0;

        foreach ($items as $item) {
            $grandTotal += (float) str_replace(',', '', $item['price'] ?? 0);
        }

        $set('../../total_price', number_format($grandTotal, 2, '.', ','));
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
                                    ->options(['onHold' => 'On Hold', 'onGoing' => 'On Going', 'Cancelled' => 'Cancelled', 'Completed' => 'Completed'])
                                    ->default('onHold')
                                    ->required(),

                                TextInput::make('total_price')
                                    ->label('Total Price')
                                    ->prefix('₱')
                                    ->readOnly()
                                    ->dehydrated()
                                    // Formats numeric value from DB to 1,000.00
                                    ->formatStateUsing(fn($state) => filled($state) ? number_format((float) $state, 2, '.', ',') : '0.00')
                                    // Removes commas before saving to DB
                                    ->dehydrateStateUsing(fn($state) => (float) str_replace(',', '', $state)),

                                TextInput::make('remarks')
                                    ->label('Remarks'),

                                Repeater::make('items')
                                    ->relationship()
                                    ->itemLabel('Unit Details')
                                    ->schema([
                                        Select::make('brand_id')
                                            ->relationship('brand', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->afterStateUpdated(fn(Set $set) => $set('product_id', null)),

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
                                                    // Format the SRP with commas when product is selected
                                                    $set('srp', number_format((float)$product->srp, 2, '.', ','));
                                                    $set('refrigerant_type', $product->refrigerant_type);
                                                    $set('hp_capacity', $product->hp_capacity);
                                                    $set('outdoor_model', in_array($product->unit_type, ['Split Type', 'Floor Mounted', 'Floor City']) ? $product->outdoor_model : '-');
                                                    $set('description', $product->description);
                                                    $set('is_inverter', $product->is_inverter ? 1 : 0);
                                                    static::updatePriceFromDiscount($get, $set);
                                                }
                                            }),

                                        TextInput::make('model_name')->readOnly()->dehydrated(),
                                        TextInput::make('unit_type')->readOnly()->dehydrated(),
                                        TextInput::make('outdoor_model')
                                            ->hidden(fn(Get $get) => !$get('unit_type') || $get('unit_type') === 'Window Type')
                                            ->readOnly()
                                            ->dehydrated(),
                                        TextInput::make('refrigerant_type')->label('Refrigerant')->readOnly()->dehydrated(),
                                        TextInput::make('hp_capacity')->label('HP Capacity')->readOnly()->dehydrated(),
                                        TextInput::make('is_inverter')->label('Inverter')->readOnly()->dehydrated(),
                                        TextInput::make('description')->label('Description')->readOnly()->dehydrated(),

                                        Group::make()
                                            ->schema([
                                                TextInput::make('srp')
                                                    ->label('SRP')
                                                    ->prefix('₱')
                                                    ->live(onBlur: true)
                                                    ->formatStateUsing(fn($state) => filled($state) ? number_format((float) $state, 2, '.', ',') : null)
                                                    ->dehydrateStateUsing(fn($state) => (float) str_replace(',', '', $state))
                                                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                                        // Ensure the current field stays formatted with commas after manual edit
                                                        $set('srp', number_format((float)str_replace(',', '', $state), 2, '.', ','));
                                                        static::updatePriceFromDiscount($get, $set);
                                                    }),

                                                TextInput::make('quantity')
                                                    ->numeric()
                                                    ->default(1)
                                                    ->live()
                                                    ->afterStateUpdated(fn(Get $get, Set $set) => static::updatePriceFromDiscount($get, $set)),

                                                TextInput::make('discount')
                                                    ->numeric()
                                                    ->suffix('%')
                                                    ->default(0)
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn(Get $get, Set $set) => static::updatePriceFromDiscount($get, $set))
                                                    ->extraInputAttributes([
                                                        'onfocus' => 'setTimeout(() => this.select(), 10)',
                                                        'onkeydown' => "if (event.key === 'Enter') { event.preventDefault(); }",
                                                    ]),

                                                TextInput::make('price')
                                                    ->label('Total Unit Price')
                                                    ->prefix('₱')
                                                    ->live(onBlur: true)
                                                    ->formatStateUsing(fn($state) => filled($state) ? number_format((float) $state, 2, '.', ',') : null)
                                                    ->dehydrateStateUsing(fn($state) => (float) str_replace(',', '', $state))
                                                    ->extraAttributes([
                                                        'onkeydown' => "if (event.key === 'Enter') { event.preventDefault(); }",
                                                    ])
                                                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                                        $set('price', number_format((float)str_replace(',', '', $state), 2, '.', ','));
                                                        static::updateDiscountFromPrice($get, $set);
                                                    }),
                                            ])->columns(4)->columnSpanFull(),
                                    ])
                                    ->columns(4)
                                    ->addActionLabel('Add Another Unit')
                                    ->collapsible()
                                    ->columnSpanFull(),
                            ])->columns(4),
                    ])->columnSpanFull(),
            ]);
    }
}
