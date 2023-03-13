<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Форма обратной связи</title>
</head>
<body>
<h1>Пожалуйста заполните форму обратной связи</h1>
<div>
    <hr>
    <div>
        <form action="{{route("send.form")}}" method="Post">
            @csrf
            <div style="margin-bottom: 15px;"><input type="text" name="first_name" placeholder="Имя" value="{{old('first_name')}}">
                @error('first_name')
                <div>
                    {{$message}}
                </div>
                @enderror()
            </div>
            <div style="margin-bottom: 15px;"><input type="text" name="price" placeholder="Цена" value="{{old('price')}}">
                @error('price')
                <div>
                    {{$message}}
                </div>
                @enderror()
            </div>
            <div style="margin-bottom: 15px;"><input type="number" name="phone" placeholder="Телефон" value="{{old('phone')}}">
                @error('phone')
                <div>
                    {{$message}}
                </div>
                @enderror()
            </div>
            <div style="margin-bottom: 15px;"><input type="email" name="email" placeholder="Почта" value="{{old('email')}}">
                @error('email')
                <div>
                    {{$message}}
                </div>
                @enderror()
            </div>
            <div style="margin-bottom: 15px;"><input type="submit" value="Добавить"></div>
        </form>
    </div>
</div>
</body>
</html>

