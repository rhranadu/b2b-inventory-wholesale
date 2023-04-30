@if (\Session::has('layouts') && \Session::has('template_name'))
    @if (\Session::get('template_name') == 'Notika')
        @include('component.header')
        @yield('main_content')
        @include('component.footer')
    @else
        @include(\Session::get('layouts'))
    @endif
@else
    @include('layouts.metronic.master')
@endif
