<template>
    <!-- Page Header -->
    <section
        class="relative flex items-center bg-center bg-cover transition-opacity duration-700
            h-[200px] md:h-[250px] lg:h-[230px]"
        :class="loaded ? 'opacity-100' : 'opacity-0'"
        :style="loaded ? { backgroundImage: `url(${heroImage})` } : {}"
        >
        <!-- Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-black mb-2">
            Hubungi Kami
            </h1>

            <p class="text-black/80 text-sm">
            <router-link to="/" class="hover:underline">Home</router-link>
            <span class="mx-2">›</span> Hubungi Kami
            </p>
        </div>
    </section>

    <section class="bg-[#f7f8fa] py-16">
        <div class="max-w-7xl mx-auto px-4 flex flex-col lg:flex-row gap-10 items-center">
            <!-- Left: Cards -->
            <div class="flex-1 flex flex-col gap-6 min-w-[320px]">
                <div class="bg-white rounded-xl shadow p-8">
                    <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">Talk to Member of Sales Team</h2>
                    <p class="text-gray-700 mb-3">Connect with our expert sales team for personalized guidance, property insights, and support tailored to your real estate needs.</p>
                    <div class="font-bold text-base text-gray-900">Toll Free : <span class="font-extrabold">888 634-5891</span></div>
                </div>
                <div class="bg-white rounded-xl shadow p-8">
                    <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">Product &amp; Account Support</h2>
                    <p class="text-gray-700 mb-4">Get dedicated help with your account, features, and services through our expert Product &amp; Account Support team today.</p>
                    <button class="px-5 py-2 bg-black text-white font-semibold rounded-lg shadow hover:bg-gray-800 transition">Go to FAQ</button>
                </div>
            </div>
            <!-- Right: Image -->
            <div class="flex-1 flex items-center justify-center min-w-[320px]">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden w-full max-w-xl h-[340px] flex items-center justify-center">
                    <img :src="contact" alt="Office Building" class="w-full h-full object-cover" />
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 mt-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white rounded-xl shadow p-6 flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full border-2 border-teal-200">
                    <svg class="w-7 h-7 text-teal-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16v16H4z" fill="#fff"/><path d="M4 4l8 8 8-8" stroke="#2dd4bf" stroke-width="2"/><path d="M4 20V4h16v16z" stroke="#2dd4bf" stroke-width="2"/></svg>
                </div>
                <div>
                    <div class="font-bold text-lg text-gray-900 mb-1">Email Address</div>
                    <div class="text-gray-700 text-base">info@example.com<br/>corporate@example.com</div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full border-2 border-teal-200">
                    <svg class="w-7 h-7 text-teal-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92V19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V16.92a2 2 0 0 1 .76-1.57l7-5.44a2 2 0 0 1 2.48 0l7 5.44a2 2 0 0 1 .76 1.57z"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div>
                    <div class="font-bold text-lg text-gray-900 mb-1">Phone Number</div>
                    <div class="text-gray-700 text-base">+81649 48103<br/>+78301 71940</div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full border-2 border-teal-200">
                    <svg class="w-7 h-7 text-teal-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12c0 5.52 4.48 10 10 10s10-4.48 10-10c0-5.52-4.48-10-10-10zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-3.31 0-6 2.69-6 6 0 2.21 1.2 4.15 3 5.19V17h6v-1.81c1.8-1.04 3-2.98 3-5.19 0-3.31-2.69-6-6-6z"/></svg>
                </div>
                <div>
                    <div class="font-bold text-lg text-gray-900 mb-1">Address</div>
                    <div class="text-gray-700 text-base">509 Rosewood Drive, San Francisco, California</div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="overflow-hidden flex items-center justify-center">
                <img :src="contact2" alt="Office Building" class="w-full object-cover" />
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-8 flex flex-col justify-center">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-6">Get In Touch</h2>
                <form class="space-y-4">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <label class="block text-gray-700 font-semibold mb-1">Your Name</label>
                            <input type="text" class="w-full rounded-md bg-gray-50 border border-gray-200 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400" />
                        </div>
                        <div class="flex-1">
                            <label class="block text-gray-700 font-semibold mb-1">Email</label>
                            <input type="email" class="w-full rounded-md bg-gray-50 border border-gray-200 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400" />
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <label class="block text-gray-700 font-semibold mb-1">Phone Number</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 bg-gray-100 border border-r-0 border-gray-200 rounded-l-md text-gray-500">🇺🇸</span>
                                <input type="text" class="w-full rounded-r-md bg-gray-50 border border-gray-200 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400" placeholder="(201) 555-0123" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <label class="block text-gray-700 font-semibold mb-1">Subject</label>
                            <input type="text" class="w-full rounded-md bg-gray-50 border border-gray-200 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400" />
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <label class="block text-gray-700 font-semibold mb-1">Country</label>
                            <select class="w-full rounded-md bg-gray-50 border border-gray-200 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400">
                                <option>Select</option>
                                <option>Indonesia</option>
                                <option>Singapore</option>
                                <option>Malaysia</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div class="flex-1"></div>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Description</label>
                        <textarea class="w-full rounded-md bg-gray-50 border border-gray-200 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400" rows="3" placeholder="Comments"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-gray-900 text-white font-semibold rounded-md py-3 mt-2 hover:bg-gray-800 transition">Submit Enquiry</button>
                </form>
            </div>
        </div>
    </section>

    <section class="mx-auto mt-10">
        <div class="overflow-hidden shadow-lg">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d416.7390707245025!2d106.8936711433187!3d-6.430961246562225!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69eb000745a9fb%3A0x5db6542652686a1e!2sKedai%20Xu%20Wang%20by%20iDManagement!5e0!3m2!1sid!2sid!4v1766299319372!5m2!1sid!2sid"
                width="100%"
                height="400"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                class="w-full h-[400px]"
            ></iframe>
        </div>
    </section>
</template>

<script setup>
    import { useHead } from '@vueuse/head'
    import { ref } from 'vue'
    import heroImage from '@/assets/banner/rent.png'
    import contact from '@/assets/contact/contact-us-img-01.jpg'
    import contact2 from '@/assets/contact/contact-us-img-02.jpg'
    const loaded = ref(true)
</script>