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
                <li>{{ link_to_route('logout', 'Logout') }}</li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li>{{ link_to_route('member.index', 'Members') }}</li>
                <li>{{ link_to_route('group.index', 'Groups') }}</li>
                <li>{{ link_to_route('settings.index', 'Settings') }}</li>
            </ul>
        </div>
    </div>
</div>