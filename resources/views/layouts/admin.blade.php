<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .sidebar-item.active {
            background-color: #F1F8E9;
            border-left: 4px solid #2E7D32;
            color: #1B5E20;
        }
        .sidebar-item:hover:not(.active) {
            background-color: rgba(129, 199, 132, 0.1);
        }
    </style>
</head>
<body class="bg-eco-cream text-gray-800">

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="bg-white w-64 fixed h-full shadow-lg">
        <div class="p-6">
            <h1 class="text-2xl font-bold text-eco-dark flex items-center">
                <i class="fas fa-leaf text-eco-accent mr-2"></i> Admin
            </h1>
        </div>
        <nav class="mt-6">
            <ul>
                <li>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center py-3 px-6 text-gray-600">
                        <i class="fas fa-chart-pie mr-3"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}" 
                       class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }} flex items-center py-3 px-6 text-gray-600">
                        <i class="fas fa-users mr-3"></i> Users
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.payments') }}" 
                       class="sidebar-item {{ request()->routeIs('admin.payments') ? 'active' : '' }} flex items-center py-3 px-6 text-gray-600">
                        <i class="fas fa-credit-card mr-3"></i> Payments
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    
    <main class="flex-1 ml-64 p-6">
        @yield('content')
    </main>
</div>

</body>
</html>
