<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Sistem Manajemen Inventaris') - InventoryApp</title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --app-sidebar-width: 270px;
            --app-sidebar-bg: #0f172a;
            --app-sidebar-hover: rgba(255, 255, 255, 0.07);
            --app-sidebar-active: #4f46e5;
            --app-bg: #f8fafc;
            --app-card-radius: 16px;
            --app-btn-radius: 10px;
            --app-border-color: #e2e8f0;
            --color-primary: #4f46e5;
            --color-primary-light: #eef2ff;
            --color-success: #10b981;
            --color-success-light: #ecfdf5;
            --color-danger: #ef4444;
            --color-danger-light: #fff1f2;
            --color-warning: #f59e0b;
            --color-warning-light: #fffbeb;
            --color-info: #0ea5e9;
            --color-info-light: #f0f9ff;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--app-bg);
            color: #1e293b;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* App Wrapper: Flex Layout */
        .app-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Sidebar Styling */
        .app-sidebar {
            width: var(--app-sidebar-width);
            flex-shrink: 0;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            color: #94a3b8;
            height: 100vh;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            z-index: 1040;
            overflow-y: auto;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Custom Scrollbar for Sidebar */
        .app-sidebar::-webkit-scrollbar {
            width: 5px;
        }
        .app-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
        }

        /* Sidebar Brand */
        .sidebar-brand {
            padding: 24px 20px 20px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }
        .sidebar-brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.35rem;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
            flex-shrink: 0;
        }
        .sidebar-brand-text {
            font-size: 1.15rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.3px;
            line-height: 1.2;
        }
        .sidebar-brand-sub {
            font-size: 0.72rem;
            color: #64748b;
            font-weight: 500;
            letter-spacing: 0.2px;
        }

        /* User Profile Pill */
        .sidebar-user-card {
            margin: 16px 16px 20px 16px;
            padding: 12px 14px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            position: relative;
        }
        .sidebar-user-avatar::after {
            content: '';
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 10px;
            height: 10px;
            background-color: #10b981;
            border: 2px solid #0f172a;
            border-radius: 50%;
        }
        .sidebar-user-name {
            color: #ffffff;
            font-weight: 600;
            font-size: 0.88rem;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-user-meta {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 4px;
        }

        /* Navigation Groups */
        .sidebar-nav {
            padding: 0 12px;
            flex-grow: 1;
        }
        .sidebar-heading {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            padding: 16px 14px 6px 14px;
            display: block;
        }
        .sidebar-nav-item {
            display: flex;
            align-items: center;
            padding: 9px 12px;
            margin-bottom: 4px;
            border-radius: 10px;
            color: #94a3b8;
            font-weight: 500;
            font-size: 0.88rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .sidebar-nav-item:hover {
            color: #ffffff;
            background-color: var(--app-sidebar-hover);
        }
        .sidebar-nav-item.active {
            color: #ffffff;
            background-color: var(--app-sidebar-active);
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.4);
            font-weight: 600;
        }
        .sidebar-nav-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            margin-right: 10px;
            background: rgba(255, 255, 255, 0.04);
            color: #94a3b8;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }
        .sidebar-nav-item:hover .sidebar-nav-icon {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.1);
        }
        .sidebar-nav-item.active .sidebar-nav-icon {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            background: rgba(0, 0, 0, 0.15);
        }

        /* Main Content Container */
        .app-main {
            flex-grow: 1;
            min-width: 0; /* CRITICAL: Prevents table overflow in flexbox */
            display: flex;
            flex-direction: column;
            background-color: var(--app-bg);
        }

        /* Top Bar */
        .app-topbar {
            background-color: #ffffff;
            border-bottom: 1px solid var(--app-border-color);
            padding: 16px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }
        .topbar-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            letter-spacing: -0.4px;
            line-height: 1.2;
        }
        .topbar-breadcrumb {
            margin: 3px 0 0 0;
            font-size: 0.8rem;
            color: #64748b;
        }
        .topbar-breadcrumb a {
            color: #64748b;
            text-decoration: none;
            transition: color 0.15s;
        }
        .topbar-breadcrumb a:hover {
            color: var(--color-primary);
        }

        /* App Body */
        .app-body {
            padding: 28px;
            flex-grow: 1;
        }

        /* Cards & Styling */
        .card {
            border: 1px solid var(--app-border-color);
            border-radius: var(--app-card-radius);
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03), 0 4px 12px rgba(15, 23, 42, 0.03);
            background-color: #ffffff;
            overflow: hidden;
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }
        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 16px 22px;
            font-weight: 600;
            font-size: 0.95rem;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Unified Table Styling */
        .table {
            margin-bottom: 0;
        }
        .table thead th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-bottom: 1px solid #e2e8f0;
            padding: 14px 18px;
            white-space: nowrap;
        }
        .table tbody td {
            padding: 14px 18px;
            vertical-align: middle;
            color: #334155;
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .table-hover tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Badges System */
        .badge {
            font-weight: 600;
            border-radius: 8px;
            padding: 5px 10px;
            font-size: 0.76rem;
            letter-spacing: 0.2px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .badge-role-admin {
            background-color: #fee2e2;
            color: #dc2626;
            border: 1px solid #fca5a5;
        }
        .badge-role-staff {
            background-color: #e0f2fe;
            color: #0284c7;
            border: 1px solid #bae6fd;
        }
        .badge-stock-in {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
        }
        .badge-stock-out {
            background-color: #ffe4e6;
            color: #e11d48;
            border: 1px solid #fecdd3;
        }
        .badge-low-stock {
            background-color: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }
        .badge-safe-stock {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        /* Buttons */
        .btn {
            border-radius: var(--app-btn-radius);
            font-weight: 500;
            padding: 8px 16px;
            transition: all 0.2s ease;
        }
        .btn-sm {
            padding: 6px 12px;
            font-size: 0.82rem;
            border-radius: 8px;
        }
        .btn-primary {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
        }
        .btn-primary:hover {
            background-color: #4338ca;
            border-color: #4338ca;
        }

        /* Action Buttons on Topbar */
        .btn-action-in {
            background-color: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
            font-weight: 600;
            font-size: 0.84rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 10px;
            text-decoration: none;
        }
        .btn-action-in:hover {
            background-color: #059669;
            color: #ffffff;
            border-color: #059669;
        }
        .btn-action-out {
            background-color: #fff1f2;
            color: #e11d48;
            border: 1px solid #fecdd3;
            font-weight: 600;
            font-size: 0.84rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 10px;
            text-decoration: none;
        }
        .btn-action-out:hover {
            background-color: #e11d48;
            color: #ffffff;
            border-color: #e11d48;
        }

        /* Mobile Sidebar Backdrop */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1030;
        }

        /* Responsive Breakpoints */
        @media (max-width: 991.98px) {
            .app-sidebar {
                position: fixed;
                transform: translateX(-100%);
            }
            .app-sidebar.show {
                transform: translateX(0);
            }
            .sidebar-backdrop.show {
                display: block;
            }
            .app-body {
                padding: 18px;
            }
            .app-topbar {
                padding: 14px 18px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="app-wrapper">
    <!-- Sidebar Navigation -->
    <aside class="app-sidebar" id="appSidebar">
        <!-- Brand Header -->
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <i class="bi bi-box-seam-fill"></i>
            </div>
            <div>
                <div class="sidebar-brand-text">InventoryApp</div>
                <div class="sidebar-brand-sub">Sistem Manajemen Stok</div>
            </div>
        </div>

        <!-- User Profile Card -->
        @auth
        <div class="sidebar-user-card">
            <div class="sidebar-user-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div style="min-width: 0; flex: 1;">
                <div class="sidebar-user-name" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</div>
                <div class="sidebar-user-meta">
                    @if(Auth::user()->isAdmin())
                        <span class="badge badge-role-admin" style="font-size: 0.68rem; padding: 2px 7px;">
                            <i class="bi bi-shield-lock-fill"></i> Admin
                        </span>
                    @else
                        <span class="badge badge-role-staff" style="font-size: 0.68rem; padding: 2px 7px;">
                            <i class="bi bi-person-badge-fill"></i> Staff
                        </span>
                    @endif
                    <small class="text-secondary" style="font-size: 0.72rem;">@ {{ Auth::user()->username ?? 'user' }}</small>
                </div>
            </div>
        </div>
        @endauth

        <!-- Navigation Menu -->
        <nav class="sidebar-nav">
            <span class="sidebar-heading">Menu Utama</span>
            <a href="{{ route('dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="sidebar-nav-icon"><i class="bi bi-grid-1x2-fill"></i></span>
                <span>Dashboard</span>
            </a>

            <span class="sidebar-heading">Operasional Stok</span>
            <a href="{{ route('stock-movements.index') }}" class="sidebar-nav-item {{ request()->routeIs('stock-movements.index') ? 'active' : '' }}">
                <span class="sidebar-nav-icon"><i class="bi bi-arrow-left-right"></i></span>
                <span>Riwayat Mutasi</span>
            </a>
            <a href="{{ route('stock-movements.create') }}" class="sidebar-nav-item {{ request()->routeIs('stock-movements.create') ? 'active' : '' }}">
                <span class="sidebar-nav-icon"><i class="bi bi-plus-slash-minus"></i></span>
                <span>Transaksi Masuk / Keluar</span>
            </a>

            <span class="sidebar-heading">Master Data</span>
            <a href="{{ route('products.index') }}" class="sidebar-nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <span class="sidebar-nav-icon"><i class="bi bi-boxes"></i></span>
                <span>Katalog Produk</span>
            </a>
            <a href="{{ route('categories.index') }}" class="sidebar-nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <span class="sidebar-nav-icon"><i class="bi bi-tags-fill"></i></span>
                <span>Kategori Produk</span>
            </a>
            <a href="{{ route('suppliers.index') }}" class="sidebar-nav-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                <span class="sidebar-nav-icon"><i class="bi bi-truck"></i></span>
                <span>Pemasok (Suppliers)</span>
            </a>

            @if(Auth::user() && Auth::user()->isAdmin())
            <span class="sidebar-heading">Administrasi</span>
            <a href="{{ route('users.index') }}" class="sidebar-nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <span class="sidebar-nav-icon"><i class="bi bi-people-fill"></i></span>
                <span>Kelola Pengguna</span>
            </a>
            @endif
        </nav>

        <!-- Sidebar Footer & Logout -->
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2 btn-sm py-2" style="border-radius: 10px;">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Keluar dari Sistem</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Backdrop for Mobile Sidebar -->
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

    <!-- Main Content Area -->
    <div class="app-main">
        <!-- Topbar Header -->
        <header class="app-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none p-2 border" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div>
                    <h1 class="topbar-title">@yield('page_title', 'Dashboard')</h1>
                    <div class="topbar-breadcrumb">
                        <a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i>Beranda</a>
                        @yield('breadcrumb')
                    </div>
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('stock-movements.create', ['type' => 'IN']) }}" class="btn-action-in shadow-sm">
                    <i class="bi bi-arrow-down-left-circle-fill"></i>
                    <span class="d-none d-sm-inline">+ Barang Masuk</span>
                </a>
                <a href="{{ route('stock-movements.create', ['type' => 'OUT']) }}" class="btn-action-out shadow-sm">
                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                    <span class="d-none d-sm-inline">- Barang Keluar</span>
                </a>
            </div>
        </header>

        <!-- Body Content -->
        <main class="app-body">
            <!-- Global Flash Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center border-0 shadow-sm mb-4" style="background-color: #ecfdf5; border-left: 4px solid #10b981 !important; border-radius: 12px; color: #065f46;" role="alert">
                    <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
                    <div class="fw-medium">{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center border-0 shadow-sm mb-4" style="background-color: #fff1f2; border-left: 4px solid #ef4444 !important; border-radius: 12px; color: #9f1239;" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-danger"></i>
                    <div class="fw-medium">{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-4" style="background-color: #fffbeb; border-left: 4px solid #f59e0b !important; border-radius: 12px; color: #92400e;" role="alert">
                    <div class="fw-semibold mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-circle-fill text-warning fs-5"></i>
                        <span>Mohon periksa kesalahan input berikut:</span>
                    </div>
                    <ul class="mb-0 ps-4 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Page Specific Content -->
            @yield('content')
        </main>
    </div>
</div>

<!-- Bootstrap 5.3 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('appSidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    sidebar.classList.toggle('show');
    backdrop.classList.toggle('show');
}
</script>
@stack('scripts')
</body>
</html>
