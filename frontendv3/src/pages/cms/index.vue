<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <Breadcrumbs main="Settings" title="CMS" />
            </div>
        </div>

        <div class="container-fluid">
            <div class="row widget-grid ms-2">
                    <div class="card">
                        <div class="card-body">
                            <Tabs value="0" class="p-tab-active">
                                <TabList  class="p-tab-active">
                                    <Tab value="0">  <i class="fa fa-cogs" /> Site Settings</Tab>
                                    <Tab value="1">  <i class="fa fa-envelope" /> Contact Me</Tab>
                                    <Tab value="2">  <i class="fa fa-user" /> About Me</Tab>
                                    <Tab value="3">  <i class="fa fa-question-circle" /> FAQ</Tab>
                                    <Tab value="4">  <i class="fa fa-file" /> Interior Dan Renovasi</Tab>
                                    <Tab value="5">  <i class="fa fa-cogs" /> Services</Tab>
                                    <Tab value="6">  <i class="fa fa-briefcase" /> Testimonial</Tab>
                                </TabList>
                                <TabPanels>
                                    <TabPanel value="0">
                                        <site_setting />
                                    </TabPanel>
                                    <TabPanel value="1">
                                        <contact_me />
                                    </TabPanel>
                                    <TabPanel value="2">
                                        <about_me />
                                    </TabPanel>
                                    <TabPanel value="3">
                                        <faq />
                                    </TabPanel>
                                    <TabPanel value="4">
                                        <RenovasiSettings />
                                    </TabPanel>
                                    <TabPanel value="5">
                                        <ServicesSettings />
                                    </TabPanel>
                                    <TabPanel value="6">
                                        <TestimonialSettings />
                                    </TabPanel>
                                </TabPanels>
                            </Tabs>
                        </div>
                    </div>
            </div>
        </div>
    </div>

</template>

<script setup>
    import Tabs from 'primevue/tabs';
    import TabList from 'primevue/tablist';
    import Tab from 'primevue/tab';
    import TabPanels from 'primevue/tabpanels';
    import TabPanel from 'primevue/tabpanel';
    import site_setting from './settings/site_setting.vue';
    import contact_me from './settings/contact_me.vue';
    import about_me from './settings/about_me.vue';
    import faq from './faq/index.vue';
    import RenovasiSettings from './renovasi/index.vue';
    import ServicesSettings from './services/index.vue';
    import TestimonialSettings from './testimoni/index.vue';

</script>