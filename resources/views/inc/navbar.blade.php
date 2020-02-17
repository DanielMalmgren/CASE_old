
<script>
    $(function() {
        $('body').on( 'click', 'a#logout', function( event ) {
            //window.location.replace("/logout");
            var wnd = window.open("{{env('SAML2_IDP_HOST')}}/wa/logout");
            //wnd.close();
            setTimeout(function() {
                wnd.close(); // detta och raden under körs efter 3 sekunder
                window.location.replace("/logout");
            }, 100);
            return false;
        });

        $('.global-search').select2({
            width: '200px',
            placeholder: "@lang('Sök')",
            ajax: {
                url: '/select2search',
                dataType: 'json'
            },
            language: "{{substr(App::getLocale(), 0, 2)}}",
            minimumInputLength: 3,
            theme: "bootstrap4"
        });

        $('.global-search').on('select2:select', function (e) {
            window.location = e.params.data.url;
        });
    });
</script>

<!-- Mobile Header -->
<div class="wsmobileheader clearfix ">
    <a id="wsnavtoggle" class="wsanimated-arrow"><span></span></a>
    <span class="smllogo"><a href="#"><img src="{{env('HEADER_LOGO')}}"></a></span>
</div>
<!-- Mobile Header -->

<div class="wsmainfull clearfix">
    <div class="wsmainwp clearfix">

        <div class="desktoplogo"><a href="{{env('APP_URL')}}"><img src="{{env('HEADER_LOGO')}}"></a></div>

        <!--Main Menu HTML Code-->
        <nav class="wsmenu clearfix">
            <ul class="wsmenu-list">
                @hasanyrole('Registrerad|Admin')
                    <li aria-haspopup="false"><a href="{{env('APP_URL')}}" class="menuhomeicon {{ request()->is('/') ? 'active' : '' }}"><i class="fa fa-home"></i><span class="hometext">&nbsp;&nbsp;@lang('Hem')</span></a></li>
                    <li aria-haspopup="false"><a href="{{env('APP_URL')}}/tracks" class="{{ request()->is('tracks') ? 'active' : '' }}"></i>@lang('Spår')</a></li>
                    @if (session()->has('authnissuer'))
                        <li aria-haspopup="true"><a href="#"><i class="fa fa-angle-right"></i>@lang('Administration')</a>
                            <ul class="sub-menu">
                                @can('use administration')
                                    @can('manage users')
                                        <li aria-haspopup="false"><a href="/users">@lang('Användare')</a></li>
                                    @endcan
                                    @canany(['add workplaces','edit workplaces'])
                                        <li aria-haspopup="false"><a href="workplace">@lang('Arbetsplatsinställningar')</a></li>
                                    @endcanany
                                    <li aria-haspopup="false"><a href="projecttime/create">@lang('Registrera projekttid')</a></li>
                                    <li aria-haspopup="false"><a href="timeattest/create">@lang('Attestera projekttid')</a></li>
                                    @can('export ESF report')
                                        <li aria-haspopup="false"><a href="timesummary">@lang('Sammanställning till ESF')</a></li>
                                    @endcan
                                @endcan
                                <li aria-haspopup="false"><a href="statistics">@lang('Statistik')</a></li>
                            </ul>
                        </li>
                    @endif
                    <li aria-haspopup="true"><a href="#"><i class="fa fa-angle-right"></i>{{Auth::user()->firstname}}</a>
                        <ul class="sub-menu">
                            <li aria-haspopup="false"><a href="{{env('APP_URL')}}/settings">@lang('Inställningar')</a></li>
                            <li aria-haspopup="false"><a href="{{env('APP_URL')}}/feedback">@lang('Feedback')</a></li>
                            {{--<li aria-haspopup="false"><a href="/projecttime/createsingleuser">@lang('Registrera projekttid')</a></li>
                            <li aria-haspopup="false"><a href="/timeattestlevel1/create">@lang('Attestera projekttid')</a></li>--}}
                            <li aria-haspopup="false"><a href="#" id="logout">@lang('Logga ut')</a></li>
                        </ul>
                    </li>
                @endhasanyrole
                <li aria-haspopup="true"><a href="#"><i class="fa fa-angle-right"></i>@lang('Hjälp')</a>
                    <ul class="sub-menu">
                        <li aria-haspopup="false"><a target="_blank" href="pdf/Evikomp%20användarmanual.pdf">@lang('Användarmanual')</a></li>
                        @can('use administration')
                            <li aria-haspopup="false"><a target="_blank" href="pdf/Evikomp%20administratörsmanual.pdf">@lang('Administratörsmanual')</a></li>
                        @endcan
                        @hasrole('Admin')
                            <li aria-haspopup="false"><a target="_blank" href="pdf/Evikomp%20intern%20manual.pdf">@lang('Intern manual')</a></li>
                        @endhasrole
                        <li aria-haspopup="false"><a target="_blank" href="https://www.linkoping.se/utforarwebben/vard-stod-och-omsorg/forskning-och-utveckling/pagaende-projekt/">@lang('Om CASE')</a></li>
                    </ul>
                </li>
                @hasanyrole('Registrerad|Admin')
                    <li class="search-wrapper" aria-haspopup="false"><select class="global-search"></select></li>
                @endhasanyrole
            </ul>
        </nav>
        <!--Menu HTML Code-->

    </div>
</div>

<div class="wsheader-fill"></div>
