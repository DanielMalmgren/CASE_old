@extends('layouts.app')

@section('content')

<script type="text/javascript">
    $(function() {
        $('#workplace').change(function(){
            var selectedValue = $(this).val();
            $("#settings").load("/wpsettingsajax/" + selectedValue);
        });
    });
</script>

    <H1>Inställningar för arbetsplats</H1>

    @if(count($workplaces) > 0)
        <select class="custom-select d-block w-100" id="workplace" name="workplace" required="">
            <option disabled selected>Välj arbetsplats...</option>
            @foreach($workplaces as $workplace)
                <option value="{{$workplace->id}}">{{$workplace->name}}</option>
            @endforeach
        </select>
    @endif

    <div id="settings"></div>

@endsection
