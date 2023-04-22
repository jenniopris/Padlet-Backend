<!DOCTYPE html>
<html lang="en">
<head>
    <title>Padlet Backend</title>
</head>
<body>
    <h1>Das ist mein Backend, juhu</h1>
    <ul>
        @foreach($padlets as $padlet)
            <li>{{ $padlet->name }}</li>
        @endforeach
    </ul>
</body>
</html>
