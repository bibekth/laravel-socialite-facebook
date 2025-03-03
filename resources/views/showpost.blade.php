<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $post->title }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="show-post">
            <div class="title-section">
                {{ $post->title }}
            </div>
            @if ($post->banner_image)
            <div class="banner-section">
                <img src="{{ asset('storage/'.$post->banner_image) }}" alt="Banner">
            </div>
            @endif
            @if(!empty($post->description))
            <div class="post-description">
                {{ $post->description }}
            </div>
            @endif
            @if(!empty($post->votes))
            <div class="votes-section">
                <ul>
                    @foreach ($post->votes as $votes)
                    <li>{{ $votes->option }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(!empty($post->comments))
            <ul>
                @foreach($post->comments as $key => $comment)
                <li>
                    {{ $comment->comment }}
                </li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
</html>
