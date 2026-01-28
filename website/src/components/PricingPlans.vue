<template>
    <section class="py-20 bg-base-200">
        <div class="max-w-7xl mx-auto px-4">

        <!-- Title -->
        <div class="text-center mb-14">
            <h2 class="text-4xl font-bold text-primary">
            Pricing Plans
            </h2>
            <p class="text-secondary mt-4 max-w-xl mx-auto">
            Choose a plan that fits your business
            </p>
        </div>

        <!-- Plans -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- Basic -->
            <div class="card bg-base-100 shadow-lg">
            <div class="card-body text-center">
                <h3 class="text-xl font-semibold">Basic</h3>
                <p class="text-secondary mt-2">For individuals</p>

                <div class="text-4xl font-bold text-primary my-6">
                $19
                <span class="text-sm font-normal text-secondary">/month</span>
                </div>

                <ul class="space-y-3 text-sm text-secondary mb-6">
                <li>✔ 5 Property Listings</li>
                <li>✔ Basic Support</li>
                <li>✔ Limited Analytics</li>
                </ul>

                <button class="btn btn-outline btn-primary w-full">
                Get Started
                </button>
            </div>
            </div>

            <!-- Popular -->
            <div class="card bg-primary text-white shadow-xl scale-105">
            <div class="card-body text-center">
                <span class="badge badge-secondary mb-3">
                Most Popular
                </span>

                <h3 class="text-xl font-semibold">Professional</h3>
                <p class="opacity-80 mt-2">For real estate agents</p>

                <div class="text-4xl font-bold my-6">
                $49
                <span class="text-sm font-normal opacity-80">/month</span>
                </div>

                <ul class="space-y-3 text-sm mb-6">
                <li>✔ Unlimited Listings</li>
                <li>✔ Priority Support</li>
                <li>✔ Advanced Analytics</li>
                </ul>

                <button class="btn btn-secondary w-full">
                Choose Plan
                </button>
            </div>
            </div>

            <!-- Premium -->
            <div class="card bg-base-100 shadow-lg">
            <div class="card-body text-center">
                <h3 class="text-xl font-semibold">Enterprise</h3>
                <p class="text-secondary mt-2">For large teams</p>

                <div class="text-4xl font-bold text-primary my-6">
                $99
                <span class="text-sm font-normal text-secondary">/month</span>
                </div>

                <ul class="space-y-3 text-sm text-secondary mb-6">
                <li>✔ Team Management</li>
                <li>✔ Dedicated Support</li>
                <li>✔ Custom Integrations</li>
                </ul>

                <button class="btn btn-outline btn-primary w-full">
                Contact Sales
                </button>
            </div>
            </div>

        </div>

        </div>
    </section>
</template>
