<?php

namespace App\Filament\Resources\Bookings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group_name')
                    ->label('Csoport neve')
                    ->searchable(),
                TextColumn::make('contact_name')
                    ->label('Kapcsolattartó')
                    ->searchable(),
                TextColumn::make('contact_email')
                    ->label('E-mail')
                    ->searchable(),
                TextColumn::make('group_size')
                    ->label('Létszám')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('booking_date')
                    ->label('Dátum')
                    ->date('Y.m.d')
                    ->sortable(),
                TextColumn::make('booking_time')
                    ->label('Időpont')
                    ->time('H:i')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Státusz')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        'completed' => 'gray',
                        default     => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'confirmed' => 'Megerősítve',
                        'cancelled' => 'Törölve',
                        'completed' => 'Lezárt',
                        default     => 'Függőben',
                    }),
                TextColumn::make('created_at')
                    ->label('Létrehozva')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Státusz')
                    ->options([
                        'pending'   => 'Függőben',
                        'confirmed' => 'Megerősítve',
                        'cancelled' => 'Törölve',
                        'completed' => 'Lezárt',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('booking_date', 'asc');
    }
}
