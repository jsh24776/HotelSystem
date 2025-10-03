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
                <button class="bg-white text-eco-primary border border-eco-primary px-4 py-2 rounded-lg hover:bg-eco-cream transition-colors flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i> Generate Report
                </button>
                <button class="bg-white text-eco-primary border border-eco-primary px-4 py-2 rounded-lg hover:bg-eco-cream transition-colors flex items-center">
                    <i class="fas fa-clipboard-list mr-2"></i> View Audit Log
                </button>
            </div>
            
            <!-- Room Status Dashboard -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-eco-dark">Room Status Dashboard</h2>
                    <div class="flex space-x-2">
                        <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors">
                            <i class="fas fa-filter"></i>
                        </button>
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
                        <label class="block text-gray-700 mb-2">Room Number</label>
                        <input type="text" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" placeholder="e.g., 101">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Room Type</label>
                        <select class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
                            <option value="">Select Room Type</option>
                            <option value="single">Single</option>
                            <option value="double">Double</option>
                            <option value="suite">Suite</option>
                            <option value="deluxe">Deluxe</option>
                        </select>
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
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Rate (per night)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-500">$</span>
                            <input type="number" class="w-full pl-8 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" placeholder="0.00" min="0" step="0.01">
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
            { id: 1, number: "101", type: "Single", amenities: ["WiFi", "TV", "AC"], rate: 89.99, status: "vacant", lastUpdated: "2023-05-15" },
            { id: 2, number: "102", type: "Double", amenities: ["WiFi", "TV", "AC", "Balcony"], rate: 119.99, status: "occupied", lastUpdated: "2023-05-18" },
            { id: 3, number: "103", type: "Suite", amenities: ["WiFi", "TV", "AC", "Mini Bar", "Jacuzzi"], rate: 199.99, status: "reserved", lastUpdated: "2023-05-17" },
            { id: 4, number: "104", type: "Single", amenities: ["WiFi", "TV", "AC"], rate: 89.99, status: "dirty", lastUpdated: "2023-05-18" },
            { id: 5, number: "105", type: "Double", amenities: ["WiFi", "TV", "AC", "Ocean View"], rate: 129.99, status: "ready", lastUpdated: "2023-05-16" },
            { id: 6, number: "106", type: "Deluxe", amenities: ["WiFi", "TV", "AC", "Mini Bar", "Balcony", "Ocean View"], rate: 159.99, status: "out", lastUpdated: "2023-05-10" },
            { id: 7, number: "201", type: "Single", amenities: ["WiFi", "TV", "AC"], rate: 89.99, status: "vacant", lastUpdated: "2023-05-15" },
            { id: 8, number: "202", type: "Double", amenities: ["WiFi", "TV", "AC", "Kitchenette"], rate: 139.99, status: "occupied", lastUpdated: "2023-05-18" },
            { id: 9, number: "203", type: "Suite", amenities: ["WiFi", "TV", "AC", "Mini Bar", "Jacuzzi", "Ocean View"], rate: 229.99, status: "vacant", lastUpdated: "2023-05-14" },
            { id: 10, number: "204", type: "Single", amenities: ["WiFi", "TV", "AC"], rate: 89.99, status: "dirty", lastUpdated: "2023-05-18" }
        ];

        // Initialize the room data
        let rooms = [...sampleRooms];

        // DOM elements
        const roomCardsContainer = document.getElementById('roomCardsContainer');
        const roomsTableBody = document.getElementById('roomsTableBody');
        const addRoomModal = document.getElementById('addRoomModal');
        const syncModal = document.getElementById('syncModal');
        const logoutModal = document.getElementById('logoutModal');
        const addRoomBtn = document.getElementById('addRoomBtn');
        const syncReservationsBtn = document.getElementById('syncReservationsBtn');
        const closeAddRoomModal = document.getElementById('closeAddRoomModal');
        const cancelAddRoom = document.getElementById('cancelAddRoom');
        const cancelSync = document.getElementById('cancelSync');
        const confirmSync = document.getElementById('confirmSync');
        const cancelLogout = document.getElementById('cancelLogout');
        const confirmLogout = document.getElementById('confirmLogout');
        const logoutBtn = document.getElementById('logoutBtn');
        const addRoomForm = document.getElementById('addRoomForm');

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
        
        // Modal controls
        addRoomBtn.addEventListener('click', function() {
            addRoomModal.classList.remove('hidden');
        });
        
        syncReservationsBtn.addEventListener('click', function() {
            syncModal.classList.remove('hidden');
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
        [addRoomModal, syncModal, logoutModal].forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });

        // Form submission for adding room
        addRoomForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // In a real app, this would send data to backend
            // For now, just show success message and close modal
            alert('Room added successfully! (This is a frontend demo - no actual data saved)');
            addRoomModal.classList.add('hidden');
            addRoomForm.reset();
        });

        // Function to render room cards
        function renderRoomCards() {
            roomCardsContainer.innerHTML = '';
            
            rooms.forEach(room => {
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
                    <p class="text-gray-600 text-sm mb-2">${room.type}</p>
                    <p class="text-eco-dark font-bold mb-3">₱
${room.rate}/night</p>
                    <div class="flex flex-wrap gap-1 mb-4">
                        ${room.amenities.map(amenity => 
                            `<span class="bg-eco-cream text-eco-dark text-xs px-2 py-1 rounded">${amenity}</span>`
                        ).join('')}
                    </div>
                    <div class="flex justify-between">
                        <button class="text-eco-accent hover:text-eco-dark text-sm flex items-center update-status-btn" data-room-id="${room.id}">
                            <i class="fas fa-edit mr-1"></i> Update
                        </button>
                        <button class="text-red-500 hover:text-red-700 text-sm flex items-center remove-room-btn" data-room-id="${room.id}">
                            <i class="fas fa-trash mr-1"></i> Remove
                        </button>
                    </div>
                `;
                
                roomCardsContainer.appendChild(card);
            });
            
            // Add event listeners to update status buttons
            document.querySelectorAll('.update-status-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const roomId = parseInt(this.getAttribute('data-room-id'));
                    updateRoomStatus(roomId);
                });
            });
            
            // Add event listeners to remove room buttons
            document.querySelectorAll('.remove-room-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const roomId = parseInt(this.getAttribute('data-room-id'));
                    removeRoom(roomId);
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
                    <td class="py-4 px-6">
                        <div class="flex flex-wrap gap-1">
                            ${room.amenities.map(amenity => 
                                `<span class="bg-eco-cream text-eco-dark text-xs px-2 py-1 rounded">${amenity}</span>`
                            ).join('')}
                        </div>
                    </td>
                    <td class="py-4 px-6 font-bold">₱
${room.rate}</td>
                    <td class="py-4 px-6">
                        <span class="px-3 py-1 rounded-full text-xs font-medium ${statusClass}">${statusText}</span>
                    </td>
                    <td class="py-4 px-6">${room.lastUpdated}</td>
                    <td class="py-4 px-6">
                        <div class="flex space-x-2">
                            <button class="text-eco-accent hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors update-status-btn" data-room-id="${room.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors remove-room-btn" data-room-id="${room.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                roomsTableBody.appendChild(row);
            });
            
            // Add event listeners to update status buttons
            document.querySelectorAll('.update-status-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const roomId = parseInt(this.getAttribute('data-room-id'));
                    updateRoomStatus(roomId);
                });
            });
            
            // Add event listeners to remove room buttons
            document.querySelectorAll('.remove-room-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const roomId = parseInt(this.getAttribute('data-room-id'));
                    removeRoom(roomId);
                });
            });
        }

        // Function to update room status
        function updateRoomStatus(roomId) {
            const room = rooms.find(r => r.id === roomId);
            if (!room) return;
            
            const newStatus = prompt(`Update status for Room ${room.number}:\n\nCurrent: ${room.status}\n\nEnter new status (vacant, occupied, reserved, dirty, ready, out):`, room.status);
            
            if (newStatus && ['vacant', 'occupied', 'reserved', 'dirty', 'ready', 'out'].includes(newStatus.toLowerCase())) {
                room.status = newStatus.toLowerCase();
                room.lastUpdated = new Date().toISOString().split('T')[0];
                
                // Re-render the UI
                renderRoomCards();
                renderRoomTable();
                
                alert(`Room ${room.number} status updated to ${newStatus}`);
            } else if (newStatus) {
                alert('Invalid status. Please use: vacant, occupied, reserved, dirty, ready, or out');
            }
        }

        // Function to remove a room
        function removeRoom(roomId) {
            const room = rooms.find(r => r.id === roomId);
            if (!room) return;
            
            if (confirm(`Are you sure you want to remove Room ${room.number}? This action cannot be undone.`)) {
                
                rooms = rooms.filter(r => r.id !== roomId);
                
                // Re-render the UI
                renderRoomCards();
                renderRoomTable();
                
                alert(`Room ${room.number} has been removed.`);
            }
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
            renderRoomCards();
            renderRoomTable();
        });

        // Initialize the UI
        renderRoomCards();
        renderRoomTable();
    </script>
</body>
</html>