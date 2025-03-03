<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <div class="show-post">
            <div class="title-section">
                {{ $post->title }}
            </div>
            @if ($post->banner_image)
            <div class="banner-section">
                <img src="{{ asset('storage/'.$post->banner_image) }}" alt="">
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

</html>
