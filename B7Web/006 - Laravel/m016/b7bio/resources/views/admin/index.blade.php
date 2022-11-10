@extends('admin.template');

@section('title', 'B7Bio | Home');

@section('content')

    <h2>Suas Páginas</h2>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Títulos</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pages as $page)
                    <tr>
                        <td>{{ $page->op_title }} ({{ $page->slug }})</td>
                        <td>
                            <a href="{{ url('/'.$page->slug) }}" target="_blank">Abrir</a> |
                            <a href="{{ url('/admin/'.$page->slug.'/links') }}" target="_blank">Links</a> |
                            <a href="{{ url('/admin/'.$page->slug.'/design') }}" target="_blank">Aparência</a> |
                            <a href="{{ url('/admin/'.$page->slug.'/stats') }}" target="_blank">Estatísticas</a>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
