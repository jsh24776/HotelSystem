<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrokenShire Admin - User Management</title>
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
                <h1 class="text-2xl font-bold text-eco-dark flex items-center space-x-2">
                    <i class="fas fa-leaf text-eco-primary"></i>
                    <span>BrokenShire</span>
                </h1>
                <p class="text-sm text-gray-500 mt-1">Users Management</p>
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
                          Ad
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold">Admin</p>
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
                        <h1 class="text-3xl font-bold text-eco-dark">Users</h1>
                        <p class="text-gray-600">Manage all users in the system</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Search users..." class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-accent focus:border-transparent">
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
                                   Ad
                                </div>
                                <span class="hidden md:block text-gray-700">Admin</span>
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
            
            <!-- User Management Content -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-eco-dark">All Guests</h2>
                
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 text-left text-gray-500">
                                <th class="py-3 px-6 font-medium">Guest</th>
                                <th class="py-3 px-6 font-medium">Email</th>
                                <th class="py-3 px-6 font-medium">Phone</th>
                             
                                <th class="py-3 px-6 font-medium">Status</th>
                               
                                <th class="py-3 px-6 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($users as $user)
                            <tr class="hover:bg-eco-cream transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-eco-light flex items-center justify-center text-eco-dark font-bold mr-3">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                            <p class="text-sm text-gray-500">ID: {{ $user->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">{{ $user->email }}</td>
                                <td class="py-4 px-6">{{ $user->phone ?? 'N/A' }}</td>
                                
                                <td class="py-4 px-6">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium 
                                        {{ $user->status == 'check-in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                             
                                <td class="py-4 px-6">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                           class="text-eco-accent hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors"
                                           title="Edit User">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                      <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline archive-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="text-yellow-500 hover:text-yellow-700 p-2 rounded-lg hover:bg-yellow-50 transition-colors archive-btn"
                                                        title="Archive Guest"
                                                        data-user-name="{{ $user->name }}">
                                                    <i class="fas fa-archive"></i>
                                                </button>
                                            </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($users->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-500">No users found</h3>
                    <p class="text-gray-400 mt-2">Get started by adding your first user</p>
                    <a href="{{ route('admin.users.create') }}" class="inline-block mt-4 bg-eco-primary text-white px-6 py-2 rounded-lg hover:bg-eco-dark transition-colors">
                        Add New User
                    </a>
                </div>
                @endif
            </div>
            
            <!-- User Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500">Total Users</p>
                            <h3 class="text-2xl font-bold text-eco-dark">{{ $users->count() }}</h3>
                            <p class="text-green-600 text-sm mt-1"><i class="fas fa-arrow-up mr-1"></i> 12.5% from last month</p>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-users text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500">Active Users</p>
                            <h3 class="text-2xl font-bold text-eco-dark">{{ $users->where('status', 'check-in')->count() }}</h3>
                            <p class="text-green-600 text-sm mt-1"><i class="fas fa-arrow-up mr-1"></i> 8.2% from last month</p>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-user-check text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500">Admin Users</p>
                            <h3 class="text-2xl font-bold text-eco-dark">{{ $users->where('role', 'admin')->count() }}</h3>
                            <p class="text-green-600 text-sm mt-1"><i class="fas fa-arrow-up mr-1"></i> 2 new this month</p>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-shield-alt text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card bg-white rounded-xl shadow-md p-6 leaf-bg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500">New This Month</p>
                            <h3 class="text-2xl font-bold text-eco-dark">
                                {{ $users->where('created_at', '>=', now()->startOfMonth())->count() }}
                            </h3>
                            <p class="text-green-600 text-sm mt-1"><i class="fas fa-arrow-up mr-1"></i> 5.3% from last month</p>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-user-plus text-eco-accent text-xl"></i>
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
  
        // Search functionality
        const searchInput = document.querySelector('input[type="text"]');
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

  
document.addEventListener('DOMContentLoaded', function() {
    const archiveButtons = document.querySelectorAll('.archive-btn');
    
    archiveButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const form = this.closest('.archive-form');
            const userName = this.getAttribute('data-user-name');
            
          
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center mr-4">
                            <i class="fas fa-archive text-yellow-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Archive User</h3>
                            <p class="text-gray-600">Are you sure you want to archive the user <strong>"${userName}"</strong>? They will be hidden from the active users list but can be restored later.</p>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button id="cancelArchive" class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                        <button id="confirmArchive" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                            Archive User
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Handle cancel
            modal.querySelector('#cancelArchive').addEventListener('click', function() {
                document.body.removeChild(modal);
            });
            
            // Handle confirm archive
            modal.querySelector('#confirmArchive').addEventListener('click', function() {
                form.submit();
            });
            
            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    document.body.removeChild(modal);
                }
            });
        });
    });
});


        
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[type="text"]');
            
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();
                    const rows = document.querySelectorAll('tbody tr');
                    
                    rows.forEach(row => {
                        const name = row.querySelector('.font-medium')?.textContent.toLowerCase() || '';
                        const email = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
                        const phone = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
                        
                        if (name.includes(searchTerm) || email.includes(searchTerm) || phone.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>