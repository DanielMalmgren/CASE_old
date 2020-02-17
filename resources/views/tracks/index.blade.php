@extends('layouts.app')

@section('title', __('Spår'))

@section('content')

    <H1>@lang('Spår')</H1>
    @if($showall)
        <small><a class="black" href="/settings">@lang('Visar nu tillfälligt samtliga spår. För att lägga till spår permanent i listan, gå till dina inställningar.')</a></small>
    @else
        <small><a class="black" href="?showall=1">@lang('Visar endast dina valda spår. Klicka här för att visa samtliga spår.')</a></small>
    @endif

    @if(count($tracks) > 0)
        <ul class="list-group mb-3 tracks">
            @foreach($tracks as $track)
                <li class="list-group-item d-flex justify-content-between lh-condensed nopadding">
                    <a href="{{env('APP_URL')}}/track/{{$track->id}}">
                        <h6 class="my-0">{{$track->translateOrDefault(App::getLocale())->name}}</h6>
                        <small class="text-muted">{{$track->translateOrDefault(App::getLocale())->subtitle}}</small>
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <br>
        @lang('Du har inga spår valda. Gå till dina inställningar för att välja vilka spår som ska visas här!')
    @endif

@endsection
