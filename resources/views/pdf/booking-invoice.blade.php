<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>فاتورة حجز</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            direction: rtl;
            text-align: right;
        }
        .row { margin-bottom: 8px; }
        .title { font-size: 18px; font-weight: 700; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: right; vertical-align: middle; }
        th { background: #f5f5f5; }
        .totals { margin-top: 14px; width: 100%; }
        .totals td { padding: 6px; }
        .bold { font-weight: 700; }
        .muted { color: #666; }

        /* للأرقام والكود والإنجليزي */
        .ltr { direction: ltr; unicode-bidi: bidi-override; text-align: left; }
        .center { text-align: center; }
        hr { border: none; border-top: 1px solid #ddd; margin: 12px 0; }
    </style>
</head>
<body>

<div class="title">@pdfAr('فاتورة حجز')</div>

<div class="row">
    @pdfAr('رقم الحجز'):
    <span class="bold ltr">{{ $booking->booking_code }}</span>
</div>

<div class="row muted">
    @pdfAr('تاريخ الإصدار'):
    <span class="ltr">{{ now()->format('Y-m-d H:i') }}</span>
</div>

<hr>

<div class="row">
    @pdfAr('اسم الزبون'):
    <span class="bold">@pdfAr($booking->guest_name ?? '-')</span>
</div>

<div class="row">
    @pdfAr('هاتف الزبون'):
    <span class="bold ltr">{{ $booking->guest_phone ?? '-' }}</span>
</div>

<div class="row">
    @pdfAr('تاريخ الدخول'):
    <span class="bold ltr">
        {{ \Carbon\Carbon::parse($booking->check_in_date)->format('Y-m-d') }}
    </span>
</div>

<div class="row">
    @pdfAr('تاريخ الخروج'):
    <span class="bold ltr">
        {{ \Carbon\Carbon::parse($booking->check_out_date)->format('Y-m-d') }}
    </span>
</div>

<div class="row">
    @pdfAr('طريقة الدفع'):
    <span class="bold ltr">{{ $booking->payment_method ?? '-' }}</span>
</div>

<div class="row">
    @pdfAr('الحالة'):
    <span class="bold ltr">{{ $booking->status ?? '-' }}</span>
</div>

<table>
    <thead>
    <tr>
        <th class="center ltr">#</th>
        <th>@pdfAr('الغرفة')</th>
        <th>@pdfAr('النوع')</th>
        <th>@pdfAr('سعر/ليلة')</th>
        <th>@pdfAr('عدد الليالي')</th>
        <th>@pdfAr('الإجمالي')</th>
    </tr>
    </thead>

    <tbody>
    @foreach($booking->rooms as $i => $room)
        <tr>
            <td class="center ltr">{{ $i + 1 }}</td>

            <td class="ltr">
                {{ $room->room_number ?? $room->name ?? $room->number ?? ('Room #' . $room->id) }}
            </td>

            <td>
                @pdfAr($room->roomType->name ?? '-')
            </td>

            <td class="ltr">
                {{ number_format((float)($room->pivot->price_per_night ?? 0), 2) }}
            </td>

            <td class="center ltr">
                {{ (int)($room->pivot->nights ?? 0) }}
            </td>

            <td class="ltr">
                {{ number_format((float)($room->pivot->line_total ?? 0), 2) }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<table class="totals">
    <tr>
        <td class="bold">@pdfAr('الإجمالي')</td>
        <td class="bold ltr">{{ number_format((float)($booking->total_amount ?? 0), 2) }}</td>
    </tr>
    <tr>
        <td>@pdfAr('المدفوع')</td>
        <td class="ltr">{{ number_format((float)($booking->paid_amount ?? 0), 2) }}</td>
    </tr>
    <tr>
        <td>@pdfAr('المتبقي')</td>
        <td class="ltr">{{ number_format((float)($booking->remaining_amount ?? 0), 2) }}</td>
    </tr>
</table>

@if(!empty($booking->notes))
    <hr>
    <div class="row bold">@pdfAr('ملاحظات:')</div>
    <div class="row">@pdfAr($booking->notes)</div>
@endif

</body>
</html>
