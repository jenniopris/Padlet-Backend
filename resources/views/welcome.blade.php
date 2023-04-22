<!DOCTYPE html>
<html lang="en">
<head>
    <title>Padlet Backend</title>
</head>
<body>
    <h1>This is my backend, wohoo</h1>
    <h2>List of Padlets</h2>
    <ol>
        @foreach($padlets as $padlet)
            <li>{{ $padlet->name }}</li>
        @endforeach
    </ol>

    <h2>List of Users</h2>
    <ol>
        @foreach($users as $user)
            <li>{{ $user->first_name ." ". $user->last_name }}</li>
        @endforeach
    </ol>

    <h2>List of Entries</h2>
    <ol>
        @foreach($entries as $entry)
            <li>{{ $entry->name }}</li>
        @endforeach
    </ol>

    <h2>List of Comments</h2>
    <ol>
        @foreach($comments as $comment)
            <li>{{ $comment->comment }}</li>
        @endforeach
    </ol>

    <h2>List of Ratings</h2>
    <ol>
        @foreach($ratings as $rating)
            <li>{{ $rating->rating }}</li>
        @endforeach
    </ol>

</body>
</html>
