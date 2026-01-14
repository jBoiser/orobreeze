<?php

namespace App\Filament\Resources\JobOrders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;

class JobOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jo_number')
                    ->label('Job Order No.')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('client.client_id')
                    ->label('Client ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('client.name')
                    ->label('Client Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('receive_date')
                    ->label('Receive Date')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('project_cost')
                    ->label('Project Cost')
                    ->money('PHP', true)
                    ->sortable()
                    ->searchable(),

                TextColumn::make('downpayment')
                    ->money('PHP', true)
                    ->sortable(),


                TextColumn::make('balance')
                    ->money('PHP')
                    ->color('danger') // Highlights remaining debt
                    ->weight('bold'),

                TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Pending' => 'danger',
                        'Downpayment' => 'warning',
                        'Paid' => 'success',
                    })
                    ->sortable(),

                TextColumn::make('task_status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'onGoing' => 'info',
                        'onHold' => 'warning',
                        'Completed' => 'success',
                        'Cancelled' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => ucfirst($state))
                    ->sortable(),

                TextColumn::make('remarks')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),

            ])->defaultSort('id', 'desc')

            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()
                    ->label('Update'),
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
