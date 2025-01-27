<nav class="main-header navbar navbar-expand navbar-white navbar-light py-3 fixed-top">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto d-flex align-items-center">
    <!-- Username -->
    <h4 class="username mb-0 mr-3">{{ auth()->user()->name }}</h4>
    
    <!-- Dropdown for Profile Picture -->
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
          @auth
          <div class="relative hidden sm:flex">
              <button class="flex items-center focus:outline-none hover:text-blue-500" id="userDropdown">
                  <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-500 text-white text-sm font-bold" title="{{ Auth::user()->name }}">
                      {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                  </div>
              </button>
          </div>
      @endauth
        </a>
        
        <!-- Dropdown Menu -->
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user me-2"></i> Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </li>
</ul>
</nav>


