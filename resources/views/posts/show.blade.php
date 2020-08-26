@extends('layouts.app')
@section('content')
<div class="container">   
    <div class="card card mt-3">
        <div class="card-header">
            {{ $post->name }}
        </div>
        <img class="card-img-top" src="{{ $post->file }}" alt="{{ $post->name }}">
        <div class="card-body">
            {{ $post->body }}
        </div>
        <div class="card-footer">
            <a href="{{ route('category', $post->category_id) }}">
              <p class="card-text float-right"><small class="text-muted">{{ $post->catname }}</small></p>
          </a>
            </div>
      </div>
</div>
@endsection