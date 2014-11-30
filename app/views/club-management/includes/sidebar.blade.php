<ul class="nav nav-sidebar">
    @if($currentUser->isAdmin())
        <li{{ ($activeMenu == 'dashboard' ? ' class="active"' : '') }}>{{ HTML::decode(link_to_route('dashboard.index', '<i class="fa fa-line-chart"></i> Dashboard')) }}</li>
        <li class="nav-divider"></li>
    @endif
    <li{{ ($activeMenu == 'members' ? ' class="active"' : '') }}>{{ HTML::decode(link_to_route('member.index', '<i class="fa fa-user"></i> Members')) }}</li>
    <li>{{ HTML::decode(link_to_route('member.create', '<i class="fa fa-plus"></i> Create Member')) }}</li>
    <li class="nav-divider"></li>
    <li{{ ($activeMenu == 'groups' ? ' class="active"' : '') }}>{{ HTML::decode(link_to_route('group.index', '<i class="fa fa-users"></i> Groups')) }}</li>
    @if($currentUser->isAdmin())
        <li>{{ HTML::decode(link_to_route('group.create', '<i class="fa fa-plus"></i> Create Group')) }}</li>
        <li class="nav-divider"></li>
        <li{{ ($activeMenu == 'users' ? ' class="active"' : '') }}>{{ HTML::decode(link_to_route('user.index', '<i class="fa fa-male"></i> Users')) }}</li>
        <li>{{ HTML::decode(link_to_route('user.create', '<i class="fa fa-plus"></i> Create User')) }}</li>
        <li class="nav-divider"></li>
        <li{{ ($activeMenu == 'results' ? ' class="active"' : '') }}>{{ HTML::decode(link_to_route('result.index', '<i class="fa fa-trophy"></i> Results')) }}</li>
        <li>{{ HTML::decode(link_to_route('result.create', '<i class="fa fa-plus"></i> Add New Result')) }}</li>
        <li{{ ($activeMenu == 'result.categories' ? ' class="active"' : '') }}>{{ HTML::decode(link_to_route('result.category.index', '<i class="fa fa-bars"></i> Result Categories')) }}</li>
        <li>{{ HTML::decode(link_to_route('result.category.create', '<i class="fa fa-plus"></i> Create Result Category')) }}</li>
        <li class="nav-divider"></li>
        <li{{ ($activeMenu == 'settings' ? ' class="active"' : '') }}>{{ HTML::decode(link_to_route('settings.index', '<i class="fa fa-cog"></i> Settings')) }}</li>
        <li{{ ($activeMenu == 'history' ? ' class="active"' : '') }}>{{ HTML::decode(link_to_route('history.index', '<i class="fa fa-cloud"></i> History')) }}</li>
    @endif
    <li class="nav-divider"></li>
    <li>{{ HTML::decode(link_to_route('logout', '<i class="fa fa-power-off"></i> Logout')) }}</li>
</ul>