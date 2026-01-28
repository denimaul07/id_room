<template>
    <li class="relative group">
        <router-link
        v-if="!subItems"
        :to="to"
        class="hover:text-primary"
        >{{ label }}</router-link>

        <template v-else>
        <button class="hover:text-primary flex items-center gap-1">
            {{ label }}
            <span class="text-xs">▼</span>
        </button>

        <ul
            class="absolute bg-white border rounded shadow-md mt-2 hidden group-hover:block"
        >
            <li
            v-for="item in subItems"
            :key="item.label"
            class="px-4 py-2 hover:bg-gray-100"
            >
            <router-link :to="item.to">{{ item.label }}</router-link>
            </li>
        </ul>
        </template>
    </li>
</template>

<script setup>
    defineProps({
        label: String,
        to: { type: String, default: '#' },
        subItems: Array
    })
</script>
