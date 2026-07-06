<?php

namespace App\Filament\Resources\Bookings\Schemas;

use App\Models\Booking;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Livewire\Component as Livewire;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('group_name')
                    ->label('Csoport neve')
                    ->required(),
                TextInput::make('contact_name')
                    ->label('Kapcsolattartó neve')
                    ->required(),
                TextInput::make('contact_email')
                    ->label('E-mail cím')
                    ->email()
                    ->required(),
                TextInput::make('contact_phone')
                    ->label('Telefonszám')
                    ->tel(),
                TextInput::make('group_size')
                    ->label('Létszám (max. 27 fő)')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(27)
                    ->helperText('A planetárium maximális kapacitása 27 fő.'),
                DatePicker::make('booking_date')
                    ->label('Foglalás dátuma')
                    ->required()
                    ->live(),
                TimePicker::make('booking_time')
                    ->label('Időpont')
                    ->required()
                    ->seconds(false)
                    ->rules([
                        function (Get $get, Livewire $livewire): Closure {
                            return function (string $attribute, $value, Closure $fail) use ($get, $livewire): void {
                                $date   = $get('booking_date');
                                $status = $get('status');

                                // Törölt foglalás nem ütközik
                                if ($status === 'cancelled' || !$date || !$value) {
                                    return;
                                }

                                // Szerkesztéskor a saját rekordot kizárjuk
                                $currentId = $livewire->record?->id ?? null;

                                $conflict = Booking::where('booking_date', $date)
                                    ->where('booking_time', $value)
                                    ->whereIn('status', ['pending', 'confirmed'])
                                    ->when($currentId, fn ($q) => $q->where('id', '!=', $currentId))
                                    ->first();

                                if ($conflict) {
                                    $fail(
                                        "Ütközés! Erre az időpontra már van aktív foglalás: "
                                        . "\"{$conflict->group_name}\" ({$conflict->booking_time}, {$conflict->status}). "
                                        . "Válasszon másik időpontot."
                                    );
                                }
                            };
                        },
                    ]),
                Textarea::make('notes')
                    ->label('Megjegyzések')
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Státusz')
                    ->options([
                        'confirmed' => 'Megerősítve',
                        'cancelled' => 'Törölve',
                        'completed' => 'Lezárt',
                    ])
                    ->required()
                    ->default('confirmed')
                    ->live(),
                TextInput::make('google_form_response_id')
                    ->label('Google Forms azonosító')
                    ->disabled(),
            ]);
    }
}
