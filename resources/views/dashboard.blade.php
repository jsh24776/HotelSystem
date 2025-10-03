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
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 100;
            justify-content: center;
            align-items: center;
        }
        
        .modal.active {
            display: flex;
        }
        
        .modal-content {
            background-color: white;
            border-radius: 12px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
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
        
        .room-card {
            transition: all 0.3s ease;
        }
        
        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .booking-status-pending {
            background-color: #FEF3C7;
            color: #92400E;
        }
        
        .booking-status-confirmed {
            background-color: #D1FAE5;
            color: #065F46;
        }
        
        .booking-status-cancelled {
            background-color: #FEE2E2;
            color: #991B1B;
        }
        
        .booking-status-completed {
            background-color: #E0E7FF;
            color: #3730A3;
        }
        
        .paypal-button {
            background-color: #0070BA;
            color: white;
            padding: 12px 24px;
            border-radius: 4px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background-color 0.3s;
        }
        
        .paypal-button:hover {
            background-color: #005EA6;
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
                        <a href="#" data-tab="search-rooms" class="nav-item flex items-center py-3 px-6 text-gray-600">
                            <i class="fas fa-search mr-3"></i> Find Rooms
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
                            JS
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold">John Smith</p>
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
        
        <main class="flex-1 ml-0 md:ml-64 p-6">
            <header class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 id="page-title" class="text-3xl font-bold text-eco-dark">Dashboard</h1>
                        <p id="page-subtitle" class="text-gray-600">Welcome back, John Smith! Here's your overview.</p>
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
                        
                        <div class="dropdown relative">
                            <button id="userDropdownToggle" class="flex items-center space-x-2 focus:outline-none">
                                <div class="w-10 h-10 rounded-full bg-eco-light flex items-center justify-center text-eco-dark font-bold">
                                    JS
                                </div>
                                <span class="hidden md:block text-gray-700">John Smith</span>
                                <i class="fas fa-chevron-down text-gray-500"></i>
                            </button>
                            
                            <div class="dropdown-content hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-20 border border-gray-200">
                                <a href="/profile" class="block px-4 py-3 text-gray-700 hover:bg-eco-cream border-b border-gray-100">
                                    <i class="fas fa-user mr-2"></i>My Profile
                                </a>
                                <a href="/settings" class="block px-4 py-3 text-gray-700 hover:bg-eco-cream border-b border-gray-100">
                                    <i class="fas fa-cog mr-2"></i>Settings
                                </a>
                                
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
                                <h3 class="text-2xl font-bold text-eco-dark">₱5,245.00</h3>
                                <p class="text-green-600 text-sm mt-1"><i class="fas fa-dollar-sign mr-1"></i> Last payment: ₱685.00</p>
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
                                        <p class="text-sm text-gray-500">Payment of ₱9,185.00 for booking #BK-7832 was confirmed</p>
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
                                <a href="#" data-tab="search-rooms" class="bg-eco-cream hover:bg-eco-light hover:bg-opacity-20 p-4 rounded-lg text-center transition-colors">
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
                        <h2 class="text-xl font-bold text-eco-dark">My Reservations</h2>
                        <button class="bg-eco-primary text-white px-4 py-2 rounded-lg hover:bg-eco-dark transition-colors flex items-center" data-tab="search-rooms">
                            <i class="fas fa-plus mr-2"></i> New Booking
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <div class="mb-6">
                            <div class="flex space-x-2 border-b border-gray-200">
                                <button class="tab-button px-4 py-2 font-medium text-gray-500 hover:text-eco-dark border-b-2 border-transparent active" data-tab="all-bookings">All Bookings</button>
                                <button class="tab-button px-4 py-2 font-medium text-gray-500 hover:text-eco-dark border-b-2 border-transparent" data-tab="upcoming-bookings">Upcoming</button>
                                <button class="tab-button px-4 py-2 font-medium text-gray-500 hover:text-eco-dark border-b-2 border-transparent" data-tab="past-bookings">Past</button>
                            </div>
                        </div>
                        
                        <div id="all-bookings" class="booking-tab active">
                            <div class="space-y-6">
                                <!-- Booking Card 1 -->
                                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                                        <div class="flex items-start space-x-4">
                                            <div class="w-16 h-16 bg-eco-light rounded-lg flex items-center justify-center text-eco-dark">
                                                <i class="fas fa-home text-xl"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-lg">Forest Retreat Cabin</h3>
                                                <div class="flex flex-wrap gap-2 mt-2">
                                                    <span class="text-sm text-gray-500"><i class="fas fa-calendar mr-1"></i> Jun 15-18, 2023 (3 nights)</span>
                                                    <span class="text-sm text-gray-500"><i class="fas fa-user mr-1"></i> 2 guests</span>
                                                </div>
                                                <div class="mt-2">
                                                    <span class="booking-status-confirmed px-3 py-1 rounded-full text-xs font-medium">Confirmed</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 md:mt-0 flex flex-col items-end">
                                            <p class="text-lg font-bold text-eco-dark">₱4,245.00</p>
                                            <p class="text-sm text-gray-500">Booking #BK-7832</p>
                                            <div class="flex space-x-2 mt-3">
                                                <button class="text-eco-accent hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="text-blue-500 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition-colors" title="View Invoice">
                                                    <i class="fas fa-file-invoice"></i>
                                                </button>
                                                <button class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors cancel-booking" title="Cancel" data-booking-id="7832" data-booking-name="Forest Retreat Cabin">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Booking Card 2 -->
                                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                                        <div class="flex items-start space-x-4">
                                            <div class="w-16 h-16 bg-eco-light rounded-lg flex items-center justify-center text-eco-dark">
                                                <i class="fas fa-mountain text-xl"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-lg">Mountain View Lodge</h3>
                                                <div class="flex flex-wrap gap-2 mt-2">
                                                    <span class="text-sm text-gray-500"><i class="fas fa-calendar mr-1"></i> Jun 22-24, 2023 (2 nights)</span>
                                                    <span class="text-sm text-gray-500"><i class="fas fa-user mr-1"></i> 1 guest</span>
                                                </div>
                                                <div class="mt-2">
                                                    <span class="booking-status-pending px-3 py-1 rounded-full text-xs font-medium">Pending Payment</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 md:mt-0 flex flex-col items-end">
                                            <p class="text-lg font-bold text-eco-dark">₱3,180.00</p>
                                            <p class="text-sm text-gray-500">Booking #BK-7921</p>
                                            <div class="flex space-x-2 mt-3">
                                                <button class="text-eco-accent hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="text-blue-500 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition-colors" title="Make Payment">
                                                    <i class="fas fa-credit-card"></i>
                                                </button>
                                                <button class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors cancel-booking" title="Cancel" data-booking-id="7921" data-booking-name="Mountain View Lodge">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Booking Card 3 -->
                                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                                        <div class="flex items-start space-x-4">
                                            <div class="w-16 h-16 bg-eco-light rounded-lg flex items-center justify-center text-eco-dark">
                                                <i class="fas fa-umbrella-beach text-xl"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-lg">Lakeside Cottage</h3>
                                                <div class="flex flex-wrap gap-2 mt-2">
                                                    <span class="text-sm text-gray-500"><i class="fas fa-calendar mr-1"></i> May 10-15, 2023 (5 nights)</span>
                                                    <span class="text-sm text-gray-500"><i class="fas fa-user mr-1"></i> 4 guests</span>
                                                </div>
                                                <div class="mt-2">
                                                    <span class="booking-status-completed px-3 py-1 rounded-full text-xs font-medium">Completed</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 md:mt-0 flex flex-col items-end">
                                            <p class="text-lg font-bold text-eco-dark">₱7,420.00</p>
                                            <p class="text-sm text-gray-500">Booking #BK-7543</p>
                                            <div class="flex space-x-2 mt-3">
                                                <button class="text-eco-accent hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="text-blue-500 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition-colors" title="View Invoice">
                                                    <i class="fas fa-file-invoice"></i>
                                                </button>
                                                <button class="text-green-500 hover:text-green-700 p-2 rounded-lg hover:bg-green-50 transition-colors" title="Book Again" data-tab="search-rooms">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="upcoming-bookings" class="booking-tab">
                            <div class="text-center py-8">
                                <i class="fas fa-calendar text-4xl text-gray-300 mb-4"></i>
                                <h3 class="text-xl font-medium text-gray-500">No upcoming bookings</h3>
                                <p class="text-gray-400 mt-2">When you have upcoming bookings, they will appear here.</p>
                                <button class="mt-4 bg-eco-primary text-white px-4 py-2 rounded-lg hover:bg-eco-dark transition-colors" data-tab="search-rooms">
                                    Book a Room
                                </button>
                            </div>
                        </div>
                        
                        <div id="past-bookings" class="booking-tab">
                            <div class="text-center py-8">
                                <i class="fas fa-history text-4xl text-gray-300 mb-4"></i>
                                <h3 class="text-xl font-medium text-gray-500">No past bookings</h3>
                                <p class="text-gray-400 mt-2">Your past bookings will appear here.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Room Search Content -->
            <div id="search-rooms" class="tab-content">
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-eco-dark">Find Available Rooms</h2>
                    </div>
                    
                    <div class="p-6">
                        <form id="search-form" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="check-in" class="block text-sm font-medium text-gray-700 mb-2">Check-in Date</label>
                                <input type="date" id="check-in" name="check-in" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" required>
                            </div>
                            
                            <div>
                                <label for="check-out" class="block text-sm font-medium text-gray-700 mb-2">Check-out Date</label>
                                <input type="date" id="check-out" name="check-out" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" required>
                            </div>
                            
                            <div>
                                <label for="guests" class="block text-sm font-medium text-gray-700 mb-2">Guests</label>
                                <select id="guests" name="guests" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
                                    <option value="1">1 Guest</option>
                                    <option value="2" selected>2 Guests</option>
                                    <option value="3">3 Guests</option>
                                    <option value="4">4 Guests</option>
                                    <option value="5">5+ Guests</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="room-type" class="block text-sm font-medium text-gray-700 mb-2">Room Type</label>
                                <select id="room-type" name="room-type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
                                    <option value="all">All Types</option>
                                    <option value="single">Single Room</option>
                                    <option value="double">Double Room</option>
                                    <option value="suite">Suite</option>
                                    <option value="family">Family Room</option>
                                </select>
                            </div>
                            
                            <div class="md:col-span-4 flex justify-end mt-2">
                                <button type="submit" class="bg-eco-primary text-white px-6 py-2 rounded-lg hover:bg-eco-dark transition-colors flex items-center">
                                    <i class="fas fa-search mr-2"></i> Search Rooms
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Available Rooms -->
                <div id="available-rooms" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Room cards will be populated by JavaScript -->
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
                                        JS
                                    </div>
                                    <button type="button" class="absolute bottom-0 right-0 bg-eco-primary text-white p-2 rounded-full">
                                        <i class="fas fa-camera text-sm"></i>
                                    </button>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold">John Smith</h3>
                                    <p class="text-gray-500">Member since Jan 2023</p>
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
                                       value="John Smith"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent"
                                       required>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       value="john.smith@example.com"
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
                                        <td class="py-4 px-6 font-medium">₱885.00</td>
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
                                        <td class="py-4 px-6 font-medium">₱1,420.00</td>
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
                                        <td class="py-4 px-6 font-medium">₱3,320.00</td>
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

    <!-- Booking Modal -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-eco-dark">Complete Your Booking</h3>
                    <button id="closeBookingModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-medium text-gray-700 mb-4">Room Details</h4>
                        <div id="booking-room-details" class="bg-eco-cream p-4 rounded-lg">
                            <!-- Room details will be populated by JavaScript -->
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-700 mb-4">Guest Information</h4>
                        <form id="booking-form">
                            <div class="space-y-4">
                                <div>
                                    <label for="guest-name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                    <input type="text" id="guest-name" name="guest-name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" value="John Smith" required>
                                </div>
                                
                                <div>
                                    <label for="guest-email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <input type="email" id="guest-email" name="guest-email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" value="john.smith@example.com" required>
                                </div>
                                
                                <div>
                                    <label for="guest-phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <input type="tel" id="guest-phone" name="guest-phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" value="+1 (555) 123-4567" required>
                                </div>
                                
                                <div>
                                    <label for="special-requests" class="block text-sm font-medium text-gray-700 mb-1">Special Requests</label>
                                    <textarea id="special-requests" name="special-requests" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" placeholder="Any special requests or requirements..."></textarea>
                                </div>
                            </div>
                            
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <button type="submit" class="w-full bg-eco-primary text-white py-3 rounded-lg hover:bg-eco-dark transition-colors font-medium">
                                    Confirm Booking
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Modal -->
    <div id="invoiceModal" class="modal">
        <div class="modal-content max-w-3xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-eco-dark">Booking Invoice</h3>
                    <div class="flex space-x-2">
                        <button class="text-eco-accent hover:text-eco-dark p-2">
                            <i class="fas fa-print"></i>
                        </button>
                        <button class="text-eco-accent hover:text-eco-dark p-2">
                            <i class="fas fa-download"></i>
                        </button>
                        <button id="closeInvoiceModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                
                <div id="invoice-content" class="bg-white p-6 border border-gray-200 rounded-lg">
                    <!-- Invoice content will be populated by JavaScript -->
                </div>
                
                <div class="mt-6 flex justify-end space-x-4">
                    <button id="closeInvoice" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Close
                    </button>
                    <button class="px-6 py-2 bg-eco-primary text-white rounded-lg hover:bg-eco-dark transition-colors">
                        Proceed to Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="modal">
        <div class="modal-content max-w-2xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-eco-dark">Complete Payment</h3>
                    <button id="closePaymentModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <h4 class="font-medium text-gray-700 mb-4">Payment Method</h4>
                        
                        <div class="space-y-4">
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <input type="radio" id="cash-payment" name="payment-method" class="mr-3" checked>
                                        <label for="cash-payment" class="font-medium">Pay at Front Desk</label>
                                    </div>
                                    <div class="w-10 h-6 bg-eco-light rounded flex items-center justify-center">
                                        <i class="fas fa-money-bill-wave text-eco-dark"></i>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Pay when you arrive at the hotel. Your booking will be confirmed immediately.</p>
                            </div>
                            
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <input type="radio" id="paypal-payment" name="payment-method" class="mr-3">
                                        <label for="paypal-payment" class="font-medium">PayPal</label>
                                    </div>
                                    <div class="w-10 h-6 bg-blue-500 rounded flex items-center justify-center">
                                        <i class="fab fa-paypal text-white"></i>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Pay securely with your PayPal account.</p>
                                
                                <div id="paypal-container" class="mt-4 hidden">
                                    <div class="bg-gray-100 p-4 rounded-lg text-center">
                                        <p class="text-sm text-gray-600 mb-4">You will be redirected to PayPal to complete your payment.</p>
                                        <button class="paypal-button w-full">
                                            <i class="fab fa-paypal"></i> Pay with PayPal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-700 mb-4">Order Summary</h4>
                        <div id="payment-summary" class="bg-eco-cream p-4 rounded-lg">
                            <!-- Payment summary will be populated by JavaScript -->
                        </div>
                        
                        <div class="mt-6">
                            <button id="confirmPayment" class="w-full bg-eco-primary text-white py-3 rounded-lg hover:bg-eco-dark transition-colors font-medium">
                                Confirm Payment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content max-w-md">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-500 text-2xl"></i>
                </div>
                
                <h3 class="text-xl font-bold text-eco-dark mb-2">Booking Confirmed!</h3>
                <p class="text-gray-600 mb-4">Your reservation has been successfully confirmed.</p>
                
                <div class="bg-eco-cream p-4 rounded-lg mb-6">
                    <p class="font-medium">Booking Reference</p>
                    <p class="text-2xl font-bold text-eco-dark">#BK-8294</p>
                    <p class="text-sm text-gray-500 mt-2">Please save this reference number for your records.</p>
                </div>
                
                <div class="flex flex-col space-y-3">
                    <button class="w-full bg-eco-primary text-white py-3 rounded-lg hover:bg-eco-dark transition-colors font-medium">
                        View Booking Details
                    </button>
                    <button id="closeConfirmationModal" class="w-full border border-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Back to Dashboard
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Modal -->
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
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-eco-primary text-white rounded-lg hover:bg-eco-dark transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Cancel Booking Modal -->
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
        // Mock data for available rooms
        const availableRooms = [
            {
                id: 1,
                name: "Forest Retreat Cabin",
                type: "Cabin",
                price: 1415,
                image: "https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80",
                description: "A cozy cabin nestled in the forest with modern amenities and a private deck.",
                maxGuests: 4,
                amenities: ["WiFi", "Private Bathroom", "Kitchenette", "Air Conditioning"]
            },
            {
                id: 2,
                name: "Mountain View Lodge",
                type: "Lodge",
                price: 1590,
                image: "https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80",
                description: "Spacious lodge with breathtaking mountain views and a fireplace.",
                maxGuests: 6,
                amenities: ["WiFi", "Private Bathroom", "Kitchen", "Fireplace", "Balcony"]
            },
            {
                id: 3,
                name: "Lakeside Cottage",
                type: "Cottage",
                price: 1484,
                image: "https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80",
                description: "Charming cottage by the lake with a private dock and fishing equipment.",
                maxGuests: 4,
                amenities: ["WiFi", "Private Bathroom", "Kitchenette", "Lake Access", "Fishing Equipment"]
            },
            {
                id: 4,
                name: "Eco Treehouse",
                type: "Treehouse",
                price: 1275,
                image: "https://images.unsplash.com/photo-1591824438703-70e2c6d0e455?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80",
                description: "Unique treehouse experience with eco-friendly features and panoramic views.",
                maxGuests: 2,
                amenities: ["WiFi", "Composting Toilet", "Solar Power", "Balcony"]
            },
            {
                id: 5,
                name: "Riverside Bungalow",
                type: "Bungalow",
                price: 1320,
                image: "https://images.unsplash.com/photo-1586375300773-8384e3e4916f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80",
                description: "Comfortable bungalow with direct river access and outdoor seating area.",
                maxGuests: 3,
                amenities: ["WiFi", "Private Bathroom", "Kitchenette", "River Access", "Outdoor Seating"]
            },
            {
                id: 6,
                name: "Wilderness Retreat",
                type: "Cabin",
                price: 1695,
                image: "https://images.unsplash.com/photo-1449824913935-59a10b8d2000?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80",
                description: "Secluded retreat deep in the wilderness with modern comforts.",
                maxGuests: 4,
                amenities: ["WiFi", "Private Bathroom", "Full Kitchen", "Hot Tub", "Fire Pit"]
            }
        ];

        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const userDropdownToggle = document.getElementById('userDropdownToggle');
        const dropdown = userDropdownToggle.closest('.dropdown');
        const mobileLogout = document.getElementById('mobileLogout');
        const logoutModal = document.getElementById('logoutModal');
        const cancelLogout = document.getElementById('cancelLogout');
        const cancelBookingModal = document.getElementById('cancelBookingModal');
        const cancelBookingNo = document.getElementById('cancelBookingNo');
        const cancelBookingYes = document.getElementById('cancelBookingYes');
        const bookingModal = document.getElementById('bookingModal');
        const closeBookingModal = document.getElementById('closeBookingModal');
        const invoiceModal = document.getElementById('invoiceModal');
        const closeInvoiceModal = document.getElementById('closeInvoiceModal');
        const closeInvoice = document.getElementById('closeInvoice');
        const paymentModal = document.getElementById('paymentModal');
        const closePaymentModal = document.getElementById('closePaymentModal');
        const confirmationModal = document.getElementById('confirmationModal');
        const closeConfirmationModal = document.getElementById('closeConfirmationModal');
        const searchForm = document.getElementById('search-form');
        const availableRoomsContainer = document.getElementById('available-rooms');
        const bookingForm = document.getElementById('booking-form');
        const bookingRoomDetails = document.getElementById('booking-room-details');
        const invoiceContent = document.getElementById('invoice-content');
        const paymentSummary = document.getElementById('payment-summary');
        const cashPayment = document.getElementById('cash-payment');
        const paypalPayment = document.getElementById('paypal-payment');
        const paypalContainer = document.getElementById('paypal-container');
        const confirmPayment = document.getElementById('confirmPayment');
        const tabButtons = document.querySelectorAll('.tab-button');
        const bookingTabs = document.querySelectorAll('.booking-tab');

        // Current booking data
        let currentBooking = null;

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            // Load available rooms
            renderAvailableRooms(availableRooms);
            
            // Set default dates for search
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            
            document.getElementById('check-in').valueAsDate = today;
            document.getElementById('check-out').valueAsDate = tomorrow;
            
            // Initialize event listeners
            initializeEventListeners();
        });

        // Initialize all event listeners
        function initializeEventListeners() {
            // Mobile menu toggle
            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('active');
            });
            
            // Overlay click to close mobile menu
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('open');
                this.classList.remove('active');
            });
            
            // User dropdown toggle
            userDropdownToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const dropdownContent = dropdown.querySelector('.dropdown-content');
                dropdownContent.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking elsewhere
            document.addEventListener('click', function() {
                const dropdownContent = dropdown.querySelector('.dropdown-content');
                dropdownContent.classList.add('hidden');
            });
            
            // Mobile logout button
            mobileLogout.addEventListener('click', function() {
                logoutModal.classList.remove('hidden');
            });
            
            // Cancel logout
            cancelLogout.addEventListener('click', function() {
                logoutModal.classList.add('hidden');
            });
            
            // Logout modal background click
            logoutModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
            
            // Navigation items
            document.querySelectorAll('.nav-item, .dropdown-content a[data-tab], a[data-tab], button[data-tab]').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const targetTab = this.getAttribute('data-tab');
                    
                    // Update active nav item
                    document.querySelectorAll('.nav-item').forEach(navItem => {
                        navItem.classList.remove('active');
                    });
                    
                    if (this.classList.contains('nav-item')) {
                        this.classList.add('active');
                    } else {
                        // Find and activate the corresponding nav item
                        const correspondingNavItem = document.querySelector(`.nav-item[data-tab="${targetTab}"]`);
                        if (correspondingNavItem) {
                            correspondingNavItem.classList.add('active');
                        }
                    }
                    
                    // Show target tab
                    document.querySelectorAll('.tab-content').forEach(tab => {
                        tab.classList.remove('active');
                    });
                    
                    document.getElementById(targetTab).classList.add('active');
                    
                    // Update page title
                    updatePageTitle(targetTab);
                    
                    // Close mobile menu
                    sidebar.classList.remove('open');
                    overlay.classList.remove('active');
                });
            });
            
            // Booking tabs
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    
                    // Update active tab button
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'border-eco-accent', 'text-eco-dark');
                    });
                    this.classList.add('active', 'border-eco-accent', 'text-eco-dark');
                    
                    // Show corresponding tab content
                    bookingTabs.forEach(tab => {
                        tab.classList.remove('active');
                    });
                    document.getElementById(tabId).classList.add('active');
                });
            });
            
            // Search form submission
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                // In a real app, this would filter rooms based on search criteria
                // For now, we'll just show all rooms
                renderAvailableRooms(availableRooms);
                showAlert('success', 'Search Complete', 'Available rooms are shown below.');
            });
            
            // Booking form submission
            bookingForm.addEventListener('submit', function(e) {
                e.preventDefault();
                bookingModal.classList.remove('active');
                // Generate invoice
                generateInvoice();
                invoiceModal.classList.add('active');
            });
            
            // Close booking modal
            closeBookingModal.addEventListener('click', function() {
                bookingModal.classList.remove('active');
            });
            
            // Close invoice modal
            closeInvoiceModal.addEventListener('click', function() {
                invoiceModal.classList.remove('active');
            });
            
            closeInvoice.addEventListener('click', function() {
                invoiceModal.classList.remove('active');
            });
            
            // Payment method selection
            cashPayment.addEventListener('change', function() {
                if (this.checked) {
                    paypalContainer.classList.add('hidden');
                }
            });
            
            paypalPayment.addEventListener('change', function() {
                if (this.checked) {
                    paypalContainer.classList.remove('hidden');
                generatePaymentSummary();
                paymentSummary.innerHTML = `
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Room Rate (3 nights)</span>
                            <span>₱4,245.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Taxes & Fees</span>
                            <span>₱635.00</span>
                        </div>
                        <div class="border-t border-gray-300 pt-2 flex justify-between font-bold">
                            <span>Total</span>
                            <span>₱4,880.00</span>
                        </div>
                    </div>
                `;
                }
            });
            
            // Confirm payment
            confirmPayment.addEventListener('click', function() {
                paymentModal.classList.remove('active');
                confirmationModal.classList.add('active');
            });
            
            // Close payment modal
            closePaymentModal.addEventListener('click', function() {
                paymentModal.classList.remove('active');
            });
            
            // Close confirmation modal
            closeConfirmationModal.addEventListener('click', function() {
                confirmationModal.classList.remove('active');
                // Navigate to bookings tab
                document.querySelector('.nav-item[data-tab="bookings"]').click();
            });
            
            // Cancel booking buttons
            document.querySelectorAll('.cancel-booking').forEach(button => {
                button.addEventListener('click', function() {
                    const bookingId = this.getAttribute('data-booking-id');
                    const bookingName = this.getAttribute('data-booking-name');
                    
                    document.getElementById('cancel-booking-text').textContent = 
                        `Are you sure you want to cancel your booking for "${bookingName}" (ID: #BK-${bookingId})?`;
                    
                    cancelBookingModal.classList.remove('hidden');
                    
                    // Set up confirmation action
                    cancelBookingYes.onclick = function() {
                        // In a real app, this would send a cancellation request to the server
                        showAlert('success', 'Booking Cancelled', `Your booking for ${bookingName} has been cancelled. A confirmation email has been sent.`);
                        cancelBookingModal.classList.add('hidden');
                        
                        // Update UI to reflect cancellation
                        setTimeout(() => {
                            const bookingCard = document.querySelector(`[data-booking-id="${bookingId}"]`).closest('.bg-white');
                            const statusElement = bookingCard.querySelector('.booking-status-confirmed, .booking-status-pending');
                            statusElement.className = 'booking-status-cancelled px-3 py-1 rounded-full text-xs font-medium';
                            statusElement.textContent = 'Cancelled';
                        }, 1000);
                    };
                });
            });
            
            // Cancel booking modal buttons
            cancelBookingNo.addEventListener('click', function() {
                cancelBookingModal.classList.add('hidden');
            });
            
            cancelBookingModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
            
            // Profile form submission
            document.getElementById('profile-form').addEventListener('submit', function(e) {
                e.preventDefault();
                showAlert('success', 'Profile Updated', 'Your profile information has been successfully updated.');
            });
            
            // Alert close button
            document.getElementById('alert-close').addEventListener('click', function() {
                document.getElementById('alert').style.display = 'none';
            });
        }

        // Render available rooms
        function renderAvailableRooms(rooms) {
            availableRoomsContainer.innerHTML = '';
            
            rooms.forEach(room => {
                const roomCard = document.createElement('div');
                roomCard.className = 'room-card bg-white rounded-xl shadow-md overflow-hidden';
                roomCard.innerHTML = `
                    <div class="h-48 bg-gray-200 overflow-hidden">
                        <img src="${room.image}" alt="${room.name}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-eco-dark">${room.name}</h3>
                            <span class="bg-eco-light bg-opacity-20 text-eco-dark text-xs font-medium px-2 py-1 rounded">${room.type}</span>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">${room.description}</p>
                        <div class="flex items-center text-sm text-gray-500 mb-4">
                            <i class="fas fa-user mr-1"></i>
                            <span>Up to ${room.maxGuests} guests</span>
                            <i class="fas fa-wifi ml-4 mr-1"></i>
                            <span>WiFi</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-2xl font-bold text-eco-dark">₱${room.price}<span class="text-sm font-normal text-gray-500">/night</span></p>
                            </div>
                            <button class="book-now-btn bg-eco-primary text-white px-4 py-2 rounded-lg hover:bg-eco-dark transition-colors" data-room-id="${room.id}">
                                Book Now
                            </button>
                        </div>
                    </div>
                `;
                
                availableRoomsContainer.appendChild(roomCard);
            });
            
            // Add event listeners to book now buttons
            document.querySelectorAll('.book-now-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const roomId = this.getAttribute('data-room-id');
                    const room = availableRooms.find(r => r.id == roomId);
                    openBookingModal(room);
                });
            });
        }

        // Open booking modal with room details
        function openBookingModal(room) {
            currentBooking = room;
            
            // Populate room details in modal
            bookingRoomDetails.innerHTML = `
                <h4 class="font-bold text-lg">${room.name}</h4>
                <p class="text-gray-600 text-sm mt-1">${room.type}</p>
                <div class="mt-4 space-y-2">
                    <div class="flex justify-between">
                        <span>Check-in</span>
                        <span>${document.getElementById('check-in').value}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Check-out</span>
                        <span>${document.getElementById('check-out').value}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Guests</span>
                        <span>${document.getElementById('guests').value}</span>
                    </div>
                    <div class="flex justify-between font-bold mt-2 pt-2 border-t border-gray-300">
                        <span>Total (3 nights)</span>
                        <span>₱${room.price * 3}.00</span>
                    </div>
                </div>
            `;
            
            // Show booking modal
            bookingModal.classList.add('active');
        }

        // Generate invoice content
        function generateInvoice() {
            const checkIn = document.getElementById('check-in').value;
            const checkOut = document.getElementById('check-out').value;
            const guests = document.getElementById('guests').value;
            const room = currentBooking;
            const roomTotal = room.price * 3;
            const taxes = Math.round(roomTotal * 0.12);
            const total = roomTotal + taxes;
            
            invoiceContent.innerHTML = `
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-eco-dark">BrokenShire Resort</h2>
                        <p class="text-gray-600">123 Nature Trail, Eco Valley</p>
                        <p class="text-gray-600">Phone: (555) 123-4567</p>
                    </div>
                    <div class="text-right">
                        <h3 class="text-lg font-bold">INVOICE</h3>
                        <p class="text-gray-600">#INV-${Math.floor(1000 + Math.random() * 9000)}</p>
                        <p class="text-gray-600">Date: ${new Date().toLocaleDateString()}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <h4 class="font-bold text-gray-700 mb-2">Bill To:</h4>
                        <p>${document.getElementById('guest-name').value}</p>
                        <p>${document.getElementById('guest-email').value}</p>
                        <p>${document.getElementById('guest-phone').value}</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-700 mb-2">Booking Details:</h4>
                        <p>Check-in: ${checkIn}</p>
                        <p>Check-out: ${checkOut}</p>
                        <p>Guests: ${guests}</p>
                    </div>
                </div>
                
                <table class="w-full mb-6">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="text-left py-2 px-4 font-medium">Description</th>
                            <th class="text-right py-2 px-4 font-medium">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border-b">${room.name} (3 nights @ ₱${room.price}/night)</td>
                            <td class="py-2 px-4 border-b text-right">₱${roomTotal}.00</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b">Taxes & Fees</td>
                            <td class="py-2 px-4 border-b text-right">₱${taxes}.00</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 font-bold">Total</td>
                            <td class="py-2 px-4 text-right font-bold">₱${total}.00</td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="text-sm text-gray-500 mt-6">
                    <p>Thank you for choosing BrokenShire Resort. We look forward to hosting you!</p>
                </div>
            `;
            
            // Update payment summary
            paymentSummary.innerHTML = `
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>Room Rate (3 nights)</span>
                        <span>₱${roomTotal}.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Taxes & Fees</span>
                        <span>₱${taxes}.00</span>
                    </div>
                    <div class="border-t border-gray-300 pt-2 flex justify-between font-bold">
                        <span>Total</span>
                        <span>₱${total}.00</span>
                    </div>
                </div>
            `;
        }

        // Generate payment summary
        function generatePaymentSummary() {
            // This would be called when payment method changes
            // For now, we use the same data as in generateInvoice
        }

        // Update page title based on active tab
        function updatePageTitle(tab) {
            const titles = {
                'dashboard': 'Dashboard',
                'bookings': 'My Reservations',
                'search-rooms': 'Find Rooms',
                'profile': 'Profile',
                'payments': 'Payments',
                'settings': 'Settings'
            };
            
            const subtitles = {
                'dashboard': 'Welcome back, John Smith! Here\'s your overview.',
                'bookings': 'Manage your current and past bookings.',
                'search-rooms': 'Find and book available rooms for your stay.',
                'profile': 'View and update your personal information.',
                'payments': 'View your payment history and manage payment methods.',
                'settings': 'Customize your account preferences and settings.'
            };
            
            document.getElementById('page-title').textContent = titles[tab];
            document.getElementById('page-subtitle').textContent = subtitles[tab];
        }

        // Show alert notification
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
            
            // Set alert content
            alertTitle.textContent = title;
            alertMessage.textContent = message;
          
            alert.style.display = 'block';
        
            setTimeout(() => {
                alert.style.display = 'none';
            }, 5000);
        }
    </script>
</body>
</html>