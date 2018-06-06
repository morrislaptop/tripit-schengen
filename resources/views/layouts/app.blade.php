<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>TripIt - Schengen - Find how many days you have left</title>

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

            html, body, input, select, textarea, button {
                font-family: 'Raleway', sans-serif;
                font-size: 16px;
            }

            .full-height {
                min-height: 100vh;
                box-sizing: border-box;
                padding: 1rem;
                padding-bottom: 51px; /* room for made with */
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

            a {
                color: #636b6f;
                font-weight: 600;
                text-decoration: none;
            }

            .links > a {
                letter-spacing: .1rem;
                text-transform: uppercase;
                font-size: 12px;
                padding: 0 25px;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            th, td {
                border-bottom: 1px solid #f5f5f5;
            }

            .footer {
                font-size: 14px;
                position: absolute;
                bottom: 0;
            }

            @media (max-width: 450px)  {
                .title {
                    font-size: 62px;
                }

                .subtitle {
                    font-size: 18px;
                }
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    TripIt Schengen
                </div>

                @yield('content')
            </div>
            <p class="footer">Made with ❤️by <a href="https://craigmorris.io">morrislaptop</a></p>
        </div>
    </body>
</html>
