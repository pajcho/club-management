<ul class="nav nav-sidebar">
    <li{{ ($activeMenu == 'members' ? ' class="active"' : '') }}>{{ HTML::decode(link_to_route('member.index', '<i class="glyphicon glyphicon-user"></i> Members')) }}</li>
    <li>{{ HTML::decode(link_to_route('member.create', '<i class="glyphicon glyphicon-plus"></i>  Create Member')) }}</li>
    <li class="nav-divider"></li>
    <li{{ ($activeMenu == 'groups' ? ' class="active"' : '') }}>{{ HTML::decode(link_to_route('group.index', '<i class="glyphicon glyphicon-bookmark"></i>  Groups')) }}</li>
    <li>{{ HTML::decode(link_to_route('group.create', '<i class="glyphicon glyphicon-plus"></i>  Create Group')) }}</li>
    <li class="nav-divider"></li>
    <li{{ ($activeMenu == 'settings' ? ' class="active"' : '') }}>{{ HTML::decode(link_to_route('settings.index', '<i class="glyphicon glyphicon-cog"></i>  Settings')) }}</li>
    <li class="nav-divider"></li>
    <li>{{ HTML::decode(link_to_route('logout', '<i class="glyphicon glyphicon-off"></i> Logout')) }}</li>
</ul>