@extends('layouts.app')
@section('search_form')
<!-- SEARCH FORM -->
<form class="form-inline ml-3">
    <div class="input-group input-group-sm">       
        <input class="form-control form-control-navbar" 
        name="buscar" type="search" placeholder="Buscar" value="buscar" aria-label="Search">       
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search">Buscar</i>
                </button>
            </div>
    </div>
</form>
@endsection
@section('content')
<div class="container">
                <div class="container-heading">
                    Lista de Posts
                </div>
                <div class="container-body">
                    <div class="card-deck">
                        @foreach($posts as $post)
                        <div class="card mt-3" style="min-width: 300px; max-height: 400px;  box-shadow:4px; padding-top:5px;">
                          <img class="card-img-top" src="{{ $post->file }}" alt="{{ $post->postname }}">
                          <div class="card-body overflow-auto">
                            <a href="{{ route('posts.show', $post->id) }}">
                            <h5 class="card-title">
                              {{ $post->postname }}
                            </h5>
                            </a>                          
                            <p class="card-text">{{ $post->body }}</p>
                            <p class="card-text float-right"><small class="text-muted">{{ $post->excerpt }}</small></p>
                          </div>
                          <div class="card-footer">
                          <a href="{{ route('category', $post->category_id) }}">
                            <p class="card-text float-right"><small class="text-muted">{{ $post->catname }}</small></p>
                        </a>
                          </div>
                        </div>
                        @endforeach                        
                      </div>
                    {{ $posts->render() }}
                </div>
        </div>
 
@endsection