<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrokenShire Admin - Inventory Management</title>
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
        
        .status-vacant {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-occupied {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .status-reserved {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .status-dirty {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-ready {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-out {
            background-color: #e5e7eb;
            color: #374151;
        }
        
        .status-archived {
            background-color: #f3f4f6;
            color: #6b7280;
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
                <p class="text-sm text-gray-500 mt-1">Inventory Management</p>
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
                                <i class="fas fa-users mr-3"></i> Guest
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
                            <p class="font-semibold">Admin</p>
                            <p class="text-sm text-gray-500">ADM Role</p>
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
                        <h1 class="text-3xl font-bold text-eco-dark">Room Inventory Management</h1>
                        <p class="text-gray-600">Manage all rooms and their statuses</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Search rooms..." class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
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
                                <span class="hidden md:block text-gray-700">Admin</span>
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
            
            <!-- Tabs Navigation -->
            <div class="flex border-b border-gray-200 mb-8">
                <button id="dashboardTab" class="tab-button py-3 px-6 font-medium text-eco-primary border-b-2 border-eco-primary">Dashboard</button>
                <button id="archivedTab" class="tab-button py-3 px-6 font-medium text-gray-500 hover:text-eco-primary">Archived Rooms</button>
                <button id="reportsTab" class="tab-button py-3 px-6 font-medium text-gray-500 hover:text-eco-primary">Reports & Analytics</button>
                <button id="auditTab" class="tab-button py-3 px-6 font-medium text-gray-500 hover:text-eco-primary">Audit Log</button>
            </div>
            
            <!-- Dashboard Tab Content -->
            <div id="dashboardContent" class="tab-content">
                <!-- Room Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500">Total Rooms</p>
                                <h3 class="text-2xl font-bold text-eco-dark">42</h3>
                                <p class="text-green-600 text-sm mt-1"><i class="fas fa-arrow-up mr-1"></i> 5 added this month</p>
                            </div>
                            <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                                <i class="fas fa-door-open text-eco-accent text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500">Available Rooms</p>
                                <h3 class="text-2xl font-bold text-eco-dark">18</h3>
                                <p class="text-green-600 text-sm mt-1"><i class="fas fa-arrow-up mr-1"></i> 43% availability</p>
                            </div>
                            <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                                <i class="fas fa-bed text-eco-accent text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500">Occupied Rooms</p>
                                <h3 class="text-2xl font-bold text-eco-dark">16</h3>
                                <p class="text-red-600 text-sm mt-1"><i class="fas fa-arrow-up mr-1"></i> 38% occupancy</p>
                            </div>
                            <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                                <i class="fas fa-user-friends text-eco-accent text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500">Maintenance</p>
                                <h3 class="text-2xl font-bold text-eco-dark">8</h3>
                                <p class="text-yellow-600 text-sm mt-1"><i class="fas fa-exclamation-triangle mr-1"></i> 19% out of service</p>
                            </div>
                            <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                                <i class="fas fa-tools text-eco-accent text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-4 mb-8">
                    <button id="addRoomBtn" class="bg-eco-primary text-white px-4 py-2 rounded-lg hover:bg-eco-dark transition-colors flex items-center">
                        <i class="fas fa-plus mr-2"></i> Add New Room
                    </button>
                    <button id="syncReservationsBtn" class="bg-eco-accent text-white px-4 py-2 rounded-lg hover:bg-eco-dark transition-colors flex items-center">
                        <i class="fas fa-sync-alt mr-2"></i> Sync Reservations
                    </button>
                    <button id="generateReportBtn" class="bg-white text-eco-primary border border-eco-primary px-4 py-2 rounded-lg hover:bg-eco-cream transition-colors flex items-center">
                        <i class="fas fa-chart-bar mr-2"></i> Generate Report
                    </button>
                    <button id="viewAuditBtn" class="bg-white text-eco-primary border border-eco-primary px-4 py-2 rounded-lg hover:bg-eco-cream transition-colors flex items-center">
                        <i class="fas fa-clipboard-list mr-2"></i> View Audit Log
                    </button>
                </div>
                
                <!-- Room Status Dashboard -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-eco-dark">Room Status Dashboard</h2>
                        <div class="flex space-x-2">
                            <select id="statusFilter" class="px-3 py-1 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
                                <option value="all">All Statuses</option>
                                <option value="vacant">Vacant</option>
                                <option value="occupied">Occupied</option>
                                <option value="reserved">Reserved</option>
                                <option value="dirty">Dirty</option>
                                <option value="ready">Ready</option>
                                <option value="out">Out of Service</option>
                            </select>
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <!-- Status Legend -->
                        <div class="flex flex-wrap gap-4 mb-6">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                                <span class="text-sm">Vacant</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                                <span class="text-sm">Occupied</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                                <span class="text-sm">Reserved</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></div>
                                <span class="text-sm">Dirty</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-green-300 mr-2"></div>
                                <span class="text-sm">Ready</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-gray-400 mr-2"></div>
                                <span class="text-sm">Out of Service</span>
                            </div>
                        </div>
                        
                        <!-- Room Cards Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" id="roomCardsContainer">
                            <!-- Room cards will be dynamically generated here -->
                        </div>
                    </div>
                </div>
                
                <!-- Room Details Table -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-eco-dark">All Rooms</h2>
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
                                    <th class="py-3 px-6 font-medium">Room Number</th>
                                    <th class="py-3 px-6 font-medium">Type</th>
                                    <th class="py-3 px-6 font-medium">Capacity</th>
                                    <th class="py-3 px-6 font-medium">Amenities</th>
                                    <th class="py-3 px-6 font-medium">Rate</th>
                                    <th class="py-3 px-6 font-medium">Status</th>
                                    <th class="py-3 px-6 font-medium">Last Updated</th>
                                    <th class="py-3 px-6 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200" id="roomsTableBody">
                                <!-- Room rows will be dynamically generated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Archived Rooms Tab Content -->
            <div id="archivedContent" class="tab-content hidden">
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-eco-dark">Archived Rooms</h2>
                        <p class="text-gray-500">Rooms that have been archived/inactivated</p>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 text-left text-gray-500">
                                    <th class="py-3 px-6 font-medium">Room Number</th>
                                    <th class="py-3 px-6 font-medium">Type</th>
                                    <th class="py-3 px-6 font-medium">Capacity</th>
                                    <th class="py-3 px-6 font-medium">Rate</th>
                                    <th class="py-3 px-6 font-medium">Archived Date</th>
                                    <th class="py-3 px-6 font-medium">Reason</th>
                                    <th class="py-3 px-6 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200" id="archivedRoomsTableBody">
                                <!-- Archived room rows will be dynamically generated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Reports Tab Content -->
            <div id="reportsContent" class="tab-content hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-bold text-eco-dark mb-4">Room Availability Breakdown</h3>
                        <div class="h-64">
                            <canvas id="availabilityChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-bold text-eco-dark mb-4">Room Utilization Rates</h3>
                        <div class="h-64">
                            <canvas id="utilizationChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-eco-dark">Out of Service Occurrences</h2>
                        <div class="flex space-x-2">
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors">
                                <i class="fas fa-download"></i> Export CSV
                            </button>
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 text-left text-gray-500">
                                    <th class="py-3 px-6 font-medium">Room Number</th>
                                    <th class="py-3 px-6 font-medium">Type</th>
                                    <th class="py-3 px-6 font-medium">Out of Service Date</th>
                                    <th class="py-3 px-6 font-medium">Return Date</th>
                                    <th class="py-3 px-6 font-medium">Reason</th>
                                    <th class="py-3 px-6 font-medium">Duration (Days)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr class="hover:bg-eco-cream transition-colors">
                                    <td class="py-4 px-6 font-medium">106</td>
                                    <td class="py-4 px-6">Deluxe</td>
                                    <td class="py-4 px-6">2023-05-10</td>
                                    <td class="py-4 px-6">2023-05-15</td>
                                    <td class="py-4 px-6">Plumbing repair</td>
                                    <td class="py-4 px-6">5</td>
                                </tr>
                                <tr class="hover:bg-eco-cream transition-colors">
                                    <td class="py-4 px-6 font-medium">205</td>
                                    <td class="py-4 px-6">Double</td>
                                    <td class="py-4 px-6">2023-05-12</td>
                                    <td class="py-4 px-6">2023-05-14</td>
                                    <td class="py-4 px-6">AC maintenance</td>
                                    <td class="py-4 px-6">2</td>
                                </tr>
                                <tr class="hover:bg-eco-cream transition-colors">
                                    <td class="py-4 px-6 font-medium">301</td>
                                    <td class="py-4 px-6">Suite</td>
                                    <td class="py-4 px-6">2023-05-08</td>
                                    <td class="py-4 px-6">2023-05-18</td>
                                    <td class="py-4 px-6">Renovation</td>
                                    <td class="py-4 px-6">10</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Audit Log Tab Content -->
            <div id="auditContent" class="tab-content hidden">
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-eco-dark">Audit Log</h2>
                        <div class="flex space-x-2">
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors">
                                <i class="fas fa-download"></i> Export
                            </button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 text-left text-gray-500">
                                    <th class="py-3 px-6 font-medium">Date & Time</th>
                                    <th class="py-3 px-6 font-medium">User</th>
                                    <th class="py-3 px-6 font-medium">Action</th>
                                    <th class="py-3 px-6 font-medium">Room</th>
                                    <th class="py-3 px-6 font-medium">Details</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr class="hover:bg-eco-cream transition-colors">
                                    <td class="py-4 px-6">2023-05-18 14:30</td>
                                    <td class="py-4 px-6">admin@brokenshire.com</td>
                                    <td class="py-4 px-6">Status Update</td>
                                    <td class="py-4 px-6">Room 104</td>
                                    <td class="py-4 px-6">Changed status from Occupied to Dirty</td>
                                </tr>
                                <tr class="hover:bg-eco-cream transition-colors">
                                    <td class="py-4 px-6">2023-05-18 11:15</td>
                                    <td class="py-4 px-6">staff@brokenshire.com</td>
                                    <td class="py-4 px-6">Room Added</td>
                                    <td class="py-4 px-6">Room 301</td>
                                    <td class="py-4 px-6">Added new Suite room with ocean view</td>
                                </tr>
                                <tr class="hover:bg-eco-cream transition-colors">
                                    <td class="py-4 px-6">2023-05-17 16:45</td>
                                    <td class="py-4 px-6">admin@brokenshire.com</td>
                                    <td class="py-4 px-6">Room Archived</td>
                                    <td class="py-4 px-6">Room 107</td>
                                    <td class="py-4 px-6">Archived room for long-term renovation</td>
                                </tr>
                                <tr class="hover:bg-eco-cream transition-colors">
                                    <td class="py-4 px-6">2023-05-17 09:20</td>
                                    <td class="py-4 px-6">staff@brokenshire.com</td>
                                    <td class="py-4 px-6">Status Update</td>
                                    <td class="py-4 px-6">Room 203</td>
                                    <td class="py-4 px-6">Changed status from Dirty to Ready</td>
                                </tr>
                                <tr class="hover:bg-eco-cream transition-colors">
                                    <td class="py-4 px-6">2023-05-16 13:10</td>
                                    <td class="py-4 px-6">admin@brokenshire.com</td>
                                    <td class="py-4 px-6">Room Updated</td>
                                    <td class="py-4 px-6">Room 105</td>
                                    <td class="py-4 px-6">Updated rate from $129.99 to $139.99</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Room Modal -->
    <div id="addRoomModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-eco-dark">Add New Room</h3>
                <button id="closeAddRoomModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="addRoomForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 mb-2">Room Number <span class="text-red-500">*</span></label>
                        <input type="text" id="roomNumber" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" placeholder="e.g., 101" required>
                        <p class="text-red-500 text-xs mt-1 hidden" id="roomNumberError">Room number must be unique</p>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Room Type <span class="text-red-500">*</span></label>
                        <select class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" required>
                            <option value="">Select Room Type</option>
                            <option value="single">Single</option>
                            <option value="double">Double</option>
                            <option value="suite">Suite</option>
                            <option value="deluxe">Deluxe</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Capacity <span class="text-red-500">*</span></label>
                        <select class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" required>
                            <option value="">Select Capacity</option>
                            <option value="1">1 Guest</option>
                            <option value="2">2 Guests</option>
                            <option value="3">3 Guests</option>
                            <option value="4">4 Guests</option>
                            <option value="5">5+ Guests</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Base Rate (per night) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-500">₱</span>
                            <input type="number" class="w-full pl-8 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" placeholder="0.00" min="0" step="0.01" required>
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 mb-2">Amenities</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-eco-accent focus:ring-eco-accent">
                                <span class="ml-2 text-gray-700">WiFi</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-eco-accent focus:ring-eco-accent">
                                <span class="ml-2 text-gray-700">TV</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-eco-accent focus:ring-eco-accent">
                                <span class="ml-2 text-gray-700">AC</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-eco-accent focus:ring-eco-accent">
                                <span class="ml-2 text-gray-700">Mini Bar</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-eco-accent focus:ring-eco-accent">
                                <span class="ml-2 text-gray-700">Balcony</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-eco-accent focus:ring-eco-accent">
                                <span class="ml-2 text-gray-700">Ocean View</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-eco-accent focus:ring-eco-accent">
                                <span class="ml-2 text-gray-700">Jacuzzi</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-eco-accent focus:ring-eco-accent">
                                <span class="ml-2 text-gray-700">Kitchenette</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 mb-2">Seasonal Rates (Optional)</label>
                        <textarea class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" rows="2" placeholder="Peak season: +20%, Holiday season: +30%"></textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 mb-2">Room Photo (Optional)</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                            <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                            <p class="text-gray-500">Drag & drop or <span class="text-eco-primary cursor-pointer">browse</span> to upload</p>
                            <p class="text-xs text-gray-400 mt-1">JPG, PNG up to 5MB</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Initial Status</label>
                        <select class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
                            <option value="vacant">Vacant</option>
                            <option value="occupied">Occupied</option>
                            <option value="reserved">Reserved</option>
                            <option value="dirty">Dirty</option>
                            <option value="ready">Ready</option>
                            <option value="out">Out of Service</option>
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 mb-2">Notes</label>
                        <textarea class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" rows="3" placeholder="Any additional notes about the room..."></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="cancelAddRoom" class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-eco-primary text-white rounded-lg hover:bg-eco-dark transition-colors">Add Room</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Room Modal -->
    <div id="editRoomModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-eco-dark">Edit Room Details</h3>
                <button id="closeEditRoomModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="editRoomForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 mb-2">Room Number</label>
                        <input type="text" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" value="101" readonly>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Room Type</label>
                        <select class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
                            <option value="single" selected>Single</option>
                            <option value="double">Double</option>
                            <option value="suite">Suite</option>
                            <option value="deluxe">Deluxe</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Capacity</label>
                        <select class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
                            <option value="1" selected>1 Guest</option>
                            <option value="2">2 Guests</option>
                            <option value="3">3 Guests</option>
                            <option value="4">4 Guests</option>
                            <option value="5">5+ Guests</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Base Rate (per night)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-500">₱</span>
                            <input type="number" class="w-full pl-8 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" value="89.99" min="0" step="0.01">
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 mb-2">Amenities</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-eco-accent focus:ring-eco-accent" checked>
                                <span class="ml-2 text-gray-700">WiFi</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-eco-accent focus:ring-eco-accent" checked>
                                <span class="ml-2 text-gray-700">TV</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-eco-accent focus:ring-eco-accent" checked>
                                <span class="ml-2 text-gray-700">AC</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-eco-accent focus:ring-eco-accent">
                                <span class="ml-2 text-gray-700">Mini Bar</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-eco-accent focus:ring-eco-accent">
                                <span class="ml-2 text-gray-700">Balcony</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-eco-accent focus:ring-eco-accent">
                                <span class="ml-2 text-gray-700">Ocean View</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-eco-accent focus:ring-eco-accent">
                                <span class="ml-2 text-gray-700">Jacuzzi</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded text-eco-accent focus:ring-eco-accent">
                                <span class="ml-2 text-gray-700">Kitchenette</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 mb-2">Seasonal Rates</label>
                        <textarea class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" rows="2">Peak season (Jun-Aug): +20%</textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <h4 class="text-lg font-medium text-eco-dark mb-3">Change History</h4>
                        <div class="border rounded-lg p-4 max-h-40 overflow-y-auto">
                            <div class="mb-2 pb-2 border-b">
                                <p class="font-medium">May 15, 2023 - Room Created</p>
                                <p class="text-sm text-gray-600">Room added to inventory by admin</p>
                            </div>
                            <div class="mb-2 pb-2 border-b">
                                <p class="font-medium">May 18, 2023 - Status Updated</p>
                                <p class="text-sm text-gray-600">Changed from Vacant to Occupied</p>
                            </div>
                            <div>
                                <p class="font-medium">May 20, 2023 - Rate Updated</p>
                                <p class="text-sm text-gray-600">Base rate increased from $79.99 to $89.99</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="cancelEditRoom" class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-eco-primary text-white rounded-lg hover:bg-eco-dark transition-colors">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div id="updateStatusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-eco-dark">Update Room Status</h3>
                <button id="closeUpdateStatusModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="mb-4">
                <p class="text-gray-700">Update status for <span class="font-bold" id="statusRoomNumber">Room 101</span></p>
                <p class="text-sm text-gray-500">Current status: <span id="currentRoomStatus" class="font-medium">Vacant</span></p>
            </div>
            
            <div class="space-y-3 mb-6">
                <button class="w-full text-left p-3 rounded-lg border border-gray-300 hover:bg-eco-cream transition-colors status-option" data-status="vacant">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-green-500 mr-3"></div>
                        <div>
                            <p class="font-medium">Vacant</p>
                            <p class="text-sm text-gray-500">Room is clean and available for booking</p>
                        </div>
                    </div>
                </button>
                
                <button class="w-full text-left p-3 rounded-lg border border-gray-300 hover:bg-eco-cream transition-colors status-option" data-status="occupied">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-red-500 mr-3"></div>
                        <div>
                            <p class="font-medium">Occupied</p>
                            <p class="text-sm text-gray-500">Guest is currently staying in the room</p>
                        </div>
                    </div>
                </button>
                
                <button class="w-full text-left p-3 rounded-lg border border-gray-300 hover:bg-eco-cream transition-colors status-option" data-status="reserved">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-blue-500 mr-3"></div>
                        <div>
                            <p class="font-medium">Reserved</p>
                            <p class="text-sm text-gray-500">Room is booked for future dates</p>
                        </div>
                    </div>
                </button>
                
                <button class="w-full text-left p-3 rounded-lg border border-gray-300 hover:bg-eco-cream transition-colors status-option" data-status="dirty">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-yellow-500 mr-3"></div>
                        <div>
                            <p class="font-medium">Dirty</p>
                            <p class="text-sm text-gray-500">Room needs cleaning after checkout</p>
                        </div>
                    </div>
                </button>
                
                <button class="w-full text-left p-3 rounded-lg border border-gray-300 hover:bg-eco-cream transition-colors status-option" data-status="ready">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-green-300 mr-3"></div>
                        <div>
                            <p class="font-medium">Ready</p>
                            <p class="text-sm text-gray-500">Room is cleaned and ready for next guest</p>
                        </div>
                    </div>
                </button>
                
                <button class="w-full text-left p-3 rounded-lg border border-gray-300 hover:bg-eco-cream transition-colors status-option" data-status="out">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-gray-400 mr-3"></div>
                        <div>
                            <p class="font-medium">Out of Service</p>
                            <p class="text-sm text-gray-500">Room is unavailable due to maintenance</p>
                        </div>
                    </div>
                </button>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" id="cancelUpdateStatus" class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="button" id="confirmUpdateStatus" class="px-4 py-2 bg-eco-primary text-white rounded-lg hover:bg-eco-dark transition-colors">Update Status</button>
            </div>
        </div>
    </div>

    <!-- Sync Reservations Modal -->
    <div id="syncModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-full bg-eco-light flex items-center justify-center mr-4">
                    <i class="fas fa-sync-alt text-eco-accent text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Sync Reservations</h3>
                    <p class="text-gray-600">Connecting to reservation system...</p>
                </div>
            </div>
            
            <div class="mb-4">
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-eco-primary h-2.5 rounded-full" style="width: 45%"></div>
                </div>
                <p class="text-right text-sm text-gray-500 mt-1">45% complete</p>
            </div>
            
            <div class="text-sm text-gray-600 mb-4">
                <p class="mb-1"><i class="fas fa-check text-green-500 mr-2"></i> Connected to RSV system</p>
                <p class="mb-1"><i class="fas fa-sync text-blue-500 mr-2"></i> Syncing reservation data</p>
                <p><i class="fas fa-clock text-gray-400 mr-2"></i> Updating room statuses</p>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button id="cancelSync" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                <button id="confirmSync" class="px-4 py-2 bg-eco-primary text-white rounded-lg hover:bg-eco-dark transition-colors">Continue</button>
            </div>
        </div>
    </div>

    <!-- Reservation Simulation Modal -->
    <div id="reservationSimulationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-eco-dark">Reservation Simulation</h3>
                <button id="closeReservationSimulationModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="mb-6">
                <p class="text-gray-700 mb-4">Simulate reservation events to see how they affect room status:</p>
                
                <div class="space-y-3">
                    <button class="w-full text-left p-3 rounded-lg border border-gray-300 hover:bg-eco-cream transition-colors reservation-event" data-event="booking">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-calendar-check text-blue-500"></i>
                            </div>
                            <div>
                                <p class="font-medium">Booking Confirmed</p>
                                <p class="text-sm text-gray-500">Room status: Reserved</p>
                            </div>
                        </div>
                    </button>
                    
                    <button class="w-full text-left p-3 rounded-lg border border-gray-300 hover:bg-eco-cream transition-colors reservation-event" data-event="checkin">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                <i class="fas fa-key text-green-500"></i>
                            </div>
                            <div>
                                <p class="font-medium">Check-in</p>
                                <p class="text-sm text-gray-500">Room status: Occupied</p>
                            </div>
                        </div>
                    </button>
                    
                    <button class="w-full text-left p-3 rounded-lg border border-gray-300 hover:bg-eco-cream transition-colors reservation-event" data-event="checkout">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center mr-3">
                                <i class="fas fa-door-open text-yellow-500"></i>
                            </div>
                            <div>
                                <p class="font-medium">Check-out</p>
                                <p class="text-sm text-gray-500">Room status: Dirty</p>
                            </div>
                        </div>
                    </button>
                    
                    <button class="w-full text-left p-3 rounded-lg border border-gray-300 hover:bg-eco-cream transition-colors reservation-event" data-event="cancellation">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                <i class="fas fa-times-circle text-red-500"></i>
                            </div>
                            <div>
                                <p class="font-medium">Cancellation</p>
                                <p class="text-sm text-gray-500">Room status: Vacant</p>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" id="cancelReservationSimulation" class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Close</button>
            </div>
        </div>
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
                <button id="confirmLogout" class="px-4 py-2 bg-eco-primary text-white rounded-lg hover:bg-eco-dark transition-colors">
                    Logout
                </button>
            </div>
        </div>
    </div>

    <script>
        // Sample room data
        const sampleRooms = [
            { id: 1, number: "101", type: "Single", capacity: "1", amenities: ["WiFi", "TV", "AC"], rate: 89.99, status: "vacant", lastUpdated: "2023-05-15" },
            { id: 2, number: "102", type: "Double", capacity: "2", amenities: ["WiFi", "TV", "AC", "Balcony"], rate: 119.99, status: "occupied", lastUpdated: "2023-05-18" },
            { id: 3, number: "103", type: "Suite", capacity: "3", amenities: ["WiFi", "TV", "AC", "Mini Bar", "Jacuzzi"], rate: 199.99, status: "reserved", lastUpdated: "2023-05-17" },
            { id: 4, number: "104", type: "Single", capacity: "1", amenities: ["WiFi", "TV", "AC"], rate: 89.99, status: "dirty", lastUpdated: "2023-05-18" },
            { id: 5, number: "105", type: "Double", capacity: "2", amenities: ["WiFi", "TV", "AC", "Ocean View"], rate: 129.99, status: "ready", lastUpdated: "2023-05-16" },
            { id: 6, number: "106", type: "Deluxe", capacity: "4", amenities: ["WiFi", "TV", "AC", "Mini Bar", "Balcony", "Ocean View"], rate: 159.99, status: "out", lastUpdated: "2023-05-10" },
            { id: 7, number: "201", type: "Single", capacity: "1", amenities: ["WiFi", "TV", "AC"], rate: 89.99, status: "vacant", lastUpdated: "2023-05-15" },
            { id: 8, number: "202", type: "Double", capacity: "2", amenities: ["WiFi", "TV", "AC", "Kitchenette"], rate: 139.99, status: "occupied", lastUpdated: "2023-05-18" },
            { id: 9, number: "203", type: "Suite", capacity: "3", amenities: ["WiFi", "TV", "AC", "Mini Bar", "Jacuzzi", "Ocean View"], rate: 229.99, status: "vacant", lastUpdated: "2023-05-14" },
            { id: 10, number: "204", type: "Single", capacity: "1", amenities: ["WiFi", "TV", "AC"], rate: 89.99, status: "dirty", lastUpdated: "2023-05-18" }
        ];

        // Archived rooms data
        const archivedRooms = [
            { id: 11, number: "107", type: "Double", capacity: "2", rate: 119.99, archivedDate: "2023-04-15", reason: "Long-term renovation" },
            { id: 12, number: "205", type: "Single", capacity: "1", rate: 79.99, archivedDate: "2023-03-22", reason: "Structural issues" },
            { id: 13, number: "301", type: "Suite", capacity: "3", rate: 199.99, archivedDate: "2023-05-01", reason: "Redesign in progress" }
        ];

        // Initialize the room data
        let rooms = [...sampleRooms];
        let currentRoomForStatusUpdate = null;
        let selectedStatus = null;

        // DOM elements
        const roomCardsContainer = document.getElementById('roomCardsContainer');
        const roomsTableBody = document.getElementById('roomsTableBody');
        const archivedRoomsTableBody = document.getElementById('archivedRoomsTableBody');
        const addRoomModal = document.getElementById('addRoomModal');
        const editRoomModal = document.getElementById('editRoomModal');
        const updateStatusModal = document.getElementById('updateStatusModal');
        const reservationSimulationModal = document.getElementById('reservationSimulationModal');
        const syncModal = document.getElementById('syncModal');
        const logoutModal = document.getElementById('logoutModal');
        
        // Tab elements
        const dashboardTab = document.getElementById('dashboardTab');
        const archivedTab = document.getElementById('archivedTab');
        const reportsTab = document.getElementById('reportsTab');
        const auditTab = document.getElementById('auditTab');
        const dashboardContent = document.getElementById('dashboardContent');
        const archivedContent = document.getElementById('archivedContent');
        const reportsContent = document.getElementById('reportsContent');
        const auditContent = document.getElementById('auditContent');
        
        // Button elements
        const addRoomBtn = document.getElementById('addRoomBtn');
        const syncReservationsBtn = document.getElementById('syncReservationsBtn');
        const generateReportBtn = document.getElementById('generateReportBtn');
        const viewAuditBtn = document.getElementById('viewAuditBtn');
        
        // Modal close buttons
        const closeAddRoomModal = document.getElementById('closeAddRoomModal');
        const cancelAddRoom = document.getElementById('cancelAddRoom');
        const closeEditRoomModal = document.getElementById('closeEditRoomModal');
        const cancelEditRoom = document.getElementById('cancelEditRoom');
        const closeUpdateStatusModal = document.getElementById('closeUpdateStatusModal');
        const cancelUpdateStatus = document.getElementById('cancelUpdateStatus');
        const closeReservationSimulationModal = document.getElementById('closeReservationSimulationModal');
        const cancelReservationSimulation = document.getElementById('cancelReservationSimulation');
        const cancelSync = document.getElementById('cancelSync');
        const confirmSync = document.getElementById('confirmSync');
        const cancelLogout = document.getElementById('cancelLogout');
        const confirmLogout = document.getElementById('confirmLogout');
        const logoutBtn = document.getElementById('logoutBtn');
        
        // Form elements
        const addRoomForm = document.getElementById('addRoomForm');
        const editRoomForm = document.getElementById('editRoomForm');
        const roomNumberInput = document.getElementById('roomNumber');
        const roomNumberError = document.getElementById('roomNumberError');
        const statusFilter = document.getElementById('statusFilter');
        const confirmUpdateStatus = document.getElementById('confirmUpdateStatus');
        const statusRoomNumber = document.getElementById('statusRoomNumber');
        const currentRoomStatus = document.getElementById('currentRoomStatus');

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
        
        // Tab navigation
        dashboardTab.addEventListener('click', function() {
            switchTab('dashboard');
        });
        
        archivedTab.addEventListener('click', function() {
            switchTab('archived');
        });
        
        reportsTab.addEventListener('click', function() {
            switchTab('reports');
        });
        
        auditTab.addEventListener('click', function() {
            switchTab('audit');
        });
        
        function switchTab(tabName) {
            // Update tab buttons
            document.querySelectorAll('.tab-button').forEach(tab => {
                tab.classList.remove('text-eco-primary', 'border-eco-primary');
                tab.classList.add('text-gray-500', 'border-transparent');
            });
            
            // Show selected tab content, hide others
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            if (tabName === 'dashboard') {
                dashboardTab.classList.remove('text-gray-500', 'border-transparent');
                dashboardTab.classList.add('text-eco-primary', 'border-eco-primary');
                dashboardContent.classList.remove('hidden');
            } else if (tabName === 'archived') {
                archivedTab.classList.remove('text-gray-500', 'border-transparent');
                archivedTab.classList.add('text-eco-primary', 'border-eco-primary');
                archivedContent.classList.remove('hidden');
                renderArchivedRoomsTable();
            } else if (tabName === 'reports') {
                reportsTab.classList.remove('text-gray-500', 'border-transparent');
                reportsTab.classList.add('text-eco-primary', 'border-eco-primary');
                reportsContent.classList.remove('hidden');
                initializeCharts();
            } else if (tabName === 'audit') {
                auditTab.classList.remove('text-gray-500', 'border-transparent');
                auditTab.classList.add('text-eco-primary', 'border-eco-primary');
                auditContent.classList.remove('hidden');
            }
        }
        
        // Modal controls
        addRoomBtn.addEventListener('click', function() {
            addRoomModal.classList.remove('hidden');
        });
        
        syncReservationsBtn.addEventListener('click', function() {
            reservationSimulationModal.classList.remove('hidden');
        });
        
        generateReportBtn.addEventListener('click', function() {
            switchTab('reports');
        });
        
        viewAuditBtn.addEventListener('click', function() {
            switchTab('audit');
        });
        
        logoutBtn.addEventListener('click', function() {
            logoutModal.classList.remove('hidden');
        });
        
        closeAddRoomModal.addEventListener('click', function() {
            addRoomModal.classList.add('hidden');
        });
        
        cancelAddRoom.addEventListener('click', function() {
            addRoomModal.classList.add('hidden');
        });
        
        closeEditRoomModal.addEventListener('click', function() {
            editRoomModal.classList.add('hidden');
        });
        
        cancelEditRoom.addEventListener('click', function() {
            editRoomModal.classList.add('hidden');
        });
        
        closeUpdateStatusModal.addEventListener('click', function() {
            updateStatusModal.classList.add('hidden');
        });
        
        cancelUpdateStatus.addEventListener('click', function() {
            updateStatusModal.classList.add('hidden');
        });
        
        closeReservationSimulationModal.addEventListener('click', function() {
            reservationSimulationModal.classList.add('hidden');
        });
        
        cancelReservationSimulation.addEventListener('click', function() {
            reservationSimulationModal.classList.add('hidden');
        });
        
        cancelSync.addEventListener('click', function() {
            syncModal.classList.add('hidden');
        });
        
        confirmSync.addEventListener('click', function() {
            // Simulate sync completion
            setTimeout(function() {
                syncModal.classList.add('hidden');
                alert('Reservations synced successfully! Room statuses updated.');
            }, 1500);
        });
        
        cancelLogout.addEventListener('click', function() {
            logoutModal.classList.add('hidden');
        });
        
        confirmLogout.addEventListener('click', function() {
            // In a real app, this would redirect to logout
            alert('Logout functionality would be implemented here');
            logoutModal.classList.add('hidden');
        });
        
        // Close modals when clicking outside
        [addRoomModal, editRoomModal, updateStatusModal, reservationSimulationModal, syncModal, logoutModal].forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });

        // Room number validation
        roomNumberInput.addEventListener('blur', function() {
            const roomNumber = this.value;
            const isDuplicate = rooms.some(room => room.number === roomNumber);
            
            if (isDuplicate) {
                roomNumberError.classList.remove('hidden');
                this.classList.add('border-red-500');
            } else {
                roomNumberError.classList.add('hidden');
                this.classList.remove('border-red-500');
            }
        });

        // Status filter
        statusFilter.addEventListener('change', function() {
            const status = this.value;
            renderRoomCards(status);
        });

        // Status option selection
        document.querySelectorAll('.status-option').forEach(option => {
            option.addEventListener('click', function() {
                // Remove selected class from all options
                document.querySelectorAll('.status-option').forEach(opt => {
                    opt.classList.remove('border-eco-primary', 'bg-eco-cream');
                });
                
                // Add selected class to clicked option
                this.classList.add('border-eco-primary', 'bg-eco-cream');
                selectedStatus = this.getAttribute('data-status');
            });
        });

        // Confirm status update
        confirmUpdateStatus.addEventListener('click', function() {
            if (currentRoomForStatusUpdate && selectedStatus) {
                updateRoomStatus(currentRoomForStatusUpdate.id, selectedStatus);
                updateStatusModal.classList.add('hidden');
                selectedStatus = null;
            }
        });

        // Reservation simulation events
        document.querySelectorAll('.reservation-event').forEach(event => {
            event.addEventListener('click', function() {
                const eventType = this.getAttribute('data-event');
                simulateReservationEvent(eventType);
            });
        });

        // Form submissions
        addRoomForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Check for duplicate room number
            const roomNumber = document.getElementById('roomNumber').value;
            const isDuplicate = rooms.some(room => room.number === roomNumber);
            
            if (isDuplicate) {
                roomNumberError.classList.remove('hidden');
                roomNumberInput.classList.add('border-red-500');
                return;
            }
            
            // In a real app, this would send data to backend
            // For now, just show success message and close modal
            alert('Room added successfully! (This is a frontend demo - no actual data saved)');
            addRoomModal.classList.add('hidden');
            addRoomForm.reset();
        });

        editRoomForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // In a real app, this would send data to backend
            alert('Room details updated successfully! (This is a frontend demo - no actual data saved)');
            editRoomModal.classList.add('hidden');
        });

        // Function to render room cards with optional status filter
        function renderRoomCards(statusFilter = 'all') {
            roomCardsContainer.innerHTML = '';
            
            const filteredRooms = statusFilter === 'all' 
                ? rooms 
                : rooms.filter(room => room.status === statusFilter);
            
            filteredRooms.forEach(room => {
                const statusClass = `status-${room.status}`;
                const statusText = room.status.charAt(0).toUpperCase() + room.status.slice(1);
                
                const card = document.createElement('div');
                card.className = 'bg-white rounded-lg shadow-md p-4 border-l-4';
                
                // Set border color based on status
                if (room.status === 'vacant') card.classList.add('border-green-500');
                else if (room.status === 'occupied') card.classList.add('border-red-500');
                else if (room.status === 'reserved') card.classList.add('border-blue-500');
                else if (room.status === 'dirty') card.classList.add('border-yellow-500');
                else if (room.status === 'ready') card.classList.add('border-green-300');
                else card.classList.add('border-gray-400');
                
                card.innerHTML = `
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg">Room ${room.number}</h3>
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${statusClass}">${statusText}</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-2">${room.type} • Capacity: ${room.capacity}</p>
                    <p class="text-eco-dark font-bold mb-3">₱${room.rate}/night</p>
                    <div class="flex flex-wrap gap-1 mb-4">
                        ${room.amenities.map(amenity => 
                            `<span class="bg-eco-cream text-eco-dark text-xs px-2 py-1 rounded">${amenity}</span>`
                        ).join('')}
                    </div>
                    <div class="flex justify-between">
                        <button class="text-eco-accent hover:text-eco-dark text-sm flex items-center update-status-btn" data-room-id="${room.id}">
                            <i class="fas fa-edit mr-1"></i> Update Status
                        </button>
                        <button class="text-red-500 hover:text-red-700 text-sm flex items-center archive-room-btn" data-room-id="${room.id}">
                            <i class="fas fa-archive mr-1"></i> Archive
                        </button>
                    </div>
                `;
                
                roomCardsContainer.appendChild(card);
            });
            
            // Add event listeners to update status buttons
            document.querySelectorAll('.update-status-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const roomId = parseInt(this.getAttribute('data-room-id'));
                    openUpdateStatusModal(roomId);
                });
            });
            
            // Add event listeners to archive room buttons
            document.querySelectorAll('.archive-room-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const roomId = parseInt(this.getAttribute('data-room-id'));
                    archiveRoom(roomId);
                });
            });
        }

        // Function to render room table
        function renderRoomTable() {
            roomsTableBody.innerHTML = '';
            
            rooms.forEach(room => {
                const statusClass = `status-${room.status}`;
                const statusText = room.status.charAt(0).toUpperCase() + room.status.slice(1);
                
                const row = document.createElement('tr');
                row.className = 'hover:bg-eco-cream transition-colors';
                row.innerHTML = `
                    <td class="py-4 px-6 font-medium">${room.number}</td>
                    <td class="py-4 px-6">${room.type}</td>
                    <td class="py-4 px-6">${room.capacity}</td>
                    <td class="py-4 px-6">
                        <div class="flex flex-wrap gap-1">
                            ${room.amenities.map(amenity => 
                                `<span class="bg-eco-cream text-eco-dark text-xs px-2 py-1 rounded">${amenity}</span>`
                            ).join('')}
                        </div>
                    </td>
                    <td class="py-4 px-6 font-bold">₱${room.rate}</td>
                    <td class="py-4 px-6">
                        <span class="px-3 py-1 rounded-full text-xs font-medium ${statusClass}">${statusText}</span>
                    </td>
                    <td class="py-4 px-6">${room.lastUpdated}</td>
                    <td class="py-4 px-6">
                        <div class="flex space-x-2">
                            <button class="text-eco-accent hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors edit-room-btn" data-room-id="${room.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-blue-500 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition-colors update-status-btn" data-room-id="${room.id}">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <button class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors archive-room-btn" data-room-id="${room.id}">
                                <i class="fas fa-archive"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                roomsTableBody.appendChild(row);
            });
            
            // Add event listeners to edit room buttons
            document.querySelectorAll('.edit-room-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const roomId = parseInt(this.getAttribute('data-room-id'));
                    openEditRoomModal(roomId);
                });
            });
            
            // Add event listeners to update status buttons
            document.querySelectorAll('.update-status-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const roomId = parseInt(this.getAttribute('data-room-id'));
                    openUpdateStatusModal(roomId);
                });
            });
            
            // Add event listeners to archive room buttons
            document.querySelectorAll('.archive-room-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const roomId = parseInt(this.getAttribute('data-room-id'));
                    archiveRoom(roomId);
                });
            });
        }

        // Function to render archived rooms table
        function renderArchivedRoomsTable() {
            archivedRoomsTableBody.innerHTML = '';
            
            archivedRooms.forEach(room => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-eco-cream transition-colors';
                row.innerHTML = `
                    <td class="py-4 px-6 font-medium">${room.number}</td>
                    <td class="py-4 px-6">${room.type}</td>
                    <td class="py-4 px-6">${room.capacity}</td>
                    <td class="py-4 px-6 font-bold">₱${room.rate}</td>
                    <td class="py-4 px-6">${room.archivedDate}</td>
                    <td class="py-4 px-6">${room.reason}</td>
                    <td class="py-4 px-6">
                        <div class="flex space-x-2">
                            <button class="text-green-500 hover:text-green-700 p-2 rounded-lg hover:bg-green-50 transition-colors unarchive-room-btn" data-room-id="${room.id}">
                                <i class="fas fa-undo-alt"></i> Unarchive
                            </button>
                        </div>
                    </td>
                `;
                
                archivedRoomsTableBody.appendChild(row);
            });
            
            // Add event listeners to unarchive room buttons
            document.querySelectorAll('.unarchive-room-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const roomId = parseInt(this.getAttribute('data-room-id'));
                    unarchiveRoom(roomId);
                });
            });
        }

        // Function to open update status modal
        function openUpdateStatusModal(roomId) {
            const room = rooms.find(r => r.id === roomId);
            if (!room) return;
            
            currentRoomForStatusUpdate = room;
            statusRoomNumber.textContent = `Room ${room.number}`;
            currentRoomStatus.textContent = room.status.charAt(0).toUpperCase() + room.status.slice(1);
            
            // Reset selected status
            selectedStatus = null;
            document.querySelectorAll('.status-option').forEach(opt => {
                opt.classList.remove('border-eco-primary', 'bg-eco-cream');
            });
            
            updateStatusModal.classList.remove('hidden');
        }

        // Function to open edit room modal
        function openEditRoomModal(roomId) {
            const room = rooms.find(r => r.id === roomId);
            if (!room) return;
            
            // In a real app, this would populate the form with room data
            editRoomModal.classList.remove('hidden');
        }

        // Function to update room status
        function updateRoomStatus(roomId, newStatus) {
            const room = rooms.find(r => r.id === roomId);
            if (!room) return;
            
            room.status = newStatus;
            room.lastUpdated = new Date().toISOString().split('T')[0];
            
            // Re-render the UI
            renderRoomCards(statusFilter.value);
            renderRoomTable();
            
            alert(`Room ${room.number} status updated to ${newStatus}`);
        }

        // Function to archive a room
        function archiveRoom(roomId) {
            const room = rooms.find(r => r.id === roomId);
            if (!room) return;
            
            if (confirm(`Are you sure you want to archive Room ${room.number}? This will remove it from active inventory.`)) {
                // In a real app, this would call an API to archive the room
                // For demo, we'll just show a message
                alert(`Room ${room.number} has been archived.`);
            }
        }

        // Function to unarchive a room
        function unarchiveRoom(roomId) {
            const room = archivedRooms.find(r => r.id === roomId);
            if (!room) return;
            
            if (confirm(`Are you sure you want to unarchive Room ${room.number}?`)) {
                // In a real app, this would call an API to unarchive the room
                alert(`Room ${room.number} has been unarchived and is now active.`);
            }
        }

        // Function to simulate reservation events
        function simulateReservationEvent(eventType) {
            let message = '';
            let statusChange = '';
            
            switch(eventType) {
                case 'booking':
                    message = 'Booking confirmed! Room status changed to Reserved.';
                    statusChange = 'reserved';
                    break;
                case 'checkin':
                    message = 'Check-in completed! Room status changed to Occupied.';
                    statusChange = 'occupied';
                    break;
                case 'checkout':
                    message = 'Check-out completed! Room status changed to Dirty.';
                    statusChange = 'dirty';
                    break;
                case 'cancellation':
                    message = 'Reservation cancelled! Room status changed to Vacant.';
                    statusChange = 'vacant';
                    break;
            }
            
            alert(`Reservation Event: ${message}\n\n(This is a simulation - no actual room statuses were changed)`);
        }

        // Search functionality
        const searchInput = document.querySelector('input[type="text"]');
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const filteredRooms = sampleRooms.filter(room => 
                room.number.toLowerCase().includes(searchTerm) || 
                room.type.toLowerCase().includes(searchTerm) ||
                room.amenities.some(amenity => amenity.toLowerCase().includes(searchTerm))
            );
            
            rooms = filteredRooms;
            renderRoomCards(statusFilter.value);
            renderRoomTable();
        });

        // Initialize charts
        function initializeCharts() {
            // Availability Chart
            const availabilityCtx = document.getElementById('availabilityChart').getContext('2d');
            new Chart(availabilityCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Vacant', 'Occupied', 'Reserved', 'Dirty', 'Ready', 'Out of Service'],
                    datasets: [{
                        data: [18, 16, 5, 4, 2, 8],
                        backgroundColor: [
                            '#10B981',
                            '#EF4444',
                            '#3B82F6',
                            '#F59E0B',
                            '#86EFAC',
                            '#9CA3AF'
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

            // Utilization Chart
            const utilizationCtx = document.getElementById('utilizationChart').getContext('2d');
            new Chart(utilizationCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Utilization Rate (%)',
                        data: [65, 72, 68, 75, 82, 78],
                        backgroundColor: '#4CAF50',
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
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
        }

        renderRoomCards();
        renderRoomTable();
    </script>
</body>
</html>