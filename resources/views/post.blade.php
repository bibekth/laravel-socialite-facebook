<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Posts</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="row g-5">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        @foreach($posts as $key => $post)
                        <form action="{{ route('posts.index') }}" class="form">
                            <input type="hidden" name="slug" value="{{ $post->slug }}">
                            <button class="btn btn-primary-outline">{{ $post->title
                                }}</button>
                        </form>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <div class="create-post">
                            <h1 class="mb-5">Create a Post</h1>
                            <form action="{{ route('posts.store') }}" method="POST" class="form"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="text" name="title" id="title" placeholder="Title"
                                    class="form-control mb-3">
                                <input type="file" name="banner_image" id="banner_image" class="form-control mb-3">
                                <textarea name="description" id="description" cols="30" rows="6"
                                    class="form-control mb-3"></textarea>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="display-6 mb-3 mt-2">Options</span>
                                    <span id="add_option" style="cursor: pointer;">Add</span>
                                </div>
                                <div id="option_container">
                                    <input type="text" name="option[]" id="option_1" class="form-control mb-3"
                                        placeholder="Option 1">
                                </div>
                                <button class="btn btn-primary form-control">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
<script>
    let optionCount = 1;
    document.getElementById("add_option").addEventListener("click", function () {
        optionCount++;
        let optionContainer = document.getElementById("option_container");
        let newInput = document.createElement("input");

        newInput.type = "text";
        newInput.name = "option[]"; // Using array notation to collect multiple options
        newInput.id = "option_" + optionCount;
        newInput.className = "form-control mb-3";
        newInput.placeholder = "Option " + optionCount;

        optionContainer.appendChild(newInput);
    });
</script>

</html>
