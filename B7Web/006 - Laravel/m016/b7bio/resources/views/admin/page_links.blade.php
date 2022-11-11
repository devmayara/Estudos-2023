@extends('admin.page')

@section('body')

    <a class="btn btn-primary btn-lg btn-block" href="{{ url('/admin/'.$page->slug.'/newlink') }}">Novo Link</a>

    <ul id="sortable">
        @foreach ($links as $link)
            <li class="link--item" data-id="{{ $link->id }}">
                <div class="link--item-order">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5zm-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5z"/>
                    </svg>
                </div>
                <div class="link--item-info">
                    <div class="link--item-title">{{ $link->title }}</div>
                    <div class="link--item-href">{{ $link->href }}</div>
                </div>
                <div class="link--item-buttons">
                    <a class="btn btn-success" href="{{ url('/admin/'.$page->slug.'/editlink/'.$link->id) }}">Editar</a>
                    <a class="btn btn-danger" href="{{ url('/admin/'.$page->slug.'/dellink/'.$link->id) }}">Excluir</a>
                </div>
            </li>
        @endforeach
    </ul>

    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
    $( function() {
      $( "#sortable" ).sortable();
        onEnd: async (e) => {
            let id = e.item.getAttribute('data-id');
            let link = `{{url('/admin/linkorder/${id}/${e.newindex}')}}`;
            await fetch(link);
            window.location.href = window.location.href;
        }
    } );
    </script>

@endsection
