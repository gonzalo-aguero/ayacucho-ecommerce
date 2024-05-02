@props([
    "gtagID" => config("app.gtag_id")
])
@if($gtagID)
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gtagID }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', "{{ $gtagID }}");
    </script>
@endif
