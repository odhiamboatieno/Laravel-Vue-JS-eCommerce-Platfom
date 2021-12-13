<div class="profile bg-shadow xs-mb15">
    <div class="user-avatar">
        <!-- start avatar -->
        <a href="{{ route('user.profile') }}" title="{{ Auth::user()->name }}">
            @php 
            $user_photo = Auth::user()->avatar ? Auth::user()->avatar : 'default_avatar.png';
            @endphp
            <img src="{{ asset('images/avatar/'.$user_photo ) }}" class="img-fluid" alt="{{ Auth::user()->name }}">
        </a>
        <p>{{ Auth::user()->name }}</p>
        <!--end avatar -->
    </div>
      <nav>
          <ul>
              <li @if(Route::is('user.profile')) class="active" @endif><a href="{{ route('user.profile') }}"> <i class='lni lni-alarm-clock'></i> Deshboard</a></li>

              <li @if(Route::is('user.information')) class="active" @endif><a href="{{ route('user.information') }}"><i class='lni lni-user'></i> Information Update </a></li>

              <li @if(Route::is('user.order')) class="active" @endif><a href="{{ route('user.order') }}"> <i class='lni lni-menu'></i> Order History</a></li>


              <li @if(Route::is('my.coupon')) class="active" @endif><a href="{{ route('my.coupon') }}"> <i class="lni lni-coin"></i> Coupon Code</a></li>

<!--                         <li><a href="#"><i class='lni lni-wallet'></i> Wallet</a></li>

              <li><a href="#"> <i class='lni lni-cart'></i> Order View</a></li> -->

              <li @if(Route::is('user.change.password')) class="active" @endif><a href="{{ route('user.change.password')}}"> <i class='lni lni-pencil-alt'></i> Change Password</a></li>

              <li><a href="{{ route('user.logout') }}"> <i class='lni lni-lock'></i> Signout</a></li>
          </ul>
      </nav>
  </div>
