<?php

namespace App\Filament\Resources\Installations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Carbon\Carbon;

use Filament\Tables\Table;

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

                TextColumn::make('brand.name')
                    ->label('Brand')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('product.model_name')
                    ->label('Model')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('outdoor_model')
                    ->label('Outdoor Model'),

                TextColumn::make('unit_type')
                    ->label('Unit Type')
                    ->sortable(),

                TextColumn::make('outdoor_model')
                    ->label('Outdoor Model'),

                TextColumn::make('srp')
                    ->label('SRP')
                    ->money('PHP')
                    ->sortable(),

                TextColumn::make('hp_capacity')
                    ->label('HP Capacity'),

                TextColumn::make('refrigerant_type')
                    ->label('Refrigerant'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
                        default => $state,
                    })
                    ->colors([
                        'warning' => 'onHold',
                        'success' => 'onGoing',
                        'danger' => 'Cancelled',
                    ])
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label('Start Date')
                    ->date()
                    ->badge()
                    ->color(fn($state): string => match (true) {
                        !$state => 'gray',
                        Carbon::parse($state)->isPast() => 'danger',
                        Carbon::parse($state)->isToday() => 'success',
                        Carbon::parse($state)->isFuture() => 'primary',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('End Date')
                    ->date()
                    ->badge()
                    ->color(fn($state): string => match (true) {
                        // If date is missing
                        !$state => 'gray',

                        // If the date has already passed
                        Carbon::parse($state)->isPast() => 'danger',

                        // If the date is exactly today
                        Carbon::parse($state)->isToday() => 'success',

                        // If the date is still in the future
                        Carbon::parse($state)->isFuture() => 'primary',

                        default => 'gray',
                    })
                    ->sortable(),



                TextColumn::make('remarks')
                    ->label('Remarks')
                    ->limit(60)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->slideOver(),
                    EditAction::make()
                        ->slideOver(),
                    DeleteAction::make(),
                    RestoreAction::make()
                        ->color('success'),
                    ForceDeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical') // This sets the 3 dots icon
                    ->tooltip('Actions')
                    ->color('gray'), // Optional: makes the dots more subtle
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
