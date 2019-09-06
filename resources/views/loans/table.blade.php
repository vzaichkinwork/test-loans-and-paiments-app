<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Loans & Payments</title>
    </head>
    <body>

    @include('loans.menu')

    <h2>Loans & Payments</h2>

    <ul>
        @foreach($loans as $loan)
            <li style="border-bottom:1px solid grey; margin-bottom:15px">
                Number: {{ $loan->loan_number }}<br>
                Amount: {{ $loan->amount }} USD<br>
                Payments: {{ $loan->payments->count() }}<br>
                Status: {{ $loan->status_human }}<br>
                @if ($loan->payments->count())
                <b>Payments</b>
                @endif
                <ul>
                    @foreach($loan->payments as $payment)
                        <li style="margin-bottom:15px">
                            Number: {{ $payment->payment_number }}<br>
                            Amount: {{ $payment->amount }} USD<br>
                            Original Amount: {{ $payment->orig_amount }} {{ $payment->orig_currency }}<br>
                            Status: {{ $payment->status_human }}<br>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>

    </body>
</html>
