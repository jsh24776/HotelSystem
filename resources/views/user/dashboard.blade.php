<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrokenShire - User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'eco-primary': '#2E7D32',
                        'eco-light': '#81C784',
                        'eco-dark': '#1B5E20',
                        'eco-accent': '#4CAF50',
                        'eco-cream': '#F1F8E9',
                    },
                    backgroundImage: {
                        'leaf-pattern': "url('data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 0h20v20H0V0zm10 17c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm0-1c3.314 0 6-2.686 6-6s-2.686-6-6-6-6 2.686-6 6 2.686 6 6 6z' fill='%234CAF50' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E')"
                    }
                }
            }
        }
    </script>
    <style>
        .leaf-bg {
            background-image: url('data:image/svg+xml,%3Csvg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"%3E%3Cpath d="M0 0h20v20H0V0zm10 17c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm0-1c3.314 0 6-2.686 6-6s-2.686-6-6-6-6 2.686-6 6 2.686 6 6 6z" fill="%234CAF50" fill-opacity="0.05" fill-rule="evenodd"/%3E%3C/svg%3E');
        }
        
        .nav-item.active {
            background-color: #F1F8E9;
            border-left: 4px solid #2E7D32;
            color: #1B5E20;
            font-weight: 600;
        }
        
        .nav-item:hover:not(.active) {
            background-color: rgba(129, 199, 132, 0.1);
        }
        
        .dashboard-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .alert {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            max-width: 400px;
        }
        
        @media (max-width: 768px) {
            .mobile-menu {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .mobile-menu.open {
                transform: translateX(0);
            }
            
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 10;
            }
            
            .overlay.active {
                display: block;
            }
        }
    </style>
</head>
<body class="bg-eco-cream text-gray-800">
    <!-- Alert Notification -->
    <div id="alert" class="alert bg-white rounded-xl shadow-lg p-4 border-l-4 border-eco-accent">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i id="alert-icon" class="fas fa-check-circle text-eco-accent text-xl mt-1"></i>
            </div>
            <div class="ml-3">
                <h3 id="alert-title" class="text-sm font-medium text-gray-900">Success</h3>
                <div id="alert-message" class="mt-1 text-sm text-gray-500">Operation completed successfully.</div>
            </div>
            <button id="alert-close" class="ml-auto text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Button -->
    <div class="md:hidden fixed top-4 left-4 z-20">
        <button id="menuToggle" class="bg-eco-primary text-white p-2 rounded-lg">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <!-- Overlay for mobile menu -->
    <div id="overlay" class="overlay"></div>
    
    <div class="flex min-h-screen">
        <!-- Sidebar Navigation -->
        <aside id="sidebar" class="mobile-menu bg-white w-64 fixed h-full shadow-lg z-10">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-eco-dark flex items-center">
                    <i class="fas fa-leaf text-eco-accent mr-2"></i>
                    BrokenShire
                </h1>
                <p class="text-sm text-gray-500 mt-1">User Dashboard</p>
            </div>
            
            <nav class="mt-6">
                <ul>
                    <li>
                        <a href="#" data-tab="dashboard" class="nav-item active flex items-center py-3 px-6 text-gray-600">
                            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="#" data-tab="bookings" class="nav-item flex items-center py-3 px-6 text-gray-600">
                            <i class="fas fa-calendar-check mr-3"></i> Bookings
                        </a>
                    </li>
                    <li>
                        <a href="#" data-tab="profile" class="nav-item flex items-center py-3 px-6 text-gray-600">
                            <i class="fas fa-user mr-3"></i> Profile
                        </a>
                    </li>
                    <li>
                        <a href="#" data-tab="payments" class="nav-item flex items-center py-3 px-6 text-gray-600">
                            <i class="fas fa-credit-card mr-3"></i> Payments
                        </a>
                    </li>
                    <li>
                        <a href="#" data-tab="settings" class="nav-item flex items-center py-3 px-6 text-gray-600">
                            <i class="fas fa-cog mr-3"></i> Settings
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="absolute bottom-0 w-full p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-eco-light flex items-center justify-center text-eco-dark font-bold">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold">{{ Auth::user()->name }}</p>
                            <p class="text-sm text-gray-500">User</p>
                        </div>
                    </div>
                    <!-- Mobile Logout Button -->
                    <button id="mobileLogout" class="md:hidden text-gray-500 hover:text-eco-dark">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 ml-0 md:ml-64 p-6">
            <!-- Header -->
            <header class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 id="page-title" class="text-3xl font-bold text-eco-dark">Dashboard</h1>
                        <p id="page-subtitle" class="text-gray-600">Welcome back, {{ Auth::user()->name }}! Here's your overview.</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <div class="relative">
                            <button class="w-10 h-10 rounded-full bg-eco-light flex items-center justify-center text-eco-dark">
                                <i class="fas fa-bell"></i>
                            </button>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">3</span>
                        </div>
                        
                        <!-- User Dropdown Menu -->
                        <div class="dropdown relative">
                            <button id="userDropdownToggle" class="flex items-center space-x-2 focus:outline-none">
                                <div class="w-10 h-10 rounded-full bg-eco-light flex items-center justify-center text-eco-dark font-bold">
                                    {{ substr(Auth::user()->name, 0, 2) }}
                                </div>
                                <span class="hidden md:block text-gray-700">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-gray-500"></i>
                            </button>
                            
                            <div class="dropdown-content mt-2">
                                <a href="#" data-tab="profile" class="block px-4 py-3 text-gray-700 hover:bg-eco-cream border-b border-gray-100">
                                    <i class="fas fa-user mr-2"></i>My Profile
                                </a>
                                <a href="#" data-tab="settings" class="block px-4 py-3 text-gray-700 hover:bg-eco-cream border-b border-gray-100">
                                    <i class="fas fa-cog mr-2"></i>Settings
                                </a>
                                <!-- Laravel Breeze Logout Form -->
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-3 text-gray-700 hover:bg-eco-cream flex items-center">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div id="dashboard" class="tab-content active">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500">Active Bookings</p>
                                <h3 class="text-2xl font-bold text-eco-dark">3</h3>
                                <p class="text-green-600 text-sm mt-1"><i class="fas fa-arrow-up mr-1"></i> 1 new this week</p>
                            </div>
                            <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                                <i class="fas fa-calendar-check text-eco-accent text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500">Total Spent</p>
                                <h3 class="text-2xl font-bold text-eco-dark">$245.00</h3>
                                <p class="text-green-600 text-sm mt-1"><i class="fas fa-dollar-sign mr-1"></i> Last payment: $85.00</p>
                            </div>
                            <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                                <i class="fas fa-credit-card text-eco-accent text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500">Loyalty Points</p>
                                <h3 class="text-2xl font-bold text-eco-dark">1,250</h3>
                                <p class="text-green-600 text-sm mt-1"><i class="fas fa-gift mr-1"></i> 50 points to next reward</p>
                            </div>
                            <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                                <i class="fas fa-award text-eco-accent text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500">Support Tickets</p>
                                <h3 class="text-2xl font-bold text-eco-dark">1</h3>
                                <p class="text-green-600 text-sm mt-1"><i class="fas fa-check mr-1"></i> All resolved</p>
                            </div>
                            <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                                <i class="fas fa-headset text-eco-accent text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Upcoming Bookings -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-eco-dark">Upcoming Bookings</h2>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-50 text-left text-gray-500">
                                        <th class="py-3 px-6 font-medium">Booking ID</th>
                                        <th class="py-3 px-6 font-medium">Service</th>
                                        <th class="py-3 px-6 font-medium">Date & Time</th>
                                        <th class="py-3 px-6 font-medium">Status</th>
                                        <th class="py-3 px-6 font-medium">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr class="hover:bg-eco-cream transition-colors">
                                        <td class="py-4 px-6 font-medium">#BK-7832</td>
                                        <td class="py-4 px-6">Forest Retreat Cabin</td>
                                        <td class="py-4 px-6">Jun 15, 2023 - 2:00 PM</td>
                                        <td class="py-4 px-6">
                                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Confirmed
                                            </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <button class="text-eco-accent hover:text-eco-dark mr-3">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-eco-cream transition-colors">
                                        <td class="py-4 px-6 font-medium">#BK-7921</td>
                                        <td class="py-4 px-6">Mountain View Lodge</td>
                                        <td class="py-4 px-6">Jun 22, 2023 - 3:30 PM</td>
                                        <td class="py-4 px-6">
                                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <button class="text-eco-accent hover:text-eco-dark mr-3">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 text-center">
                            <a href="#" data-tab="bookings" class="text-eco-accent hover:text-eco-dark font-medium">
                                View all bookings <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-eco-dark">Recent Activity</h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="bg-eco-light bg-opacity-20 p-2 rounded-lg mr-4">
                                        <i class="fas fa-calendar-plus text-eco-accent"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium">New booking created</p>
                                        <p class="text-sm text-gray-500">You booked Forest Retreat Cabin for June 15</p>
                                        <p class="text-xs text-gray-400 mt-1">2 days ago</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="bg-eco-light bg-opacity-20 p-2 rounded-lg mr-4">
                                        <i class="fas fa-credit-card text-eco-accent"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium">Payment received</p>
                                        <p class="text-sm text-gray-500">Payment of $85.00 for booking #BK-7832 was confirmed</p>
                                        <p class="text-xs text-gray-400 mt-1">5 days ago</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="bg-eco-light bg-opacity-20 p-2 rounded-lg mr-4">
                                        <i class="fas fa-user-edit text-eco-accent"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium">Profile updated</p>
                                        <p class="text-sm text-gray-500">You updated your contact information</p>
                                        <p class="text-xs text-gray-400 mt-1">1 week ago</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-eco-dark">Quick Actions</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-2 gap-4">
                                <a href="#" data-tab="bookings" class="bg-eco-cream hover:bg-eco-light hover:bg-opacity-20 p-4 rounded-lg text-center transition-colors">
                                    <i class="fas fa-plus-circle text-eco-accent text-2xl mb-2"></i>
                                    <p class="font-medium">New Booking</p>
                                </a>
                                <a href="#" data-tab="payments" class="bg-eco-cream hover:bg-eco-light hover:bg-opacity-20 p-4 rounded-lg text-center transition-colors">
                                    <i class="fas fa-file-invoice-dollar text-eco-accent text-2xl mb-2"></i>
                                    <p class="font-medium">Payment History</p>
                                </a>
                                <a href="#" data-tab="profile" class="bg-eco-cream hover:bg-eco-light hover:bg-opacity-20 p-4 rounded-lg text-center transition-colors">
                                    <i class="fas fa-user-edit text-eco-accent text-2xl mb-2"></i>
                                    <p class="font-medium">Edit Profile</p>
                                </a>
                                <a href="#" class="bg-eco-cream hover:bg-eco-light hover:bg-opacity-20 p-4 rounded-lg text-center transition-colors">
                                    <i class="fas fa-question-circle text-eco-accent text-2xl mb-2"></i>
                                    <p class="font-medium">Get Help</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bookings Content -->
            <div id="bookings" class="tab-content">
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-eco-dark">My Bookings</h2>
                        <button class="bg-eco-primary text-white px-4 py-2 rounded-lg hover:bg-eco-dark transition-colors flex items-center">
                            <i class="fas fa-plus mr-2"></i> New Booking
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-50 text-left text-gray-500">
                                        <th class="py-3 px-6 font-medium">Booking ID</th>
                                        <th class="py-3 px-6 font-medium">Service</th>
                                        <th class="py-3 px-6 font-medium">Date & Time</th>
                                        <th class="py-3 px-6 font-medium">Amount</th>
                                        <th class="py-3 px-6 font-medium">Status</th>
                                        <th class="py-3 px-6 font-medium">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr class="hover:bg-eco-cream transition-colors">
                                        <td class="py-4 px-6 font-medium">#BK-7832</td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-lg bg-eco-light flex items-center justify-center text-eco-dark font-bold mr-3">
                                                    <i class="fas fa-home"></i>
                                                </div>
                                                <div>
                                                    <p class="font-medium">Forest Retreat Cabin</p>
                                                    <p class="text-sm text-gray-500">2 guests, 3 nights</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">Jun 15-18, 2023<br><span class="text-sm text-gray-500">Check-in: 2:00 PM</span></td>
                                        <td class="py-4 px-6 font-medium">$245.00</td>
                                        <td class="py-4 px-6">
                                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Confirmed
                                            </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex space-x-2">
                                                <button class="text-eco-accent hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="text-blue-500 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition-colors" title="Modify">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors cancel-booking" title="Cancel" data-booking-id="7832" data-booking-name="Forest Retreat Cabin">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-eco-cream transition-colors">
                                        <td class="py-4 px-6 font-medium">#BK-7921</td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-lg bg-eco-light flex items-center justify-center text-eco-dark font-bold mr-3">
                                                    <i class="fas fa-mountain"></i>
                                                </div>
                                                <div>
                                                    <p class="font-medium">Mountain View Lodge</p>
                                                    <p class="text-sm text-gray-500">1 guest, 2 nights</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">Jun 22-24, 2023<br><span class="text-sm text-gray-500">Check-in: 3:30 PM</span></td>
                                        <td class="py-4 px-6 font-medium">$180.00</td>
                                        <td class="py-4 px-6">
                                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex space-x-2">
                                                <button class="text-eco-accent hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="text-blue-500 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition-colors" title="Modify">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors cancel-booking" title="Cancel" data-booking-id="7921" data-booking-name="Mountain View Lodge">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-eco-cream transition-colors">
                                        <td class="py-4 px-6 font-medium">#BK-7543</td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-lg bg-eco-light flex items-center justify-center text-eco-dark font-bold mr-3">
                                                    <i class="fas fa-umbrella-beach"></i>
                                                </div>
                                                <div>
                                                    <p class="font-medium">Lakeside Cottage</p>
                                                    <p class="text-sm text-gray-500">4 guests, 5 nights</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">May 10-15, 2023<br><span class="text-sm text-gray-500">Completed</span></td>
                                        <td class="py-4 px-6 font-medium">$420.00</td>
                                        <td class="py-4 px-6">
                                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Completed
                                            </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex space-x-2">
                                                <button class="text-eco-accent hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="text-blue-500 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition-colors" title="Book Again">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Content -->
            <div id="profile" class="tab-content">
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-eco-dark">My Profile</h2>
                    </div>
                    
                    <form id="profile-form" class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2 flex items-center space-x-6 mb-6">
                                <div class="relative">
                                    <div class="w-24 h-24 rounded-full bg-eco-light flex items-center justify-center text-eco-dark font-bold text-2xl">
                                        {{ substr(Auth::user()->name, 0, 2) }}
                                    </div>
                                    <button type="button" class="absolute bottom-0 right-0 bg-eco-primary text-white p-2 rounded-full">
                                        <i class="fas fa-camera text-sm"></i>
                                    </button>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold">{{ Auth::user()->name }}</h3>
                                    <p class="text-gray-500">Member since {{ Auth::user()->created_at->format('M Y') }}</p>
                                    <button type="button" class="text-eco-accent hover:text-eco-dark text-sm font-medium mt-2">
                                        Change Avatar
                                    </button>
                                </div>
                            </div>
                            
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ Auth::user()->name }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent"
                                       required>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       value="{{ Auth::user()->email }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent"
                                       required>
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="tel" 
                                       name="phone" 
                                       id="phone" 
                                       value="+1 (555) 123-4567"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
                            </div>
                            
                            <div>
                                <label for="birthdate" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                                <input type="date" 
                                       name="birthdate" 
                                       id="birthdate" 
                                       value="1990-01-15"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <textarea name="address" 
                                          id="address" 
                                          rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">123 Green Street, Eco City, EC 12345</textarea>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                            <button type="reset" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                Reset Changes
                            </button>
                            <button type="submit" class="px-6 py-2 bg-eco-primary text-white rounded-lg hover:bg-eco-dark transition-colors">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payments Content -->
            <div id="payments" class="tab-content">
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-eco-dark">Payment History</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-50 text-left text-gray-500">
                                        <th class="py-3 px-6 font-medium">Transaction ID</th>
                                        <th class="py-3 px-6 font-medium">Date</th>
                                        <th class="py-3 px-6 font-medium">Description</th>
                                        <th class="py-3 px-6 font-medium">Amount</th>
                                        <th class="py-3 px-6 font-medium">Status</th>
                                        <th class="py-3 px-6 font-medium">Invoice</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr class="hover:bg-eco-cream transition-colors">
                                        <td class="py-4 px-6 font-medium">#TX-7832</td>
                                        <td class="py-4 px-6">Jun 10, 2023</td>
                                        <td class="py-4 px-6">Payment for booking #BK-7832</td>
                                        <td class="py-4 px-6 font-medium">$85.00</td>
                                        <td class="py-4 px-6">
                                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <button class="text-eco-accent hover:text-eco-dark">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-eco-cream transition-colors">
                                        <td class="py-4 px-6 font-medium">#TX-7543</td>
                                        <td class="py-4 px-6">May 5, 2023</td>
                                        <td class="py-4 px-6">Payment for booking #BK-7543</td>
                                        <td class="py-4 px-6 font-medium">$420.00</td>
                                        <td class="py-4 px-6">
                                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <button class="text-eco-accent hover:text-eco-dark">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-eco-cream transition-colors">
                                        <td class="py-4 px-6 font-medium">#TX-7129</td>
                                        <td class="py-4 px-6">Apr 12, 2023</td>
                                        <td class="py-4 px-6">Payment for booking #BK-7129</td>
                                        <td class="py-4 px-6 font-medium">$320.00</td>
                                        <td class="py-4 px-6">
                                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <button class="text-eco-accent hover:text-eco-dark">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Methods -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-eco-dark">Payment Methods</h2>
                        <button class="bg-eco-primary text-white px-4 py-2 rounded-lg hover:bg-eco-dark transition-colors flex items-center">
                            <i class="fas fa-plus mr-2"></i> Add Payment Method
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-12 h-8 bg-blue-500 rounded flex items-center justify-center text-white mr-4">
                                        <i class="fab fa-cc-visa"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium">Visa ending in 4242</p>
                                        <p class="text-sm text-gray-500">Expires 05/2025</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button class="text-eco-accent hover:text-eco-dark">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-12 h-8 bg-gray-800 rounded flex items-center justify-center text-white mr-4">
                                        <i class="fab fa-cc-mastercard"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium">Mastercard ending in 8888</p>
                                        <p class="text-sm text-gray-500">Expires 11/2024</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button class="text-eco-accent hover:text-eco-dark">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Content -->
            <div id="settings" class="tab-content">
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-eco-dark">Account Settings</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-8">
                            <!-- Notification Settings -->
                            <div>
                                <h3 class="text-lg font-bold text-eco-dark mb-4">Notification Preferences</h3>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-medium">Email Notifications</p>
                                            <p class="text-sm text-gray-500">Receive updates about your bookings via email</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer" checked>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-eco-accent"></div>
                                        </label>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-medium">SMS Notifications</p>
                                            <p class="text-sm text-gray-500">Receive text messages for important updates</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-eco-accent"></div>
                                        </label>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-medium">Promotional Emails</p>
                                            <p class="text-sm text-gray-500">Receive special offers and promotions</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer" checked>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-eco-accent"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Privacy Settings -->
                            <div>
                                <h3 class="text-lg font-bold text-eco-dark mb-4">Privacy & Security</h3>
                                <div class="space-y-4">
                                    <div>
                                        <p class="font-medium mb-2">Change Password</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <input type="password" placeholder="Current Password" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
                                            <input type="password" placeholder="New Password" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
                                        </div>
                                        <button class="mt-4 bg-eco-primary text-white px-4 py-2 rounded-lg hover:bg-eco-dark transition-colors">
                                            Update Password
                                        </button>
                                    </div>
                                    
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                        <div>
                                            <p class="font-medium">Two-Factor Authentication</p>
                                            <p class="text-sm text-gray-500">Add an extra layer of security to your account</p>
                                        </div>
                                        <button class="bg-eco-primary text-white px-4 py-2 rounded-lg hover:bg-eco-dark transition-colors text-sm">
                                            Enable 2FA
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Account Actions -->
                            <div>
                                <h3 class="text-lg font-bold text-eco-dark mb-4">Account Actions</h3>
                                <div class="space-y-4">
                                    <button class="w-full text-left p-4 border border-gray-200 rounded-lg hover:bg-red-50 hover:border-red-200 transition-colors group">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-red-600 group-hover:text-red-700">Delete Account</p>
                                                <p class="text-sm text-gray-500">Permanently delete your account and all data</p>
                                            </div>
                                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-red-500"></i>
                                        </div>
                                    </button>
                                    
                                    <button class="w-full text-left p-4 border border-gray-200 rounded-lg hover:bg-yellow-50 hover:border-yellow-200 transition-colors group">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-yellow-600 group-hover:text-yellow-700">Deactivate Account</p>
                                                <p class="text-sm text-gray-500">Temporarily deactivate your account</p>
                                            </div>
                                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-yellow-500"></i>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mr-4">
                    <i class="fas fa-sign-out-alt text-red-500 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Confirm Logout</h3>
                    <p class="text-gray-600">Are you sure you want to logout?</p>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button id="cancelLogout" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                <!-- Laravel Breeze Logout Form -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-eco-primary text-white rounded-lg hover:bg-eco-dark transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Cancel Booking Confirmation Modal -->
    <div id="cancelBookingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Cancel Booking</h3>
                    <p id="cancel-booking-text" class="text-gray-600">Are you sure you want to cancel this booking?</p>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button id="cancelBookingNo" class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50">No, Keep It</button>
                <button id="cancelBookingYes" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">Yes, Cancel Booking</button>
            </div>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('overlay').classList.toggle('active');
        });
        
        document.getElementById('overlay').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('open');
            this.classList.remove('active');
        });
        
        // User dropdown functionality
        const dropdownToggle = document.getElementById('userDropdownToggle');
        const dropdown = dropdownToggle.closest('.dropdown');
        
        dropdownToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.classList.toggle('open');
        });
        
        document.addEventListener('click', function() {
            dropdown.classList.remove('open');
        });
        
        if (dropdown.querySelector('.dropdown-content')) {
            dropdown.querySelector('.dropdown-content').addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
        
        // Logout modal functionality
        document.getElementById('mobileLogout').addEventListener('click', function() {
            document.getElementById('logoutModal').classList.remove('hidden');
        });
        
        document.getElementById('cancelLogout').addEventListener('click', function() {
            document.getElementById('logoutModal').classList.add('hidden');
        });
        
        document.getElementById('logoutModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
        
        // Tab navigation
        document.querySelectorAll('.nav-item, .dropdown-content a[data-tab], a[data-tab]').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Get the target tab
                const targetTab = this.getAttribute('data-tab');
                
                // Update active nav item
                document.querySelectorAll('.nav-item').forEach(navItem => {
                    navItem.classList.remove('active');
                });
                this.classList.add('active');
                
                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(tab => {
                    tab.classList.remove('active');
                });
                
                // Show target tab content
                document.getElementById(targetTab).classList.add('active');
                
                // Update page title and subtitle
                updatePageTitle(targetTab);
                
                // Close mobile menu if open
                document.getElementById('sidebar').classList.remove('open');
                document.getElementById('overlay').classList.remove('active');
            });
        });
        
        // Function to update page title based on active tab
        function updatePageTitle(tab) {
            const titles = {
                'dashboard': 'Dashboard',
                'bookings': 'Bookings',
                'profile': 'Profile',
                'payments': 'Payments',
                'settings': 'Settings'
            };
            
            const subtitles = {
                'dashboard': 'Welcome back, {{ Auth::user()->name }}! Here\'s your overview.',
                'bookings': 'Manage your current and past bookings.',
                'profile': 'View and update your personal information.',
                'payments': 'View your payment history and manage payment methods.',
                'settings': 'Customize your account preferences and settings.'
            };
            
            document.getElementById('page-title').textContent = titles[tab];
            document.getElementById('page-subtitle').textContent = subtitles[tab];
        }
        
        // Profile form submission
        document.getElementById('profile-form').addEventListener('submit', function(e) {
            e.preventDefault();
            showAlert('success', 'Profile Updated', 'Your profile information has been successfully updated.');
        });
        
        // Cancel booking functionality
        document.querySelectorAll('.cancel-booking').forEach(button => {
            button.addEventListener('click', function() {
                const bookingId = this.getAttribute('data-booking-id');
                const bookingName = this.getAttribute('data-booking-name');
                
                document.getElementById('cancel-booking-text').textContent = 
                    `Are you sure you want to cancel your booking for "${bookingName}" (ID: #BK-${bookingId})?`;
                
                document.getElementById('cancelBookingModal').classList.remove('hidden');
                
                // Set up confirmation handler
                document.getElementById('cancelBookingYes').onclick = function() {
                    // In a real app, you would make an API call here
                    showAlert('success', 'Booking Cancelled', `Your booking for ${bookingName} has been cancelled. A confirmation email has been sent.`);
                    document.getElementById('cancelBookingModal').classList.add('hidden');
                    
                    // In a real app, you would update the UI to reflect the cancellation
                    // For this demo, we'll just simulate it
                    setTimeout(() => {
                        // Find and update the booking status
                        const bookingRow = document.querySelector(`[data-booking-id="${bookingId}"]`).closest('tr');
                        const statusCell = bookingRow.querySelector('td:nth-child(5)');
                        statusCell.innerHTML = '<span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Cancelled</span>';
                    }, 1000);
                };
            });
        });
        
        document.getElementById('cancelBookingNo').addEventListener('click', function() {
            document.getElementById('cancelBookingModal').classList.add('hidden');
        });
        
        document.getElementById('cancelBookingModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
        
        // Alert functionality
        function showAlert(type, title, message) {
            const alert = document.getElementById('alert');
            const alertIcon = document.getElementById('alert-icon');
            const alertTitle = document.getElementById('alert-title');
            const alertMessage = document.getElementById('alert-message');
            
            // Set alert type
            if (type === 'success') {
                alert.classList.remove('border-red-500');
                alert.classList.add('border-eco-accent');
                alertIcon.className = 'fas fa-check-circle text-eco-accent text-xl mt-1';
            } else if (type === 'error') {
                alert.classList.remove('border-eco-accent');
                alert.classList.add('border-red-500');
                alertIcon.className = 'fas fa-exclamation-circle text-red-500 text-xl mt-1';
            }
            
            // Set content
            alertTitle.textContent = title;
            alertMessage.textContent = message;
            
            // Show alert
            alert.style.display = 'block';
            
            
            setTimeout(() => {
                alert.style.display = 'none';
            }, 5000);
        }
        
       
        document.getElementById('alert-close').addEventListener('click', function() {
            document.getElementById('alert').style.display = 'none';
        });
        
    
        const searchInput = document.querySelector('input[type="text"]');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
               
                console.log('Searching for:', searchTerm);
            });
        }
    </script>
</body>
</html>