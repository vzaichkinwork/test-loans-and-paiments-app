<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Calculator</title>

        <script
            src="https://code.jquery.com/jquery-2.2.4.min.js"
            integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
            crossorigin="anonymous"></script>

        <script type="text/javascript">
            $(function () {
                $('#calculator').submit(function (ev) {
                    ev.preventDefault();
                    $('#result').html('');

                    $.ajax({
                        url : '{{ route('calc.conversion') }}',
                        method: 'post',
                        dataType: 'json',
                        data: $('#calculator').serialize(),
                        success: function (data) {
                            $('#result').html(
                                data.orig_amount + data.orig_currency +
                                ' = ' +
                                data.conv_amount + data.conv_currency
                            );
                        },
                        error: function (jqXHR) {
                            alert(jqXHR.responseJSON.error);
                        }
                    });
                });
            })
        </script>
    </head>
    <body>

    @include('loans.menu')

    <h2>Calculator</h2>

    <fieldset>
    <form id="calculator" action="{{ route('calc.conversion') }}" method="post">

    <div>
        <label for="amount">Currency Amount</label>
        <input type="number" name="amount" id="amount" min="0.00" step="0.01">
    </div>

    <div>
        <label for="currency_from">Currency Converting from</label>
        <select name="currency_from" id="currency_from">
            <option value="USD">USD</option>
            <option value="EUR">EUR</option>
            <option value="GBP">GBP</option>
            <option value="BYN">BYN</option>
        </select>
    </div>

    <div>
        <label for="currency_to">Currency Converting to</label>
        <select name="currency_to" id="currency_to">
            <option value="USD">USD</option>
            <option value="EUR">EUR</option>
            <option value="GBP">GBP</option>
            <option value="BYN">BYN</option>
        </select>
    </div>

        <h3 id="result"></h3>

    <input type="submit" value="Calculate">

    </form>
    </fieldset>

    </body>
</html>
