<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('partials.meta', $meta ?? [])

    <link rel="icon" href="/images/slimcms-logo.png" type="image/png">

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link href="/css/style.css" rel="stylesheet">

    @stack('head')
</head>