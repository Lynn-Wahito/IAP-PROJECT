<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
   



        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        
      

        <style>
        /* Styles for the floating banner */
        .floating-banner {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #4CAF50;
            color: #fff;
            padding: 20px;
            text-align: center;
            transform: translateY(100%); /* Initially hidden below the viewport */
            transition: transform 10s ease-in-out;
        }

        /* Animation to slide the banner into view */
        .floating-banner.active {
            transform: translateY(0);
        }
    </style>
    </head>
    <body class="font-sans antialiased">
    <div class="container">
       <div class="row" style="margin-top:50px">
             <div class="col-md-6 offset-md-3">
                
                 @livewire('booking-page', ['event_id' => $event->id])
             </div>
       </div>
   </div>

        @livewireScripts

                <script>
   var bannerTimeout;

function showBanner() {
    // Clear any existing timeout to ensure multiple clicks don't overlap
    clearTimeout(bannerTimeout);

    // Show the banner
    document.getElementById('banner').style.transform = 'translateY(0)';

    // Set a timeout to automatically hide the banner after 10 minutes (600000 milliseconds)
    bannerTimeout = setTimeout(hideBanner, 600000);
}

function hideBanner() {
    // Hide the banner
    document.getElementById('banner').style.transform = 'translateY(100%)';

    // Clear the timeout
    clearTimeout(bannerTimeout);
}


    function goToPaymentPage() {
        clearTimeout(bannerTimeout);

        // Redirect to the payments page
        window.location.href = '/payments';
    }
</script>





       
    </body>

</html>
