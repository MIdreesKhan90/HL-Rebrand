<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- css file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="{{asset('/assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/assets/css/main.css?v=1.2.1')}}">

    <!-- site title -->
    <title>{{$title}} - {{env('APP_NAME')}}</title>
</head>
<body>

@yield('content')

<script src="{{asset('/assets/js/jquery.min.js')}}"></script>
<script src="{{asset('/assets/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('/assets/js/main.js')}}"></script>
@yield('custom-script')
</body>
</html>
