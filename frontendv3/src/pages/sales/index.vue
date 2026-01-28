<template>
    <div>
        <div class="container-fluid pb-5">
            <div class="row">
                <Breadcrumbs main="Sales" title="List Sales" />

                <Sales />
            </div>
        </div>

    </div>
</template>

<script setup>
    import Sales from '@/pages/sales/sales.vue';
</script>