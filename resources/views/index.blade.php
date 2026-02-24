<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>index </title>
    @vite(['resources/js/app.js', 'resources/css/app.css']);
</head>

<body>
    <div id="loader" class="fixed inset-0 bg-white flex items-center justify-center z-50">
        <div class="w-16 h-16 border-4 border-sky-500 border-t-transparent rounded-full animate-spin"></div>
    </div>

    <div id="content" class="opacity-0">
        <h1 class="text-3xl font-bold text-sky-600 text-center mt-20">
            Welcome to System Management Counselling
        </h1>
    </div>
</body>

</html>
