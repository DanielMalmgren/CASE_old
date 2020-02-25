@extends('layouts.app')

@section('title', $track->translateOrDefault(App::getLocale())->name)

@section('content')

    <H1>{{$track->translateOrDefault(App::getLocale())->name}}</H1>

    @if(count($lessons) > 0)
        <div class="list-group mb-4 lessonslist" id="lessonslist">
            @foreach($lessons as $lesson)
                @include('inc.listlesson')
            @endforeach
        </div>
    @endif

    @can('manage lessons')
        <a href="{{env('APP_URL')}}/lessons/create/{{$track->id}}" class="btn btn-primary">@lang('Lägg till lektion')</a>

        <script type="text/javascript" language="javascript" src="{{asset('vendor/jquery-ui-1.12.1.custom/jquery-ui.min.js')}}"></script>
        <script type="text/javascript">
            $(function() {
                $("#lessonslist").sortable({
                update: function (e, u) {
                    var token = "{{ csrf_token() }}";
                    var data = $(this).sortable('serialize');
                    $.ajax({
                        url: '{{env('APP_URL')}}/lessons/reorder',
                        data : {_token:token,data:data},
                        type: 'POST'
                    });
                }
                });
            });
        </script>
    @endcan

@endsection
