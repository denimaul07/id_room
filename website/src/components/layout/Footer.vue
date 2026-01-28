<template>
    <footer class="bg-gray-900 text-gray-300 pt-12">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 px-6">
        
        <!-- About -->
        <div>
            <h3 class="text-xl text-white font-bold mb-2">IDROOM</h3>
            <p class="text-sm">
            Helping you find the best property solutions with trusted listings.
            </p>
        </div>

        <!-- Quick Links -->
        <div>
            <h4 class="text-white font-semibold mb-2">Quick Links</h4>
            <ul class="space-y-2 text-sm">
            <li><router-link to="/">Home</router-link></li>
            <li><router-link to="/listing/buy">Buy Property</router-link></li>
            <li><router-link to="/listing/rent">Rent Property</router-link></li>
            <li><router-link to="/about">About Us</router-link></li>
            </ul>
        </div>

        <!-- Contact -->
        <div>
            <h4 class="text-white font-semibold mb-2">Contact</h4>
            <p class="text-sm">123 Main Street, City</p>
            <p class="text-sm">info@idroom.com</p>
            <p class="text-sm">+62 812 xxxx xxxx</p>
        </div>

        <!-- Newsletter -->
        <div>
            <h4 class="text-white font-semibold mb-2">Newsletter</h4>
            <form class="flex gap-2">
            <input
                type="email"
                placeholder="Email address"
                class="px-3 py-2 rounded border focus:ring-2 focus:ring-primary"
            />
            <button class="bg-primary text-white px-3 rounded">
                Subscribe
            </button>
            </form>
        </div>

        </div>

        <div class="text-center text-sm text-gray-500 py-4 border-t border-gray-800">
        © {{ new Date().getFullYear() }} IDROOM. All rights reserved.
        </div>
    </footer>
</template>
