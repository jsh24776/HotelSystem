
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrokenShire Admin Dashboard</title>
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
                <h1 class="text-2xl font-bold text-eco-dark flex items-center">
                    <i class="fas fa-leaf text-eco-accent mr-2"></i>
                     Admin
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
                    <li>
                        <a href="#" class="sidebar-item flex items-center py-3 px-6 text-gray-600">
                            <i class="fas fa-boxes mr-3"></i>
                            Inventory
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-item flex items-center py-3 px-6 text-gray-600">
                            <i class="fas fa-calendar-check mr-3"></i>
                            Current Bookings
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-item flex items-center py-3 px-6 text-gray-600">
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
                            JD
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold">John Doe</p>
                            <p class="text-sm text-gray-500">Administrator</p>
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
                        <h1 class="text-3xl font-bold text-eco-dark">Dashboard</h1>
                        <p class="text-gray-600">Welcome back, John. Here's what's happening today.</p>
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
                                    JD
                                </div>
                                <span class="hidden md:block text-gray-700">John Doe</span>
                                <i class="fas fa-chevron-down text-gray-500"></i>
                            </button>
                            
                            <div class="dropdown-content mt-2">
                                <a href="/profile" class="block px-4 py-3 text-gray-700 hover:bg-eco-cream border-b border-gray-100">
                                    <i class="fas fa-user mr-2"></i>My Profile
                                </a>
                                <a href="/settings" class="block px-4 py-3 text-gray-700 hover:bg-eco-cream border-b border-gray-100">
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
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500">Total Revenue</p>
                            <h3 class="text-2xl font-bold text-eco-dark">₱120,293</h3>
                            <p class="text-green-600 text-sm mt-1"><i class="fas fa-arrow-up mr-1"></i> 12.5% from last month</p>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-dollar-sign text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500">Active Users</p>
                            <h3 class="text-2xl font-bold text-eco-dark">1,248</h3>
                            <p class="text-green-600 text-sm mt-1"><i class="fas fa-arrow-up mr-1"></i> 8.2% from last month</p>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-users text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500">New Bookings</p>
                            <h3 class="text-2xl font-bold text-eco-dark">86</h3>
                            <p class="text-green-600 text-sm mt-1"><i class="fas fa-arrow-up mr-1"></i> 5.3% from last week</p>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-calendar-check text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500">Inventory Items</p>
                            <h3 class="text-2xl font-bold text-eco-dark">542</h3>
                            <p class="text-red-600 text-sm mt-1"><i class="fas fa-arrow-down mr-1"></i> 3.1% from last month</p>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-boxes text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-eco-dark">Revenue Overview</h2>
                        <div class="flex space-x-2">
                            <button class="time-filter-btn active px-3 py-1 bg-eco-cream text-eco-dark rounded-lg text-sm">Monthly</button>
                            <button class="time-filter-btn px-3 py-1 text-gray-500 rounded-lg text-sm">Quarterly</button>
                            <button class="time-filter-btn px-3 py-1 text-gray-500 rounded-lg text-sm">Yearly</button>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-eco-dark">Recent Bookings</h2>
                        <button class="text-eco-accent text-sm font-medium">View All</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-gray-500 border-b">
                                    <th class="pb-3">Customer</th>
                                    <th class="pb-3">Date</th>
                                    <th class="pb-3">Status</th>
                                    <th class="pb-3">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-100">
                                    <td class="py-3">Earl Jonas Tigbo</td>
                                    <td class="py-3">Jun 12, 2023</td>
                                    <td class="py-3"><span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Confirmed</span></td>
                                    <td class="py-3 font-medium">₱600</td>
                                </tr>
                                <tr class="border-b border-gray-100">
                                    <td class="py-3">Elyze Seplon</td>
                                    <td class="py-3">Jun 11, 2023</td>
                                    <td class="py-3"><span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span></td>
                                    <td class="py-3 font-medium">₱1,200</td>
                                </tr>
                                <tr class="border-b border-gray-100">
                                    <td class="py-3">Joshua Regidor</td>
                                    <td class="py-3">Jun 10, 2023</td>
                                    <td class="py-3"><span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Confirmed</span></td>
                                    <td class="py-3 font-medium">₱800</td>
                                </tr>
                                <tr class="border-b border-gray-100">
                                    <td class="py-3">Chris Brown</td>
                                    <td class="py-3">Jun 9, 2023</td>
                                    <td class="py-3"><span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Cancelled</span></td>
                                    <td class="py-3 font-medium">₱1,220</td>
                                </tr>
                                <tr>
                                    <td class="py-3">Kai Cenat</td>
                                    <td class="py-3">Jun 8, 2023</td>
                                    <td class="py-3"><span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Confirmed</span></td>
                                    <td class="py-3 font-medium">₱1,220</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Inventory and User Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Low Stock Items -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-eco-dark">Low Stock Items</h2>
                        <button class="text-eco-accent text-sm font-medium">Manage Inventory</button>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                            <div>
                                <p class="font-medium">Organic Bamboo Towels</p>
                                <p class="text-sm text-gray-500">Only 12 left in stock</p>
                            </div>
                            <button class="reorder-btn px-3 py-1 bg-eco-primary text-white rounded-lg text-sm">Reorder</button>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                            <div>
                                <p class="font-medium">Eco-Friendly Shampoo</p>
                                <p class="text-sm text-gray-500">Only 23 left in stock</p>
                            </div>
                            <button class="reorder-btn px-3 py-1 bg-eco-primary text-white rounded-lg text-sm">Reorder</button>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                            <div>
                                <p class="font-medium">Recycled Paper Products</p>
                                <p class="text-sm text-gray-500">Only 18 left in stock</p>
                            </div>
                            <button class="reorder-btn px-3 py-1 bg-eco-primary text-white rounded-lg text-sm">Reorder</button>
                        </div>
                    </div>
                </div>
                
                <!-- User Activity -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-eco-dark">Recent User Activity</h2>
                        <button class="text-eco-accent text-sm font-medium">View All</button>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-eco-light flex items-center justify-center text-eco-dark font-bold mr-3">
                                AJ
                            </div>
                            <div>
                                <p class="font-medium">Earl Jonas Tigno made a new booking</p>
                                <p class="text-sm text-gray-500">2 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-eco-light flex items-center justify-center text-eco-dark font-bold mr-3">
                                SW
                            </div>
                            <div>
                                <p class="font-medium">Jade Alberca updated her profile</p>
                                <p class="text-sm text-gray-500">5 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-eco-light flex items-center justify-center text-eco-dark font-bold mr-3">
                                MB
                            </div>
                            <div>
                                <p class="font-medium">Kai Cenat cancelled a booking</p>
                                <p class="text-sm text-gray-500">Yesterday</p>
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
        
     
        document.querySelectorAll('.sidebar-item').forEach(item => {
            item.addEventListener('click', function(e) {
            document.querySelectorAll('.sidebar-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
                
                // Update page title based on selection
                const pageTitle = this.textContent.trim();
                document.querySelector('main header h1').textContent = pageTitle;
                document.querySelector('main header p').textContent = `Manage your ${pageTitle.toLowerCase()} here.`;
                
                // Close mobile menu after selection
                if (window.innerWidth < 768) {
                    document.getElementById('sidebar').classList.remove('open');
                    document.getElementById('overlay').classList.remove('active');
                }
            });
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
        
        
        document.querySelectorAll('.time-filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.time-filter-btn').forEach(b => {
                    b.classList.remove('active', 'bg-eco-cream', 'text-eco-dark');
                    b.classList.add('text-gray-500');
                });
                this.classList.add('active', 'bg-eco-cream', 'text-eco-dark');
                this.classList.remove('text-gray-500');
                
                
                console.log(`Filter changed to: ${this.textContent}`);
            });
        });
        
        
        document.querySelectorAll('.reorder-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const productName = this.closest('.flex.justify-between').querySelector('.font-medium').textContent;
                alert(`Reorder request sent for: ${productName}`);
            });
        });
        
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue ($)',
                    data: [97897, 100218, 109123, 115050, 100980, 123580],
                    borderColor: '#2E7D32',
                    backgroundColor: 'rgba(46, 125, 50, 0.1)',
                    borderWidth: 2, 
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Make all buttons interactive
        document.querySelectorAll('button').forEach(button => {
            // Skip buttons that already have event listeners
            if (!button.id && !button.classList.contains('reorder-btn') && !button.classList.contains('time-filter-btn')) {
                button.addEventListener('click', function() {
                    // Visual feedback
                    this.style.transform = 'scale(0.98)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                    
                    // Simple alert for demonstration
                    const buttonText = this.textContent.trim();
                    if (buttonText && !this.closest('form')) {
                        alert(`"${buttonText}" button clicked!`);
                    }
                });
            }
        });
        
document.querySelectorAll('.sidebar-item').forEach(item => {
    item.addEventListener('click', function() {
        // Just for UI feedback, don't prevent navigation
        document.querySelectorAll('.sidebar-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');

        // Optional: update main header text dynamically
        const sectionName = this.textContent.trim();
        document.querySelector('main header h1').textContent = sectionName;
        document.querySelector('main header p').textContent = `Manage your ${sectionName.toLowerCase()} here.`;
    });
});
         
    </script>
</body>
</html>