@extends('layouts.main')

@section('title', 'Edição de Tarefas')

@section('content')
<div class="container">
    <div class="row">
        <div class="">
            <h1>Edição</h1>
            <form method="POST">
                @csrf
                <div class="form-group row">
                  <label for="titulo" class="col-sm-2 col-form-label">Título:</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="title" id="titulo">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-10">
                    <input class="btn btn-primary" type="submit" name="submit" value="Adicionar">
                  </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
