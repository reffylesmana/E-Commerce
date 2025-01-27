<aside class="main-sidebar bg-white">
  <!-- Brand Logo -->
  <a href="#" class="brand-link mt-2" style="margin-left:-1rem;">
    <div class="brand-logo">
      <img src="{{ asset('img/logo.png') }}" alt="TeknoShop Logo" class="logo h-8 w-auto">
    </div>
  </a>
  <hr class="mt-2">

  <!-- Sidebar -->
  <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
              <li class="nav-item menu-open">
                  <li class="nav-item side-bar mt-4 {{ Request::routeIs('seller.dashboard') ? 'aktif' : '' }}">
                      <a href="{{ route('seller.dashboard') }}" class="nav-link">
                          <i class="nav-icon fas fa-th-large"></i>
                          <p>Dashboard</p>
                      </a>
                  </li>
              </li>
          </ul>
      </nav>
      <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
