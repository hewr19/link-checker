<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Pro</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f5f7fb; color: #222; }
        .container { max-width: 1000px; margin: 2rem auto; padding: 1rem; }
        .card { background: #fff; border-radius: 12px; box-shadow: 0 6px 18px rgba(0,0,0,.08); padding: 1rem 1.25rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border-bottom: 1px solid #ececec; padding: .75rem; text-align: left; }
        .btn { background: #2067f5; color: #fff; border: none; padding: .7rem 1rem; border-radius: 8px; cursor: pointer; }
        .status { background: #e8f8ee; border: 1px solid #a8e1be; padding: .7rem; border-radius: 8px; margin-bottom: .8rem; }
    </style>
</head>
<body>
<div class="container">
    @yield('content')
</div>
</body>
</html>
