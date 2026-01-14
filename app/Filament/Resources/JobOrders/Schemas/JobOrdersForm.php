<?php

namespace App\Filament\Resources\JobOrders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;
use App\Models\JobOrder;


class JobOrdersForm
{
    public static function configure($schema)
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
                                    ->default(fn() => JobOrder::generateNextJobOrder())
                                    ->disabled()
                                    ->dehydrated(),

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
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(Get $get, Set $set) => self::updateBalance($get, $set))
                                    ->formatStateUsing(function ($state) {
                                        if (! $state) return null;
                                        return number_format((float) str_replace(',', '', $state), 2, '.', ',');
                                    })
                                    ->dehydrateStateUsing(fn($state) => (float) str_replace(',', '', $state))
                                    ->extraAlpineAttributes([
                                        'x-mask:dynamic' => '$money($input, \'.\', \',\', 2)',
                                    ])
                                    ->extraInputAttributes(['onfocus' => 'this.select()'])
                                    ->required(),

                                TextInput::make('downpayment')
                                    ->label('Downpayment Amount')
                                    ->prefix('₱')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(Get $get, Set $set) => self::updateBalance($get, $set))
                                    ->extraAlpineAttributes([
                                        'x-mask:dynamic' => '$money($input, \'.\', \',\', 2)',
                                    ])
                                    ->dehydrateStateUsing(fn($state) => (float) str_replace(',', '', $state))
                                    ->formatStateUsing(function ($state) {
                                        if ($state === null || $state === '') return '0.00';
                                        return number_format((float) str_replace(',', '', $state), 2, '.', ',');
                                    })
                                    ->default(0),

                                TextInput::make('balance')
                                    ->label('Remaining Balance')
                                    ->prefix('₱')
                                    ->readOnly()
                                    // Formatting the balance so it also shows commas
                                    ->formatStateUsing(fn($state) => number_format((float) $state, 2, '.', ','))
                                    ->placeholder('0.00'),

                                Select::make('payment_status')
                                    ->options([
                                        'Pending' => 'Pending',
                                        'Downpayment' => 'Downpayment',
                                        'Paid' => 'Paid',
                                    ])
                                    ->default('Pending')
                                    ->required(),

                                Select::make('task_status')
                                    ->options([
                                        'onGoing' => 'On Going',
                                        'onHold' => 'On Hold',
                                        'Completed' => 'Completed',
                                        'Cancelled' => 'Cancelled',
                                    ])
                                    ->default('onHold')
                                    ->required(),

                                Textarea::make('remarks')
                                    ->columnSpanFull() // Make remarks take full width
                                    ->rows(3),

                            ])->columns(2),
                    ])->columnSpanFull(),
            ]);
    }

    protected static function updateBalance(Get $get, Set $set): void
    {
        // Strip commas and default to 0 if the value is null or empty string
        $rawCost = str_replace(',', '', $get('project_cost') ?? '0');
        $rawDown = str_replace(',', '', $get('downpayment') ?? '0');

        $cost = (float) ($rawCost ?: 0);
        $down = (float) ($rawDown ?: 0);

        $balance = $cost - $down;

        $set('balance', number_format($balance, 2, '.', ','));
    }
}
