@extends('admin.template')

@section('title', 'B7Bio | 123 | Links')

@section('content')

    Página: 123

    <ul>
        <li><a href="{{ url('/admin/123/links') }}">Links</a></li>
        <li><a href="{{ url('/admin/123/design') }}">Aparência</a></li>
        <li><a href="{{ url('/admin/123/stats') }}">Estatísticas</a></li>
    </ul>

    @yield('body')

    <div>
        <iframe src="{{ url('/slug') }}" frameborder="0"></iframe>
    </div>

@endsection
