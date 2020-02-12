@extends('layouts.app')

@section('title', __('Startsida'))

@section('content')

@if($should_attest)
    <div class="new border border-danger importantnotification">
        <h1>@lang('Du har tid att attestera för') {{$monthstr}}!</h1>

        <a href="/timeattestlevel1/create" class="btn btn-primary">@lang('Gå till attesteringen')</a>
        <br>

    </div>
    <br>
@endif

@if($lesson)
    <h1>@lang('Nästa lektion')</h1>
    @include('inc.listlesson')
    <br>
@endif

<H1>@lang('Nyheter')</H1>

@if(count($announcements) > 0)
    <div class="list-group mb-4">
        @foreach($announcements as $announcement)
            <a href="announcements/{{$announcement->id}}" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{$announcement->heading}}</h5>
                    <small>{{\Carbon\Carbon::parse($announcement->created_at)->format('Y-m-d')}}</small>
                </div>
                <p class="mb-1">{{$announcement->preamble}}</p>
            </a>
        @endforeach
      </div>
@endif

@can('manage announcements')
    <a href="announcements/create" class="btn btn-primary">@lang('Skapa nytt meddelande')</a>
@endcan

@endsection
