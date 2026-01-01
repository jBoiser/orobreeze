<?php

namespace App\Filament\Resources\Suppliers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ActionGroup;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;

class SuppliersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('supplier_id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created On')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),

                TextColumn::make('company_name')
                    ->label('Company Name')
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Sales Representative')
                    ->searchable(),

                TextColumn::make('contact_number')
                    ->label('Sales Contact')
                    ->searchable(),

                TextColumn::make('email_address')
                    ->label('Sales Email')
                    ->searchable(),

                TextColumn::make('owner')
                    ->searchable(),

                TextColumn::make('office_contact_number')
                    ->label('Office Number')
                    ->searchable(),
                TextColumn::make('office_email_address')
                    ->label('Office Email')
                    ->searchable(),

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
                    ->color('gray'), // Optional: makes the dots more subtle
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
