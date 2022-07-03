@if (\Request::is($href))
    <li class="active">
@else
    <li class="item">
@endif
    <a href="{{$href}}" >
        <i class="material-icons" style="color: #e91e63;">{{$icon}}</i>
        <span class="menu-icon">{{$text}}</span>
    </a>
</li>
