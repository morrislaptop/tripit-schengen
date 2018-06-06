<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>TripIt - Schengen</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .subtitle {
                font-size: 24px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    TripIt Schengen
                </div>

                <div class="subtitle m-b-md">
                    You have {{ $remaining }} days remaining as of {{ $now->format('d M Y') }}
                </div>

                <form class="m-b-md">
                    <input type="date" name="date" value="{{ $now->format('Y-m-d') }}" />
                    <input type="submit" />
                </form>

                <div class="links m-b-md">
                    <a href="/refresh">Refresh</a>
                </div>

                <table cellpadding="8">
                    <tr>
                        <th align="left">Country</th>
                        <th>Arrive</th>
                        <th>Leave</th>
                        <th>Diff</th>
                        <th>Remaining</th>
                    </tr>
                    <tr>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>90</th>
                    </tr>
                    @foreach ($log as $trip)
                        <tr>
                            <td align="left">{{ $trip['country'] }}</td>
                            <td style="font-style: {{ $trip['arrive_same'] ? 'normal' : 'italic' }};">{{ $trip['arrive']->format('d M Y') }}</td>
                            <td style="font-style: {{ $trip['leave_same'] ? 'normal' : 'italic' }};">{{ $trip['leave']->format('d M Y') }}</td>
                            <td>{{ $trip['diff'] }}</td>
                            <td>{{ $trip['remaining'] }}</td>
                        </tr>
                    @endforeach
                </table>

            </div>
        </div>
    </body>
</html>
