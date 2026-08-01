@foreach($articles as $article)
    @include('articles._card', ['article' => $article])
@endforeach
