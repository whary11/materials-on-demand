<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config("app.app_name") }}</title>

        <!-- Font Awesome-->
    <link
    rel="stylesheet"
    type="text/css"
    href="{{asset("/assets/css/vendors/fontawesome.css")}}"
  />

  <!-- Flag icon-->
  <link
    rel="stylesheet"
    type="text/css"
    href="{{asset("/assets/css/vendors/themify-icons.css")}}"
  />

  <!-- slick icon-->
  <link
    rel="stylesheet"
    type="text/css"
    href="/assets/css/vendors/slick.css"
  />
  <link
    rel="stylesheet"
    type="text/css"
    href="/assets/css/vendors/slick-theme.css"
  />

  <!-- jsgrid css-->
  <link
    rel="stylesheet"
    type="text/css"
    href="/assets/css/vendors/jsgrid.css"
  />

  <!-- Bootstrap css-->
  <link
    rel="stylesheet"
    type="text/css"
    href="/assets/css/vendors/bootstrap.css"
  />

  <!-- App css-->
  <link rel="stylesheet" type="text/css" href="/assets/css/admin.css" />
    </head>
    <body class="antialiased">

        <div id="app">
            <App />
        </div>
        
        <div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
            Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
        </div>
    </body>


    {{-- Template INIT --}}
    <!-- latest jquery-->
    <script src="/assets/js/jquery-3.3.1.min.js"></script>

    <!-- Bootstrap js-->
    <script src="/assets/js/bootstrap.bundle.min.js"></script>

    <!-- feather icon js-->
    <script src="/assets/js/icons/feather-icon/feather.min.js"></script>
    <script src="/assets/js/icons/feather-icon/feather-icon.js"></script>

    <!-- Sidebar jquery-->
    <script src="/assets/js/sidebar-menu.js"></script>
    <script src="/assets/js/slick.js"></script>

    <!-- Jsgrid js-->
    <script src="/assets/js/jsgrid/jsgrid.min.js"></script>
    <script src="/assets/js/jsgrid/griddata-invoice.js"></script>
    <script src="/assets/js/jsgrid/jsgrid-invoice.js"></script>

    <!-- lazyload js-->
    <script src="/assets/js/lazysizes.min.js"></script>

    <!--right sidebar js-->
    <script src="/assets/js/chat-menu.js"></script>

    <!--script admin-->
    <script src="/assets/js/admin-script.js"></script>
    <script>
      $(".single-item").slick({
        arrows: false,
        dots: true,
      });
    </script>

    {{-- Template Fin --}}
    <script src="{{ mix('/js/app.js') }}"></script>
</html>
