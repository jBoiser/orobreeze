<?php

namespace App\Filament\Resources\Installations\Tables;

use Carbon\Carbon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Section as InfolistSection;


class InstallationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jobOrder.jo_number')
                    ->label('Job Order')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('jobOrder.client.name')
                    ->label('Client Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('items.brand.name')
                    ->label('Brands')
                    ->listWithLineBreaks()
                    ->bulleted(),

                TextColumn::make('items.model_name')
                    ->label('Models')
                    ->listWithLineBreaks()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('total_price')
                    ->label('Total Amount')
                    ->money('PHP')
                    ->weight('bold')
                    ->color('danger')
                    ->sortable(),

                TextColumn::make('service_by')
                    ->label('Service By')
                    ->badge()
                    ->colors([
                        'primary' => 'Team A',
                        'success' => 'Team B',
                    ])
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'onHold' => 'On Hold',
                        'onGoing' => 'On Going',
                        'Cancelled' => 'Cancelled',
                        'Completed' => 'Completed',
                        default => $state,
                    })
                    ->colors([
                        'warning' => 'onHold',
                        'primary' => 'onGoing',
                        'danger' => 'Cancelled',
                        'success' => 'Completed',
                    ])
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label('Start Date')
                    ->date()
                    ->badge()
                    ->color(fn($state): string => match (true) {
                        !$state => 'gray',
                        Carbon::parse($state)->isPast() => 'danger',
                        Carbon::parse($state)->isToday() => 'primary',
                        Carbon::parse($state)->isFuture() => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('End Date')
                    ->date()
                    ->badge()
                    ->color(fn($state): string => match (true) {
                        !$state => 'gray',
                        Carbon::parse($state)->isPast() => 'danger',
                        Carbon::parse($state)->isToday() => 'primary',
                        Carbon::parse($state)->isFuture() => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('remarks')
                    ->label('Remarks')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->slideOver()
                        ->infolist(
                            fn($infolist) => $infolist
                                ->schema([
                                    InfolistSection::make('General Information')
                                        ->schema([
                                            Grid::make(4)
                                                ->schema([
                                                    TextEntry::make('jobOrder.jo_number')->label('Job Order #'),
                                                    TextEntry::make('jobOrder.client.name')->label('Client'),
                                                    TextEntry::make('service_by')->label('Service By')->badge(),
                                                    TextEntry::make('status')->badge()->formatStateUsing(fn(string $state): string => match ($state) {
                                                        'onHold' => 'On Hold',
                                                        'onGoing' => 'On Going',
                                                        'Cancelled' => 'Cancelled',
                                                        'Completed' => 'Completed',
                                                        default => $state,
                                                    })
                                                        ->colors([
                                                            'warning' => 'onHold',
                                                            'primary' => 'onGoing',
                                                            'danger' => 'Cancelled',
                                                            'success' => 'Completed',
                                                        ]),
                                                    TextEntry::make('start_date')->badge()
                                                        ->color(fn($state): string => match (true) {
                                                            !$state => 'gray',
                                                            Carbon::parse($state)->isPast() => 'danger',
                                                            Carbon::parse($state)->isToday() => 'primary',
                                                            Carbon::parse($state)->isFuture() => 'success',
                                                            default => 'gray',
                                                        }),
                                                    TextEntry::make('end_date')->badge()
                                                        ->color(fn($state): string => match (true) {
                                                            !$state => 'gray',
                                                            Carbon::parse($state)->isPast() => 'danger',
                                                            Carbon::parse($state)->isToday() => 'primary',
                                                            Carbon::parse($state)->isFuture() => 'success',
                                                            default => 'gray',
                                                        }),
                                                ]),
                                        ]),

                                    InfolistSection::make('Installed Units')
                                        ->description('List of all units included in this installation')
                                        ->schema([
                                            RepeatableEntry::make('items')
                                                ->hiddenLabel()
                                                ->schema([
                                                    Grid::make(4)
                                                        ->schema([
                                                            TextEntry::make('brand.name')->label('Brand')->weight('bold'),
                                                            TextEntry::make('model_name')->label('Model'),
                                                            TextEntry::make('unit_type')->label('Unit Type'),
                                                            TextEntry::make('hp_capacity')->label('Capacity'),
                                                            TextEntry::make('description')->label('Description'),
                                                            IconEntry::make('is_inverter')->label('Inverter')->boolean(),
                                                            TextEntry::make('srp')->label('SRP')->money('PHP')->weight('bold'),
                                                            TextEntry::make('quantity')->label('Qty')->suffix(' units')->weight('bold'),
                                                            TextEntry::make('discount')->label('Discount')->formatStateUsing(fn($state) => (int) $state)->suffix('%')->weight('bold'),
                                                            TextEntry::make('price')->label('Subtotal')->money('PHP')->weight('bold')->color('success'),
                                                        ]),
                                                ])
                                                ->grid(['default' => 1]),
                                        ]),

                                    InfolistSection::make('Summary')
                                        ->schema([
                                            TextEntry::make('total_price')
                                                ->columnStart(2)
                                                ->label('Grand Total')
                                                ->money('PHP')
                                                ->weight('bold')
                                                ->color('danger'),

                                        ]),
                                ])
                        ),
                    EditAction::make()
                        ->slideOver(),
                    DeleteAction::make(),
                    RestoreAction::make()
                        ->color('success'),
                    ForceDeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('Actions')
                    ->color('gray'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
