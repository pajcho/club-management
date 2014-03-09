<ul class="nav nav-sidebar">
    <li{{ ($activeMenu == 'members' ? ' class="active"' : '') }}>{{ link_to_route('member.index', 'Members') }}</li>
    <li>{{ link_to_route('member.create', 'Create Member') }}</li>
</ul>
<ul class="nav nav-sidebar">
    <li{{ ($activeMenu == 'groups' ? ' class="active"' : '') }}>{{ link_to_route('group.index', 'Groups') }}</li>
    <li>{{ link_to_route('group.create', 'Create Group') }}</li>
</ul>
<ul class="nav nav-sidebar">
    <li{{ ($activeMenu == 'settings' ? ' class="active"' : '') }}>{{ link_to_route('settings.index', 'Settings') }}</li>
</ul>
<ul class="nav nav-sidebar">
    <li><a href="#">Reports</a></li>
</ul>
