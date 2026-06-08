<section class="blog-comments" aria-labelledby="blog-comments-heading">
    <h2 id="blog-comments-heading" class="blog-comments__heading">Add Comments:</h2>

    @if (session('status'))
        <p class="blog-comments__flash" role="status">{{ session('status') }}</p>
    @endif

    @if ($errors->any())
        <ul class="blog-comments__errors" role="alert">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('blog.comments.store', $article) }}" class="blog-comments__form">
        @csrf
        <div class="blog-comments__row">
            <div class="blog-comments__field">
                <label for="blog-comment-name">Name<span aria-hidden="true">*</span></label>
                <input id="blog-comment-name" name="author_name" type="text" value="{{ old('author_name') }}" required autocomplete="name">
            </div>
            <div class="blog-comments__field">
                <label for="blog-comment-email">Email<span aria-hidden="true">*</span></label>
                <input id="blog-comment-email" name="author_email" type="email" value="{{ old('author_email') }}" required autocomplete="email">
            </div>
        </div>
        <div class="blog-comments__field">
            <label for="blog-comment-body">Comment<span aria-hidden="true">*</span></label>
            <textarea id="blog-comment-body" name="body" rows="6" required>{{ old('body') }}</textarea>
        </div>
        <button type="submit" class="blog-comments__submit">Send</button>
    </form>
</section>
