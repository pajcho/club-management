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
                <li class="active">
                    <a href="" onclick="return false;">Logged in as: {{ $currentUser->full_name }}</a>
                </li>

                <li{{ ($activeMenu == 'members' ? ' class="active"' : '') }}>
                    {{ HTML::decode(link_to('#', '<i class="glyphicon glyphicon-user"></i> Members <b class="caret"></b>', array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'))) }}
                    <ul class="dropdown-menu">
                        <li>{{ HTML::decode(link_to_route('member.index', 'Members')) }}</li>
                        <li>{{ HTML::decode(link_to_route('member.create', 'Create Member')) }}</li>
                    </ul>
                </li>

                <li{{ ($activeMenu == 'groups' ? ' class="active"' : '') }}>
                    {{ HTML::decode(link_to('#', '<i class="glyphicon glyphicon-bookmark"></i> Groups <b class="caret"></b>', array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'))) }}
                    <ul class="dropdown-menu">
                        <li>{{ HTML::decode(link_to_route('group.index', 'Groups')) }}</li>
                        @if($currentUser->isAdmin())
                            <li>{{ HTML::decode(link_to_route('group.create', 'Create Group')) }}</li>
                        @endif
                    </ul>
                </li>
                @if($currentUser->isAdmin())
                    <li{{ ($activeMenu == 'users' ? ' class="active"' : '') }}>
                        {{ HTML::decode(link_to('#', '<i class="glyphicon glyphicon-bookmark"></i> Users <b class="caret"></b>', array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'))) }}
                        <ul class="dropdown-menu">
                            <li>{{ HTML::decode(link_to_route('user.index', 'Users')) }}</li>
                            <li>{{ HTML::decode(link_to_route('user.create', 'Create User')) }}</li>
                        </ul>
                    </li>
                    <li{{ ($activeMenu == 'settings' ? ' class="active"' : '') }}>{{ HTML::decode(link_to_route('settings.index', '<i class="glyphicon glyphicon-cog"></i> Settings')) }}</li>
                @endif
                <li>{{ HTML::decode(link_to_route('logout', '<i class="glyphicon glyphicon-off"></i> Logout')) }}</li>
            </ul>
        </div>
    </div>
</div>