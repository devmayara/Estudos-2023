@extends('layouts.main')

@section('title', 'Listagem de Tarefas')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Listagem</h1>
            </div>
            <a class="btn btn-primary" href="{{ route('tarefas.add') }}">Adicionar nova tarefa</a>
            <div class="col-12 mt-5">
                @if (count($list) > 0)
                    <ul class="list-group">
                        @foreach ($list as $item)
                            <li class="list-group-item">
                                <a class="btn btn-primary btn-sm" href="{{ route('tarefas.done', ['id'=>$item->id]) }}">@if ($item->resolvido===1) desmarcar @else marcar @endif</a>
                                {{$item->titulo}}
                                <a class="btn btn-primary btn-sm" href="{{ route('tarefas.edit', ['id'=>$item->id]) }}">editar</a>
                                <a class="btn btn-primary btn-sm" href="{{ route('tarefas.del', ['id'=>$item->id]) }}">exluir</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    Não há itens a serem listados!
                @endif
            </div>
        </div>
    </div>
@endsection
