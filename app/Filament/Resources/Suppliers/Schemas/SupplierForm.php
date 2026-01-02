<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use App\Models\Supplier;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique;

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
                                    ->label('Name')
                                    ->unique()
                                    ->required(),

                                TextInput::make('contact_number')
                                    ->unique()
                                    ->label('Contact')
                                    ->tel()
                                    ->required()
                                    ->mask('+63 999 9999 999')
                                    ->placeholder('+63 xxx xxxx xxx'),

                                TextInput::make('address')
                                    ->label('Address')
                                    ->required(),

                                TextInput::make('email_address')
                                    ->unique()
                                    ->email()
                                    ->required(),

                            ])->columns(2),

                        Section::make('Supplier Information')
                            ->schema([
                                TextInput::make('company_name')
                                    ->label('Company Name')
                                    ->required(),

                                TextInput::make('owner')
                                    ->unique()
                                    ->label("Owner's Name (Optinal)"),

                                TextInput::make('office_email_address')
                                    ->label("Office Email Address (Optional)")
                                    ->unique()
                                    ->email(),

                                TextInput::make('office_contact_number')
                                    ->unique()
                                    ->label("Office Contact (Optional)")
                                    ->tel()
                                    ->mask('+63 999 9999 999')
                                    ->placeholder('+63 xxx xxxx xxx'),

                                TextInput::make('office_address')
                                    ->label("Office Address (Optional)")
                                    ->columnSpanFull(),

                            ])->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
