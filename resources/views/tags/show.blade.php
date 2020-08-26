@extends('layouts.app')

@section('content')
<div class="container">
  
    <div class="card card mt-3">
        <div class="card-header">Ver etiqueta</div>
        <div class="card-body">
            <p><strong>Nombre</strong> {{ $tag->name }}</p>
                    <p><strong>Slug</strong> {{ $tag->slug }}</p>
        </div>
      </div>
</div>
@endsection