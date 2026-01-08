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
                                TextInput::make('supplier_id')
                                    ->label('ID')
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

                                TextInput::make('name')
                                    ->placeholder('Full Name')
                                    ->label('Name')
                                    ->unique()
                                    ->required(),

                                TextInput::make('contact_number')
                                    ->unique()
                                    ->label('Contact')
                                    ->tel()
                                    ->required()
                                    ->mask('+63 999 999 9999')
                                    ->placeholder('+63 xxx xxx xxxx'),

                                TextInput::make('address')
                                    ->placeholder('Street, Barangay, City, State')
                                    ->label('Address')
                                    ->required(),

                                TextInput::make('email_address')
                                    ->placeholder('Email Address')
                                    ->unique()
                                    ->email()
                                    ->required(),

                            ])->columns(2),

                        Section::make('Supplier Information')
                            ->schema([
                                TextInput::make('company_name')
                                    ->placeholder('Company Name')
                                    ->label('Company Name')
                                    ->required(),

                                TextInput::make('owner')
                                    ->placeholder('Full Name (Optional)')
                                    ->unique()
                                    ->label("Owner's Name"),

                                TextInput::make('office_email_address')
                                    ->placeholder('Email Address (Optional)')
                                    ->label("Office Email Address")
                                    ->unique()
                                    ->email(),

                                TextInput::make('office_contact_number')
                                    ->unique()
                                    ->label("Office Contact Number")
                                    ->tel()
                                    ->mask('+63 999 999 9999')
                                    ->placeholder('+63 xxx xxx xxxx (Optional)'),

                                TextInput::make('office_address')
                                    ->placeholder('Street, Barangay, City, State (Optional)')
                                    ->label("Office Address")
                                    ->columnSpanFull(),

                            ])->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
