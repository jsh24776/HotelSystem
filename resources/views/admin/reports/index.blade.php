<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrokenShire Admin - Reports & Analytics</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        
        .sidebar-item.active {
            background-color: #F1F8E9;
            border-left: 4px solid #2E7D32;
            color: #1B5E20;
        }
        
        .sidebar-item:hover:not(.active) {
            background-color: rgba(129, 199, 132, 0.1);
        }
        
        .dashboard-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            border-radius: 8px;
            z-index: 100;
            overflow: hidden;
        }
        
        .dropdown.open .dropdown-content {
            display: block;
        }
        
        .status-confirmed {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .status-checked-in {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .status-checked-out {
            background-color: #e5e7eb;
            color: #374151;
        }
        
        .trend-up {
            color: #10b981;
        }
        
        .trend-down {
            color: #ef4444;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.open {
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
            
            .dropdown-content {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 80%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body class="bg-eco-cream text-gray-800">
    <!-- Mobile Menu Button -->
    <div class="md:hidden fixed top-4 left-4 z-20">
        <button id="menuToggle" class="bg-eco-primary text-white p-2 rounded-lg">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <div id="overlay" class="overlay"></div>
    
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar bg-white w-64 fixed h-full shadow-lg z-10">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-eco-dark flex items-center space-x-2">
                    <i class="fas fa-leaf text-eco-primary"></i>
                    <span>BrokenShire</span>
                </h1>
                <p class="text-sm text-gray-500 mt-1">Reports & Analytics</p>
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
                       <a href="{{ route('admin.payments.index') }}" 
                            class="sidebar-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }} flex items-center py-3 px-6 text-gray-600">
                            <i class="fas fa-credit-card mr-3"></i> Payments
                            </a>
                    </li>
                    <li>
                       <a href="{{ route('admin.inventory.index') }}" 
                       class="sidebar-item {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }} flex items-center py-3 px-6 text-gray-600">
                            <i class="fas fa-boxes mr-3"></i>
                            Inventory
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.bookings.index') }}" 
                         class="sidebar-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }} flex items-center py-3 px-6 text-gray-600">
                            <i class="fas fa-calendar-check mr-3"></i>
                            Current Bookings
                        </a>
                    </li>
                    <li>
                          <a href="{{ route('admin.reports.index') }}" 
                           class="sidebar-item {{ request()->routeIs('reports.bookings.*') ? 'active' : '' }} flex items-center py-3 px-6 text-gray-600">
                            <i class="fas fa-chart-bar mr-3"></i>
                            Reports & Analytics
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="absolute bottom-0 w-full p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-eco-light flex items-center justify-center text-eco-dark font-bold">
                            AD
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold">Admin User</p>
                            <p class="text-sm text-gray-500">ADM Role</p>
                        </div>
                    </div>
                
                </div>
            </div>
        </aside>
        

        <main class="flex-1 ml-0 md:ml-64 p-6">
       
            <header class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-eco-dark">Reports & Analytics Dashboard</h1>
                        <p class="text-gray-600">Monitor performance metrics and generate business insights</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Search reports..." class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
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
                                    AD
                                </div>
                                <span class="hidden md:block text-gray-700">Admin User</span>
                                <i class="fas fa-chevron-down text-gray-500"></i>
                            </button>
                            
                            <div class="dropdown-content mt-2">
                                <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-eco-cream border-b border-gray-100">
                                    <i class="fas fa-user mr-2"></i>My Profile
                                </a>
                                <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-eco-cream border-b border-gray-100">
                                    <i class="fas fa-cog mr-2"></i>Settings
                                </a>
                                <button id="logoutBtn" class="w-full text-left px-4 py-3 text-gray-700 hover:bg-eco-cream flex items-center">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Log Out
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <h2 class="text-xl font-bold text-eco-dark mb-4 md:mb-0">Report Period</h2>
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center space-x-2">
                            <label class="text-gray-700">From:</label>
                            <input type="date" class="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" value="2023-04-01">
                        </div>
                        <div class="flex items-center space-x-2">
                            <label class="text-gray-700">To:</label>
                            <input type="date" class="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" value="2023-05-18">
                        </div>
                        <button class="bg-eco-primary text-white px-4 py-2 rounded-lg hover:bg-eco-dark transition-colors flex items-center">
                            <i class="fas fa-sync-alt mr-2"></i> Update Reports
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500">Total Reservations</p>
                            <h3 class="text-2xl font-bold text-eco-dark">142</h3>
                            <p class="trend-up text-sm mt-1"><i class="fas fa-arrow-up mr-1"></i> 12.5% from last period</p>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-calendar-check text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500">Occupancy Rate</p>
                            <h3 class="text-2xl font-bold text-eco-dark">78%</h3>
                            <p class="trend-up text-sm mt-1"><i class="fas fa-arrow-up mr-1"></i> 5.2% from last period</p>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-bed text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500">Total Revenue</p>
                            <h3 class="text-2xl font-bold text-eco-dark">₱
424,850</h3>
                            <p class="trend-up text-sm mt-1"><i class="fas fa-arrow-up mr-1"></i> 8.7% from last period</p>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-dollar-sign text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500">Guest Satisfaction</p>
                            <h3 class="text-2xl font-bold text-eco-dark">4.6/5</h3>
                            <p class="trend-down text-sm mt-1"><i class="fas fa-arrow-down mr-1"></i> 0.2 from last period</p>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-star text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-eco-dark">Reservations Trend</h3>
                        <div class="flex space-x-2">
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="reservationsChart"></canvas>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-eco-dark">Revenue by Room Type</h3>
                        <div class="flex space-x-2">
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Additional Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-eco-dark">Guest Satisfaction</h3>
                        <div class="flex space-x-2">
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="satisfactionChart"></canvas>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-eco-dark">Payment Methods</h3>
                        <div class="flex space-x-2">
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="paymentMethodsChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Report Tables Section -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
                <!-- Recent Reservations -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-eco-dark">Recent Reservations</h2>
                        <div class="flex space-x-2">
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors">
                                <i class="fas fa-filter"></i>
                            </button>
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 text-left text-gray-500">
                                    <th class="py-3 px-6 font-medium">Reservation ID</th>
                                    <th class="py-3 px-6 font-medium">Guest</th>
                                    <th class="py-3 px-6 font-medium">Check-In</th>
                                    <th class="py-3 px-6 font-medium">Status</th>
                                    <th class="py-3 px-6 font-medium">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200" id="reservationsTableBody">
                                <!-- Reservation rows will be dynamically generated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Top Performing Rooms -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-eco-dark">Top Performing Rooms</h2>
                        <div class="flex space-x-2">
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors">
                                <i class="fas fa-filter"></i>
                            </button>
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 text-left text-gray-500">
                                    <th class="py-3 px-6 font-medium">Room</th>
                                    <th class="py-3 px-6 font-medium">Type</th>
                                    <th class="py-3 px-6 font-medium">Occupancy</th>
                                    <th class="py-3 px-6 font-medium">Revenue</th>
                                    <th class="py-3 px-6 font-medium">Rating</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200" id="roomsTableBody">
                                <!-- Room rows will be dynamically generated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Export Section -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <h2 class="text-xl font-bold text-eco-dark">Export Reports</h2>
                    <p class="text-gray-600 mt-2 md:mt-0">Generate and download comprehensive reports</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="border border-gray-200 rounded-lg p-4 text-center hover:border-eco-accent transition-colors">
                        <div class="w-12 h-12 bg-eco-light bg-opacity-20 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-calendar-alt text-eco-accent text-xl"></i>
                        </div>
                        <h3 class="font-medium text-gray-800 mb-2">Reservations Report</h3>
                        <p class="text-sm text-gray-600 mb-4">Detailed reservation data and trends</p>
                        <div class="flex justify-center space-x-2">
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors export-btn" data-report="reservations" data-format="pdf">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors export-btn" data-report="reservations" data-format="excel">
                                <i class="fas fa-file-excel"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="border border-gray-200 rounded-lg p-4 text-center hover:border-eco-accent transition-colors">
                        <div class="w-12 h-12 bg-eco-light bg-opacity-20 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-chart-line text-eco-accent text-xl"></i>
                        </div>
                        <h3 class="font-medium text-gray-800 mb-2">Financial Report</h3>
                        <p class="text-sm text-gray-600 mb-4">Revenue, payments, and financial metrics</p>
                        <div class="flex justify-center space-x-2">
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors export-btn" data-report="financial" data-format="pdf">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors export-btn" data-report="financial" data-format="excel">
                                <i class="fas fa-file-excel"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="border border-gray-200 rounded-lg p-4 text-center hover:border-eco-accent transition-colors">
                        <div class="w-12 h-12 bg-eco-light bg-opacity-20 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-star text-eco-accent text-xl"></i>
                        </div>
                        <h3 class="font-medium text-gray-800 mb-2">Feedback Report</h3>
                        <p class="text-sm text-gray-600 mb-4">Guest reviews and satisfaction metrics</p>
                        <div class="flex justify-center space-x-2">
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors export-btn" data-report="feedback" data-format="pdf">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors export-btn" data-report="feedback" data-format="excel">
                                <i class="fas fa-file-excel"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="border border-gray-200 rounded-lg p-4 text-center hover:border-eco-accent transition-colors">
                        <div class="w-12 h-12 bg-eco-light bg-opacity-20 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-file-contract text-eco-accent text-xl"></i>
                        </div>
                        <h3 class="font-medium text-gray-800 mb-2">Summary Report</h3>
                        <p class="text-sm text-gray-600 mb-4">Consolidated overview of all metrics</p>
                        <div class="flex justify-center space-x-2">
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors export-btn" data-report="summary" data-format="pdf">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors export-btn" data-report="summary" data-format="excel">
                                <i class="fas fa-file-excel"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Export Modal -->
    <div id="exportModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-full bg-eco-light flex items-center justify-center mr-4">
                    <i class="fas fa-download text-eco-accent text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800" id="exportModalTitle">Export Report</h3>
                    <p class="text-gray-600">Your report is being generated...</p>
                </div>
            </div>
            
            <div class="mb-4">
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-eco-primary h-2.5 rounded-full" id="exportProgress" style="width: 0%"></div>
                </div>
                <p class="text-right text-sm text-gray-500 mt-1" id="exportProgressText">0% complete</p>
            </div>
            
            <div class="text-sm text-gray-600 mb-4">
                <p class="mb-1"><i class="fas fa-database text-blue-500 mr-2"></i> Fetching data from SYS</p>
                <p class="mb-1"><i class="fas fa-cogs text-yellow-500 mr-2"></i> Processing report data</p>
                <p><i class="fas fa-file-export text-green-500 mr-2"></i> Generating export file</p>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button id="cancelExport" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                <button id="confirmExport" class="px-4 py-2 bg-eco-primary text-white rounded-lg hover:bg-eco-dark transition-colors hidden">Download</button>
            </div>
        </div>
    </div>


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
                <button id="confirmLogout" class="px-4 py-2 bg-eco-primary text-white rounded-lg hover:bg-eco-dark transition-colors">
                    Logout
                </button>
            </div>
        </div>
    </div>

    <script>
        // Sample data
        const sampleReservations = [
            { id: 1, reservationId: "RES-001", guest: "John Smith", checkIn: "2023-05-15", status: "checked-in", amount: 681.70 },
            { id: 2, reservationId: "RES-002", guest: "Jane Doe", checkIn: "2023-05-16", status: "confirmed", amount: 452.50 },
            { id: 3, reservationId: "RES-003", guest: "Mike Johnson", checkIn: "2023-05-17", status: "pending", amount: 789.25 },
            { id: 4, reservationId: "RES-004", guest: "Sarah Williams", checkIn: "2023-05-12", status: "checked-out", amount: 325.80 },
            { id: 5, reservationId: "RES-005", guest: "Robert Brown", checkIn: "2023-05-10", status: "cancelled", amount: 1024.90 }
        ];

        const sampleRooms = [
            { id: 1, room: "101", type: "Single", occupancy: "85%", revenue: 2450, rating: 4.7 },
            { id: 2, room: "102", type: "Double", occupancy: "92%", revenue: 3120, rating: 4.8 },
            { id: 3, room: "201", type: "Suite", occupancy: "78%", revenue: 4850, rating: 4.9 },
            { id: 4, room: "202", type: "Deluxe", occupancy: "65%", revenue: 3920, rating: 4.5 },
            { id: 5, room: "301", type: "Single", occupancy: "88%", revenue: 2670, rating: 4.6 }
        ];

        // Initialize data
        let reservations = [...sampleReservations];
        let rooms = [...sampleRooms];

        // DOM elements
        const reservationsTableBody = document.getElementById('reservationsTableBody');
        const roomsTableBody = document.getElementById('roomsTableBody');
        const exportModal = document.getElementById('exportModal');
        const logoutModal = document.getElementById('logoutModal');
        const exportModalTitle = document.getElementById('exportModalTitle');
        const exportProgress = document.getElementById('exportProgress');
        const exportProgressText = document.getElementById('exportProgressText');
        const cancelExport = document.getElementById('cancelExport');
        const confirmExport = document.getElementById('confirmExport');
        const cancelLogout = document.getElementById('cancelLogout');
        const confirmLogout = document.getElementById('confirmLogout');
        const logoutBtn = document.getElementById('logoutBtn');

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
        
        dropdown.querySelector('.dropdown-content').addEventListener('click', function(e) {
            e.stopPropagation();
        });
        

        logoutBtn.addEventListener('click', function() {
            logoutModal.classList.remove('hidden');
        });
        
        cancelLogout.addEventListener('click', function() {
            logoutModal.classList.add('hidden');
        });
        
        confirmLogout.addEventListener('click', function() {

            alert('Logout functionality would be implemented here');
            logoutModal.classList.add('hidden');
        });
        

        [exportModal, logoutModal].forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });

    
        function renderReservationsTable() {
            reservationsTableBody.innerHTML = '';
            
            reservations.forEach(reservation => {
                const statusClass = `status-${reservation.status}`;
                const statusText = reservation.status.charAt(0).toUpperCase() + reservation.status.slice(1).replace('-', ' ');
                
                const row = document.createElement('tr');
                row.className = 'hover:bg-eco-cream transition-colors';
                row.innerHTML = `
                    <td class="py-4 px-6 font-medium">${reservation.reservationId}</td>
                    <td class="py-4 px-6">${reservation.guest}</td>
                    <td class="py-4 px-6">${reservation.checkIn}</td>
                    <td class="py-4 px-6">
                        <span class="px-3 py-1 rounded-full text-xs font-medium ${statusClass}">${statusText}</span>
                    </td>
                    <td class="py-4 px-6 font-bold">$${reservation.amount}</td>
                `;
                
                reservationsTableBody.appendChild(row);
            });
        }

        // Function to render rooms table
        function renderRoomsTable() {
            roomsTableBody.innerHTML = '';
            
            rooms.forEach(room => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-eco-cream transition-colors';
                row.innerHTML = `
                    <td class="py-4 px-6 font-medium">${room.room}</td>
                    <td class="py-4 px-6">${room.type}</td>
                    <td class="py-4 px-6">
                        <div class="flex items-center">
                            <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                <div class="bg-eco-primary h-2 rounded-full" style="width: ${room.occupancy}"></div>
                            </div>
                            <span>${room.occupancy}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6 font-bold">$${room.revenue}</td>
                    <td class="py-4 px-6">
                        <div class="flex items-center">
                            <span class="mr-1">${room.rating}</span>
                            <i class="fas fa-star text-yellow-400"></i>
                        </div>
                    </td>
                `;
                
                roomsTableBody.appendChild(row);
            });
        }

        // Export functionality
        document.querySelectorAll('.export-btn').forEach(button => {
            button.addEventListener('click', function() {
                const reportType = this.getAttribute('data-report');
                const format = this.getAttribute('data-format');
                
                // Set modal title based on report type
                const reportTitles = {
                    'reservations': 'Reservations Report',
                    'financial': 'Financial Report',
                    'feedback': 'Feedback Report',
                    'summary': 'Summary Report'
                };
                
                exportModalTitle.textContent = `Export ${reportTitles[reportType]} as ${format.toUpperCase()}`;
                exportModal.classList.remove('hidden');
                
                // Simulate export progress
                let progress = 0;
                const interval = setInterval(() => {
                    progress += 5;
                    exportProgress.style.width = `${progress}%`;
                    exportProgressText.textContent = `${progress}% complete`;
                    
                    if (progress >= 100) {
                        clearInterval(interval);
                        exportProgressText.textContent = 'Export complete!';
                        confirmExport.classList.remove('hidden');
                    }
                }, 100);
                
                // Store interval to clear if canceled
                window.exportInterval = interval;
            });
        });
        
        cancelExport.addEventListener('click', function() {
            exportModal.classList.add('hidden');
            clearInterval(window.exportInterval);
            exportProgress.style.width = '0%';
            exportProgressText.textContent = '0% complete';
            confirmExport.classList.add('hidden');
        });
        
        confirmExport.addEventListener('click', function() {
            exportModal.classList.add('hidden');
            alert('Your report has been downloaded! (This is a frontend demo - no actual file downloaded)');
            exportProgress.style.width = '0%';
            exportProgressText.textContent = '0% complete';
            confirmExport.classList.add('hidden');
        });

        // Initialize charts
        function initializeCharts() {
            // Reservations Trend Chart
            const reservationsCtx = document.getElementById('reservationsChart').getContext('2d');
            new Chart(reservationsCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                    datasets: [{
                        label: 'Reservations',
                        data: [120, 135, 128, 142, 156],
                        borderColor: '#4CAF50',
                        backgroundColor: 'rgba(76, 175, 80, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    }
                }
            });

            // Revenue by Room Type Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: ['Single', 'Double', 'Suite', 'Deluxe'],
                    datasets: [{
                        label: 'Revenue ($)',
                        data: [8450, 11200, 15600, 9800],
                        backgroundColor: [
                            'rgba(76, 175, 80, 0.7)',
                            'rgba(76, 175, 80, 0.8)',
                            'rgba(76, 175, 80, 0.9)',
                            'rgba(76, 175, 80, 0.6)'
                        ],
                        borderColor: [
                            '#4CAF50',
                            '#4CAF50',
                            '#4CAF50',
                            '#4CAF50'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Guest Satisfaction Chart
            const satisfactionCtx = document.getElementById('satisfactionChart').getContext('2d');
            new Chart(satisfactionCtx, {
                type: 'radar',
                data: {
                    labels: ['Cleanliness', 'Comfort', 'Location', 'Staff', 'Amenities', 'Value'],
                    datasets: [{
                        label: 'Current Period',
                        data: [4.7, 4.6, 4.8, 4.9, 4.5, 4.4],
                        backgroundColor: 'rgba(76, 175, 80, 0.2)',
                        borderColor: '#4CAF50',
                        pointBackgroundColor: '#4CAF50',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#4CAF50'
                    }, {
                        label: 'Previous Period',
                        data: [4.5, 4.7, 4.6, 4.8, 4.3, 4.5],
                        backgroundColor: 'rgba(33, 150, 243, 0.2)',
                        borderColor: '#2196F3',
                        pointBackgroundColor: '#2196F3',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#2196F3'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            angleLines: {
                                display: true
                            },
                            suggestedMin: 3,
                            suggestedMax: 5
                        }
                    }
                }
            });

            // Payment Methods Chart
            const paymentMethodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
            new Chart(paymentMethodsCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Credit Card', 'Cash', 'Online', 'Bank Transfer'],
                    datasets: [{
                        data: [45, 25, 20, 10],
                        backgroundColor: [
                            '#4CAF50',
                            '#2196F3',
                            '#FF9800',
                            '#9C27B0'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Search functionality
        const searchInput = document.querySelector('input[type="text"]');
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const filteredReservations = sampleReservations.filter(reservation => 
                reservation.reservationId.toLowerCase().includes(searchTerm) || 
                reservation.guest.toLowerCase().includes(searchTerm)
            );
            
            const filteredRooms = sampleRooms.filter(room => 
                room.room.toLowerCase().includes(searchTerm) || 
                room.type.toLowerCase().includes(searchTerm)
            );
            
            reservations = filteredReservations;
            rooms = filteredRooms;
            renderReservationsTable();
            renderRoomsTable();
        });

        // Initialize the UI
        renderReservationsTable();
        renderRoomsTable();
        initializeCharts();
    </script>
</body>
</html>