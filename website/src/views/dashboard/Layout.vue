<template>
    <div class="dashboard-container flex flex-col md:flex-row min-h-screen items-stretch">
        <!-- Sidebar: tampil di atas pada mobile, kiri pada desktop -->
        <div class="md:block w-full md:w-[260px] flex-shrink-0 bg-white flex flex-col h-full">
            <DashboardSidebar :activeMenu="activeMenu" @menu="activeMenu = $event" />
        </div>
        <main class="content flex-1 w-full">
            <component :is="menuComponent" />
            <router-view />
        </main>
    </div>
</template>

<script setup>
    import { ref, computed } from 'vue'
    import DashboardContent from './DashboardContent.vue';
    import AkunContent from './AkunContent.vue';
    import DashboardSidebar from './DashboardSidebar.vue';
    import MembershipContent from './MembershipContent.vue';
    import MyMembership from './MyMembership.vue';
    import TransactionContent from './TransactionContent.vue';
    import Booking from './Booking.vue';
    const activeMenu = ref('dashboard')

    const menuComponent = computed(() => {
        switch (activeMenu.value) {
            case 'dashboard': return DashboardContent
            case 'akun': return AkunContent
            case 'membership': return MembershipContent
            case 'mymembership': return MyMembership
            case 'transactions': return TransactionContent
            case 'booking': return Booking  
            default: return DashboardContent
        }
    })
</script>

<style scoped>
    .dashboard-container {
        background: #f7f7f7;
    }
    @media (max-width: 767px) {
        .dashboard-container {
            flex-direction: column;
        }
        .content {
            padding: 8px;
        }
    }



    /* CONTENT */
    .content {
        flex: 1;
        padding: 10px;
        overflow-y: auto;
    }
</style>
