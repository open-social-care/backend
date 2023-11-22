<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="background-color: #dddddd; margin: 0; padding: 0;">
<div style="width: 80%; margin: 0 auto; text-align: center; padding-top: 20px;">
    <div style="margin-bottom: 20px;">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" width="90">
    </div>
    <div style="background-color: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        @yield('content')
    </div>
</div>
</body>
</html>

