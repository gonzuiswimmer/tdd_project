@extends('layouts.index')

@section('content')

<h1>ブログ詳細</h1>

<h2>{{ $post->title }}</h2>
<div>{!! nl2br(e($post->body)) !!}</div>

<p>書き手：{{ $post->user->name }}</p>

@endsection
