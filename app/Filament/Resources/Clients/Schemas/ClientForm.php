<?php

namespace App\Filament\Resources\Clients\Schemas;

use App\Models\Client;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Client Information')
                            ->description('Basic details and contact information')
                            ->schema([
                                TextInput::make('client_id')
                                    ->label('Client ID')
                                    ->default(fn() => Client::generateNextClientId())
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
                                    ->label('Client Name')
                                    ->placeholder('Full Name')
                                    ->unique()
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('company')
                                    ->placeholder('Optional')
                                    ->label('Company Name')
                                    ->nullable()
                                    ->maxLength(255),
                            ])->columns(2),

                        Section::make('Contact Details')
                            ->schema([
                                TextInput::make('email')
                                    ->label('Email Address')
                                    ->placeholder('Email Address')
                                    ->unique()
                                    ->email()
                                    ->required(),

                                TextInput::make('phone_number')
                                    ->unique()
                                    ->tel()
                                    ->required()
                                    ->mask('+63 999 999 9999')
                                    ->placeholder('+63 xxx xxx xxxx'),

                                TextInput::make('address')
                                    ->placeholder('Street, Barangay, City, State')
                                    ->required()
                                    ->columnSpanFull(),
                            ])->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
