<!DOCTYPE html>
<html>
<head>
    <title>New Comment</title>
</head>
<body>
    <h1>New Comment on Post: {{ $comment->commentable->title }}</h1>
    <p>{{ $comment->content }}</p>
</body>
</html>
