<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                   @if(Auth::guard('admin')->user()->avatar)
                    <img alt="Avatar" style="max-width: 80px;" class="img-fluid rounded-circle" src="{{ asset('images/admin/'.Auth::guard('admin')->user()->avatar) }}"/>
                    @else
                    <img alt="Avatar" style="max-width: 80px;" class="img-fluid rounded-circle" src="{{ asset('images/default_avatar.png') }}"/>
                    @endif


                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="block m-t-xs font-bold">{{ Auth::guard('admin')->user()->name }}</span>
                        <span class="text-muted text-xs block">{{ Auth::guard('admin')->user()->role->role_name }}<b class="caret"></b></span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
<!--                         <li><a class="dropdown-item" href="profile.html">Profile</a></li> -->
                        <li><a class="dropdown-item" href="{{ route('change.password')}}">Change Password</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.logout') }}">Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    SG+
                </div>
            </li>
       
            <li @if(Route::is('admin.dashboard')) class="active" @endif>
                <a href="{{ route('admin.dashboard') }}"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
            </li>

            @php
                    $side_menu = sideMenu(Auth::guard('admin')->user()->role_id) 
            @endphp

            @foreach($side_menu as $value) 

            @if(count($value['sub_menu'])>0)
            <li class="parent">
                <a href="#"><i class="fa {{ $value['icon'] }}"></i> <span class="nav-label">{{ $value['name'] }}</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                        @foreach($value['sub_menu'] as $sub)
                        <li @if(Route::currentRouteName() == $sub->menu_url) class="active active_url" @endif>
                            <a href="{{ $sub->menu_url ? route($sub->menu_url) : '' }}" >
                                {{ $sub->name }}
                            </a>
                      
                        </li>         
                        @endforeach
                </ul>
            </li>

            @else 

            <li @if(Route::currentRouteName() == $value['url']) class="active" @endif>
                <a href="{{ $value['url'] ? route($value['url']) : '' }}">
                    <i class="fa {{ $value['icon'] }}"></i>
                    <span>{{ $value['name'] }}</span>
                </a>
            </li> 


            @endif

            @endforeach
        </ul>
    </div>
</nav>