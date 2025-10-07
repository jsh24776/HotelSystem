<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrokenShire Admin - User Profile</title>
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
                    }
                }
            }
        }
    </script>
    <style>
        .leaf-bg {
            background-image: url('data:image/svg+xml,%3Csvg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org2000/svg"%3E%3Cpath d="M0 0h20v20H0V0zm10 17c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm0-1c3.314 0 6-2.686 6-6s-2.686-6-6-6-6 2.686-6 6 2.686 6 6 6z" fill="%234CAF50" fill-opacity="0.05" fill-rule="evenodd"/%3E%3C/svg%3E');
        }
        .reservation-confirmed { background-color: #d1fae5; color: #065f46; }
        .reservation-checked-in { background-color: #dbeafe; color: #1e40af; }
        .reservation-checked-out { background-color: #e5e7eb; color: #374151; }
        .reservation-cancelled { background-color: #fee2e2; color: #991b1b; }
    </style>
</head>
<body class="bg-eco-cream text-gray-800">
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
                                <i class="fas fa-users mr-3"></i> Guests
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
                    <h1 class="text-3xl font-bold text-eco-dark">Guest Profile</h1>
                    <p class="text-gray-600">Guest stay history and details</p>
                </div>
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-eco-primary text-white px-4 py-2 rounded-lg hover:bg-eco-dark transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Users
                </a>
            </div>
        </header>

        <!-- User Profile Card -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex items-center space-x-6">
                <div class="w-20 h-20 rounded-full bg-eco-light flex items-center justify-center text-eco-dark font-bold text-2xl">
                    {{ substr($user->name, 0, 2) }}
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-eco-dark">{{ $user->name }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-3">
                        <div>
                            <p class="text-gray-500">Email</p>
                            <p class="font-medium">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Phone</p>
                            <p class="font-medium">{{ $user->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Member Since</p>
                            <p class="font-medium">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stay History Section -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-bold text-eco-dark">Stay History</h2>
                <div class="flex space-x-2">
                    <button id="refreshStayHistory" class="text-eco-primary hover:text-eco-dark p-2 rounded-lg hover:bg-eco-cream transition-colors">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div id="stayStats" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-6 bg-gray-50">
                <!-- Stats will be loaded via JavaScript -->
            </div>

            <!-- Stay History Table -->
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 text-left text-gray-500">
                                <th class="py-3 px-6 font-medium">Reservation ID</th>
                                <th class="py-3 px-6 font-medium">Room</th>
                                <th class="py-3 px-6 font-medium">Check-In</th>
                                <th class="py-3 px-6 font-medium">Check-Out</th>
                                <th class="py-3 px-6 font-medium">Duration</th>
                                <th class="py-3 px-6 font-medium">Guests</th>
                                <th class="py-3 px-6 font-medium">Amount</th>
                                <th class="py-3 px-6 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody id="stayHistoryTableBody" class="divide-y divide-gray-200">
                            <!-- Data will be loaded via JavaScript -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div id="emptyStayHistory" class="text-center py-12 hidden">
                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-500">No Stay History</h3>
                    <p class="text-gray-400 mt-2">This guest hasn't made any reservations yet.</p>
                </div>
            </div>
        </div>
    </main>

    <script>
        const userId = {{ $user->id }};

        // Load stay history
        function loadStayHistory() {
            fetch(`/admin/users/${userId}/stay-history`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    renderStayStats(data.stats);
                    renderStayHistoryTable(data.reservations);
                })
                .catch(error => {
                    console.error('Error fetching stay history:', error);
                    document.getElementById('stayHistoryTableBody').innerHTML = `
                        <tr>
                            <td colspan="8" class="py-4 px-6 text-center text-gray-500">
                                Error loading stay history
                            </td>
                        </tr>
                    `;
                });
        }

        function renderStayStats(stats) {
            const statsContainer = document.getElementById('stayStats');
            statsContainer.innerHTML = `
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500">Total Stays</p>
                            <h3 class="text-2xl font-bold text-eco-dark">${stats.total_stays}</h3>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-calendar-check text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500">Total Nights</p>
                            <h3 class="text-2xl font-bold text-eco-dark">${stats.total_nights}</h3>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-moon text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500">Total Spent</p>
                            <h3 class="text-2xl font-bold text-eco-dark">₱${parseFloat(stats.total_spent).toLocaleString()}</h3>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-peso-sign text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500">Avg. Stay</p>
                            <h3 class="text-2xl font-bold text-eco-dark">${stats.average_stay} nights</h3>
                        </div>
                        <div class="bg-eco-light bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-chart-line text-eco-accent text-xl"></i>
                        </div>
                    </div>
                </div>
            `;
        }

        function renderStayHistoryTable(reservations) {
            const tableBody = document.getElementById('stayHistoryTableBody');
            const emptyState = document.getElementById('emptyStayHistory');

            if (!reservations || reservations.length === 0) {
                tableBody.innerHTML = '';
                emptyState.classList.remove('hidden');
                return;
            }

            emptyState.classList.add('hidden');
            tableBody.innerHTML = '';

            reservations.forEach(reservation => {
                const statusClass = `reservation-${reservation.status.toLowerCase()}`;
                const row = document.createElement('tr');
                row.className = 'hover:bg-eco-cream transition-colors';
                row.innerHTML = `
                    <td class="py-4 px-6 font-medium">${reservation.reservation_id}</td>
                    <td class="py-4 px-6">
                        <div>
                            <div class="font-medium">Room ${reservation.room_number}</div>
                            <div class="text-sm text-gray-500">${reservation.room_type}</div>
                        </div>
                    </td>
                    <td class="py-4 px-6">${formatDate(reservation.check_in_date)}</td>
                    <td class="py-4 px-6">${formatDate(reservation.check_out_date)}</td>
                    <td class="py-4 px-6">${reservation.stay_duration} night${reservation.stay_duration !== 1 ? 's' : ''}</td>
                    <td class="py-4 px-6">${reservation.guest_count}</td>
                    <td class="py-4 px-6 font-bold">₱${parseFloat(reservation.total_amount).toLocaleString()}</td>
                    <td class="py-4 px-6">
                        <span class="px-3 py-1 rounded-full text-xs font-medium ${statusClass}">
                            ${reservation.status}
                        </span>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }

        document.getElementById('refreshStayHistory').addEventListener('click', loadStayHistory);

      
        document.addEventListener('DOMContentLoaded', loadStayHistory);
    </script>
</body>
</html>