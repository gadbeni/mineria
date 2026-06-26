<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
      
        <?php $admin_favicon = Voyager::setting('admin.icon_image', ''); ?>
        @if($admin_favicon == '')
            <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/png">
        @else
            <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">
        @endif
        <style>
            body{
                margin: 0px auto;
                font-family: Arial, sans-serif;
            }
            .container {
                display: flex;
                justify-content: center;
                width: 100%;
                background: rgb(115,117,117);
                background: linear-gradient(90deg, rgba(115,117,117,1) 0%, rgba(173,173,173,1) 50%, rgba(115,117,117,1) 100%);
            }
            .sheet {
                padding: 30px;
                width: 780px;
                background-color: white
            }
            .content {
                text-align: justify;
                padding: 0px 34px;
                font-size: 11px;
            }
            #logo{
                margin: 0px;
                width: 90px;
            }
            .page-head {
                text-align: center;
                margin-bottom: 30px
            }
            .page-head h3 {
                margin-top: 0px !important
            }
            #watermark {
                position: fixed;
                top: 350px;
                opacity: 0.1;
                z-index:  100;
                width: 100%
            }
            #watermark img{
                position: relative;
                width: 300px;
                left: 210px;
            }

            .btn {
                padding: 8px 15px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 14px;
            }
            .btn-print {
                background-color: #28a745;
                color: white;
            }
            .btn-print:hover {
                background-color: #218838;
            }
            .btn-close {
                background-color: #6c757d;
                color: white;
            }
            .btn-close:hover {
                background-color: #5a6268;
            }
            .text-center{
                text-align: center;
            }
            ol p{
                margin: 10px
            }
            .table-signature {
                width: 100%;
                text-align: center;
                margin-top: 50px;
                margin-bottom: 80px;
            }

            @page {
                size: letter portrait;
                margin: 10mm 10mm 10mm 10mm;
            }
            @media print {
                .options {
                    display: none !important;
                }
                body {
                    margin: 0;
                }
                .sheet {
                    padding: 0;
                    width: 100%;
                    max-width: 100%;
                    box-shadow: none;
                    background-color: white;
                }
                .container {
                    display: block;
                    width: 100%;
                    background: none !important;
                }
                #watermark {
                    position: fixed;
                    top: 40%;
                }
                .table-signature {
                    margin-bottom: 0px;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="sheet">
                
                <div id="watermark">
                    <img src="{{ asset('images/icon.png') }}" /> 
                </div>
		        <div class="options" style="position: fixed; bottom: 16px; right: 20px; z-index: 9999;">
                    <button type="button" class="btn btn-close" onclick="cerrarVentana()">Cancelar</button>
                    <button type="button" class="btn btn-print" onclick="window.print()">&#128438; Imprimir</button>
		        </div>
                @yield('content')
            </div>
        </div>

        @yield('css')

        <script>
            function cerrarVentana() {
                window.close();
                // Fallback si el navegador bloquea window.close()
                setTimeout(function() {
                    window.history.back();
                }, 200);
            }

            document.body.addEventListener('keypress', function(e) {
                switch (e.key) {
                    case 'Enter':
                        window.print();
                        break;
                    case 'Escape':
                        cerrarVentana();
                        break;
                    default:
                        break;
                }
            });
        </script>
        @yield('script')
    </body>
</html>