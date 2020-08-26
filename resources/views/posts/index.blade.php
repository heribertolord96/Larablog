@extends('layouts.app')
@section('search_form')
<!-- SEARCH FORM -->
<form class="form-inline ml-3">
    <div class="input-group input-group-sm">       
        <input class="form-control form-control-navbar" 
        name="buscar" type="search" placeholder="Search"  aria-label="Search">       
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
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Lista de Entradas 
                    <a href="{{ route('posts.create') }}" class="pull-right btn btn-sm btn-primary">
                        Crear post
                    </a>
                </div>
                <div class="container-body">
                        <div class="card-deck">
                            @foreach($posts as $post)
                            <div class="card mt-3" style="min-width: 300px; max-height: 400px;  box-shadow:4px; padding-top:5px;">
                                <div class="card-header"><h5 class="card-title">{{ $post->postname }}</h5> </div>
                              <img class="card-img-top" src="{{ $post->file }}" alt="{{ $post->postname }}">
                              <div class="card-body overflow-auto">                                
                                <p class="card-text">{{ $post->body }}</p>
                                <p class="card-text float-right"><small class="text-muted">{{ $post->excerpt }}</small></p>
                              </div>
                              <div class="card-footer">
                                <div class="card-tools pull-right">                                    
                                    <button type="button" class="btn btn-card-tool" >
                                    <a  href="{{ route('posts.edit', $post->id) }}">
                                    <i class="fa fa-fw fa-pen 50px">Editar</i>
                                    </a>
                                    </button>
                                    <button type="button" class="btn btn-card-tool" >
                                    {!! Form::open(['route' => ['posts.destroy', $post->id], 'method' => 'DELETE']) !!}
                                    <button class="btn-danger">
                                    <i class="fa fa-fw fa-trash ">Eliminar</i>
                                    </button>                           
                                    {!! Form::close() !!}
                                    </button>
                                    <button type="button" class="btn btn-card-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-card-tool" data-widget="remove">
                                    <i class="fa fa-times"></i></button>
                                 </div>
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
            </div>
        </div>
    </div>
@endsection