<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse:first">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            {{ link_to('/', site_title(), array('class' => 'navbar-brand')) }}
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    {{ HTML::decode(link_to('#', $currentUser->full_name . ' <b class="caret"></b>', array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'))) }}
                    <ul class="dropdown-menu">
                        <li>{{ HTML::decode(link_to_route('user.show', '<i class="fa fa-pencil"></i> Edit Profile', $currentUser->id)) }}</li>
                        <li class="nav-divider"></li>
                        <li>{{ HTML::decode(link_to_route('logout', '<i class="fa fa-power-off"></i> Logout</span>')) }}</li>
                    </ul>
                </li>

                <li{{ ($activeMenu == 'members' ? ' class="active"' : '') }}>
                    {{ HTML::decode(link_to('#', '<i class="fa fa-user"></i> Members <b class="caret"></b>', array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'))) }}
                    <ul class="dropdown-menu">
                        <li>{{ HTML::decode(link_to_route('member.index', 'Members')) }}</li>
                        <li>{{ HTML::decode(link_to_route('member.create', 'Create Member')) }}</li>
                        <li class="nav-divider"></li>
                        <li{{ ($activeMenu == 'results' ? ' class="active"' : '') }}>{{ HTML::decode(link_to_route('result.index', 'Results')) }}</li>
                        <li>{{ HTML::decode(link_to_route('result.create', 'Add New Result')) }}</li>
                        <li{{ ($activeMenu == 'result.categories' ? ' class="active"' : '') }}>{{ HTML::decode(link_to_route('result.category.index', 'Result Categories')) }}</li>
                        <li>{{ HTML::decode(link_to_route('result.category.create', 'Create Result Category')) }}</li>
                    </ul>
                </li>

                <li{{ ($activeMenu == 'groups' ? ' class="active"' : '') }}>
                    {{ HTML::decode(link_to('#', '<i class="fa fa-users"></i> Groups <b class="caret"></b>', array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'))) }}
                    <ul class="dropdown-menu">
                        <li>{{ HTML::decode(link_to_route('group.index', 'Groups')) }}</li>
                        @if($currentUser->isAdmin())
                            <li>{{ HTML::decode(link_to_route('group.create', 'Create Group')) }}</li>
                        @endif
                    </ul>
                </li>
                @if($currentUser->isAdmin())
                    <li{{ ($activeMenu == 'users' ? ' class="active"' : '') }}>
                        {{ HTML::decode(link_to('#', '<i class="fa fa-male"></i> Users <b class="caret"></b>', array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'))) }}
                        <ul class="dropdown-menu">
                            <li>{{ HTML::decode(link_to_route('user.index', 'Users')) }}</li>
                            <li>{{ HTML::decode(link_to_route('user.create', 'Create User')) }}</li>
                        </ul>
                    </li>
                    <li{{ ($activeMenu == 'settings' ? ' class="active"' : '') }}>
                        {{ HTML::decode(link_to('#', '<i class="fa fa-cog"></i> Settings <b class="caret"></b>', array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'))) }}
                        <ul class="dropdown-menu">
                            <li>{{ HTML::decode(link_to_route('settings.index', 'Settings')) }}</li>
                            <li>{{ HTML::decode(link_to_route('settings.clear.cache', 'Clear Cache')) }}</li>
                        </ul>
                    </li>
                    <li {{ ($activeMenu == 'history' ? ' class="active"' : '') }}>{{ HTML::decode(link_to_route('history.index', '<i class="fa fa-cloud" title="History" data-placement="bottom"></i> <span class="hidden-sm">History</span>')) }}</li>
                @endif
            </ul>
        </div>
    </div>
</div>