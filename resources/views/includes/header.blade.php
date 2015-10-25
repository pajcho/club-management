<div class="navbar navbar-inverse navbar-fixed-top app-header" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header app-header-logo">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse:first">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            {!! link_to('/', site_title(), array('class' => 'navbar-brand')) !!}

            <div class="mobile visible-xs app-header-search">
                <app-search></app-search>
            </div>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav app-header-menu-items">
                @if($currentUser->isAdmin())
                    <li {{ ($activeMenu == 'dashboard' ? ' class="active"' : '') }}>{!! Html::decode(link_to_route('dashboard.index', '<i class="fa fa-line-chart" title="Dashboard" data-placement="bottom"></i> <span class="hidden-sm hidden-md">Dashboard</span>')) !!}</li>
                @endif
                <li{{ ($activeMenu == 'members' ? ' class="active"' : '') }}>
                    {!! Html::decode(link_to('#', '<i class="fa fa-user"></i> <span class="hidden-sm">Members</span> <b class="caret"></b>', array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'))) !!}
                    <ul class="dropdown-menu">
                        <li>{!! Html::decode(link_to_route('member.index', 'Members')) !!}</li>
                        <li>{!! Html::decode(link_to_route('member.create', 'Create Member')) !!}</li>
                        <li class="nav-divider"></li>
                        <li{{ ($activeMenu == 'results' ? ' class="active"' : '') }}>{!! Html::decode(link_to_route('result.index', 'Results')) !!}</li>
                        <li>{!! Html::decode(link_to_route('result.create', 'Add New Result')) !!}</li>
                        <li{{ ($activeMenu == 'result.categories' ? ' class="active"' : '') }}>{!! Html::decode(link_to_route('result.category.index', 'Result Categories')) !!}</li>
                        <li>{!! Html::decode(link_to_route('result.category.create', 'Create Result Category')) !!}</li>
                    </ul>
                </li>

                <li{{ ($activeMenu == 'groups' ? ' class="active"' : '') }}>
                    {!! Html::decode(link_to('#', '<i class="fa fa-users"></i> <span class="hidden-sm">Groups</span> <b class="caret"></b>', array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'))) !!}
                    <ul class="dropdown-menu">
                        <li>{!! Html::decode(link_to_route('group.index', 'Groups')) !!}</li>
                        @if($currentUser->isAdmin())
                            <li>{!! Html::decode(link_to_route('group.create', 'Create Group')) !!}</li>
                        @endif
                    </ul>
                </li>
                @if($currentUser->isAdmin())
                    <li{{ ($activeMenu == 'users' ? ' class="active"' : '') }}>
                        {!! Html::decode(link_to('#', '<i class="fa fa-male"></i> <span class="hidden-sm">Users</span> <b class="caret"></b>', array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'))) !!}
                        <ul class="dropdown-menu">
                            <li>{!! Html::decode(link_to_route('user.index', 'Users')) !!}</li>
                            <li>{!! Html::decode(link_to_route('user.create', 'Create User')) !!}</li>
                        </ul>
                    </li>
                    <li{{ ($activeMenu == 'settings' ? ' class="active"' : '') }}>
                        {!! Html::decode(link_to('#', '<i class="fa fa-cog"></i> <span class="hidden-sm">Settings</span> <b class="caret"></b>', array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'))) !!}
                        <ul class="dropdown-menu">
                            <li>{!! Html::decode(link_to_route('settings.index', 'Settings')) !!}</li>
                            <li>{!! Html::decode(link_to_route('settings.clear.cache', 'Clear Cache')) !!}</li>
                        </ul>
                    </li>
                    <li {{ ($activeMenu == 'history' ? ' class="active"' : '') }}>{!! Html::decode(link_to_route('history.index', '<i class="fa fa-cloud" title="History" data-placement="bottom"></i> <span class="hidden-sm hidden-md">History</span>')) !!}</li>
                @endif

                <li class="hidden-xs app-header-search">
                  <div class="col-xs-12">
                     <app-search></app-search>
                  </div>
                </li>
            </ul>

            <ul class="nav navbar-nav app-header-user">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle user-avatar" data-toggle="dropdown">
                        <img class="img-circle" width="40" height="40" src="{{ $currentUser->gravatar }}" />
                        <span class="hidden-sm">{{ $currentUser->full_name }}</span><span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>{!! Html::decode(link_to_route('user.show', '<i class="fa fa-pencil"></i> Edit Profile', $currentUser->id)) !!}</li>
                        <li>{!! Html::decode(link_to_route('user.attendance.index', '<i class="fa fa-clock-o"></i> Attendance', $currentUser->id)) !!}</li>
                        <li class="nav-divider"></li>
                        <li>{!! Html::decode(link_to_route('logout', '<i class="fa fa-power-off"></i> Logout</span>')) !!}</li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
