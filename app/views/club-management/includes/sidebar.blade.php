<ul class="nav nav-sidebar">
    <li{{ (Request::is('member*') ? ' class="active"' : '') }}>{{ link_to_route('member.index', 'Members') }}</li>
    <li>{{ link_to_route('member.create', 'Create Member') }}</li>
</ul>
<ul class="nav nav-sidebar">
    <li><a href="#">Reports</a></li>
</ul>
