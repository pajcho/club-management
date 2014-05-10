<ul class="nav nav-sidebar">
    <li{{ ($activeMenu == 'members' ? ' class="active"' : '') }}>{{ HTML::decode(link_to_route('member.index', '<i class="glyphicon glyphicon-user"></i> Members')) }}</li>
    <li>{{ HTML::decode(link_to_route('member.create', '<i class="glyphicon glyphicon-plus"></i> Create Member')) }}</li>
    <li class="nav-divider"></li>
    <li{{ ($activeMenu == 'groups' ? ' class="active"' : '') }}>{{ HTML::decode(link_to_route('group.index', '<i class="glyphicon glyphicon-bookmark"></i> Groups')) }}</li>
    @if($currentUser->isAdmin())
        <li>{{ HTML::decode(link_to_route('group.create', '<i class="glyphicon glyphicon-plus"></i> Create Group')) }}</li>
        <li class="nav-divider"></li>
        <li{{ ($activeMenu == 'users' ? ' class="active"' : '') }}>{{ HTML::decode(link_to_route('user.index', '<i class="glyphicon glyphicon-bookmark"></i> Users')) }}</li>
        <li>{{ HTML::decode(link_to_route('user.create', '<i class="glyphicon glyphicon-plus"></i> Create User')) }}</li>
        <li class="nav-divider"></li>
        <li{{ ($activeMenu == 'results' ? ' class="active"' : '') }}>{{ HTML::decode(link_to_route('result.index', '<i class="glyphicon glyphicon-stats"></i> Results')) }}</li>
        <li>{{ HTML::decode(link_to_route('result.create', '<i class="glyphicon glyphicon-plus"></i> Add New Result')) }}</li>
        <li{{ ($activeMenu == 'result.categories' ? ' class="active"' : '') }}>{{ HTML::decode(link_to_route('result.category.index', '<i class="glyphicon glyphicon-align-justify"></i> Result Categories')) }}</li>
        <li>{{ HTML::decode(link_to_route('result.category.create', '<i class="glyphicon glyphicon-plus"></i> Create Result Category')) }}</li>
        <li class="nav-divider"></li>
        <li{{ ($activeMenu == 'settings' ? ' class="active"' : '') }}>{{ HTML::decode(link_to_route('settings.index', '<i class="glyphicon glyphicon-cog"></i> Settings')) }}</li>
        <li{{ ($activeMenu == 'history' ? ' class="active"' : '') }}>{{ HTML::decode(link_to_route('history.index', '<i class="glyphicon glyphicon-cloud"></i> History')) }}</li>
    @endif
    <li class="nav-divider"></li>
    <li>{{ HTML::decode(link_to_route('logout', '<i class="glyphicon glyphicon-off"></i> Logout')) }}</li>
</ul>