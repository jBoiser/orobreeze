<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Brand Information')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(
                                        fn(?string $state, Set $set) =>
                                        $set('slug', Str::slug($state))
                                    ),

                                // TextInput::make('slug')
                                //     ->required()
                                //     ->unique(ignoreRecord: true)
                                //     ->readOnly()
                                //     ->dehydrated(),

                                FileUpload::make('logo')
                                    ->image()
                                    ->rules(['mimes:jpeg,png,webp', 'max:2048'])
                                    ->disk('public')
                                    ->directory('brand-logos')
                                    // Removed imageEditor() to avoid nested overlay interfering with modal close
                                    ->visibility('public'),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }
}
