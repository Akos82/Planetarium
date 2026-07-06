<?php

namespace App\Filament\Resources\Shows\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ShowsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('age_recommendation')
                    ->searchable(),
                TextColumn::make('duration_minutes')
                    ->numeric()
                    ->sortable(),
                ImageColumn::make('image'),
                TextColumn::make('url')
                    ->label('Link')
                    ->url(fn ($record) => $record->url)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('language')
                    ->label('Nyelv')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'hu'   => 'Magyar',
                        'ro'   => 'Román',
                        'both' => 'Magyar & Román',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match($state) {
                        'hu'   => 'info',
                        'ro'   => 'warning',
                        'both' => 'success',
                        default => 'gray',
                    }),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('language')
                    ->label('Nyelv')
                    ->options([
                        'hu'   => 'Magyar',
                        'ro'   => 'Román',
                        'both' => 'Magyar & Román',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
