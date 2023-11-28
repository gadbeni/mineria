<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('page_title') | {{ env('APP_NAME', 'SYSALMACEN') }}</title>
    <!-- Favicon -->
    <?php $admin_favicon = Voyager::setting('admin.icon_image', ''); ?>
    @if($admin_favicon == '')
        <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">
    @endif
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}
    <style>
        body{
            margin: 0px auto;
            font-family: Arial, sans-serif;
            font-weight: 100;
        }
        .btn-print{
            padding: 5px 10px
        }

        /* #borde {
            border-color: rgb(26, 113, 2);
            border-width: 5px;
            border-style: solid;
            margin: 20px;
            padding: 20px;
        } */

        #watermark {
            width: 100%;
            position: fixed;
            top: 180px;
            opacity: 0.1;
            z-index:  -1;
            text-align: center
        }
        #watermark img{
            position: relative;
            width: 450px;
        }
        @media print{
            .hide-print{
                display: none
            }
           
            

            .content{
                padding: 0px 0px
            }
        }
    </style>
    @yield('css')
</head>
<body>
    {{-- <div class="hide-print" style="text-align: right; padding: 10px 0px">
        <button class="btn-print" onclick="window.close()">Cancelar <i class="fa fa-close"></i></button>
        <button class="btn-print" onclick="window.print()"> Imprimir <i class="fa fa-print"></i></button>
    </div> --}}
    
    <div id="watermark">
        <img src="{{ asset('images/icon.png') }}" /> 
    </div>
    
    <div class="content">
        @yield('content')
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        document.body.addEventListener('keypress', function(e) {
            switch (e.key) {
                case 'Enter':
                    window.print();
                    break;
                case 'Escape':
                    window.close();
                default:
                    break;
            }
        });
    </script>

    @yield('script')
</body>
</html>