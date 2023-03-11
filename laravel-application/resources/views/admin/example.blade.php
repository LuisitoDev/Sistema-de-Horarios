<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token()}}"/>
    <script src="{{asset('js/app.js')}}" defer></script>
    <title>Admin Example</title>
</head>
<body>
    <h1>Admin Example</h1>
    <form action="{{route('assessors.post')}}" method="post">
        @csrf
        <input type="text" name="email" >
        <button>Enviar</button>
    </form>
    <button>Enviar con axios</button>
</body>
</html>