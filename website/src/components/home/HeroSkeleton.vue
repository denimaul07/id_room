<template>
    <section class="relative min-h-[70vh] md:min-h-[85vh] flex items-center bg-gray-200 overflow-hidden">
        <div class="absolute inset-0 bg-black/30"></div>

        <!-- Shimmer overlay -->
        <div class="shimmer absolute inset-0"></div>

        <div class="relative z-10 w-full">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 text-center">
            <div class="skeleton h-8 sm:h-10 md:h-14 w-2/3 mx-auto mb-4"></div>
            <div class="skeleton h-4 w-1/2 mx-auto mb-6"></div>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <div class="skeleton h-12 w-full sm:w-40 rounded-lg"></div>
            <div class="skeleton h-12 w-full sm:w-40 rounded-lg"></div>
            </div>
        </div>
        </div>
    </section>
</template>

<style scoped>
    .skeleton {
    background: linear-gradient(
        110deg,
        #e5e7eb 8%,
        #f3f4f6 18%,
        #e5e7eb 33%
    );
    background-size: 200% 100%;
    animation: shimmer 1.4s infinite linear;
    border-radius: 0.5rem;
    }

    @keyframes shimmer {
    to {
        background-position-x: -200%;
    }
    }
</style>
