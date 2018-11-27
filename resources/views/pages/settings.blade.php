@extends('layouts.app')

@section('content')

<div class="col-md-5 mb-3">
    <H1>Inställningar</H1>

    <form method="post" action="{{action('PagesController@storeSettings')}}" accept-charset="UTF-8">
        @csrf

        <div class="mb-3">
            <label for="locale">Språk</label>
            <select class="custom-select d-block w-100" id="locale" name="locale" required="">
                @foreach($locales as $locale)
                    @if($locale->id === $user->locale_id)
                        <option value="{{$locale->id}}" selected>{{$locale->name}}</option>
                    @else
                        <option value="{{$locale->id}}">{{$locale->name}}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary btn-lg btn-block" type="submit">Spara</button>
    </form>
</div>

@endsection
