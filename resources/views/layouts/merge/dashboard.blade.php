{{-- Header --}}
@include('layouts._include.dashboard.header')

{{-- Aside --}}
@if (auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('Secretaria')))
    @include('layouts._include.dashboard.aside-admin')
@endif

{{-- Nav --}}
@include('layouts._include.dashboard.nav')


{{-- Content --}}
@yield('content')

{{-- Footer --}}
@include('layouts._include.dashboard.footer')

