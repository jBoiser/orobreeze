<?php

namespace App\Filament\Resources\Products\Tables;

use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;

use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('brand.name')
                    ->label('Brand')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('model_name')
                    ->label('Model')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('unit_type')
                    ->label('Unit Type')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('hp_capacity')
                    ->label('Capacity')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('outdoor_model')
                    ->label('Outdoor Model')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_inverter')
                    ->boolean()
                    ->label('Inverter')
                    ->sortable(),

                TextColumn::make('refrigerant_type')
                    ->label('Refrigerant Type')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('srp')
                    ->money('PHP')
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Description')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),


            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                    RestoreAction::make()
                        ->color('success'),
                    ForceDeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical') // This sets the 3 dots icon
                    ->tooltip('Actions')
                    ->color('gray'), // Optional: makes the dots more sub
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
