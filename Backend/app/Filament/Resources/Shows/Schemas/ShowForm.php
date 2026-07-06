<?php

namespace App\Filament\Resources\Shows\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

/**
 * Előadás (Show) adminfelület – adatbeviteli űrlap definíciója.
 *
 * A Filament 4 Schema-alapú megközelítést alkalmaz: a form mezőit
 * egy statikus configure() metódusban deklaráljuk, amely a Filament
 * resource-ban automatikusan meghívódik a létrehozás és szerkesztés oldalon.
 */
class ShowForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Cím')
                    ->required(),
                Textarea::make('description')
                    ->label('Leírás')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('age_recommendation')
                    ->label('Ajánlott korosztály'),
                TextInput::make('duration_minutes')
                    ->label('Időtartam (perc)')
                    ->required()
                    ->numeric(),
                FileUpload::make('image')
                    ->label('Kép')
                    ->image()
                    ->disk('public')
                    ->maxSize(20480),
                TextInput::make('url')
                    ->label('Előadás linkje (URL)')
                    ->url()
                    ->placeholder('https://...')
                    ->helperText('Opcionális külső link az előadás részleteihez'),
                Select::make('language')
                    ->label('Előadás nyelve')
                    ->options([
                        'hu'   => 'Magyar',
                        'ro'   => 'Román',
                        'both' => 'Magyar és Román',
                    ])
                    ->default('hu')
                    ->required()
                    ->helperText('Milyen nyelven tartják az előadást?'),
                Toggle::make('is_active')
                    ->label('Aktív')
                    ->required(),
                TextInput::make('sort_order')
                    ->label('Sorrend')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
