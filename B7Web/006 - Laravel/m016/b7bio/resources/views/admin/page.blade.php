@extends('admin.template')

@section('title', 'B7Bio | '.$page->op_title.' | Links')

@section('content')

    <h2>Página: {{ $page->op_title }}</h2>

    <div class="area">
        <div class="leftside">
            <ul class="d-flex">
                <li @if ($menu=='links') class="active" @endif><a class="list-group-item" href="{{ url('/admin/'.$page->op_title.'/links') }}">Links</a></li>
                <li @if ($menu=='design') class="active" @endif><a class="list-group-item" href="{{ url('/admin/'.$page->op_title.'/design') }}">Aparência</a></li>
                <li @if ($menu=='stats') class="active" @endif><a class="list-group-item" href="{{ url('/admin/'.$page->op_title.'/stats') }}">Estatísticas</a></li>
            </ul>

            @yield('body')

        </div>

        <div class="rightside">
            <iframe src="{{ url('/'.$page->slug.'') }}" frameborder="0"></iframe>
        </div>
    </div>

@endsection
