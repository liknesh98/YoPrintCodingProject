<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <title>YoPrint Coding Project by Liknesh</title>
    <style>
        body {
            margin: 0;
            display: flex;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            width: 200px;
            background-color: #2c3e50;
            height: 100vh;
            padding: 20px;
            color: white;
        }
        .sidebar a {
            display: block;
            color: white;
            margin: 10px 0;
            text-decoration: none;
        }
        .content {
            padding: 30px;
            flex-grow: 1;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Menu</h2>
        <a href="{{ route('uploadPage') }}">Upload File</a>
    </div>

    <div class="content">
        @yield('content')
    </div>
</body>
</html>
