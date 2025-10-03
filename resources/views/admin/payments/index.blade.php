<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrokenShire Admin - Payments Management</title>
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
        
        .status-paid {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-failed {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .status-refunded {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .status-partial {
            background-color: #f3e8ff;
            color: #7c3aed;
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
       
        <aside id="sidebar" class="sidebar bg-white w-64 fixed h-full shadow-lg z-10">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-eco-dark flex items-center space-x-2">
                    <i class="fas fa-leaf text-eco-primary"></i>
                    <span>BrokenShire</span>
                </h1>
                <p class="text-sm text-gray-500 mt-1">Payments Management</p>
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
                            <p class="text-sm text-gray-500">FD Role</p>
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
                        <h1 class="text-3xl font-bold text-eco-dark">Payments Management</h1>
                        <p class="text-gray-600">Manage invoices, payments, receipts, and refunds</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Search payments..." class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
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
            
            <!-- Payment Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500">Total Revenue</p>
                            <h3 class="text-2xl font-bold text-eco-dark">₱812,458</h3>
                            <p class="text-green-600 text-sm mt-1"><i class="fas fa-arrow-up mr-1"></i> 8.5% from last month</p>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-dollar-sign text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500">Pending Payments</p>
                            <h3 class="text-2xl font-bold text-eco-dark">₱13,245</h3>
                            <p class="text-yellow-600 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i> 12 invoices pending</p>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-clock text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500">Successful Payments</p>
                            <h3 class="text-2xl font-bold text-eco-dark">142</h3>
                            <p class="text-green-600 text-sm mt-1"><i class="fas fa-check-circle mr-1"></i> 94% success rate</p>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-check text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500">Refunds Issued</p>
                            <h3 class="text-2xl font-bold text-eco-dark">₱2,850</h3>
                            <p class="text-blue-600 text-sm mt-1"><i class="fas fa-undo-alt mr-1"></i> 5 refunds this month</p>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-exchange-alt text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-4 mb-8">
                <button id="generateInvoiceBtn" class="bg-eco-primary text-white px-4 py-2 rounded-lg hover:bg-eco-dark transition-colors flex items-center">
                    <i class="fas fa-plus mr-2"></i> Generate Invoice
                </button>
                <button id="cashPaymentBtn" class="bg-eco-accent text-white px-4 py-2 rounded-lg hover:bg-eco-dark transition-colors flex items-center">
                    <i class="fas fa-money-bill-wave mr-2"></i> Record Cash Payment
                </button>
                <button id="onlinePaymentBtn" class="bg-white text-eco-primary border border-eco-primary px-4 py-2 rounded-lg hover:bg-eco-cream transition-colors flex items-center">
                    <i class="fas fa-credit-card mr-2"></i> Process Online Payment
                </button>
                <button id="issueReceiptBtn" class="bg-white text-eco-primary border border-eco-primary px-4 py-2 rounded-lg hover:bg-eco-cream transition-colors flex items-center">
                    <i class="fas fa-receipt mr-2"></i> Issue Receipt
                </button>
                <button id="refundPaymentBtn" class="bg-white text-eco-primary border border-eco-primary px-4 py-2 rounded-lg hover:bg-eco-cream transition-colors flex items-center">
                    <i class="fas fa-undo-alt mr-2"></i> Refund Payment
                </button>
            </div>
            
            <!-- Recent Invoices -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-eco-dark">Recent Invoices</h2>
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
                                <th class="py-3 px-6 font-medium">Invoice #</th>
                                <th class="py-3 px-6 font-medium">Guest</th>
                                <th class="py-3 px-6 font-medium">Reservation</th>
                                <th class="py-3 px-6 font-medium">Amount</th>
                                <th class="py-3 px-6 font-medium">Status</th>
                                <th class="py-3 px-6 font-medium">Due Date</th>
                                <th class="py-3 px-6 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200" id="invoicesTableBody">
                            <!-- Invoice rows will be dynamically generated here -->
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Payment History -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-eco-dark">Payment History</h2>
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
                                <th class="py-3 px-6 font-medium">Payment ID</th>
                                <th class="py-3 px-6 font-medium">Invoice #</th>
                                <th class="py-3 px-6 font-medium">Guest</th>
                                <th class="py-3 px-6 font-medium">Amount</th>
                                <th class="py-3 px-6 font-medium">Method</th>
                                <th class="py-3 px-6 font-medium">Status</th>
                                <th class="py-3 px-6 font-medium">Date</th>
                                <th class="py-3 px-6 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200" id="paymentsTableBody">
                            <!-- Payment rows will be dynamically generated here -->
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Financial Reports Preview -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-eco-dark mb-4">Revenue by Payment Method</h3>
                    <div class="h-64">
                        <canvas id="paymentMethodChart"></canvas>
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

    <!-- Generate Invoice Modal -->
    <div id="generateInvoiceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-eco-dark">Generate New Invoice</h3>
                <button id="closeGenerateInvoiceModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="generateInvoiceForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 mb-2">Guest Name</label>
                        <select class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
                            <option value="">Select Guest</option>
                            <option value="john">Jade Albercas</option>
                            <option value="jane">John Cena</option>
                            <option value="mike">
                           John Paul Domon-As</option>
                            <option value="sarah">Art Mkenjo</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Reservation ID</label>
                        <select class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
                            <option value="">Select Reservation</option>
                            <option value="res-001">RES-001 (
Jade Albercas)</option>
                            <option value="res-002">RES-002 (John Cena)</option>
                            <option value="res-003">RES-003 (John Paul Domon-As)</option>
                            <option value="res-004">RES-004 (Art Mkenjo)</option>
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <h4 class="text-lg font-medium text-eco-dark mb-3">Invoice Items</h4>
                        <div class="space-y-4">
                            <div class="grid grid-cols-12 gap-4 items-center">
                                <div class="col-span-5">
                                    <input type="text" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" placeholder="Item Description">
                                </div>
                                <div class="col-span-2">
                                    <input type="number" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" placeholder="Quantity" min="1" value="1">
                                </div>
                                <div class="col-span-3">
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 text-gray-500">₱</span>
                                        <input type="number" class="w-full pl-8 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" placeholder="0.00" min="0" step="0.01">
                                    </div>
                                </div>
                                <div class="col-span-2">
                                    <button type="button" class="w-full bg-eco-primary text-white px-4 py-2 rounded-lg hover:bg-eco-dark transition-colors">
                                        Add
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Sample items -->
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-medium">Room Charge (Suite, 3 nights)</span>
                                    <span class="font-bold">₱597.00</span>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <span>Tax (10%)</span>
                                    <span>₱59.70</span>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <span>Service Fee</span>
                                    <span>₱25.00</span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t">
                                    <span class="font-bold">Total</span>
                                    <span class="font-bold text-eco-dark">₱681.70</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 mb-2">Notes</label>
                        <textarea class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" rows="3" placeholder="Additional notes for the invoice..."></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="cancelGenerateInvoice" class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-eco-primary text-white rounded-lg hover:bg-eco-dark transition-colors">Generate Invoice</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Cash Payment Modal -->
    <div id="cashPaymentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-eco-dark">Record Cash Payment</h3>
                <button id="closeCashPaymentModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="cashPaymentForm">
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 mb-2">Invoice #</label>
                        <select class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
                            <option value="">Select Invoice</option>
                            <option value="inv-001">INV-001 (John Smith)</option>
                            <option value="inv-002">INV-002 (Jane Doe)</option>
                            <option value="inv-003">INV-003 (Mike Johnson)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Amount Received</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-500">₱</span>
                            <input type="number" class="w-full pl-8 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" placeholder="0.00" min="0" step="0.01" value="681.70">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Received By</label>
                        <input type="text" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" value="Front Desk Staff" readonly>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Payment Date</label>
                        <input type="date" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" value="2023-05-18">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="cancelCashPayment" class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-eco-primary text-white rounded-lg hover:bg-eco-dark transition-colors">Record Payment</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Online Payment Modal -->
    <div id="onlinePaymentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-eco-dark">Process Online Payment</h3>
                <button id="closeOnlinePaymentModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-eco-light rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-credit-card text-eco-accent text-2xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-800">Redirecting to Payment Gateway</h4>
                <p class="text-gray-600 mt-2">You will be redirected to our secure payment processor to complete this transaction.</p>
            </div>
            
            <div class="border rounded-lg p-4 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Invoice #</span>
                    <span class="font-medium">INV-001</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Amount</span>
                    <span class="font-bold text-eco-dark">₱681.70</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Guest</span>
                    <span class="font-medium">
Jade Albercas</span>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" id="cancelOnlinePayment" class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                <button id="processOnlinePayment" class="px-4 py-2 bg-eco-primary text-white rounded-lg hover:bg-eco-dark transition-colors">Proceed to Payment</button>
            </div>
        </div>
    </div>

    <!-- Payment Gateway Modal -->
    <div id="paymentGatewayModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <div class="text-center mb-6">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal" class="h-10 mx-auto mb-4">
                <h4 class="text-lg font-medium text-gray-800">Secure Payment</h4>
                <p class="text-gray-600 mt-2">Complete your payment securely</p>
            </div>
            
            <div class="border rounded-lg p-4 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Merchant</span>
                    <span class="font-medium">BrokenShire Hotel</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Amount</span>
                    <span class="font-bold text-eco-dark">₱681.70 USD</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Description</span>
                    <span class="font-medium">Hotel Stay - INV-001</span>
                </div>
            </div>
            
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-gray-700 mb-2">Card Number</label>
                    <input type="text" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" placeholder="1234 5678 9012 3456" value="4111 1111 1111 1111">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 mb-2">Expiry Date</label>
                        <input type="text" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" placeholder="MM/YY" value="12/25">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">CVV</label>
                        <input type="text" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent" placeholder="123" value="123">
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" id="cancelGatewayPayment" class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                <button id="confirmGatewayPayment" class="px-4 py-2 bg-eco-primary text-white rounded-lg hover:bg-eco-dark transition-colors">Pay $681.70</button>
            </div>
        </div>
    </div>


    <div id="paymentSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-green-500 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Payment Successful!</h3>
            <p class="text-gray-600 mb-6">Your payment of ₱1,681.70 has been processed successfully.</p>
            
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
        const sampleInvoices = [
            { id: 1, number: "INV-001", guest: "John Smith", reservation: "RES-001", amount: 681.70, status: "paid", dueDate: "2023-05-20" },
            { id: 2, number: "INV-002", guest: "Jane Doe", reservation: "RES-002", amount: 452.50, status: "pending", dueDate: "2023-05-22" },
            { id: 3, number: "INV-003", guest: "Mike Johnson", reservation: "RES-003", amount: 789.25, status: "pending", dueDate: "2023-05-18" },
            { id: 4, number: "INV-004", guest: "Sarah Williams", reservation: "RES-004", amount: 325.80, status: "paid", dueDate: "2023-05-15" },
            { id: 5, number: "INV-005", guest: "Robert Brown", reservation: "RES-005", amount: 1024.90, status: "failed", dueDate: "2023-05-10" }
        ];

        const samplePayments = [
            { id: 1, paymentId: "PAY-001", invoice: "INV-001", guest: "John Smith", amount: 681.70, method: "Credit Card", status: "paid", date: "2023-05-18" },
            { id: 2, paymentId: "PAY-002", invoice: "INV-004", guest: "Sarah Williams", amount: 325.80, method: "Cash", status: "paid", date: "2023-05-15" },
            { id: 3, paymentId: "PAY-003", invoice: "INV-002", guest: "Jane Doe", amount: 452.50, method: "Credit Card", status: "pending", date: "2023-05-17" },
            { id: 4, paymentId: "PAY-004", invoice: "INV-005", guest: "Robert Brown", amount: 1024.90, method: "Online", status: "failed", date: "2023-05-10" },
            { id: 5, paymentId: "PAY-005", invoice: "INV-001", guest: "John Smith", amount: 150.00, method: "Credit Card", status: "refunded", date: "2023-05-12" }
        ];

        // Initialize data
        let invoices = [...sampleInvoices];
        let payments = [...samplePayments];

        // DOM elements
        const invoicesTableBody = document.getElementById('invoicesTableBody');
        const paymentsTableBody = document.getElementById('paymentsTableBody');
        const generateInvoiceModal = document.getElementById('generateInvoiceModal');
        const cashPaymentModal = document.getElementById('cashPaymentModal');
        const onlinePaymentModal = document.getElementById('onlinePaymentModal');
        const paymentGatewayModal = document.getElementById('paymentGatewayModal');
        const paymentSuccessModal = document.getElementById('paymentSuccessModal');
        const logoutModal = document.getElementById('logoutModal');
        
        const generateInvoiceBtn = document.getElementById('generateInvoiceBtn');
        const cashPaymentBtn = document.getElementById('cashPaymentBtn');
        const onlinePaymentBtn = document.getElementById('onlinePaymentBtn');
        const issueReceiptBtn = document.getElementById('issueReceiptBtn');
        const refundPaymentBtn = document.getElementById('refundPaymentBtn');
        
        const closeGenerateInvoiceModal = document.getElementById('closeGenerateInvoiceModal');
        const cancelGenerateInvoice = document.getElementById('cancelGenerateInvoice');
        const closeCashPaymentModal = document.getElementById('closeCashPaymentModal');
        const cancelCashPayment = document.getElementById('cancelCashPayment');
        const closeOnlinePaymentModal = document.getElementById('closeOnlinePaymentModal');
        const cancelOnlinePayment = document.getElementById('cancelOnlinePayment');
        const processOnlinePayment = document.getElementById('processOnlinePayment');
        const cancelGatewayPayment = document.getElementById('cancelGatewayPayment');
        const confirmGatewayPayment = document.getElementById('confirmGatewayPayment');
        const closeSuccessModal = document.getElementById('closeSuccessModal');
        const viewReceipt = document.getElementById('viewReceipt');
        const cancelLogout = document.getElementById('cancelLogout');
        const confirmLogout = document.getElementById('confirmLogout');
        const logoutBtn = document.getElementById('logoutBtn');
        
        const generateInvoiceForm = document.getElementById('generateInvoiceForm');
        const cashPaymentForm = document.getElementById('cashPaymentForm');

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
        generateInvoiceBtn.addEventListener('click', function() {
            generateInvoiceModal.classList.remove('hidden');
        });
        
        cashPaymentBtn.addEventListener('click', function() {
            cashPaymentModal.classList.remove('hidden');
        });
        
        onlinePaymentBtn.addEventListener('click', function() {
            onlinePaymentModal.classList.remove('hidden');
        });
        
        issueReceiptBtn.addEventListener('click', function() {
            alert('Receipt generation functionality would be implemented here');
        });
        
        refundPaymentBtn.addEventListener('click', function() {
            alert('Refund payment functionality would be implemented here');
        });
        
        logoutBtn.addEventListener('click', function() {
            logoutModal.classList.remove('hidden');
        });
        
        closeGenerateInvoiceModal.addEventListener('click', function() {
            generateInvoiceModal.classList.add('hidden');
        });
        
        cancelGenerateInvoice.addEventListener('click', function() {
            generateInvoiceModal.classList.add('hidden');
        });
        
        closeCashPaymentModal.addEventListener('click', function() {
            cashPaymentModal.classList.add('hidden');
        });
        
        cancelCashPayment.addEventListener('click', function() {
            cashPaymentModal.classList.add('hidden');
        });
        
        closeOnlinePaymentModal.addEventListener('click', function() {
            onlinePaymentModal.classList.add('hidden');
        });
        
        cancelOnlinePayment.addEventListener('click', function() {
            onlinePaymentModal.classList.add('hidden');
        });
        
        processOnlinePayment.addEventListener('click', function() {
            onlinePaymentModal.classList.add('hidden');
            paymentGatewayModal.classList.remove('hidden');
        });
        
        cancelGatewayPayment.addEventListener('click', function() {
            paymentGatewayModal.classList.add('hidden');
        });
        
        confirmGatewayPayment.addEventListener('click', function() {
            paymentGatewayModal.classList.add('hidden');
            paymentSuccessModal.classList.remove('hidden');
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
        [generateInvoiceModal, cashPaymentModal, onlinePaymentModal, paymentGatewayModal, paymentSuccessModal, logoutModal].forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });

        // Form submissions
        generateInvoiceForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Invoice generated successfully! (This is a frontend demo - no actual data saved)');
            generateInvoiceModal.classList.add('hidden');
            generateInvoiceForm.reset();
        });

        cashPaymentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Cash payment recorded successfully! (This is a frontend demo - no actual data saved)');
            cashPaymentModal.classList.add('hidden');
            cashPaymentForm.reset();
        });

        // Function to render invoices table
        function renderInvoicesTable() {
            invoicesTableBody.innerHTML = '';
            
            invoices.forEach(invoice => {
                const statusClass = `status-${invoice.status}`;
                const statusText = invoice.status.charAt(0).toUpperCase() + invoice.status.slice(1);
                
                const row = document.createElement('tr');
                row.className = 'hover:bg-eco-cream transition-colors';
                row.innerHTML = `
                    <td class="py-4 px-6 font-medium">${invoice.number}</td>
                    <td class="py-4 px-6">${invoice.guest}</td>
                    <td class="py-4 px-6">${invoice.reservation}</td>
                    <td class="py-4 px-6 font-bold">₱${invoice.amount}</td>
                    <td class="py-4 px-6">
                        <span class="px-3 py-1 rounded-full text-xs font-medium ${statusClass}">${statusText}</span>
                    </td>
                    <td class="py-4 px-6">${invoice.dueDate}</td>
                    <td class="py-4 px-6">
                        <div class="flex space-x-2">
                            <button class="text-eco-accent hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors view-invoice-btn" data-invoice-id="${invoice.id}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors payment-btn" data-invoice-id="${invoice.id}">
                                <i class="fas fa-credit-card"></i>
                            </button>
                            <button class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                <i class="fas fa-print"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                invoicesTableBody.appendChild(row);
            });
            
            // Add event listeners to view invoice buttons
            document.querySelectorAll('.view-invoice-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const invoiceId = parseInt(this.getAttribute('data-invoice-id'));
                    viewInvoice(invoiceId);
                });
            });
            
            // Add event listeners to payment buttons
            document.querySelectorAll('.payment-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const invoiceId = parseInt(this.getAttribute('data-invoice-id'));
                    processPayment(invoiceId);
                });
            });
        }

        // Function to render payments table
        function renderPaymentsTable() {
            paymentsTableBody.innerHTML = '';
            
            payments.forEach(payment => {
                const statusClass = `status-${payment.status}`;
                const statusText = payment.status.charAt(0).toUpperCase() + payment.status.slice(1);
                
                const row = document.createElement('tr');
                row.className = 'hover:bg-eco-cream transition-colors';
                row.innerHTML = `
                    <td class="py-4 px-6 font-medium">${payment.paymentId}</td>
                    <td class="py-4 px-6">${payment.invoice}</td>
                    <td class="py-4 px-6">${payment.guest}</td>
                    <td class="py-4 px-6 font-bold">$${payment.amount}</td>
                    <td class="py-4 px-6">${payment.method}</td>
                    <td class="py-4 px-6">
                        <span class="px-3 py-1 rounded-full text-xs font-medium ${statusClass}">${statusText}</span>
                    </td>
                    <td class="py-4 px-6">${payment.date}</td>
                    <td class="py-4 px-6">
                        <div class="flex space-x-2">
                            <button class="text-eco-accent hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors receipt-btn" data-payment-id="${payment.id}">
                                <i class="fas fa-receipt"></i>
                            </button>
                            <button class="text-blue-500 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition-colors refund-btn" data-payment-id="${payment.id}">
                                <i class="fas fa-undo-alt"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                paymentsTableBody.appendChild(row);
            });
            
            // Add event listeners to receipt buttons
            document.querySelectorAll('.receipt-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const paymentId = parseInt(this.getAttribute('data-payment-id'));
                    issueReceipt(paymentId);
                });
            });
            
            // Add event listeners to refund buttons
            document.querySelectorAll('.refund-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const paymentId = parseInt(this.getAttribute('data-payment-id'));
                    refundPayment(paymentId);
                });
            });
        }

        // Function to view invoice details
        function viewInvoice(invoiceId) {
            const invoice = invoices.find(i => i.id === invoiceId);
            if (!invoice) return;
            
            alert(`Invoice Details:\n\nInvoice #: ${invoice.number}\nGuest: ${invoice.guest}\nReservation: ${invoice.reservation}\nAmount: $${invoice.amount}\nStatus: ${invoice.status}\nDue Date: ${invoice.dueDate}`);
        }

        // Function to process payment
        function processPayment(invoiceId) {
            const invoice = invoices.find(i => i.id === invoiceId);
            if (!invoice) return;
            
            // For demo purposes, we'll just open the online payment modal
            onlinePaymentModal.classList.remove('hidden');
        }

        // Function to issue receipt
        function issueReceipt(paymentId) {
            const payment = payments.find(p => p.id === paymentId);
            if (!payment) return;
            
            alert(`Receipt for Payment ${payment.paymentId}\n\nIssued to: ${payment.guest}\nAmount: $${payment.amount}\nMethod: ${payment.method}\nDate: ${payment.date}\n\nReceipt would be generated and available for download/printing.`);
        }

        // Function to refund payment
        function refundPayment(paymentId) {
            const payment = payments.find(p => p.id === paymentId);
            if (!payment) return;
            
            if (confirm(`Are you sure you want to refund ₱${payment.amount} for payment ${payment.paymentId}?`)) {
                // In a real app, this would call an API to process the refund
                payment.status = 'refunded';
                
                // Re-render the UI
                renderPaymentsTable();
                
                alert(`Refund of ₱${payment.amount} has been processed for payment ${payment.paymentId}.`);
            }
        }

        // Search functionality
        const searchInput = document.querySelector('input[type="text"]');
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const filteredInvoices = sampleInvoices.filter(invoice => 
                invoice.number.toLowerCase().includes(searchTerm) || 
                invoice.guest.toLowerCase().includes(searchTerm) ||
                invoice.reservation.toLowerCase().includes(searchTerm)
            );
            
            const filteredPayments = samplePayments.filter(payment => 
                payment.paymentId.toLowerCase().includes(searchTerm) || 
                payment.guest.toLowerCase().includes(searchTerm) ||
                payment.invoice.toLowerCase().includes(searchTerm)
            );
            
            invoices = filteredInvoices;
            payments = filteredPayments;
            renderInvoicesTable();
            renderPaymentsTable();
        });

        // Initialize charts
        function initializeCharts() {
            // Payment Method Chart
            const paymentMethodCtx = document.getElementById('paymentMethodChart').getContext('2d');
            new Chart(paymentMethodCtx, {
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

            // Revenue Trend Chart
            const revenueTrendCtx = document.getElementById('revenueTrendChart').getContext('2d');
            new Chart(revenueTrendCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Revenue ($)',
                        data: [8500, 10200, 9800, 11000, 12458, 10500],
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
        renderInvoicesTable();
        renderPaymentsTable();
        initializeCharts();
    </script>
</body>
</html>