@extends('layouts.app')

@section('title', __('Aktiva sessioner'))

@section('content')

<meta http-equiv="refresh" content="10" />

<div class="card">
    <div class="card-body">

        <h1>@lang('Aktiva sessioner')</h1><br>

        <table class="table">
            <thead>
              <tr>
                <th scope="col">@lang('Tidpunkt')</th>
                <th scope="col">@lang('Namn')</th>
              </tr>
            </thead>
            <tbody>
            @foreach($sessions as $session)
                <tr>
                    <td>{{$session->updated_at}}</td>
                    <td>{{$session->user->name}}</td>
                </tr>
            @endforeach
            </tbody>
          </table>

    </div>
</div>

@endsection
