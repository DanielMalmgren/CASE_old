
@extends('layouts.app')

@section('title', __('Nyhet'))

@section('content')

    <H1>{{$announcement->heading}}</H1>

    <div class="card">
            <div class="card-body">
                <H2>{{$announcement->preamble}}</H2>

                {!!$announcement->bodytext!!}
            </div>
    </div>

    @can('manage announcements')
        <br>
        <a href="{{env('APP_URL')}}/announcements/{{$announcement->id}}/edit" class="btn btn-primary">@lang('Redigera')</a>
        <br><br>

        <form class="delete" action="{{action('AnnouncementsController@destroy', $announcement->id)}}" method="post">
            @method('delete')
            {{ csrf_field() }}
            <input class="btn btn-primary" type="submit" value="@lang('Radera')">
        </form>

        <script type="text/javascript">

            $(".delete").on("submit", function(){
                return confirm("@lang('Vill du verkligen radera denna nyhet?')");
            });

        </script>
    @endcan

@endsection
