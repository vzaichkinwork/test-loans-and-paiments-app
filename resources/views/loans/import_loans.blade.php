<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Calculator</title>
    </head>
    <body>

    @include('loans.menu')

    @if (session('error'))
        <h3 style="color:red">{{ session('error') }}</h3>
    @endif
    @if (session('success'))
        <h3 style="color:green">{{ session('success') }}</h3>
    @endif

    <h2>Loans Import</h2>

    <fieldset>
        <form id="calculator" action="{{ route('import.loans.store') }}" method="post" enctype="multipart/form-data">

            <div>
                <label for="amount">Loans CSV File</label>
                <input type="file" name="csv" id="loans">
            </div>

            <input type="submit" value="Upload">

        </form>
    </fieldset>

    </body>
</html>
