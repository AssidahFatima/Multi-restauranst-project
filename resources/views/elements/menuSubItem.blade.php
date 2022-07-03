@if (\Request::is($href))
    <li class="active">
@else
    <li>
@endif
    <a href="{{$href}}" >
        {{$text}}
    </a>
</li>
