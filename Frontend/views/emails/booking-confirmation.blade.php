<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .card { background: #fff; border-radius: 8px; max-width: 560px; margin: 0 auto; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .header { background: #16a34a; color: #fff; border-radius: 6px; padding: 20px 24px; margin-bottom: 24px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 6px 0 0; font-size: 13px; opacity: .85; }
        .row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f0f0f0; font-size: 14px; }
        .row:last-child { border-bottom: none; }
        .label { color: #6b7280; }
        .value { font-weight: 600; color: #111827; }
        .footer { margin-top: 24px; font-size: 12px; color: #9ca3af; text-align: center; }
        .notice { background: #fefce8; border: 1px solid #fde047; border-radius: 6px; padding: 12px 16px; font-size: 13px; color: #854d0e; margin-top: 20px; }
    </style>
</head>
<body>
<div class="card">
    <div class="header">
        <h1>Foglalás visszaigazolva</h1>
        <p>Sapientia EMTE Planetárium – Csíkszeredai Kar</p>
    </div>

    <p style="font-size:14px;color:#374151;">Kedves <strong>{{ $booking->contact_name }}</strong>!</p>
    <p style="font-size:14px;color:#374151;">Az alábbi időpontra sikeresen rögzítettük foglalását:</p>

    <div style="margin:20px 0;">
        <div class="row"><span class="label">Intézmény neve</span><span class="value">{{ $booking->group_name }}</span></div>
        <div class="row"><span class="label">Dátum</span><span class="value">{{ \Carbon\Carbon::parse($booking->booking_date)->format('Y. m. d.') }}</span></div>
        <div class="row"><span class="label">Időpont</span><span class="value">{{ substr($booking->booking_time, 0, 5) }}</span></div>
        <div class="row"><span class="label">Csoportlétszám</span><span class="value">{{ $booking->group_size }} fő</span></div>
        <div class="row"><span class="label">Vetítés típusa</span><span class="value">{{ $booking->with_presentation ? 'Előadással (300 lej)' : 'Előadó nélkül (250 lej)' }}</span></div>
        @if($booking->show)
        <div class="row"><span class="label">Választott előadás</span><span class="value">{{ $booking->show->title }}</span></div>
        @endif
        @if($booking->notes)
        <div class="row"><span class="label">Megjegyzés</span><span class="value">{{ $booking->notes }}</span></div>
        @endif
    </div>

    <div class="notice">
        ⚠ A fizetést a látogatás előtt kell teljesíteni az intézmény fizetési portálján keresztül.
        Kérdés esetén vegye fel velünk a kapcsolatot: <strong>planetarium@uni.sapientia.ro</strong>
    </div>

    <div class="footer">
        Sapientia Erdélyi Magyar Tudományegyetem – Csíkszeredai Kar<br>
        Csíkszereda, Szabadság tér 1., 6. emelet &nbsp;|&nbsp; 0724 517 526
    </div>
</div>
</body>
</html>
