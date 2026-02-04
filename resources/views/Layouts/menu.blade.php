@php
    $active = $active ?? '';
    $settings = ['settings', 'user', 'category', 'role', 'company', 'exchange', 'unit'];
    $inventoryMenus = ['products', 'low'];
    $sales=array('customer');
@endphp

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ url('/') }}" class="brand-link">
        <img src="{{ asset('logo.jpg') }}" class="brand-image img-circle elevation-3" style="opacity: .8" />
        <span class="brand-text font-weight-light">My Stock</span>
    </a>

    <div class="sidebar">
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search" />
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>
 <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ ($active == 'home') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ __('Dashboard') }}

</p>
                    </a>
                </li>
                <li class="nav-item has-treeview  {{in_array($active,$sales)?'menu-open':''}}">
                            <a href="#" class="nav-link {{ ($active == 'sale') ? 'active' : '' }}">
                               <i class="nav-icon fas fa-money-bill-wave"></i>
                                <p>{{ __('Sales') }}</p>
                                 <i class="right fas fa-angle-left"></i>
                            </a>
                            <ul class="nav nav-treeview">
                             <li class="nav-item">
                            <a href="{{ route('customer.index') }}" class="nav-link {{ ($active == 'customers') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-box"></i>
                                <p>{{ __('Customers') }}</p>
                            </a>
                        </ul>
                        </li>
                <li class="nav-item has-treeview {{ in_array($active, $settings) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ in_array($active, $settings) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                           {{ __('Setting')}}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('user.index') }}" class="nav-link {{ ($active == 'user') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>{{ __('Users') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('category.index') }}" class="nav-link {{ ($active == 'category') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-list"></i>
                                <p>{{ __('Categories') }}</p>
                            </a>
                        </li>
                         
                        <li class="nav-item">
                            <a href="{{ route('role.index') }}" class="nav-link {{ ($active == 'role') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-tag"></i>
                                <p>{{ __('Roles') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('company.index') }}" class="nav-link {{ ($active == 'company') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-building"></i>
                                <p>Company Info</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('exchange.index') }}" class="nav-link {{ ($active == 'exchange') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-exchange-alt"></i>
                               <p>{{ __('Exchanges') }}</p>

                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('unit.index') }}" class="nav-link {{ ($active == 'unit') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-balance-scale"></i>
                                <p>{{ __('Units') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="nav-item has-treeview {{ in_array($active, $inventoryMenus) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ in_array($active, $inventoryMenus) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p>
                            {{ __('Inventory') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('product.low') }}" class="nav-link {{ ($active == 'low') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-exclamation-triangle"></i>
                                <p>{{ __('Low Stock') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('product.index') }}" class="nav-link {{ ($active == 'products') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-box"></i>
                                <p>{{ __('Products') }}</p>
                            </a>
                        </li>
                        
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>