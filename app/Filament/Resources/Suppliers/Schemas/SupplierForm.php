<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use App\Models\Supplier;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Supplier Sales Representative')
                            ->description('Point of contact for Orders and Sales')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Sales Representative')
                                    ->unique()
                                    ->required(),

                                TextInput::make('email_address')
                                    ->unique()
                                    ->email()
                                    ->required(),

                                TextInput::make('contact_number')
                                    ->unique()
                                    ->label('Sales Contact')
                                    ->tel()
                                    ->required(),

                            ])->columns(3),

                        Section::make('Supplier Information')
                            ->schema([
                                TextInput::make('supplier_id')
                                    ->label('Supplier ID')
                                    ->unique()
                                    ->default(fn() => Supplier::generateNextSupplierId())
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),

                                TextInput::make('created_at')
                                    ->label('Created Date')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->afterStateHydrated(fn($state, $set) => $set('created_at', \Carbon\Carbon::parse($state)->format('M j, Y H:i')))
                                    ->visible(fn($operation) => in_array($operation, ['create', 'view'])),

                                TextInput::make('updated_at')
                                    ->label('Updated Date')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->afterStateHydrated(fn($state, $set) => $set('updated_at', \Carbon\Carbon::parse($state)->format('M j, Y H:i')))
                                    ->visible(fn($operation) => $operation === 'edit'),

                                TextInput::make('company_name')
                                    ->required(),

                                TextInput::make('owner')
                                    ->unique()
                                    ->label('Owner Name')
                                    ->placeholder('Optional'),

                                TextInput::make('office_email_address')
                                    ->unique()
                                    ->email()
                                    ->required(),

                                TextInput::make('office_contact_number')
                                    ->unique()
                                    ->label('Office Contact')
                                    ->tel()
                                    ->required(),

                                TextInput::make('office_address')
                                    ->label('Office Address')
                                    ->required()
                                    ->columnSpanFull(),

                            ])->columns(2),

                    ])
                    ->columnSpanFull(),
            ]);
    }
}
