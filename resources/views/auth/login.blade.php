<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Логин</title>
</head>
<body>
@if($errors->any())
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
<form action="{{ route('login') }}" method="post">
    @csrf
    <input type="text" name="username" required/><br/>
    <input type="password" name="password" required/><br/>
    <input type="checkbox" name="remember" value="1" id="remember"/>
    <label for="remember">Запомнить</label><br/>
    <input type="submit" value="Войти">
</form>
</body>
</html>
