<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo mb-4">
        <a href="#" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('assets/img/logo4fix.png') }}" alt="Logo" width="40" height="50">
            </span>
            <span class="app-brand-text menu-text fw-bolder ms-2 fs-6">Karya Hutama Oxygen</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Data</span>
        </li>
        <li class="menu-item {{ request()->routeIs('data_botol.*') ? 'active' : '' }}">
            <a href="{{ route('data_botol.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div data-i18n="Analytics">Data Botol</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('data_pelanggan.*') ? 'active' : '' }}">
            <a href="{{ route('data_pelanggan.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-circle"></i>
                <div data-i18n="Analytics">Data Pelanggan</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('data_pinjaman.*') ? 'active' : '' }}">
            <a href="{{ route('data_pinjaman.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-money"></i>
                <div data-i18n="Analytics">Data Pinjaman</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('data_supplier.*') ? 'active' : '' }}">
            <a href="{{ route('data_supplier.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-car"></i>
                <div data-i18n="Analytics">Data Supplier</div>
            </a>
        </li>

        <!-- Components -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Botol</span></li>
        <li class="menu-item {{ request()->routeIs('transaksi_isi_botol.create') ? 'active' : '' }}">
            <a href="{{ route('transaksi_isi_botol.create') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-transfer"></i>
                <div data-i18n="Analytics">Transaksi Isi Botol</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('transaksi_isi_botol.index') ? 'active' : '' }}">
            <a href="{{ route('transaksi_isi_botol.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-list-ul"></i>
                <div data-i18n="Analytics">Crosscheck Isi Botol</div>
            </a>
        </li>
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Lainnya</span></li>
        <li class="menu-item {{ request()->routeIs('data_pinjaman.create') ? 'active' : '' }}">
            <a href="{{ route('data_pinjaman.create') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Analytics">Form Pinjaman</div>
            </a>
        </li>
    </ul>
</aside>
