<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrokenShire Admin - Reservation & Booking Management</title>
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
        
        .status-available {
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
        
        .status-out {
            background-color: #e5e7eb;
            color: #374151;
        }
        
        .reservation-confirmed {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .reservation-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .reservation-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .reservation-checked-in {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .reservation-checked-out {
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

    <div class="md:hidden fixed top-4 left-4 z-20">
        <button id="menuToggle" class="bg-eco-primary text-white p-2 rounded-lg">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <div id="overlay" class="overlay"></div>
    
    <div class="flex min-h-screen">
   
        <aside id="sidebar" class="sidebar bg-white w-64 fixed h-full shadow-lg z-10">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-eco-dark flex items-center space-x-2">
                    <i class="fas fa-leaf text-eco-primary"></i>
                    <span>BrokenShire</span>
                </h1>
                <p class="text-sm text-gray-500 mt-1">Reservation & Booking</p>
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
                            FD
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold">Front Desk</p>
                            <p class="text-sm text-gray-$500">FD Role</p>
                        </div>
                    </div>
                ->
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
                        <h1 class="text-3xl font-bold text-eco-dark">Reservation & Booking Management</h1>
                        <p class="text-gray-600">Manage customer reservations, room bookings, and payments</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Search reservations..." class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
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
                                    FD
                                </div>
                                <span class="hidden md:block text-gray-700">Front Desk</span>
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
            
        
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500">Active Reservations</p>
                            <h3 class="text-2xl font-bold text-eco-dark">24</h3>
                            <p class="text-green-600 text-sm mt-1"><i class="fas fa-arrow-up mr-1"></i> 5 from yesterday</p>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-calendar-check text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500">Available Rooms</p>
                            <h3 class="text-2xl font-bold text-eco-dark">18</h3>
                            <p class="text-green-600 text-sm mt-1"><i class="fas fa-bed mr-1"></i> 43% availability</p>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-door-open text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500">Today's Check-ins</p>
                            <h3 class="text-2xl font-bold text-eco-dark">8</h3>
                            <p class="text-blue-600 text-sm mt-1"><i class="fas fa-user-check mr-1"></i> 2 pending arrivals</p>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-key text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500">Today's Revenue</p>
                            <h3 class="text-2xl font-bold text-eco-dark">₱12,458</h3>
                            <p class="text-green-600 text-sm mt-1"><i class="fas fa-peso-sign mr-1"></i> 12% from yesterday</p>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-money-bill-wave text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            
  
            <div class="flex flex-wrap gap-4 mb-8">
                <button id="createReservationBtn" class="bg-eco-primary text-white px-4 py-2 rounded-lg hover:bg-eco-dark transition-colors flex items-center">
                    <i class="fas fa-plus mr-2"></i> Create Reservation
                </button>
                <button id="manageCustomersBtn" class="bg-eco-accent text-white px-4 py-2 rounded-lg hover:bg-eco-dark transition-colors flex items-center">
                    <i class="fas fa-user-friends mr-2"></i> Manage Customers
                </button>
                <button id="checkAvailabilityBtn" class="bg-white text-eco-primary border border-eco-primary px-4 py-2 rounded-lg hover:bg-eco-cream transition-colors flex items-center">
                    <i class="fas fa-bed mr-2"></i> Check Room Availability
                </button>
                <button id="generateInvoiceBtn" class="bg-white text-eco-primary border border-eco-primary px-4 py-2 rounded-lg hover:bg-eco-cream transition-colors flex items-center">
                    <i class="fas fa-file-invoice-dollar mr-2"></i> Generate Invoice
                </button>
            </div>
          
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-eco-dark">Current Reservations</h2>
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
                                <th class="py-3 px-6 font-medium">Room</th>
                                <th class="py-3 px-6 font-medium">Check-In</th>
                                <th class="py-3 px-6 font-medium">Check-Out</th>
                                <th class="py-3 px-6 font-medium">Status</th>
                                <th class="py-3 px-6 font-medium">Amount</th>
                                <th class="py-3 px-6 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200" id="reservationsTableBody">
                        
                        </tbody>
                    </table>
                </div>
            </div>
            
      
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-eco-dark">Room Availability</h2>
                    <div class="flex space-x-2">
                        <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors">
                            <i class="fas fa-filter"></i>
                        </button>
                        <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-6">
              
                    <div class="flex flex-wrap gap-4 mb-6">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                            <span class="text-sm">Available</span>
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
                            <div class="w-3 h-3 rounded-full bg-gray-400 mr-2"></div>
                            <span class="text-sm">Out of Service</span>
                        </div>
                    </div>
                    
               >
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" id="roomCardsContainer">
                      
                    </div>
                </div>
            </div>
            
       
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-eco-dark mb-4">Reservation Status Distribution</h3>
                    <div class="h-64">
                        <canvas id="reservationStatusChart"></canvas>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-eco-dark mb-4">Monthly Revenue Trend</h3>
                    <div class="h-64">
                        <canvas id="revenueTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Create Reservation Modal -->
    <div id="createReservationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-eco-dark">Create New Reservation</h3>
                <button id="closeCreateReservationModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="createReservationForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 mb-2">Guest Name</label>
                        <select class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
                            <option value="">Select Guest</option>
                            <option value="john">John Smith</option>
                            <option value="jane">Jane Doe</option>
                            <option value="mike">Mike Johnson</option>
                            <option value="sarah">Sarah Williams</option>
                            <option value="new">+ Add New Customer</option>
                        </select>
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
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Check-In Date</label>
                        <input type="date" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" value="2023-05-20">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Check-Out Date</label>
                        <input type="date" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" value="2023-05-23">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Number of Guests</label>
                        <select class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
                            <option value="1">1 Guest</option>
                            <option value="2" selected>2 Guests</option>
                            <option value="3">3 Guests</option>
                            <option value="4">4 Guests</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Room Number</label>
                        <select class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
                            <option value="">Auto-assign</option>
                            <option value="101">101 (Single)</option>
                            <option value="102">102 (Double)</option>
                            <option value="201">201 (Suite)</option>
                            <option value="202">202 (Deluxe)</option>
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 mb-2">Special Requests</label>
                        <textarea class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" rows="3" placeholder="Any special requests or notes..."></textarea>
                    </div>
                </div>
                
            
                <div class="mt-6 border rounded-lg p-4">
                    <h4 class="text-lg font-medium text-eco-dark mb-3">Price Summary</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Room Rate (3 nights)</span>
                            <span>₱697.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tax (10%)</span>
                            <span>₱59.70</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Service Fee</span>
                            <span>₱25.00</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t font-bold">
                            <span>Total</span>
                            <span class="text-eco-dark">1,981.70</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="cancelCreateReservation" class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-eco-primary text-white rounded-lg hover:bg-eco-dark transition-colors">Create Reservation</button>
                </div>
            </form>
        </div>
    </div>


    <div id="customerManagementModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-eco-dark">Customer Management</h3>
                <button id="closeCustomerManagementModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="relative w-full md:w-auto mb-4 md:mb-0">
                        <input type="text" placeholder="Search customers..." class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent w-full">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <button class="bg-eco-primary text-white px-4 py-2 rounded-lg hover:bg-eco-dark transition-colors flex items-center">
                        <i class="fas fa-plus mr-2"></i> Add New Customer
                    </button>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 text-left text-gray-500">
                            <th class="py-3 px-6 font-medium">Customer ID</th>
                            <th class="py-3 px-6 font-medium">Name</th>
                            <th class="py-3 px-6 font-medium">Email</th>
                            <th class="py-3 px-6 font-medium">Phone</th>
                            <th class="py-3 px-6 font-medium">Reservations</th>
                            <th class="py-3 px-6 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" id="customersTableBody">
                 
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Invoice Preview Modal -->
    <div id="invoicePreviewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-eco-dark">Invoice Preview</h3>
                <button id="closeInvoicePreviewModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Invoice Template -->
            <div class="border rounded-lg p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-eco-dark">BrokenShire Hotel</h2>
                        <p class="text-gray-600">123 Forest Lane, Green Valley</p>
                        <p class="text-gray-600">Phone: (555) 123-4567</p>
                    </div>
                    <div class="text-right">
                        <h3 class="text-xl font-bold text-eco-dark">INVOICE</h3>
                        <p class="text-gray-600">Invoice #: INV-001</p>
                        <p class="text-gray-600">Date: May 18, 2023</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <h4 class="font-medium text-gray-700 mb-2">Bill To:</h4>
                        <p class="font-bold">John Smith</p>
                        <p>123 Main Street</p>
                        <p>Cityville, ST 12345</p>
                        <p>john.smith@email.com</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-700 mb-2">Reservation Details:</h4>
                        <p><span class="font-medium">Check-In:</span> May 20, 2023</p>
                        <p><span class="font-medium">Check-Out:</span> May 23, 2023</p>
                        <p><span class="font-medium">Room:</span> 201 (Suite)</p>
                        <p><span class="font-medium">Guests:</span> 2</p>
                    </div>
                </div>
                
                <table class="w-full mb-6">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2 text-left font-medium">Description</th>
                            <th class="py-2 text-right font-medium">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="py-3">Suite Room (3 nights @ ₱239/night)</td>
                            <td class="py-3 text-right">₱597.00</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3">Tax (10%)</td>
                            <td class="py-3 text-right">₱59.70</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3">Service Fee</td>
                            <td class="py-3 text-right">₱25.00</td>
                        </tr>
                        <tr>
                            <td class="py-3 font-bold">Total</td>
                            <td class="py-3 text-right font-bold text-eco-dark">₱1,681.70</td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="text-center text-gray-500 text-sm mt-8">
                    <p>Thank you for choosing BrokenShire Hotel!</p>
                    <p>For questions about this invoice, please contact frontdesk@brokenshire.com</p>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button id="cancelInvoice" class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                <button id="printInvoice" class="px-4 py-2 bg-eco-primary text-white rounded-lg hover:bg-eco-dark transition-colors flex items-center">
                    <i class="fas fa-print mr-2"></i> Print Invoice
                </button>
                <button id="processPayment" class="px-4 py-2 bg-eco-accent text-white rounded-lg hover:bg-eco-dark transition-colors flex items-center">
                    <i class="fas fa-credit-card mr-2"></i> Process Payment
                </button>
            </div>
        </div>
    </div>

    <!-- Payment Processing Modal -->
    <div id="paymentProcessingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-eco-dark">Process Payment</h3>
                <button id="closePaymentProcessingModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="border rounded-lg p-4 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Invoice #</span>
                    <span class="font-medium">INV-001</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Amount Due</span>
                    <span class="font-bold text-eco-dark">₱681.70</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Guest</span>
                    <span class="font-medium">John Smith</span>
                </div>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 mb-2">Payment Method</label>
                    <div class="grid grid-cols-2 gap-4">
                        <button id="cashPaymentBtn" class="border border-eco-primary text-eco-primary px-4 py-3 rounded-lg hover:bg-eco-cream transition-colors flex items-center justify-center">
                            <i class="fas fa-money-bill-wave mr-2"></i> Cash
                        </button>
                        <button id="onlinePaymentBtn" class="border border-eco-primary text-eco-primary px-4 py-3 rounded-lg hover:bg-eco-cream transition-colors flex items-center justify-center">
                            <i class="fas fa-credit-card mr-2"></i> Online
                        </button>
                    </div>
                </div>
                
                <div id="cashPaymentSection" class="hidden">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 mb-2">Amount Received</label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500">₱</span>
                                <input type="number" class="w-full pl-8 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" value="681.70">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 mb-2">Received By</label>
                            <input type="text" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" value="Front Desk Staff" readonly>
                        </div>
                    </div>
                </div>
                
                <div id="onlinePaymentSection" class="hidden text-center">
                    <div class="w-16 h-16 bg-eco-light rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-credit-card text-eco-accent text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-800">Redirecting to Payment Gateway</h4>
                    <p class="text-gray-600 mt-2">You will be redirected to our secure payment processor to complete this transaction.</p>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button id="cancelPayment" class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                <button id="confirmPayment" class="px-4 py-2 bg-eco-primary text-white rounded-lg hover:bg-eco-dark transition-colors hidden">Process Payment</button>
                <button id="proceedOnlinePayment" class="px-4 py-2 bg-eco-primary text-white rounded-lg hover:bg-eco-dark transition-colors hidden">Proceed to Payment</button>
            </div>
        </div>
    </div>

    <!-- Payment Success Modal -->
    <div id="paymentSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-green-500 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Payment Successful!</h3>
            <p class="text-gray-600 mb-6">Payment of ₱1,681.70 has been processed successfully.</p>
            
            <div class="border rounded-lg p-4 mb-6 text-left">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Transaction ID</span>
                    <span class="font-medium">TXN-789456123</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Invoice #</span>
                    <span class="font-medium">INV-001</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Date</span>
                    <span class="font-medium">May 18, 2023</span>
                </div>
            </div>
            
            <div class="flex justify-center space-x-3">
                <button id="viewReceipt" class="px-4 py-2 bg-eco-primary text-white rounded-lg hover:bg-eco-dark transition-colors">View Receipt</button>
                <button id="closeSuccessModal" class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Done</button>
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
        // Sample data
        const sampleReservations = [
            { id: 1, reservationId: "RES-001", guest: "John Smith", room: "201 (Suite)", checkIn: "2023-05-20", checkOut: "2023-05-23", status: "confirmed", amount: 681.70 },
            { id: 2, reservationId: "RES-002", guest: "Jane Doe", room: "102 (Double)", checkIn: "2023-05-18", checkOut: "2023-05-21", status: "checked-in", amount: 452.50 },
            { id: 3, reservationId: "RES-003", guest: "Mike Johnson", room: "101 (Single)", checkIn: "2023-05-19", checkOut: "2023-05-22", status: "pending", amount: 267.80 },
            { id: 4, reservationId: "RES-004", guest: "Sarah Williams", room: "202 (Deluxe)", checkIn: "2023-05-15", checkOut: "2023-05-18", status: "checked-out", amount: 894.25 },
            { id: 5, reservationId: "RES-005", guest: "Robert Brown", room: "301 (Suite)", checkIn: "2023-05-22", checkOut: "2023-05-25", status: "confirmed", amount: 1024.90 }
        ];

        const sampleRooms = [
            { id: 1, number: "101", type: "Single", amenities: ["WiFi", "TV", "AC"], rate: 89.99, status: "available" },
            { id: 2, number: "102", type: "Double", amenities: ["WiFi", "TV", "AC", "Balcony"], rate: 119.99, status: "occupied" },
            { id: 3, number: "103", type: "Single", amenities: ["WiFi", "TV", "AC"], rate: 89.99, status: "available" },
            { id: 4, number: "104", type: "Double", amenities: ["WiFi", "TV", "AC", "Ocean View"], rate: 129.99, status: "reserved" },
            { id: 5, number: "201", type: "Suite", amenities: ["WiFi", "TV", "AC", "Mini Bar", "Jacuzzi"], rate: 199.99, status: "reserved" },
            { id: 6, number: "202", type: "Deluxe", amenities: ["WiFi", "TV", "AC", "Mini Bar", "Balcony", "Ocean View"], rate: 159.99, status: "dirty" },
            { id: 7, number: "203", type: "Suite", amenities: ["WiFi", "TV", "AC", "Mini Bar", "Jacuzzi", "Ocean View"], rate: 229.99, status: "available" },
            { id: 8, number: "301", type: "Single", amenities: ["WiFi", "TV", "AC"], rate: 89.99, status: "out" }
        ];

        const sampleCustomers = [
            { id: 1, customerId: "CUST-001", name: "John Smith", email: "john.smith@email.com", phone: "(555) 123-4567", reservations: 3 },
            { id: 2, customerId: "CUST-002", name: "Jane Doe", email: "jane.doe@email.com", phone: "(555) 234-5678", reservations: 5 },
            { id: 3, customerId: "CUST-003", name: "Mike Johnson", email: "mike.johnson@email.com", phone: "(555) 345-6789", reservations: 2 },
            { id: 4, customerId: "CUST-004", name: "Sarah Williams", email: "sarah.williams@email.com", phone: "(555) 456-7890", reservations: 7 },
            { id: 5, customerId: "CUST-005", name: "Robert Brown", email: "robert.brown@email.com", phone: "(555) 567-8901", reservations: 4 }
        ];

      
        let reservations = [...sampleReservations];
        let rooms = [...sampleRooms];
        let customers = [...sampleCustomers];

        const reservationsTableBody = document.getElementById('reservationsTableBody');
        const roomCardsContainer = document.getElementById('roomCardsContainer');
        const customersTableBody = document.getElementById('customersTableBody');
        
        const createReservationModal = document.getElementById('createReservationModal');
        const customerManagementModal = document.getElementById('customerManagementModal');
        const invoicePreviewModal = document.getElementById('invoicePreviewModal');
        const paymentProcessingModal = document.getElementById('paymentProcessingModal');
        const paymentSuccessModal = document.getElementById('paymentSuccessModal');
        const logoutModal = document.getElementById('logoutModal');
        
        const createReservationBtn = document.getElementById('createReservationBtn');
        const manageCustomersBtn = document.getElementById('manageCustomersBtn');
        const checkAvailabilityBtn = document.getElementById('checkAvailabilityBtn');
        const generateInvoiceBtn = document.getElementById('generateInvoiceBtn');
        
        const closeCreateReservationModal = document.getElementById('closeCreateReservationModal');
        const cancelCreateReservation = document.getElementById('cancelCreateReservation');
        const closeCustomerManagementModal = document.getElementById('closeCustomerManagementModal');
        const closeInvoicePreviewModal = document.getElementById('closeInvoicePreviewModal');
        const cancelInvoice = document.getElementById('cancelInvoice');
        const printInvoice = document.getElementById('printInvoice');
        const processPayment = document.getElementById('processPayment');
        const closePaymentProcessingModal = document.getElementById('closePaymentProcessingModal');
        const cancelPayment = document.getElementById('cancelPayment');
        const cashPaymentBtn = document.getElementById('cashPaymentBtn');
        const onlinePaymentBtn = document.getElementById('onlinePaymentBtn');
        const confirmPayment = document.getElementById('confirmPayment');
        const proceedOnlinePayment = document.getElementById('proceedOnlinePayment');
        const closeSuccessModal = document.getElementById('closeSuccessModal');
        const viewReceipt = document.getElementById('viewReceipt');
        const cancelLogout = document.getElementById('cancelLogout');
        const confirmLogout = document.getElementById('confirmLogout');
        const logoutBtn = document.getElementById('logoutBtn');
        
        const createReservationForm = document.getElementById('createReservationForm');
        const cashPaymentSection = document.getElementById('cashPaymentSection');
        const onlinePaymentSection = document.getElementById('onlinePaymentSection');

  
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('overlay').classList.toggle('active');
        });
        
        document.getElementById('overlay').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('open');
            this.classList.remove('active');
        });
        
  
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
        createReservationBtn.addEventListener('click', function() {
            createReservationModal.classList.remove('hidden');
        });
        
        manageCustomersBtn.addEventListener('click', function() {
            customerManagementModal.classList.remove('hidden');
            renderCustomersTable();
        });
        
        checkAvailabilityBtn.addEventListener('click', function() {
            alert('Room availability dashboard would open here');
        });
        
        generateInvoiceBtn.addEventListener('click', function() {
            invoicePreviewModal.classList.remove('hidden');
        });
        
        logoutBtn.addEventListener('click', function() {
            logoutModal.classList.remove('hidden');
        });
        
        closeCreateReservationModal.addEventListener('click', function() {
            createReservationModal.classList.add('hidden');
        });
        
        cancelCreateReservation.addEventListener('click', function() {
            createReservationModal.classList.add('hidden');
        });
        
        closeCustomerManagementModal.addEventListener('click', function() {
            customerManagementModal.classList.add('hidden');
        });
        
        closeInvoicePreviewModal.addEventListener('click', function() {
            invoicePreviewModal.classList.add('hidden');
        });
        
        cancelInvoice.addEventListener('click', function() {
            invoicePreviewModal.classList.add('hidden');
        });
        
        printInvoice.addEventListener('click', function() {
            alert('Invoice would be printed here');
        });
        
        processPayment.addEventListener('click', function() {
            invoicePreviewModal.classList.add('hidden');
            paymentProcessingModal.classList.remove('hidden');
        });
        
        closePaymentProcessingModal.addEventListener('click', function() {
            paymentProcessingModal.classList.add('hidden');
            resetPaymentSections();
        });
        
        cancelPayment.addEventListener('click', function() {
            paymentProcessingModal.classList.add('hidden');
            resetPaymentSections();
        });
        
        cashPaymentBtn.addEventListener('click', function() {
            cashPaymentSection.classList.remove('hidden');
            onlinePaymentSection.classList.add('hidden');
            confirmPayment.classList.remove('hidden');
            proceedOnlinePayment.classList.add('hidden');
        });
        
        onlinePaymentBtn.addEventListener('click', function() {
            onlinePaymentSection.classList.remove('hidden');
            cashPaymentSection.classList.add('hidden');
            proceedOnlinePayment.classList.remove('hidden');
            confirmPayment.classList.add('hidden');
        });
        
        confirmPayment.addEventListener('click', function() {
            paymentProcessingModal.classList.add('hidden');
            paymentSuccessModal.classList.remove('hidden');
            resetPaymentSections();
        });
        
        proceedOnlinePayment.addEventListener('click', function() {
            paymentProcessingModal.classList.add('hidden');
            paymentSuccessModal.classList.remove('hidden');
            resetPaymentSections();
        });
        
        closeSuccessModal.addEventListener('click', function() {
            paymentSuccessModal.classList.add('hidden');
        });
        
        viewReceipt.addEventListener('click', function() {
            paymentSuccessModal.classList.add('hidden');
            alert('Receipt viewer would open here');
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
        [createReservationModal, customerManagementModal, invoicePreviewModal, paymentProcessingModal, paymentSuccessModal, logoutModal].forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    if (modal === paymentProcessingModal) {
                        resetPaymentSections();
                    }
                }
            });
        });

        // Form submissions
        createReservationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Reservation created successfully! (This is a frontend demo - no actual data saved)');
            createReservationModal.classList.add('hidden');
            createReservationForm.reset();
        });

        // Function to reset payment sections
        function resetPaymentSections() {
            cashPaymentSection.classList.add('hidden');
            onlinePaymentSection.classList.add('hidden');
            confirmPayment.classList.add('hidden');
            proceedOnlinePayment.classList.add('hidden');
        }

        // Function to render reservations table
        function renderReservationsTable() {
            reservationsTableBody.innerHTML = '';
            
            reservations.forEach(reservation => {
                const statusClass = `reservation-${reservation.status}`;
                const statusText = reservation.status.charAt(0).toUpperCase() + reservation.status.slice(1).replace('-', ' ');
                
                const row = document.createElement('tr');
                row.className = 'hover:bg-eco-cream transition-colors';
                row.innerHTML = `
                    <td class="py-4 px-6 font-medium">${reservation.reservationId}</td>
                    <td class="py-4 px-6">${reservation.guest}</td>
                    <td class="py-4 px-6">${reservation.room}</td>
                    <td class="py-4 px-6">${reservation.checkIn}</td>
                    <td class="py-4 px-6">${reservation.checkOut}</td>
                    <td class="py-4 px-6">
                        <span class="px-3 py-1 rounded-full text-xs font-medium ${statusClass}">${statusText}</span>
                    </td>
                    <td class="py-4 px-6 font-bold">₱${reservation.amount}</td>
                    <td class="py-4 px-6">
                        <div class="flex space-x-2">
                            <button class="text-eco-accent hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors view-reservation-btn" data-reservation-id="${reservation.id}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors edit-reservation-btn" data-reservation-id="${reservation.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100 transition-colors invoice-btn" data-reservation-id="${reservation.id}">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                reservationsTableBody.appendChild(row);
            });
            
            // Add event listeners to reservation buttons
            document.querySelectorAll('.view-reservation-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const reservationId = parseInt(this.getAttribute('data-reservation-id'));
                    viewReservation(reservationId);
                });
            });
            
            document.querySelectorAll('.edit-reservation-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const reservationId = parseInt(this.getAttribute('data-reservation-id'));
                    editReservation(reservationId);
                });
            });
            
            document.querySelectorAll('.invoice-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const reservationId = parseInt(this.getAttribute('data-reservation-id'));
                    generateInvoice(reservationId);
                });
            });
        }

        // Function to render room cards
        function renderRoomCards() {
            roomCardsContainer.innerHTML = '';
            
            rooms.forEach(room => {
                const statusClass = `status-${room.status}`;
                const statusText = room.status.charAt(0).toUpperCase() + room.status.slice(1);
                
                const card = document.createElement('div');
                card.className = 'bg-white rounded-lg shadow-md p-4 border-l-4';
                
                // Set border color based on status
                if (room.status === 'available') card.classList.add('border-green-500');
                else if (room.status === 'occupied') card.classList.add('border-red-500');
                else if (room.status === 'reserved') card.classList.add('border-blue-500');
                else if (room.status === 'dirty') card.classList.add('border-yellow-500');
                else card.classList.add('border-gray-400');
                
                card.innerHTML = `
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg">Room ${room.number}</h3>
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${statusClass}">${statusText}</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-2">${room.type}</p>
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
                        <button class="text-eco-primary hover:text-eco-dark text-sm flex items-center book-room-btn" data-room-id="${room.id}">
                            <i class="fas fa-calendar-plus mr-1"></i> Book
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
            
            // Add event listeners to book room buttons
            document.querySelectorAll('.book-room-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const roomId = parseInt(this.getAttribute('data-room-id'));
                    bookRoom(roomId);
                });
            });
        }

        // Function to render customers table
        function renderCustomersTable() {
            customersTableBody.innerHTML = '';
            
            customers.forEach(customer => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-eco-cream transition-colors';
                row.innerHTML = `
                    <td class="py-4 px-6 font-medium">${customer.customerId}</td>
                    <td class="py-4 px-6">${customer.name}</td>
                    <td class="py-4 px-6">${customer.email}</td>
                    <td class="py-4 px-6">${customer.phone}</td>
                    <td class="py-4 px-6">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-eco-light text-eco-dark">${customer.reservations} bookings</span>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex space-x-2">
                            <button class="text-eco-accent hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors view-customer-btn" data-customer-id="${customer.id}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors edit-customer-btn" data-customer-id="${customer.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100 transition-colors book-customer-btn" data-customer-id="${customer.id}">
                                <i class="fas fa-calendar-plus"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                customersTableBody.appendChild(row);
            });
            
            // Add event listeners to customer buttons
            document.querySelectorAll('.view-customer-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const customerId = parseInt(this.getAttribute('data-customer-id'));
                    viewCustomer(customerId);
                });
            });
            
            document.querySelectorAll('.edit-customer-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const customerId = parseInt(this.getAttribute('data-customer-id'));
                    editCustomer(customerId);
                });
            });
            
            document.querySelectorAll('.book-customer-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const customerId = parseInt(this.getAttribute('data-customer-id'));
                    bookForCustomer(customerId);
                });
            });
        }

        // Function to view reservation details
        function viewReservation(reservationId) {
            const reservation = reservations.find(r => r.id === reservationId);
            if (!reservation) return;
            
            alert(`Reservation Details:\n\nID: ${reservation.reservationId}\nGuest: ${reservation.guest}\nRoom: ${reservation.room}\nCheck-In: ${reservation.checkIn}\nCheck-Out: ${reservation.checkOut}\nStatus: ${reservation.status}\nAmount: $${reservation.amount}`);
        }

        // Function to edit reservation
        function editReservation(reservationId) {
            const reservation = reservations.find(r => r.id === reservationId);
            if (!reservation) return;
            
            alert(`Edit reservation ${reservation.reservationId} functionality would open here`);
        }

        // Function to generate invoice
        function generateInvoice(reservationId) {
            const reservation = reservations.find(r => r.id === reservationId);
            if (!reservation) return;
            
            invoicePreviewModal.classList.remove('hidden');
        }

        // Function to update room status
        function updateRoomStatus(roomId) {
            const room = rooms.find(r => r.id === roomId);
            if (!room) return;
            
            const newStatus = prompt(`Update status for Room ${room.number}:\n\nCurrent: ${room.status}\n\nEnter new status (available, occupied, reserved, dirty, out):`, room.status);
            
            if (newStatus && ['available', 'occupied', 'reserved', 'dirty', 'out'].includes(newStatus.toLowerCase())) {
                room.status = newStatus.toLowerCase();
                
                // Re-render the UI
                renderRoomCards();
                
                alert(`Room ${room.number} status updated to ${newStatus}`);
            } else if (newStatus) {
                alert('Invalid status. Please use: available, occupied, reserved, dirty, or out');
            }
        }

        // Function to book room
        function bookRoom(roomId) {
            const room = rooms.find(r => r.id === roomId);
            if (!room) return;
            
            if (room.status === 'available') {
                createReservationModal.classList.remove('hidden');
            } else {
                alert(`Room ${room.number} is not available for booking. Current status: ${room.status}`);
            }
        }

        // Function to view customer
        function viewCustomer(customerId) {
            const customer = customers.find(c => c.id === customerId);
            if (!customer) return;
            
            alert(`Customer Details:\n\nID: ${customer.customerId}\nName: ${customer.name}\nEmail: ${customer.email}\nPhone: ${customer.phone}\nReservations: ${customer.reservations}`);
        }

        
        function editCustomer(customerId) {
            const customer = customers.find(c => c.id === customerId);
            if (!customer) return;
            
            alert(`Edit customer ${customer.name} functionality would open here`);
        }

        function bookForCustomer(customerId) {
            const customer = customers.find(c => c.id === customerId);
            if (!customer) return;
            
            createReservationModal.classList.remove('hidden');
        }

        const searchInput = document.querySelector('input[type="text"]');
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const filteredReservations = sampleReservations.filter(reservation => 
                reservation.reservationId.toLowerCase().includes(searchTerm) || 
                reservation.guest.toLowerCase().includes(searchTerm) ||
                reservation.room.toLowerCase().includes(searchTerm)
            );
            
            reservations = filteredReservations;
            renderReservationsTable();
        });

   
        function initializeCharts() {
       
            const reservationStatusCtx = document.getElementById('reservationStatusChart').getContext('2d');
            new Chart(reservationStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Confirmed', 'Checked-In', 'Pending', 'Checked-Out', 'Cancelled'],
                    datasets: [{
                        data: [35, 20, 15, 25, 5],
                        backgroundColor: [
                            '#4CAF50',
                            '#2196F3',
                            '#FF9800',
                            '#9C27B0',
                            '#F44336'
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

            const revenueTrendCtx = document.getElementById('revenueTrendChart').getContext('2d');
            new Chart(revenueTrendCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                    datasets: [{
                        label: 'Revenue ($)',
                        data: [18500, 21200, 19800, 22400, 24850],
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
                        }
                    }
                }
            });
        }

        // Initialize the UI
        renderReservationsTable();
        renderRoomCards();
        initializeCharts();
    </script>
</body>
</html>