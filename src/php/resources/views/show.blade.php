@extends('layouts.index')

@section('content')

@if (today()->is('12-25'))
<h1>メリークリスマス！</h1>
@endif

<h1>ブログ詳細</h1>
<h2>{{ $post->title }}</h2>
<div>{!! nl2br(e($post->body)) !!}</div>

<p>書き手：{{ $post->user->name }}</p>

<h2>コメント</h2>
@foreach ($post->comments()->oldest()->get() as $comment )
    <hr>
    <p>{{ $comment->name }} {{ $comment->created_at }}</p>
    <p>{!! nl2br(e($comment->body)) !!}</p>
@endforeach

@endsection
