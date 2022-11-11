@extends('admin.template')

@section('title', 'B7Bio | 123 | Links')

@section('content')

    <h2>Página: 123</h2>

    <div class="area">
        <div class="leftside">
            <ul class="d-flex">
                <li @if ($menu=='links') class="active" @endif><a class="list-group-item" href="{{ url('/admin/123/links') }}">Links</a></li>
                <li @if ($menu=='design') class="active" @endif><a class="list-group-item" href="{{ url('/admin/123/design') }}">Aparência</a></li>
                <li @if ($menu=='stats') class="active" @endif><a class="list-group-item" href="{{ url('/admin/123/stats') }}">Estatísticas</a></li>
            </ul>

            @yield('body')

        </div>

        <div class="rightside">
            <iframe src="{{ url('/slug') }}" frameborder="0"></iframe>
        </div>
    </div>

@endsection
