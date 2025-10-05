<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BrokenShire Hotel - Sustainable Luxury</title>
    <script>
        window.appRoutes = {
            login: "{{ route('login') }}",
            register: "{{ route('register') }}",
            dashboard: "{{ Auth::check() ? (Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard')) : route('login') }}"
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>[x-cloak] { display: none !important; }</style>
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
</head>
<body 
    x-data="{ loginOpen: false, registerOpen: false, mobileMenuOpen: false }" 
    class="bg-gray-50"
    @open-login-modal.window="loginOpen = true; registerOpen = false"
    @open-register-modal.window="registerOpen = true; loginOpen = false">

    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="#" class="text-2xl font-bold text-eco-primary flex items-center">
                        <i class="fas fa-leaf mr-2"></i>
                        Brokenshire Hotel
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#rooms" class="text-gray-700 hover:text-eco-primary transition-colors">Rooms</a>
                    <a href="#amenities" class="text-gray-700 hover:text-eco-primary transition-colors">Amenities</a>
                    <a href="#dining" class="text-gray-700 hover:text-eco-primary transition-colors">Dining</a>
                    <a href="#contact" class="text-gray-700 hover:text-eco-primary transition-colors">Contact</a>
                    
                    @guest
                        <button @click="loginOpen = true; mobileMenuOpen = false" 
                                class="text-gray-700 hover:text-eco-primary transition-colors">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </button>   
                        <button @click="registerOpen = true; mobileMenuOpen = false" 
                                class="bg-gradient-to-r from-eco-primary to-eco-accent text-white px-6 py-2 rounded-full hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                            <i class="fas fa-user-plus mr-2"></i>Register
                        </button>
                    @else
                        <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}"
                           class="text-eco-primary hover:text-eco-accent transition-colors">
                            <i class="fas fa-user-circle mr-2"></i>{{ Auth::user()->name }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-eco-primary transition-colors">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    @endguest
                </div>
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 hover:text-eco-primary">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative h-screen">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1587061949409-02df41d5e562?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" 
                 alt="Eco-friendly Hotel Garden" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-30"></div>
        </div>
        <div class="relative h-full flex items-center justify-center text-center px-4">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                    Sustainable Luxury in Harmony with Nature
                </h1>
                <p class="text-xl text-white mb-8">
                    Experience eco-friendly comfort and mindful hospitality at Brokenshire Hotel.
                </p>
                <a href="#rooms" class="bg-gradient-to-r from-eco-primary to-eco-accent text-white px-8 py-4 rounded-full text-lg font-semibold hover:shadow-xl transform hover:-translate-y-1 transition-all">
                    <i class="fas fa-leaf mr-2"></i>
                    Book Your Green Retreat
                </a>
            </div>
        </div>
    </div>

    <!-- Rooms Section -->
    <section id="rooms" class="py-20 bg-eco-cream bg-leaf-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-eco-primary"><i class="fas fa-leaf text-3xl mb-4"></i></span>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Eco-Friendly Accommodations</h2>
                <p class="text-xl text-gray-600">Sustainable luxury meets comfort in our green spaces</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Garden Room -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:-translate-y-1 transition-all duration-300">
                    <img src="{{ asset('images/Brok1.jpg') }}" alt="Garden Room" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-tree text-eco-primary mr-2"></i>
                            <h3 class="text-xl font-bold text-eco-primary">Garden Room</h3>
                        </div>
                        <p class="text-gray-600 mb-4">Sustainable room with private garden view and eco-friendly amenities.</p>
                        <div class="flex items-center mb-4">
                            <span class="bg-eco-light text-eco-dark text-xs px-2 py-1 rounded-full mr-2">Solar Powered</span>
                            <span class="bg-eco-light text-eco-dark text-xs px-2 py-1 rounded-full">Organic Linens</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-eco-primary font-bold">₱620/night</span>
                            <button class="bg-gradient-to-r from-eco-primary to-eco-accent text-white px-4 py-2 rounded-full hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                                Book Now
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Eco Suite -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:-translate-y-1 transition-all duration-300">
                    <img src="{{ asset('images/Brok2.jpg') }}" alt="Eco Suite" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-leaf text-eco-primary mr-2"></i>
                            <h3 class="text-xl font-bold text-eco-primary">Eco Suite</h3>
                        </div>
                        <p class="text-gray-600 mb-4">Luxurious suite with sustainable materials and green balcony.</p>
                        <div class="flex items-center mb-4">
                            <span class="bg-eco-light text-eco-dark text-xs px-2 py-1 rounded-full mr-2">Energy Efficient</span>
                            <span class="bg-eco-light text-eco-dark text-xs px-2 py-1 rounded-full">Recycled Materials</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-eco-primary font-bold">₱800/night</span>
                            <button class="bg-gradient-to-r from-eco-primary to-eco-accent text-white px-4 py-2 rounded-full hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                                Book Now
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Green Villa -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:-translate-y-1 transition-all duration-300">
                    <img src="{{ asset('images/brok3.jpeg') }}" alt="Green Villa" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-seedling text-eco-primary mr-2"></i>
                            <h3 class="text-xl font-bold text-eco-primary">Green Villa</h3>
                        </div>
                        <p class="text-gray-600 mb-4">Premium eco-villa with private garden and sustainable luxury.</p>
                        <div class="flex items-center mb-4">
                            <span class="bg-eco-light text-eco-dark text-xs px-2 py-1 rounded-full mr-2">Zero Waste</span>
                            <span class="bg-eco-light text-eco-dark text-xs px-2 py-1 rounded-full">Natural Materials</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-eco-primary font-bold">₱1,220/night</span>
                            <button class="bg-gradient-to-r from-eco-primary to-eco-accent text-white px-4 py-2 rounded-full hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                                Book Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Amenities Section -->
    <section id="amenities" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-eco-primary"><i class="fas fa-seedling text-3xl mb-4"></i></span>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Eco-Friendly Amenities</h2>
                <p class="text-xl text-gray-600">Sustainable luxury for mindful travelers</p>
            </div>
            <div class="grid md:grid-cols-2 gap-12">
                <div class="space-y-8">
                    <div class="flex items-start p-6 bg-eco-cream rounded-xl transform hover:-translate-y-1 transition-all duration-300">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-eco-primary rounded-full flex items-center justify-center">
                                <i class="fas fa-solar-panel text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-eco-primary mb-2">Solar-Heated Pool</h3>
                            <p class="text-gray-600">Energy-efficient swimming pool with natural filtration system.</p>
                        </div>
                    </div>
                    <div class="flex items-start p-6 bg-eco-cream rounded-xl transform hover:-translate-y-1 transition-all duration-300">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-eco-primary rounded-full flex items-center justify-center">
                                <i class="fas fa-spa text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-eco-primary mb-2">Organic Spa</h3>
                            <p class="text-gray-600">Natural treatments using locally-sourced organic products.</p>
                        </div>
                    </div>
                    <div class="flex items-start p-6 bg-eco-cream rounded-xl transform hover:-translate-y-1 transition-all duration-300">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-eco-primary rounded-full flex items-center justify-center">
                                <i class="fas fa-bicycle text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-eco-primary mb-2">Green Fitness</h3>
                            <p class="text-gray-600">Eco-gym with energy-generating equipment and outdoor yoga.</p>
                        </div>
                    </div>
                    <div class="flex items-start p-6 bg-eco-cream rounded-xl transform hover:-translate-y-1 transition-all duration-300">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-eco-primary rounded-full flex items-center justify-center">
                                <i class="fas fa-recycle text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-eco-primary mb-2">Zero-Waste Program</h3>
                            <p class="text-gray-600">Comprehensive recycling and composting throughout the hotel.</p>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <img src="{{ asset('images/swim.webp') }}" 
                         alt="Eco-friendly Amenities" 
                         class="rounded-t-xl shadow-xl">
                    <div class="bg-white bg-opacity-90 p-4 rounded-b-xl shadow-xl">
                        <div class="flex items-center justify-around text-eco-primary">
                            <div class="text-center">
                                <i class="fas fa-leaf text-2xl mb-2"></i>
                                <p class="text-sm">100% Green Energy</p>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-water text-2xl mb-2"></i>
                                <p class="text-sm">Water Conservation</p>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-seedling text-2xl mb-2"></i>
                                <p class="text-sm">Organic Gardens</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dining Section -->
    <section id="dining" class="py-20 bg-eco-cream bg-leaf-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-eco-primary"><i class="fas fa-utensils text-3xl mb-4"></i></span>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Farm-to-Table Dining</h2>
                <p class="text-xl text-gray-600">Sustainable gastronomy with local flavors</p>
            </div>
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1466637574441-749b8f19452f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" 
                         alt="Organic Restaurant" 
                         class="rounded-xl shadow-xl">
                    <div class="absolute -bottom-6 -right-6 bg-white p-4 rounded-xl shadow-lg">
                        <div class="flex items-center space-x-4">
                            <img src="https://images.unsplash.com/photo-1607631568010-a87245c0daf8?ixlib=rb-1.2.1&auto=format&fit=crop&w=100&q=80"
                                 alt="Organic Produce"
                                 class="w-16 h-16 rounded-full object-cover">
                            <div>
                                <p class="text-eco-primary font-bold">Farm Fresh</p>
                                <p class="text-sm text-gray-600">Daily harvested ingredients</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-lg">
                    <h3 class="text-2xl font-bold text-eco-primary mb-4">The Garden Restaurant</h3>
                    <p class="text-gray-600 mb-6">
                        Savor organic, locally-sourced cuisine prepared by our expert chefs. 
                        Our menu changes with the seasons, ensuring the freshest ingredients 
                        from our own garden and local sustainable farms.
                    </p>
                    <div class="space-y-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-clock text-eco-primary mr-2"></i>
                            <div>
                                <p class="font-semibold">Opening Hours:</p>
                                <p class="text-gray-600">
                                    Breakfast: 6:30 AM - 10:30 AM<br>
                                    Lunch: 12:00 PM - 3:00 PM<br>
                                    Dinner: 6:00 PM - 10:00 PM
                                </p>
                            </div>
                        </div>
                        <div class="flex space-x-4">
                            <span class="bg-eco-light text-eco-dark px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-leaf mr-1"></i> Organic
                            </span>
                            <span class="bg-eco-light text-eco-dark px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-carrot mr-1"></i> Vegan Options
                            </span>
                            <span class="bg-eco-light text-eco-dark px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-seedling mr-1"></i> Local Produce
                            </span>
                        </div>
                    </div>
                    <button class="bg-gradient-to-r from-eco-primary to-eco-accent text-white px-6 py-3 rounded-full hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Reserve a Table
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-eco-primary"><i class="fas fa-envelope text-3xl mb-4"></i></span>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Get in Touch</h2>
                <p class="text-xl text-gray-600">We're committed to sustainable hospitality</p>
            </div>
            <div class="grid md:grid-cols-2 gap-12">
                <div class="bg-eco-cream p-8 rounded-xl shadow-lg">
                    <form class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" id="name" name="name" 
                                   class="mt-1 block w-full rounded-full border-gray-300 shadow-sm focus:border-eco-primary focus:ring-eco-primary">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" name="email" 
                                   class="mt-1 block w-full rounded-full border-gray-300 shadow-sm focus:border-eco-primary focus:ring-eco-primary">
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                            <textarea id="message" name="message" rows="4" 
                                      class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-eco-primary focus:ring-eco-primary"></textarea>
                        </div>
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-eco-primary to-eco-accent text-white px-6 py-3 rounded-full hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Send Message
                        </button>
                    </form>
                </div>
                <div class="space-y-8">
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-eco-primary rounded-full flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-eco-primary mb-2">Our Location</h3>
                                <p class="text-gray-600"> Poblacion District, Davao City,<br>Davao del Sur</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-eco-primary rounded-full flex items-center justify-center">
                                <i class="fas fa-phone-alt text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-eco-primary mb-2">Contact Info</h3>
                                <p class="text-gray-600">Phone: (082) 221 0487<br>Email: Brokenshire04@gmail.com</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-eco-primary rounded-full flex items-center justify-center">
                                <i class="fas fa-share-alt text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-eco-primary mb-2">Connect With Us</h3>
                                <div class="flex space-x-4 mt-2">
                                    <a href="#" class="w-10 h-10 bg-eco-light rounded-full flex items-center justify-center text-eco-primary hover:bg-eco-primary hover:text-white transition-colors">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="#" class="w-10 h-10 bg-eco-light rounded-full flex items-center justify-center text-eco-primary hover:bg-eco-primary hover:text-white transition-colors">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                    <a href="#" class="w-10 h-10 bg-eco-light rounded-full flex items-center justify-center text-eco-primary hover:bg-eco-primary hover:text-white transition-colors">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-eco-dark text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <i class="fas fa-leaf text-eco-light text-2xl mr-2"></i>
                        <h3 class="text-xl font-bold">BrokenShire Hotel</h3>
                    </div>
                    <p class="text-gray-300">Sustainable luxury in harmony with nature.</p>
                    <div class="mt-4 flex space-x-2">
                        <span class="bg-eco-primary bg-opacity-20 text-eco-light text-xs px-2 py-1 rounded-full">
                            <i class="fas fa-star text-xs mr-1"></i> Eco-Certified
                        </span>
                        <span class="bg-eco-primary bg-opacity-20 text-eco-light text-xs px-2 py-1 rounded-full">
                            <i class="fas fa-leaf text-xs mr-1"></i> Carbon Neutral
                        </span>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-link text-eco-light mr-2"></i>
                        Quick Links
                    </h4>
                    <ul class="space-y-2">
                        <li>
                            <a href="#rooms" class="text-gray-300 hover:text-white flex items-center">
                                <i class="fas fa-chevron-right text-eco-light text-xs mr-2"></i>
                                Eco Rooms
                            </a>
                        </li>
                        <li>
                            <a href="#amenities" class="text-gray-300 hover:text-white flex items-center">
                                <i class="fas fa-chevron-right text-eco-light text-xs mr-2"></i>
                                Green Amenities
                            </a>
                        </li>
                        <li>
                            <a href="#dining" class="text-gray-300 hover:text-white flex items-center">
                                <i class="fas fa-chevron-right text-eco-light text-xs mr-2"></i>
                                Farm-to-Table Dining
                            </a>
                        </li>
                        <li>
                            <a href="#contact" class="text-gray-300 hover:text-white flex items-center">
                                <i class="fas fa-chevron-right text-eco-light text-xs mr-2"></i>
                                Contact
                            </a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-clock text-eco-light mr-2"></i>
                        Hours
                    </h4>
                    <ul class="space-y-2 text-gray-300">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-eco-light mt-1 mr-2"></i>
                            <span>Check-in: 9:00 AM</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-eco-light mt-1 mr-2"></i>
                            <span>Check-out: 8:00 AM</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-eco-light mt-1 mr-2"></i>
                            <span>24/7 Eco-Conscious Service</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-envelope text-eco-light mr-2"></i>
                        Green Newsletter
                    </h4>
                    <p class="text-sm text-gray-300 mb-4">Stay updated with our eco-initiatives and special offers.</p>
                    <form class="space-y-4">
                        <div class="relative">
                            <input type="email" placeholder="Enter your email" 
                                   class="w-full px-4 py-2 rounded-full bg-white bg-opacity-10 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-eco-light pr-12">
                            <button type="submit" 
                                    class="absolute right-1 top-1 bg-gradient-to-r from-eco-primary to-eco-accent text-white p-2 rounded-full hover:shadow-lg transition-all">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center">
                <p class="text-gray-300">&copy; 2002 Brokenshire Hotel. Creating a sustainable future in hospitality.</p>
                <div class="mt-4 flex justify-center space-x-6">
                    <img src="{{ asset ('images/eco-bg.png') }}" alt="Eco Certification" class="h-12 opacity-50 hover:opacity-100 transition-opacity">
                    <img src="{{ asset('images/green-cert.png')}}" alt="Green Hotel" class="h-12 opacity-50 hover:opacity-100 transition-opacity">
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="fixed top-16 inset-x-0 p-2 md:hidden z-50"
        x-cloak>
        <div class="rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 overflow-hidden">
            <div class="px-5 pt-4 flex items-center justify-between">
                <div class="h-8"></div>
                <div class="-mr-2">
                    <button @click="mobileMenuOpen = false" 
                            class="rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                        <span class="sr-only">Close menu</span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="#rooms" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-eco-primary hover:bg-gray-50">Rooms</a>
                <a href="#amenities" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-eco-primary hover:bg-gray-50">Amenities</a>
                <a href="#dining" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-eco-primary hover:bg-gray-50">Dining</a>
                <a href="#contact" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-eco-primary hover:bg-gray-50">Contact</a>

                @guest
                    <button @click="loginOpen = true; mobileMenuOpen = false" 
                            class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-eco-primary hover:text-eco-accent">
                        Sign In
                    </button>
                    <button @click="registerOpen = true; mobileMenuOpen = false"
                            class="block w-full px-3 py-2 rounded-md text-base font-medium bg-gradient-to-r from-eco-primary to-eco-accent text-white">
                        Create Account
                    </button>
                @else
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium text-eco-primary hover:text-eco-accent">
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-eco-primary">
                            Logout
                        </button>
                    </form>
                @endguest
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <div x-show="loginOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         @keydown.escape.window="loginOpen = false">
        
        <div class="fixed inset-0 bg-black bg-opacity-40"
             x-show="loginOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="loginOpen = false"></div>

        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden w-full max-w-md mx-auto"
                 x-show="loginOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 @click.stop>

                <button @click="loginOpen = false" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 z-10">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <div class="p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900">Welcome Back</h2>
                        <p class="text-gray-600 mt-2">Sign in to your eco-friendly account</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="login-email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input id="login-email" type="email" name="email" required autofocus
                                   class="mt-1 block w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-eco-primary focus:ring focus:ring-eco-primary/20 transition-shadow">
                        </div>

                        <div>
                            <label for="login-password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input id="login-password" type="password" name="password" required
                                   class="mt-1 block w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-eco-primary focus:ring focus:ring-eco-primary/20 transition-shadow">
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <input type="checkbox" name="remember" class="rounded border-gray-300 text-eco-primary focus:ring-eco-primary">
                                <span class="ml-2 text-sm text-gray-600">Remember me</span>
                            </label>

                            <a href="#" class="text-sm text-eco-primary hover:text-eco-accent">
                                Forgot password?
                            </a>
                        </div>

                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-eco-primary to-eco-accent text-white px-8 py-4 rounded-xl text-lg font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-eco-primary focus:ring-opacity-50">
                            Sign in
                        </button>

                        <p class="text-center text-sm text-gray-600 mt-4">
                            Don't have an account?
                            <a href="#" @click.prevent="loginOpen = false; registerOpen = true" class="text-eco-primary hover:text-eco-accent font-medium">
                                Create account
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div x-show="registerOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         @keydown.escape.window="registerOpen = false">
        
        <div class="fixed inset-0 bg-black bg-opacity-40"
             x-show="registerOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="registerOpen = false"></div>

        <div class="relative min-h-screen flex items-center justify-center p-6">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-3xl mx-auto flex flex-col md:flex-row"
                 x-show="registerOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 @click.stop>

                <button @click="registerOpen = false" 
                        class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 z-10">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Left Side Illustration -->
                <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-eco-primary/10 to-eco-accent/10 items-center justify-center p-6">
                    <div class="text-center space-y-3">
                        <h2 class="text-3xl font-bold text-eco-primary">Join Us!</h2>
                        <p class="text-gray-600">Be part of our eco-friendly community 🌱</p>
                        <svg class="mx-auto w-32 h-32 text-eco-primary/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                  d="M12 3v18m9-9H3" />
                        </svg>
                    </div>
                </div>

                <!-- Registration Form -->
                <div class="p-8 md:w-1/2">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Create Account</h2>
                        <p class="text-gray-600 mt-1">Fill in your details below</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="register-name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input id="register-name" type="text" name="name" required autofocus
                                       class="mt-1 block w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-eco-primary focus:ring focus:ring-eco-primary/20 transition-shadow">
                            </div>

                            <div>
                                <label for="register-phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input id="register-phone" type="tel" name="phone" required
                                       class="mt-1 block w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-eco-primary focus:ring focus:ring-eco-primary/20 transition-shadow">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="register-email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input id="register-email" type="email" name="email" required
                                       class="mt-1 block w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-eco-primary focus:ring focus:ring-eco-primary/20 transition-shadow">
                            </div>

                            <div>
                                <label for="register-password" class="block text-sm font-medium text-gray-700">Password</label>
                                <input id="register-password" type="password" name="password" required
                                       class="mt-1 block w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-eco-primary focus:ring focus:ring-eco-primary/20 transition-shadow">
                            </div>
                        </div>

                        <div>
                            <label for="register-password-confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input id="register-password-confirmation" type="password" name="password_confirmation" required
                                   class="mt-1 block w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-eco-primary focus:ring focus:ring-eco-primary/20 transition-shadow">
                        </div>

                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-eco-primary to-eco-accent text-white px-8 py-4 rounded-xl text-lg font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-eco-primary focus:ring-opacity-50">
                            Register
                        </button>

                        <p class="text-center text-sm text-gray-600 mt-4">
                            Already have an account?
                            <a href="#" @click.prevent="registerOpen = false; loginOpen = true" 
                               class="text-eco-primary hover:text-eco-accent font-medium">
                                Sign in
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>