<template>
    <div>

        <div class="container-fluid  pb-5">
            <div class="row">
                <Breadcrumbs main="Dashboard" />
            </div>

            <div class="row widget-grid">
                <div class="col-xxl-4 col-xl-4 col-sm-12 box-col-6">
                    <div class="card profile-box">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                <div class="greeting-user">
                                    <h4 class="f-w-600 mb-0">{{greeting}}</h4>
                                    <p>Welcome {{ user.name }} hope you have a nice day today</p>
                                    <div class="whatsnew-btn z-3">
                                    ID ROOM SYSTEM
                                    </div>
                                </div>
                                </div>
                                <div>
                                <div class="clockbox">
                                    <svg id="clock" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 600">
                                    <g id="face">
                                        <circle class="circle" cx="300" cy="300" r="253.9"></circle>
                                        <path class="hour-marks"
                                        d="M300.5 94V61M506 300.5h32M300.5 506v33M94 300.5H60M411.3 107.8l7.9-13.8M493 190.2l13-7.4M492.1 411.4l16.5 9.5M411 492.3l8.9 15.3M189 492.3l-9.2 15.9M107.7 411L93 419.5M107.5 189.3l-17.1-9.9M188.1 108.2l-9-15.6">
                                        </path>
                                        <circle class="mid-circle" cx="300" cy="300" r="16.2"></circle>
                                    </g>
                                    <g id="hour">
                                        <path class="hour-hand" d="M300.5 298V142"></path>
                                        <circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
                                    </g>
                                    <g id="minute">
                                        <path class="minute-hand" d="M300.5 298V67"></path>
                                        <circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
                                    </g>
                                    <g id="second">
                                        <path class="second-hand" d="M300.5 350V55"></path>
                                        <circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
                                    </g>
                                    </svg>
                                </div>
                                <div class="badge f-10 p-0" id="txt"></div>
                                </div>
                            </div>
                            <div class="cartoon">
                                <img class="img-fluid" src="@/assets/images/dashboard/cartoon.svg"
                                alt="vector women with leptop" width="80%"/>
                            </div>
                        </div>
                    </div>
                </div>

                

                <div class="col-xxl-8 col-xl-8 col-sm-12 box-col-12">
                    <div class="row">
                        <h2 class="text-center text-white">Summary Dashboard Overview</h2>
                        <h6 class="text-center text-white mb-4">   {{ filterLabel }}</h6>
                        <!-- Filter Section -->

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body p-2">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <label class="form-label mb-0 fw-bold">
                                                <i class="fa fa-filter"></i> Filter :
                                            </label>
                                        </div>
                                        <div class="col-auto">
                                            <select v-model="selectedFilter" class="form-select form-select-sm" @change="filter">
                                                <option value="today">Hari ini</option>
                                                <option value="month">Bulan ini</option>
                                                <option value="year">Tahun ini</option>
                                                <option value="custom">Custom Filter</option>
                                            </select>
                                        </div>
                                        <div v-if="selectedFilter === 'custom'" class="col-auto">
                                            <input type="date" v-model="customStart" class="form-control form-control-sm" @change="filter" />
                                        </div>
                                        <div v-if="selectedFilter === 'custom'" class="col-auto">
                                            <input type="date" v-model="customEnd" class="form-control form-control-sm" @change="filter" />
                                        </div>
                                        <div class="col-auto">
                                            <button class="btn btn-primary btn-sm" @click="clear" :disabled="loading">
                                                <ReloadOutlined />
                                                {{ loading ? 'Loading...' : 'Reset' }}
                                            </button>
                                        </div>
                                        <div class="col-auto">
                                            <button class="btn btn-success btn-sm" @click="exportReport" :disabled="loading">
                                                <FileExcelOutlined />
                                                {{ loading ? 'Loading...' : 'Export Data' }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4" v-for="data in 3" v-if="loading">

                    <a href="#" class="card widget-1">
                        <div class="card-body">
                            <div class="widget-content">
                                <div class="widget-round" :class="data.class">
                                    <div class="bg-round">
                                        <a-skeleton-avatar :active="true" />
                                    </div>
                                </div>
                                <div>
                                    <h4><a-skeleton-input :active="true" /></h4>
                                    <span class="f-light">
                                        <a-skeleton-input :active="true" size="small"/>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 pb-2" v-else>
                    <div class="card">
                        <div class="card-header bg-white d-flex align-items-center justify-content-between">
                            <h5 class="mb-0 text-dark fw-bold">
                                <i class="fa fa-exclamation-triangle text-warning me-2"></i>
                                Operational Alerts
                            </h5>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="card course-box">
                                <div class="card-body">
                                    <div class="course-widget">
                                        <div class="course-icon warning">
                                            <svg class="fill-icon">
                                                <use href="@/assets/svg/icon-sprite.svg#course-1"></use>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="mb-0"> {{ need_attention.pending_payment || 0 }} Pending Payment</h4>
                                            <span class="f-light">Waiting for admin confirmation</span>
                                            <router-link class="btn btn-light f-light"
                                                to="/all-transactions?type=PENDING"> View Transactions → <span class="ms-2">
                                                    <svg class="fill-icon f-light">
                                                        <use href="@/assets/svg/icon-sprite.svg#arrowright"></use>
                                                    </svg></span>
                                            </router-link>
                                        </div>
                                    </div>
                                </div>
                                <ul class="square-group">
                                    <li class="square-1 warning"></li>
                                    <li class="square-1 primary"></li>
                                    <li class="square-2 warning1"></li>
                                    <li class="square-3 danger"></li>
                                    <li class="square-4 light"></li>
                                    <li class="square-5 warning"></li>
                                    <li class="square-6 success"></li>
                                    <li class="square-7 success"></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">

                            <div class="card course-box">
                                <div class="card-body">
                                    <div class="course-widget">
                                        <div class="course-icon">
                                            <svg class="fill-icon">
                                                <use href="@/assets/svg/icon-sprite.svg#course-2"></use>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="mb-0">{{ need_attention.unpaid_booking || 0 }} Booking Unpaid &gt; 1 Hour</h4>
                                            <span class="f-light">Will release inventory soon</span><router-link class="btn btn-light f-light"
                                                to="/all-transactions?type=PENDING&source=BOOKING&unpaid=true">Review Booking →<span class="ms-2">
                                                    <svg class="fill-icon f-light">
                                                        <use href="@/assets/svg/icon-sprite.svg#arrowright"></use>
                                                    </svg></span></router-link>
                                        </div>
                                    </div>
                                </div>
                                <ul class="square-group">
                                    <li class="square-1 warning"></li>
                                    <li class="square-1 primary"></li>
                                    <li class="square-2 warning1"></li>
                                    <li class="square-3 danger"></li>
                                    <li class="square-4 light"></li>
                                    <li class="square-5 warning"></li>
                                    <li class="square-6 success"></li>
                                    <li class="square-7 success"></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card course-box">
                                <div class="card-body">
                                    <div class="course-widget">
                                        <div class="course-icon">
                                            <svg class="fill-icon">
                                                <use href="@/assets/svg/icon-sprite.svg#x-circle"></use>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="mb-0">{{ need_attention.failed_payment || 0 }}  Failed Payment</h4>
                                            <span class="f-light">Payment needs investigation</span><router-link class="btn btn-light f-light"
                                                        to="all-transactions?type=FAILED">  View Logs → →<span class="ms-2">
                                                    <svg class="fill-icon f-light">
                                                        <use href="@/assets/svg/icon-sprite.svg#arrowright"></use>
                                                    </svg></span></router-link>
                                        </div>
                                    </div>
                                </div>
                                <ul class="square-group">
                                    <li class="square-1 warning"></li>
                                    <li class="square-1 primary"></li>
                                    <li class="square-2 warning1"></li>
                                    <li class="square-3 danger"></li>
                                    <li class="square-4 light"></li>
                                    <li class="square-5 warning"></li>
                                    <li class="square-6 success"></li>
                                    <li class="square-7 success"></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <Tabs v-model:value="activeTab" class="p-tab-active">
                    <TabList class="p-tab-active">

                        <Tab value="0"> <span style="color: #222 !important;">Dashboard Payment</span></Tab>
                        <Tab value="1"> <span style="color: #222 !important;">Dashboard Booking</span></Tab>
                        <Tab value="2"> <span style="color: #222 !important;">Dashboard Membership</span></Tab>
                        <Tab value="3"> <span style="color: #222 !important;">Dashboard Leads</span></Tab>
                        <Tab value="4"> <span style="color: #222 !important;">Dashboard CRM</span></Tab>
                    </TabList>
                    <TabPanels>
                        <TabPanel value="0">
                            <div class="row">
                                <div class="col-xxl-4 col-xl-4 col-sm-12 box-col-6">
                                    <div class="row">
                                        <div class="col-md-12" v-for="data in 2" v-if="loading">

                                            <a href="#" class="card widget-1">
                                                <div class="card-body">
                                                    <div class="widget-content">
                                                        <div class="widget-round" :class="data.class">
                                                            <div class="bg-round">
                                                                <a-skeleton-avatar :active="true" />
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h4><a-skeleton-input :active="true" /></h4>
                                                            <span class="f-light">
                                                                <a-skeleton-input :active="true" size="small"/>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-12" v-for="data in 3" v-if="loading">

                                            <div class="card widget-1">
                                                <div class="card-body">
                                                    <div class="widget-content">
                                                        <div class="widget-round" :class="data.class">
                                                            <div class="bg-round">
                                                                <a-skeleton-avatar :active="true" />
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h4><a-skeleton-input :active="true" /></h4>
                                                            <span class="f-light">
                                                                <a-skeleton-input :active="true" size="small"/>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12" v-for="data in state.data" :key="data.title" v-else>
                                            <router-link :to="data.url"  class="card widget-1">
                                                <div class="card-body">
                                                    <div class="widget-content" >
                                                        <div class="widget-round" :class="data.class">
                                                            <div class="bg-round">
                                                                <svg class="svg-fill">
                                                                    <use :xlink:href="iconSpritePath + `#${data.icon}`"></use>
                                                                </svg>
                                                                <!-- Removed halfcircle SVG reference because symbol does not exist -->
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h5>{{ data.number }}</h5>
                                                            <span class="f-light">
                                                                {{ data.title }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </router-link >
                                        </div>

                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header d-flex justify-content-between align-items-center p-3">
                                                    <h5 class="mb-0">Revenue Composition</h5>
                                                </div>
                                                <div class="card-body">
                                                    <apexchart type="donut" height="350" :options="pieOptions" :series="pieSeries"></apexchart>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-xxl-8 col-xl-8 col-sm-12 box-col-12">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between align-items-center p-3">
                                                <h5 class="mb-0">Revenue Trend</h5>
                                            </div>
                                            <div class="card-body">
                                                <div v-if="loading">
                                                    <a-skeleton active :paragraph="{ rows: 8 }" />
                                                </div>
                                                <div v-else>
                                                    <Tabs value="0" class="p-tab-active">
                                                        <TabList class="p-tab-active" style="color: black;">
                                                            <Tab value="0">
                                                                <span style="color: #222 !important;"> To days Revenue</span>
                                                            </Tab>
                                                            <Tab value="1">
                                                                <span style="color: #222 !important;"> The Last 7 Days Revenue</span>
                                                            </Tab>
                                                            <Tab value="2">
                                                                <span style="color: #222 !important;"> This Month Revenue</span>
                                                            </Tab>
                                                            <Tab value="3">
                                                                <span style="color: #222 !important;"> This Year Revenue</span>
                                                            </Tab>
                                                        </TabList>
                                                        <TabPanel value="0">
                                                            <apexchart type="bar" height="350" :options="chartOptions" :series="series"></apexchart>
                                                        </TabPanel>
                                                        <TabPanel value="1">
                                                            <apexchart type="bar" height="350" :options="weeklyOptions" :series="weeklySeries"></apexchart>
                                                        </TabPanel>
                                                        <TabPanel value="2">
                                                            <apexchart type="bar" height="350" :options="monthlyOptions" :series="monthlySeries"></apexchart>
                                                        </TabPanel>
                                                        <TabPanel value="3">
                                                            <apexchart type="bar" height="350" :options="yearsOptions" :series="yearsSeries"></apexchart>
                                                        </TabPanel>
                                                    </Tabs>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between align-items-center p-3">
                                                <h5 class="mb-0">Live Transaction Feed</h5>
                                            </div>
                                            <div class="card-body p-3">
                                                <div class="table-responsive pt-2  d-md-block d-none">
                                                    <table class="table">
                                                        <thead>
                                                            <tr class="border-bottom-primary">
                                                                <th class="bg-primary text-nowrap text-center sticky-column">No</th>
                                                                <th class="bg-primary text-nowrap text-center">User</th>
                                                                <th class="bg-primary text-nowrap text-center">Activity</th>
                                                                <th class="bg-primary text-nowrap text-center">Amount</th>
                                                                <th class="bg-primary text-nowrap text-center">Status</th>
                                                                <th class="bg-primary text-nowrap text-center">Time</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-if="loading"> 
                                                                <td class="text-center" colspan="7"><a-skeleton active /></td>
                                                            </tr>
                                                            <tr v-else-if="state.listTransaction.length==0">
                                                                <td class="text-center" colspan="7"><a-empty/></td>
                                                            </tr>
                                                            <tr v-for="(item, index) in state.listTransaction" :key="item.id" v-else>
                                                                <td class="text-center sticky-column">{{ index + 1 }}</td>
                                                                <td class="text-nowrap text-center">{{ item.user }}</td>
                                                                <td class="text-nowrap">{{ item.activity }}</td>
                                                                <td class="text-nowrap text-left">{{ item.amount.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3)}}</td>
                                                                <td class="text-nowrap text-center">
                                                                    <span
                                                                        :class="[
                                                                            'badge',
                                                                            'text-white',
                                                                            'fw-semibold',
                                                                            item.status === 'PAID'
                                                                                ? 'bg-success'
                                                                                : item.status === 'PENDING'
                                                                                ? 'bg-warning'
                                                                                : item.status === 'FAILED'
                                                                                ? 'bg-danger'
                                                                                : item.status === 'REFUNDED'
                                                                                ? 'bg-info'
                                                                                : item.status === 'CANCELLED'
                                                                                ? 'bg-secondary'
                                                                                : 'bg-light text-dark'
                                                                        ]"
                                                                    >
                                                                        {{ item.status }}
                                                                    </span>
                                                                </td>
                                                                <td class="text-nowrap text-center">{{ dayjs(item.created_at).format('DD MMM YYYY HH:mm:ss') }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>  
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between align-items-center p-3">
                                                <h5 class="mb-0">Property Performance {{ filterLabel }}</h5>
                                            </div>
                                            <div class="card-body p-3">
                                                <div class="table-responsive pt-2  d-md-block d-none">
                                                    <table class="table">
                                                        <thead>
                                                            <tr class="border-bottom-primary">
                                                                <th class="bg-primary text-nowrap text-center sticky-column">No</th>
                                                                <th class="bg-primary text-nowrap text-center">Property Name</th>
                                                                <th class="bg-primary text-nowrap text-center">Total Bookings</th>
                                                                <th class="bg-primary text-nowrap text-center">Revenue</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-if="loading"> 
                                                                <td class="text-center" colspan="7"><a-skeleton active /></td>
                                                            </tr>
                                                            <tr v-else-if="state.listProperty.total==0">
                                                                <td class="text-center" colspan="7"><a-empty/></td>
                                                            </tr>
                                                            <tr v-for="(item, index) in state.listProperty.data" :key="item.id" v-else>
                                                                <td class="text-center sticky-column">{{ index + state.listProperty.from }}</td>
                                                                <td class="text-nowrap text-left">{{ item.property }}</td>
                                                                <td class="text-nowrap text-center">{{ item.booking_today }}</td>
                                                                <td class="text-nowrap text-left">{{ item.revenue.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3)}}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>  

                                                <div class="row py-2">
                                                    <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                                                        Showing {{ state.listProperty.from }} to {{ state.listProperty.to }} of {{ state.listProperty.total }} entries
                                                    </div>
                                                    <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                                        <a-pagination :current="state.listProperty.current_page" :total="state.listProperty.total" v-model:pageSize="pagging"
                                                            @change="handlePageChange" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </TabPanel>

                        <TabPanel value="1">
                            <div class="row">
                                <div class="col-xxl-4 col-xl-4 col-sm-12 box-col-6">
                                    <div class="col-sm-12">
                                        <div class="card o-hidden">
                                            <div class="card-body balance-widget">
                                                <span class="f-w-500 f-light">Total Balance</span>
                                                <h4 class="mb-3 mt-1 f-w-500 f-22">
                                                    <span class="counter">{{ DataBooking.revenue }} </span><span class="f-light f-14 f-w-400 ms-1">{{ filterLabel }}</span>
                                                </h4>
                                                <router-link :to="`all-transactions?type=PAID&source=BOOKING&date=${selectedFilter}&customStart=${customStart}&customEnd=${customEnd}`" class="
                                                    purchase-btn
                                                    btn btn-primary btn-hover-effect
                                                    f-w-500
                                                    ">Detail Transaction</router-link>
                                                <div class="mobile-right-img">
                                                    <img class="mobile-img" src="@/assets/images/dashboard-2/profit.png" alt="mobile with coin" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <router-link :to="`booking-transactions?tab=1&date=${selectedFilter}&customStart=${customStart}&customEnd=${customEnd}`" class="card widget-1">
                                            <div class="card-body">
                                                <div class="widget-content" >
                                                    <div class="widget-round primary">
                                                        <div class="bg-round">
                                                            <svg class="svg-fill">
                                                                <use :xlink:href="iconSpritePath + `#arrow-up-right-circle`"></use>
                                                            </svg>
                                                            <svg class="half-circle svg-fill">
                                                                <use :xlink:href="iconSpritePath + `#halfcircle`"></use>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h5>{{ DataBooking.total_booking_today }}</h5>
                                                        <span class="f-light">
                                                            Total Booking {{ filterLabel }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </router-link>
                                    </div>

                                    <div class="col-sm-12">
                                        <router-link :to="`booking-transactions?tab=1&status=COMPLETED&date=${selectedFilter}&customStart=${customStart}&customEnd=${customEnd}`" class="card widget-1">
                                            <div class="card-body">
                                                <div class="widget-content" >
                                                    <div class="widget-round biru">
                                                        <div class="bg-round">
                                                            <svg class="svg-fill">
                                                                <use :xlink:href="iconSpritePath + `#file-earmark-check-fill`"></use>
                                                            </svg>
                                                            <svg class="half-circle svg-fill">
                                                                <use :xlink:href="iconSpritePath + `#halfcircle`"></use>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h5>{{ DataBooking.completed_booking }}</h5>
                                                        <span class="f-light">
                                                            Complete Booking {{ filterLabel }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </router-link>
                                    </div>

                                    <div class="col-sm-12">
                                        <router-link :to="`booking-transactions?tab=1&status=CANCELLED &date=${selectedFilter}&customStart=${customStart}&customEnd=${customEnd}`" class="card widget-1">
                                            <div class="card-body">
                                                <div class="widget-content" >
                                                    <div class="widget-round danger">
                                                        <div class="bg-round">
                                                            <svg class="svg-fill">
                                                                <use :xlink:href="iconSpritePath + `#x-circle`"></use>
                                                            </svg>
                                                            <svg class="half-circle svg-fill">
                                                                <use :xlink:href="iconSpritePath + `#halfcircle`"></use>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h5>{{ DataBooking.cancelled_booking }}</h5>
                                                        <span class="f-light">
                                                            Cancel Booking {{ filterLabel }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </router-link>
                                    </div>
                                </div>

                                <div class="col-xxl-8 col-xl-8 col-sm-12 box-col-12">
                                    <div class="row">
                                        <div class="col-sm-12 col-lg-4 col-xl-4">
                                            <router-link :to="`booking-transactions?tab=1&status=ACTIVE_BOOKING &date=${selectedFilter}&customStart=${customStart}&customEnd=${customEnd}`" class="card widget-1">
                                                <div class="card-body">
                                                    <div class="widget-content" >
                                                        <div class="widget-round success">
                                                            <div class="bg-round">
                                                                <svg class="svg-fill">
                                                                    <use :xlink:href="iconSpritePath + `#check2-circle`"></use>
                                                                </svg>
                                                                <svg class="half-circle svg-fill">
                                                                    <use :xlink:href="iconSpritePath + `#halfcircle`"></use>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h5>{{ DataBooking.active_stay }}</h5>
                                                            <span class="f-light">
                                                                Active Booking {{ filterLabel }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </router-link>
                                        </div>

                                        <div class="col-sm-12 col-lg-4 col-xl-4">
                                            <router-link :to="`booking-transactions?tab=2&status=CHECK_IN_TODAY&date=${selectedFilter}&customStart=${customStart}&customEnd=${customEnd}`" class="card widget-1">
                                                <div class="card-body">
                                                    <div class="widget-content" >
                                                        <div class="widget-round biru">
                                                            <div class="bg-round">
                                                                <svg class="svg-fill">
                                                                    <use :xlink:href="iconSpritePath + `#box-arrow-in-right`"></use>
                                                                </svg>
                                                                <svg class="half-circle svg-fill">
                                                                    <use :xlink:href="iconSpritePath + `#halfcircle`"></use>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h5>{{ DataBooking.check_in_today }}</h5>
                                                            <span class="f-light">
                                                                Check In To Day
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </router-link>
                                        </div>

                                        <div class="col-sm-12 col-lg-4 col-xl-4">
                                            <div class="card widget-1">
                                                <div class="card-body">
                                                    <div class="widget-content" >
                                                        <div class="widget-round warning">
                                                            <div class="bg-round">
                                                                <svg class="svg-fill">
                                                                    <use :xlink:href="iconSpritePath + `#box-arrow-in-left`"></use>
                                                                </svg>
                                                                <svg class="half-circle svg-fill">
                                                                    <use :xlink:href="iconSpritePath + `#halfcircle`"></use>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h5>{{ DataBooking.check_out_today }}</h5>
                                                            <span class="f-light">
                                                                Check Out To Day
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header d-flex justify-content-between align-items-center p-3">
                                                    <h5 class="mb-0">Booking Availability</h5>
                                                
                                                </div>
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="d-flex gap-2">
                                                    
                                                        

                                                            <!-- Filter Property -->
                                                            <a-select v-model:value="filterProperty" show-search placeholder="Pilih Property" style="width: 200px">
                                                                <a-select-option value="">Semua Property</a-select-option>
                                                                <a-select-option v-for="property in state.listGetProperty" :key="property.odata" :value="property.odata">{{ property.properties }}</a-select-option>  
                                                            </a-select>


                                                            <a-button type="primary" class="bg-dark"  @click="resetFilterBookingAvailability">
                                                                <template #icon>
                                                                    <ReloadOutlined />
                                                                </template>
                                                            </a-button>

                                                            <button class="btn btn-sm btn-light" @click="openCalendarFullscreen">
                                                                <i class="fa fa-expand me-1"></i>
                                                                Full Screen
                                                            </button>

                                                        </div>
                                                    </div>

                                                    <FullCalendar
                                                        id="calendar"
                                                        :key="calendarKey"
                                                        ref="calendarRef"
                                                        :options="calendarOptions"
                                                    />
                                                </div>
                                            </div>
                                        </div>

                                
                                        <Tabs value="0" class="p-tab-active">
                                            <TabList class="p-tab-active" style="color: black;">
                                                <Tab value="0">
                                                    <span style="color: #222 !important;">Today</span>
                                                </Tab>
                                                <Tab value="1">
                                                    <span style="color: #222 !important;">Last 7 Days</span>
                                                </Tab>
                                                <Tab value="2">
                                                    <span style="color: #222 !important;">This Month</span>
                                                </Tab>
                                                <Tab value="3">
                                                    <span style="color: #222 !important;">This Year</span>
                                                </Tab>
                                            </TabList>
                                            <TabPanel value="0">
                                                <apexchart type="bar" height="350" :options="bookingStatusOptions" :series="bookingStatusDailySeries"></apexchart>
                                            </TabPanel>
                                            <TabPanel value="1">
                                                <apexchart type="bar" height="350" :options="bookingStatusWeeklyOptions" :series="bookingStatusWeeklySeries"></apexchart>
                                            </TabPanel>
                                            <TabPanel value="2">
                                                <apexchart type="bar" height="350" :options="bookingStatusMonthlyOptions" :series="bookingStatusMonthlySeries"></apexchart>
                                            </TabPanel>
                                            <TabPanel value="3">
                                                <apexchart type="bar" height="350" :options="bookingStatusYearlyOptions" :series="bookingStatusYearlySeries"></apexchart>
                                            </TabPanel>
                                        </Tabs>
                                    </div>
                                    
                                </div>
                            </div>
                        </TabPanel>

                        <TabPanel value="2">
                            <div class="row">
                                <div class="col-xxl-4 col-xl-4 col-sm-12 box-col-6">
                                    <div class="col-sm-12">
                                        <div class="card o-hidden">
                                            <div class="card-body balance-widget">
                                                <span class="f-w-500 f-light">Total Balance</span>
                                                <h4 class="mb-3 mt-1 f-w-500 f-22">
                                                    <span class="counter">{{ DataMembership.revenue }} </span><span class="f-light f-14 f-w-400 ms-1">{{ filterLabel }}</span>
                                                </h4>
                                                <router-link :to="`all-transactions?type=PAID&source=MEMBERSHIP&date=${selectedFilter}&customStart=${customStart}&customEnd=${customEnd}`" class="
                                                    purchase-btn
                                                    btn btn-primary btn-hover-effect
                                                    f-w-500
                                                    ">Detail Transaction</router-link>
                                                <div class="mobile-right-img">
                                                    <img class="mobile-img" src="@/assets/images/dashboard-2/profit.png" alt="mobile with coin" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <router-link :to="`membership-transactions?tab=1&date=${selectedFilter}&customStart=${customStart}&customEnd=${customEnd}`" class="card widget-1">
                                            <div class="card-body">
                                                <div class="widget-content" >
                                                    <div class="widget-round primary">
                                                        <div class="bg-round">
                                                            <svg class="svg-fill">
                                                                <use :xlink:href="iconSpritePath + `#arrow-up-right-circle`"></use>
                                                            </svg>
                                                            <svg class="half-circle svg-fill">
                                                                <use :xlink:href="iconSpritePath + `#halfcircle`"></use>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h5>{{ DataMembership.total_membership }}</h5>
                                                        <span class="f-light">
                                                            Total Membership
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </router-link>
                                    </div>

                                    <div class="col-sm-12">
                                        <router-link :to="`membership-transactions?tab=1&status=active&date=${selectedFilter}&customStart=${customStart}&customEnd=${customEnd}`" class="card widget-1">
                                            <div class="card-body">
                                                <div class="widget-content" >
                                                    <div class="widget-round biru">
                                                        <div class="bg-round">
                                                            <svg class="svg-fill">
                                                                <use :xlink:href="iconSpritePath + `#file-earmark-check-fill`"></use>
                                                            </svg>
                                                            <svg class="half-circle svg-fill">
                                                                <use :xlink:href="iconSpritePath + `#halfcircle`"></use>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h5>{{ DataMembership.total_active }}</h5>
                                                        <span class="f-light">
                                                            Active Membership
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </router-link>
                                    </div>

                                    <div class="col-sm-12">
                                        <router-link :to="`membership-transactions?tab=1&status=expired&date=${selectedFilter}&customStart=${customStart}&customEnd=${customEnd}`" class="card widget-1">
                                            <div class="card-body">
                                                <div class="widget-content" >
                                                    <div class="widget-round danger">
                                                        <div class="bg-round">
                                                            <svg class="svg-fill">
                                                                <use :xlink:href="iconSpritePath + `#clock-history`"></use>
                                                            </svg>
                                                            <svg class="half-circle svg-fill">
                                                                <use :xlink:href="iconSpritePath + `#halfcircle`"></use>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h5>{{ DataMembership.total_expired }}</h5>
                                                        <span class="f-light">
                                                            Expired Membership
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </router-link>
                                    </div>

                                    <div class="col-sm-12">
                                        <router-link :to="`membership-transactions?tab=1&status=cancelled&date=${selectedFilter}&customStart=${customStart}&customEnd=${customEnd}`" class="card widget-1">
                                            <div class="card-body">
                                                <div class="widget-content" >
                                                    <div class="widget-round warning">
                                                        <div class="bg-round">
                                                            <svg class="svg-fill">
                                                                <use :xlink:href="iconSpritePath + `#x-circle`"></use>
                                                            </svg>
                                                            <svg class="half-circle svg-fill">
                                                                <use :xlink:href="iconSpritePath + `#halfcircle`"></use>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h5>{{ DataMembership.total_cancelled }}</h5>
                                                        <span class="f-light">
                                                            Cancelled Membership
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </router-link>
                                    </div>

                                    <div class="col-sm-12">
                                        <router-link :to="`membership-transactions?tab=1&status=completed&date=${selectedFilter}&customStart=${customStart}&customEnd=${customEnd}`" class="card widget-1">
                                            <div class="card-body">
                                                <div class="widget-content" >
                                                    <div class="widget-round success">
                                                        <div class="bg-round">
                                                            <svg class="svg-fill">
                                                                <use :xlink:href="iconSpritePath + `#check2-circle`"></use>
                                                            </svg>
                                                            <svg class="half-circle svg-fill">
                                                                <use :xlink:href="iconSpritePath + `#halfcircle`"></use>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h5>{{ DataMembership.total_completed }}</h5>
                                                        <span class="f-light">
                                                            Completed Membership
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </router-link>
                                    </div>
                                </div>

                                <div class="col-xxl-8 col-xl-8 col-sm-12 box-col-12">
                                    <Tabs value="0" class="p-tab-active">
                                        <TabList class="p-tab-active" style="color: black;">
                                            <Tab value="0">
                                                <span style="color: #222 !important;">Today</span>
                                            </Tab>
                                            <Tab value="1">
                                                <span style="color: #222 !important;">Last 7 Days</span>
                                            </Tab>
                                            <Tab value="2">
                                                <span style="color: #222 !important;">This Month</span>
                                            </Tab>
                                            <Tab value="3">
                                                <span style="color: #222 !important;">This Year</span>
                                            </Tab>
                                        </TabList>
                                        <TabPanel value="0">
                                            <apexchart type="bar" height="350" :options="membershipStatusOptions" :series="membershipStatusDailySeries"></apexchart>
                                        </TabPanel>
                                        <TabPanel value="1">
                                            <apexchart type="bar" height="350" :options="membershipStatusWeeklyOptions" :series="membershipStatusWeeklySeries"></apexchart>
                                        </TabPanel>
                                        <TabPanel value="2">
                                            <apexchart type="bar" height="350" :options="membershipStatusMonthlyOptions" :series="membershipStatusMonthlySeries"></apexchart>
                                        </TabPanel>
                                        <TabPanel value="3">
                                            <apexchart type="bar" height="350" :options="membershipStatusYearlyOptions" :series="membershipStatusYearlySeries"></apexchart>
                                        </TabPanel>
                                    </Tabs>
                                </div>
                            </div>  
                        </TabPanel>

                        <TabPanel value="3">
                            <div class="row">
                                <div class="col-xxl-4 col-xl-4 col-sm-12 box-col-6">
                                    <router-link :to="`crm?tab=TOTAL&date=${selectedFilter}&customStart=${customStart}&customEnd=${customEnd}`" class="card widget-1">
                                        <div class="card-body">
                                            <div class="widget-content" >
                                                <div class="widget-round primary">
                                                    <div class="bg-round">
                                                        <svg class="svg-fill">
                                                            <use :xlink:href="iconSpritePath + `#arrow-up-right-circle`"></use>
                                                        </svg>
                                                        <svg class="half-circle svg-fill">
                                                            <use :xlink:href="iconSpritePath + `#halfcircle`"></use>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h5>{{ crmSummary.total_crm }}</h5>
                                                    <span class="f-light">
                                                        Total Leads {{ filterLabel }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </router-link>

                                    <router-link :to="`crm?tab=NEEDFU&status=OPEN&date=${selectedFilter}&customStart=${customStart}&customEnd=${customEnd}`" class="card widget-1">
                                        <div class="card-body">
                                            <div class="widget-content" >
                                                <div class="widget-round biru">
                                                    <div class="bg-round">
                                                        <svg class="svg-fill">
                                                            <use :xlink:href="iconSpritePath + `#file-earmark-check-fill`"></use>
                                                        </svg>
                                                        <svg class="half-circle svg-fill">
                                                            <use :xlink:href="iconSpritePath + `#halfcircle`"></use>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h5>{{ crmSummary.total_needfu }}</h5>
                                                    <span class="f-light">
                                                        Need Follow Up Leads {{ filterLabel }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </router-link>

                                    <router-link :to="`crm?tab=FOLLOWUP&status=IN_PROGRESS&date=${selectedFilter}&customStart=${customStart}&customEnd=${customEnd}`" class="card widget-1">
                                        <div class="card-body">
                                            <div class="widget-content" >
                                                <div class="widget-round warning">
                                                    <div class="bg-round">
                                                        <svg class="svg-fill">
                                                            <use :xlink:href="iconSpritePath + `#clock-history`"></use>
                                                        </svg>
                                                        <svg class="half-circle svg-fill">
                                                            <use :xlink:href="iconSpritePath + `#halfcircle`"></use>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h5>{{ crmSummary.total_followup }}</h5>
                                                    <span class="f-light">
                                                        In Progress Leads {{ filterLabel }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </router-link>

                                    <router-link :to="`crm?tab=CLOSING&status=CLOSED&date=${selectedFilter}&customStart=${customStart}&customEnd=${customEnd}`" class="card widget-1">
                                        <div class="card-body">
                                            <div class="widget-content" >
                                                <div class="widget-round success">
                                                    <div class="bg-round">
                                                        <svg class="svg-fill">
                                                            <use :xlink:href="iconSpritePath + `#check2-circle`"></use>
                                                        </svg>
                                                        <svg class="half-circle svg-fill">
                                                            <use :xlink:href="iconSpritePath + `#halfcircle`"></use>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h5>{{ crmSummary.total_closing }}</h5>
                                                    <span class="f-light">
                                                        Closed Leads {{ filterLabel }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </router-link>

                                    <router-link :to="`crm?tab=LOST&status=LOST&date=${selectedFilter}&customStart=${customStart}&customEnd=${customEnd}`" class="card widget-1">
                                        <div class="card-body">
                                            <div class="widget-content" >
                                                <div class="widget-round danger">
                                                    <div class="bg-round">
                                                        <svg class="svg-fill">
                                                            <use :xlink:href="iconSpritePath + `#x-circle`"></use>
                                                        </svg>
                                                        <svg class="half-circle svg-fill">
                                                            <use :xlink:href="iconSpritePath + `#halfcircle`"></use>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h5>{{ crmSummary.total_lost }}</h5>
                                                    <span class="f-light">
                                                        Lost Leads {{ filterLabel }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </router-link> 
                                </div>

                                <div class="col-xxl-8 col-xl-8 col-sm-12 box-col-12">
                                    <Tabs value="0" class="p-tab-active">
                                        <TabList class="p-tab-active" style="color: black;">
                                        <Tab value="0"><span style="color: #222 !important;">Today</span></Tab>
                                        <Tab value="1"><span style="color: #222 !important;">Last 7 Days</span></Tab>
                                        <Tab value="2"><span style="color: #222 !important;">This Month</span></Tab>
                                        <Tab value="3"><span style="color: #222 !important;">This Year</span></Tab>
                                        </TabList>
                                        <TabPanel value="0">
                                            <apexchart type="bar" height="350" :options="crmChartOptions" :series="crmDailySeries"></apexchart>
                                        </TabPanel>
                                        <TabPanel value="1">
                                            <apexchart type="bar" height="350" :options="crmChartWeeklyOptions" :series="crmWeeklySeries"></apexchart>
                                        </TabPanel>
                                        <TabPanel value="2">
                                            <apexchart type="bar" height="350" :options="crmChartMonthlyOptions" :series="crmMonthlySeries"></apexchart>
                                        </TabPanel>
                                        <TabPanel value="3">
                                            <apexchart type="bar" height="350" :options="crmChartYearlyOptions" :series="crmYearlySeries"></apexchart>
                                        </TabPanel>
                                    </Tabs>
                                    <div class="mt-4">
                                        <h5>User Achievement Status {{ filterLabel }}</h5>
                                        <div v-if="!state.userAchievement || Object.keys(state.userAchievement).length === 0" class="text-center p-4">
                                            <p class="mb-0">No achievement data available.</p>
                                        </div>
                                        <div v-else class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">No</th>
                                                        <th class="text-center">User Name</th>
                                                        <th class="text-center">Need FU</th>
                                                        <th class="text-center">FollowUp</th>
                                                        <th class="text-center">Lost</th>
                                                        <th class="text-center">Closing</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(ach, idx) in state.userAchievement" :key="ach.assigned_name">
                                                        <td class="text-center">{{ idx + 1 }}</td>
                                                        <td>{{ ach.assigned_name}}</td>
                                                        <td class="text-center">{{ ach.needfu }}</td>
                                                        <td class="text-center">{{ ach.followup }}</td>
                                                        <td class="text-center">{{ ach.lost }}</td>
                                                        <td class="text-center">{{ ach.closing }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </TabPanel>
                        
                        <TabPanel value="4">
                            <crm  />
                        </TabPanel>
                    </TabPanels>
                </Tabs>
            </div>
        </div>

        <a-modal v-model:open="processing"  :footer="null" :closable=false   width="400px">
            <div style="align-items:center;justify-content: center;display: flex;" width="100px">
                <img class="img-fluid" :src="waitingicon" alt="vector women with leptop"/>
            </div>

            <div style="align-items:center;justify-content: center;display: flex;">
                {{ pesan }}
            </div>
        </a-modal>

        <a-modal
            v-model:open="modalCalendarFullscreen"
            title="Room Availability Calendar"
            width="95%"
            style="top:20px"
            :footer="null"
            :destroyOnClose="true"
        >
            <FullCalendar
                id="calendar-fullscreen"
                :key="calendarModalKey"
                ref="calendarModalRef"
                :options="calendarOptions"
            />
        </a-modal>

    
        <a-modal v-model:open="modalDetailBookingUsers" title="Detail Booking Availability" width="70%" style="top:20px" :footer="null">
            <div class="row">
                <div class="col-12 col-lg-6 mb-3">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>No Invoice</th>
                                <td>{{ state.bookingDetail.invoice_code }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <a-tag :color="getBookingStatusColor(state.bookingDetail.status)">
                                        {{ state.bookingDetail.status }}
                                    </a-tag>
                                </td>
                            </tr>
                            <tr>
                                <th>Metode Pembayaran</th>
                                <td>{{ state.bookingDetail.payment_method }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Tagihan</th>
                                <td>{{ parseInt(state.bookingDetail.amount).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Dibuat</th>
                                <td>{{ dayjs(state.bookingDetail.created_at).format('DD MMM YYYY HH:mm:ss') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-12 col-lg-6 mb-3">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>User</th>
                                <td>{{ state.bookingDetail.booking?.user?.name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ state.bookingDetail.booking?.user?.email }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ state.bookingDetail.booking?.user?.phone }}</td>
                            </tr>
                            <tr>
                                <th>Membership</th>
                                <td>
                                    {{ state.bookingDetail.booking?.membership?.title || '-' }}
                                    <span v-if="state.bookingDetail.booking?.membership">({{ state.bookingDetail.booking.membership.discount_percent }}% diskon)</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-12 mb-3">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Property</th>
                                <td>{{ state.bookingDetail.booking?.property?.properties }}</td>
                                <th>Room</th>
                                <td>{{ state.selectedBookingCell.room_name || state.bookingDetail.booking?.room?.room_name }}</td>
                            </tr>
                            <tr>
                                <th>Sub Room</th>
                                <td>
                                    <span v-if="state.selectedBookingCell.sub_room_code || state.selectedBookingCell.sub_room_name">
                                        {{ state.selectedBookingCell.sub_room_code || '-' }} - {{ state.selectedBookingCell.sub_room_name || '-' }}
                                    </span>
                                    <span v-else>-</span>
                                </td>
                                <th>Tanggal Slot</th>
                                <td>{{ state.selectedBookingCell.date || '-' }}</td>
                            </tr>
                            <tr>
                                <th>Check-in</th>
                                <td>{{ state.bookingDetail.booking?.checkin_date }}</td>
                                <th>Check-out</th>
                                <td>{{ state.bookingDetail.booking?.checkout_date }}</td>
                            </tr>
                            <tr>
                                <th>Harga Dasar</th>
                                <td>{{ parseInt(state.bookingDetail.booking?.base_price || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                <th>Diskon</th>
                                <td>{{ parseInt(state.bookingDetail.booking?.discount_amount || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                            </tr>
                            <tr>
                                <th>Pajak</th>
                                <td>{{ parseInt(state.bookingDetail.booking?.tax_amount || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                <th>Service Fee</th>
                                <td>{{ parseInt(state.bookingDetail.booking?.service_fee || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                            </tr>
                            <tr>
                                <th>Total Bayar</th>
                                <td colspan="3">{{ parseInt(state.bookingDetail.booking?.grand_total || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-12 mb-3" v-if="state.selectedBookingCell.sub_rooms && state.selectedBookingCell.sub_rooms.length">
                    <h5>Breakdown Sub Room (Room Ini)</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Code Room</th>
                                <th class="text-center">Name Room</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(sub, i) in state.selectedBookingCell.sub_rooms" :key="sub.odata || i">
                                <td class="text-center">{{ i + 1 }}</td>
                                <td>{{ sub.code_room || '-' }}</td>
                                <td>{{ sub.name_room || '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-12">
                    <h5>Daftar Tamu</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Gender</th>
                                <th>HP</th>
                                <th>Email</th>
                                <th>NIK</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(p, i) in state.bookingDetail.booking?.passengers || []" :key="i">
                                <td>{{ i + 1 }}</td>
                                <td>{{ p.guest_name }}</td>
                                <td>{{ p.guest_gender == '0' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td>{{ p.guest_phone }}</td>
                                <td>{{ p.guest_email }}</td>
                                <td>{{ p.guest_nik }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </a-modal>

    </div>
</template>

<script setup>

    import { apiGetData, apiCetakPDF, apiExportExcel, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
    import axios from 'axios';
    import { useDebounceFn } from '@vueuse/core'
    import { ref, reactive, onUnmounted, onMounted, computed , watch, nextTick} from 'vue'
    import { useStore } from "vuex";
    import { useRouter } from "vue-router";
    import iconSpritePath from '@/assets/svg/icon-sprite.svg';
    import crm from './crm.vue';
    import {
        EyeOutlined,
        ReloadOutlined,
        FileExcelOutlined
    } from '@ant-design/icons-vue';
    import checkRole from '@/store/modules/role.js';
    import Tabs from 'primevue/tabs';
    import TabList from 'primevue/tablist';
    import Tab from 'primevue/tab';
    import TabPanels from 'primevue/tabpanels';
    import TabPanel from 'primevue/tabpanel';
    import FullCalendar from "@fullcalendar/vue3";
    import dayGridPlugin from '@fullcalendar/daygrid';
    import timeGridPlugin from '@fullcalendar/timegrid'
    import interactionPlugin from '@fullcalendar/interaction';
    import resourceTimelinePlugin from '@fullcalendar/resource-timeline';
    import resourceTimeGridPlugin from '@fullcalendar/resource-timegrid';
    const isSuperAdmin = checkRole(['superAdmin','admin']);
    const isStaff = checkRole(['properties']);
    const isReceptionis = checkRole(['receptionis']);
    const admincro = checkRole(['admincro']);
    const store = useStore();
    const router = useRouter();
    const user = store.getters["auth/currentUser"];
    const chartOptions = ref({});
    const series = ref([]);
    const weeklyOptions = ref({});
    const weeklySeries = ref([]);
    const monthlyOptions = ref({});
    const monthlySeries = ref([]);
    const yearsOptions = ref({});
    const yearsSeries = ref([]);
    const pieSeries = ref([]);
    const pieOptions = ref({});
    const interval = ref(null);
    const pagging = ref(5);
    const modalPDF = ref(false);
    const pdfUrl = ref("");
    const need_attention = ref([]);
    const activeTab = ref('0');
    const bookingStatusDailySeries = ref([]);
    const bookingStatusWeeklySeries = ref([]);
    const bookingStatusMonthlySeries = ref([]);
    const bookingStatusYearlySeries = ref([]);
    const bookingStatusOptions = ref({});
    const bookingStatusWeeklyOptions = ref({});
    const bookingStatusMonthlyOptions = ref({});
    const bookingStatusYearlyOptions = ref({});


     // Membership Status Trend
    const membershipStatusDailySeries = ref([]);
    const membershipStatusWeeklySeries = ref([]);
    const membershipStatusMonthlySeries = ref([]);
    const membershipStatusYearlySeries = ref([]);
    const membershipStatusOptions = ref({});
    const membershipStatusWeeklyOptions = ref({});
    const membershipStatusMonthlyOptions = ref({});
    const membershipStatusYearlyOptions = ref({});


    // CRM Status Trend
    const crmDailySeries = ref([]);
    const crmWeeklySeries = ref([]);
    const crmMonthlySeries = ref([]);
    const crmYearlySeries = ref([]);
    const crmChartOptions = ref({});
    const crmChartWeeklyOptions = ref({});  
    const crmChartMonthlyOptions = ref({});
    const crmChartYearlyOptions = ref({});

    const crmSummary = ref({
        total_crm: 0,
        total_needfu: 0,
        total_followup: 0,
        total_closing: 0,
        total_lost: 0,
    });


    // Filter states
    const selectedYear = ref(parseInt(dayjs().format('YYYY')));

    //Boking
    const DataBooking = ref([]);
    const DataMembership = ref([]);

    const filterDateBooking = ref(dayjs());
    const filterStatusRoom = ref([]);
    const searchBooking = ref("");
    const loadingBooking = ref(false);
    const modalDetailBooking = ref(false);
    const modalDetailBookingUsers = ref(false);
    const filterProperty = ref("");

    const timerSettings = () => {
        const HOURHAND = document.querySelector("#hour");
        const MINUTEHAND = document.querySelector("#minute");
        const SECONDHAND = document.querySelector("#second");
        const txtClock = document.getElementById("txt");

        // Pastikan elemen ditemukan
        if (!HOURHAND || !MINUTEHAND || !SECONDHAND || !txtClock) {
        console.error("Element jam tidak ditemukan di DOM!");
        return;
        }

        function runClock() {
            const date = new Date();
            let hr = date.getHours();
            let min = date.getMinutes();
            let sec = date.getSeconds();
            const ampm = hr >= 12 ? "PM" : "AM";

            // Konversi ke format 12 jam
            hr = hr % 12 || 12;

            // Format agar selalu dua digit
            const formattedHr = hr.toString().padStart(2, "0");
            const formattedMin = min.toString().padStart(2, "0");
            const formattedSec = sec.toString().padStart(2, "0");

            // Hitung posisi jarum jam
            const hrPosition = (hr * 360) / 12 + (min * (360 / 60)) / 12;
            const minPosition = (min * 360) / 60 + (sec * (360 / 60)) / 60;
            const secPosition = (sec * 360) / 60;

            // Update posisi jarum jam
            HOURHAND.style.transform = `rotate(${hrPosition}deg)`;
            MINUTEHAND.style.transform = `rotate(${minPosition}deg)`;
            SECONDHAND.style.transform = `rotate(${secPosition}deg)`;

            // Update teks jam
            txtClock.innerHTML = `${formattedHr}:${formattedMin}:${formattedSec} ${ampm}`;
        }

        // Jalankan clock langsung agar tidak menunggu 1 detik pertama
        runClock();

        // Interval untuk memperbarui setiap detik
        setInterval(runClock, 1000);
    };

    const currentTime = ref(dayjs().hour());

    const greeting = computed(() => {
        if (currentTime.value >= 5 && currentTime.value < 12) {
            return "Good Morning ☀️";
        } else if (currentTime.value >= 12 && currentTime.value < 18) {
            return "Good Afternoon 🌤️";
        } else if (currentTime.value >= 18 && currentTime.value < 22) {
            return "Good Evening 🌆";
        } else {
            return "Good Night 🌙";
        }
    });


    const state = reactive({
        data:{},
        listTransaction:{},
        listProperty:{},
        room_availability_calendar:{},
        bookingDetail: {},
        selectedBookingCell: {},
        listGetProperty:{},
        userAchievement:{},
    });

    const getBookingStatusColor = (status) => {
        const normalizedStatus = String(status || '').toUpperCase();

        if (normalizedStatus === 'PAID') return 'green';
        if (normalizedStatus === 'PENDING') return 'orange';
        if (normalizedStatus === 'PREPARED') return 'gold';
        if (normalizedStatus === 'BLOCKED') return 'red';
        return 'red';
    };


    const clear = async () => {
        selectedFilter.value = 'today';
        customStart.value = dayjs().startOf('day').format('YYYY-MM-DD');
        customEnd.value = dayjs().endOf('day').format('YYYY-MM-DD');
        await getData();
    };

    const getData = async (page = state.listProperty.current_page || 1) => {

        loading.value = true;
        const params = {page: page, selectedFilter: selectedFilter.value, customStart: customStart.value, customEnd: customEnd.value, pagging: pagging.value };
        
        const response = await apiGetData("/dashboard/summary", params);
        state.data = response.data;
        state.listTransaction = response.live_transaction_feed;
        need_attention.value = response.need_attention || [];
        state.listProperty = response.property_performance || [];
        DataBooking.value = response.dataBooking || [];
        DataMembership.value = response.dataMembership || [];

        // Daily
        const daily = response.transaction_chart?.daily || {};
        series.value = [
            { name: 'Topup', data: daily.topup || [] },
            { name: 'Booking', data: daily.booking || [] },
            { name: 'Membership', data: daily.membership || [] },
        ];
        chartOptions.value = {
            chart: {
                height: 350,
                type: 'bar',
                stacked: false,
                toolbar: { show: true }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'bottom',
                markers: {
                    width: 16,
                    height: 16,
                    radius: 4
                }
            },
            xaxis: {
                categories: daily.dates || [],
                labels: {
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            yaxis: {
                min: 0,
                title: {
                    text: '$ (thousands)',
                    style: {
                        color: '#222',
                        fontSize: '14px'
                    }
                },
                labels: {
                    formatter: val => 'Rp ' + val.toLocaleString(),
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: val => 'Rp ' + val.toLocaleString()
                }
            },
            fill: {
                opacity: 1
            },
            grid: {
                borderColor: '#e0e0e0'
            }
        };

        // Weekly
        const weekly = response.transaction_chart?.weekly || {};
        weeklySeries.value = [
            { name: 'Topup', data: weekly.topup || [] },
            { name: 'Booking', data: weekly.booking || [] },
            { name: 'Membership', data: weekly.membership || [] },
        ];
        weeklyOptions.value = {
            chart: {
                height: 350,
                type: 'bar',
                stacked: false,
                toolbar: { show: true }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'bottom',
                markers: {
                    width: 16,
                    height: 16,
                    radius: 4
                }
            },
            xaxis: {
                categories: weekly.dates || [],
                labels: {
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            yaxis: {
                min: 0,
                title: {
                    text: '$ (thousands)',
                    style: {
                        color: '#222',
                        fontSize: '14px'
                    }
                },
                labels: {
                    formatter: val => 'Rp ' + val.toLocaleString(),
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: val => val
                }
            },
            fill: {
                opacity: 1
            },
            grid: {
                borderColor: '#e0e0e0'
            }
        };

        // Monthly
        const monthly = response.transaction_chart?.monthly || {};
        monthlySeries.value = [
            { name: 'Topup', data: monthly.topup || [] },
            { name: 'Booking', data: monthly.booking || [] },
            { name: 'Membership', data: monthly.membership || [] },
        ];
        monthlyOptions.value = {
            chart: {
                height: 350,
                type: 'bar',
                stacked: false,
                toolbar: { show: true }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'bottom',
                markers: {
                    width: 16,
                    height: 16,
                    radius: 4
                }
            },
            xaxis: {
                categories: monthly.dates || [],
                labels: {
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            yaxis: {
                min: 0,
                title: {
                    text: 'Rp (dalam ribuan)',
                    style: {
                        color: '#222',
                        fontSize: '14px'
                    },
                },
                labels: {
                    formatter: val => 'Rp ' + val.toLocaleString(),
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: val => 'Rp ' + val.toLocaleString()
                }
            },
            fill: {
                opacity: 1
            },
            grid: {
                borderColor: '#e0e0e0'
                        }
        };

        // Yearly
        const yearly = response.transaction_chart?.yearly || {};
        yearsSeries.value = [
            { name: 'Topup', data: yearly.topup || [] },
            { name: 'Booking', data: yearly.booking || [] },
            { name: 'Membership', data: yearly.membership || [] },
        ];

        yearsOptions.value = {
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 5,
                    borderRadiusApplication: 'end',
                    dataLabels: {
                        position: 'top'
                    }
                },
            },
            dataLabels: {
                enabled: true,
                formatter: val => 'Rp ' + val.toLocaleString(),
                offsetY: -10,
                style: {
                    fontSize: '12px',
                    colors: ['#222']
                }
            },
                stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['Jan','Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct','Nov','Dec'],
            },
            yaxis: {
                title: {
                    text: 'Rp (dalam ribuan)'
                },
                labels: {
                    formatter: val => 'Rp ' + val.toLocaleString()
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: val => 'Rp ' + val.toLocaleString()
                }
            }
        };

        // Pie chart
        const composition = response.transaction_composition || {};
        pieSeries.value = [composition.booking || 0, composition.topup || 0, composition.membership || 0];
        pieOptions.value = {
            chart: {
                type: 'donut',
                height: 350
            },
            labels: ['Booking', 'Topup', 'Membership'],
            colors: ['#4CAF50', '#2196F3', '#FF9800'],
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                formatter: val => val + '%',
                style: {
                    fontSize: '14px',
                    colors: ['#ffffff']
                }
            },
            tooltip: {
                y: {
                    formatter: val => val + '%'
                }
            }
        };


         // Daily
        const dailyBooking = response.booking_status_trend?.daily || {};
        bookingStatusDailySeries.value = [
            { name: 'PENDING', data: dailyBooking.pending || [] },
            { name: 'PAID', data: dailyBooking.paid || [] },
            { name: 'CANCELLED', data: dailyBooking.cancelled || [] },
            { name: 'EXPIRED', data: dailyBooking.expired || [] },
            { name: 'COMPLETED', data: dailyBooking.completed || [] },
            { name: 'BLOCKED', data: dailyBooking.blocked || [] },
        ];

        bookingStatusOptions.value = {
            chart: {
                height: 350,
                type: 'bar',
                stacked: false,
                toolbar: { show: true }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'bottom',
                markers: {
                    width: 16,
                    height: 16,
                    radius: 4
                }
            },
            xaxis: {
                categories: dailyBooking.dates || [],
                labels: {
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            yaxis: {
                min: 0,
                title: {
                    style: {
                        color: '#222',
                        fontSize: '14px'
                    }
                },
                labels: {
                    formatter: val => val,
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: val => val
                }
            },
            fill: {
                opacity: 1
            },
            grid: {
                borderColor: '#e0e0e0'
            }
        };

        // Weekly
        const weeklyBooking = response.booking_status_trend?.weekly || {};
        bookingStatusWeeklySeries.value = [
            { name: 'PENDING', data: weeklyBooking.pending || [] },
            { name: 'PAID', data: weeklyBooking.paid || [] },
            { name: 'CANCELLED', data: weeklyBooking.cancelled || [] },
            { name: 'EXPIRED', data: weeklyBooking.expired || [] },
            { name: 'COMPLETED', data: weeklyBooking.completed || [] },
            { name: 'BLOCKED', data: weeklyBooking.blocked || [] },
        ];

        bookingStatusWeeklyOptions.value = {
            chart: {
                height: 350,
                type: 'bar',
                stacked: false,
                toolbar: { show: true }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'bottom',
                markers: {
                    width: 16,
                    height: 16,
                    radius: 4
                }
            },
            xaxis: {
                categories: weeklyBooking.dates || [],
                labels: {
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            yaxis: {
                min: 0,
                title: {
                    style: {
                        color: '#222',
                        fontSize: '14px'
                    }
                },
                labels: {
                    formatter: val =>  val,
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: val => val
                }
            },
            fill: {
                opacity: 1
            },
            grid: {
                borderColor: '#e0e0e0'
            }
        };

        // Monthly
        const monthlyBooking = response.booking_status_trend?.monthly || {};
        bookingStatusMonthlySeries.value = [
            { name: 'PENDING', data: monthlyBooking.pending || [] },
            { name: 'PAID', data: monthlyBooking.paid || [] },
            { name: 'CANCELLED', data: monthlyBooking.cancelled || [] },
            { name: 'EXPIRED', data: monthlyBooking.expired || [] },
            { name: 'COMPLETED', data: monthlyBooking.completed || [] },
            { name: 'BLOCKED', data: monthlyBooking.blocked || [] },
        ];
        bookingStatusMonthlyOptions.value = {
            chart: {
                height: 350,
                type: 'bar',
                stacked: false,
                toolbar: { show: true }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'bottom',
                markers: {
                    width: 16,
                    height: 16,
                    radius: 4
                }
            },
            xaxis: {
                categories: monthlyBooking.dates || [],
                labels: {
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            yaxis: {
                min: 0,
                title: {
                    style: {
                        color: '#222',
                        fontSize: '14px'
                    },
                },
                labels: {
                    formatter: val => val,
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: val => val
                }
            },
            fill: {
                opacity: 1
            },
            grid: {
                borderColor: '#e0e0e0'
                        }
        };

        // Yearly
        const yearlyBooking = response.booking_status_trend?.yearly || {};
        bookingStatusYearlySeries.value = [
            { name: 'PENDING', data: yearlyBooking.pending || [] },
            { name: 'PAID', data: yearlyBooking.paid || [] },
            { name: 'CANCELLED', data: yearlyBooking.cancelled || [] },
            { name: 'EXPIRED', data: yearlyBooking.expired || [] },
            { name: 'COMPLETED', data: yearlyBooking.completed || [] },
            { name: 'BLOCKED', data: yearlyBooking.blocked || [] },
        ];

        bookingStatusYearlyOptions.value = {
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 5,
                    borderRadiusApplication: 'end',
                    dataLabels: {
                        position: 'top'
                    }
                },
            },
            dataLabels: {
                enabled: true,
                formatter: val => val,
                offsetY: -10,
                style: {
                    fontSize: '12px',
                    colors: ['#222']
                }
            },
                stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['Jan','Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct','Nov','Dec'],
            },
            yaxis: {
                labels: {
                    formatter: val => val
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: val => val
                }
            }
        };

        // Membership Status Trend
        const membershipStatusTrend = response.membership_status_trend || {};
        // Daily
        membershipStatusDailySeries.value = [
            { name: 'PENDING', data: membershipStatusTrend.daily?.pending || [] },
            { name: 'ACTIVE', data: membershipStatusTrend.daily?.active || [] },
            { name: 'EXPIRED', data: membershipStatusTrend.daily?.expired || [] },
            { name: 'CANCELLED', data: membershipStatusTrend.daily?.cancelled || [] },
        ];

         // Chart options
        membershipStatusOptions.value = {
            chart: {
                height: 350,
                type: 'bar',
                stacked: false,
                toolbar: { show: true }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'bottom',
                markers: {
                    width: 16,
                    height: 16,
                    radius: 4
                }
            },
            xaxis: {
                categories: membershipStatusTrend.daily?.dates || [],
                labels: {
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            yaxis: {
                min: 0,
                title: {
                    text: 'Total Membership',
                    style: {
                        color: '#222',
                        fontSize: '14px'
                    }
                },
                labels: {
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: val => val
                }
            },
            fill: {
                opacity: 1
            },
            grid: {
                borderColor: '#e0e0e0'
            }
        };

        // Weekly
        membershipStatusWeeklySeries.value = [
            { name: 'PENDING', data: membershipStatusTrend.weekly?.pending || [] },
            { name: 'ACTIVE', data: membershipStatusTrend.weekly?.active || [] },
            { name: 'EXPIRED', data: membershipStatusTrend.weekly?.expired || [] },
            { name: 'CANCELLED', data: membershipStatusTrend.weekly?.cancelled || [] },
        ];

        membershipStatusWeeklyOptions.value = {
            chart: {
                height: 350,
                type: 'bar',
                stacked: false,
                toolbar: { show: true }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'bottom',
                markers: {
                    width: 16,
                    height: 16,
                    radius: 4
                }
            },
            xaxis: {
                categories: membershipStatusTrend.weekly?.dates || [],
                labels: {
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            yaxis: {
                min: 0,
                title: {
                    text: 'Total Membership',
                    style: {
                        color: '#222',
                        fontSize: '14px'
                    }
                },
                labels: {
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: val => val
                }
            },
            fill: {
                opacity: 1
            },
            grid: {
                borderColor: '#e0e0e0'
            }
        };

        // Monthly
        membershipStatusMonthlySeries.value = [
            { name: 'PENDING', data: membershipStatusTrend.monthly?.pending || [] },
            { name: 'ACTIVE', data: membershipStatusTrend.monthly?.active || [] },
            { name: 'EXPIRED', data: membershipStatusTrend.monthly?.expired || [] },
            { name: 'CANCELLED', data: membershipStatusTrend.monthly?.cancelled || [] },
        ];

        membershipStatusMonthlyOptions.value = {
            chart: {
                height: 350,
                type: 'bar',
                stacked: false,
                toolbar: { show: true }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'bottom',
                markers: {
                    width: 16,
                    height: 16,
                    radius: 4
                }
            },
            xaxis: {
                categories: membershipStatusTrend.monthly?.dates || [],
                labels: {
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            yaxis: {
                min: 0,
                title: {
                    text: 'Total Membership',
                    style: {
                        color: '#222',
                        fontSize: '14px'
                    }
                },
                labels: {
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: val => val
                }
            },
            fill: {
                opacity: 1
            },
            grid: {
                borderColor: '#e0e0e0'
            }
        };

        // Yearly
        membershipStatusYearlySeries.value = [
            { name: 'PENDING', data: membershipStatusTrend.yearly?.pending || [] },
            { name: 'ACTIVE', data: membershipStatusTrend.yearly?.active || [] },
            { name: 'EXPIRED', data: membershipStatusTrend.yearly?.expired || [] },
            { name: 'CANCELLED', data: membershipStatusTrend.yearly?.cancelled || [] },
        ];
       
        membershipStatusYearlyOptions.value = {
            chart: {
                height: 350,
                type: 'bar',
                stacked: false,
                toolbar: { show: true }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'bottom',
                markers: {
                    width: 16,
                    height: 16,
                    radius: 4
                }
            },

            xaxis: {
                categories: ['Jan','Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct','Nov','Dec'],
                labels: {
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            yaxis: {
                min: 0,
                title: {
                    text: 'Total Membership',
                    style: {
                        color: '#222',
                        fontSize: '14px'
                    }
                },
                labels: {
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },

            tooltip: {
                y: {
                    formatter: val => val
                }
            },
            fill: {
                opacity: 1
            },
            grid: {
                borderColor: '#e0e0e0'
            }
        };


        const crmChart = response.crm_status_trend || {};
        crmDailySeries.value = [
            { name: 'NEEDFU', data: crmChart.daily?.needfu || [] },
            { name: 'FOLLOWUP', data: crmChart.daily?.followup || [] },
            { name: 'CLOSING', data: crmChart.daily?.closing || [] },
            { name: 'LOST', data: crmChart.daily?.lost || [] },
        ];
        crmWeeklySeries.value = [
            { name: 'NEEDFU', data: crmChart.weekly?.needfu || [] },
            { name: 'FOLLOWUP', data: crmChart.weekly?.followup || [] },
            { name: 'CLOSING', data: crmChart.weekly?.closing || [] },
            { name: 'LOST', data: crmChart.weekly?.lost || [] },
        ];
        crmMonthlySeries.value = [
            { name: 'NEEDFU', data: crmChart.monthly?.needfu || [] },
            { name: 'FOLLOWUP', data: crmChart.monthly?.followup || [] },
            { name: 'CLOSING', data: crmChart.monthly?.closing || [] },
            { name: 'LOST', data: crmChart.monthly?.lost || [] },
        ];
        crmYearlySeries.value = [
            { name: 'NEEDFU', data: crmChart.yearly?.needfu || [] },
            { name: 'FOLLOWUP', data: crmChart.yearly?.followup || [] },
            { name: 'CLOSING', data: crmChart.yearly?.closing || [] },
            { name: 'LOST', data: crmChart.yearly?.lost || [] },
        ];

        crmChartOptions.value = {
            chart: {
                height: 350,
                type: 'bar',
                stacked: false,
                toolbar: { show: true }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4,
                }
            },
            dataLabels: { enabled: false },
            legend: { position: 'bottom' },
            xaxis: {
                categories: crmChart.daily?.dates || [],
                labels: { style: { colors: '#222', fontSize: '14px' } }
            },
            yaxis: {
                min: 0,
                title: { text: 'Total CRM', style: { color: '#222', fontSize: '14px' } },
                labels: { style: { colors: '#222', fontSize: '14px' } }
            },
            tooltip: { y: { formatter: val => val } },
            fill: { opacity: 1 },
            grid: { borderColor: '#e0e0e0' }
        };

        crmChartWeeklyOptions.value = {
            chart: {
                height: 350,
                type: 'bar',
                stacked: false,
                toolbar: { show: true }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4,
                }
            },
            dataLabels: { enabled: false },
            legend: { position: 'bottom' },
            xaxis: {
                categories: crmChart.weekly?.dates || [],
                labels: { style: { colors: '#222', fontSize: '14px' } }
            },
            yaxis: {
                min: 0,
                title: { text: 'Total CRM', style: { color: '#222', fontSize: '14px' } },
                labels: { style: { colors: '#222', fontSize: '14px' } }
            },
            tooltip: { y: { formatter: val => val } },
            fill: { opacity: 1 },
            grid: { borderColor: '#e0e0e0' }
        };

        crmChartMonthlyOptions.value = {
            chart: {
                height: 350,
                type: 'bar',
                stacked: false,
                toolbar: { show: true }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4,
                }
            },
            dataLabels: { enabled: false },
            legend: { position: 'bottom' },
            xaxis: {
                categories: crmChart.monthly?.dates || [],
                labels: { style: { colors: '#222', fontSize: '14px' } }
            },
            yaxis: {
                min: 0,
                title: { text: 'Total CRM', style: { color: '#222', fontSize: '14px' } },
                labels: { style: { colors: '#222', fontSize: '14px' } }
            },
            tooltip: { y: { formatter: val => val } },
            fill: { opacity: 1 },
            grid: { borderColor: '#e0e0e0' }
        };

        crmChartYearlyOptions.value = {
            chart: {
                height: 350,
                type: 'bar',
                stacked: false,
                toolbar: { show: true }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4,
                }
            },
            dataLabels: { enabled: false },
            legend: { position: 'bottom' },
            xaxis: {
                categories: ['Jan','Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct','Nov','Dec'],
                labels: { style: { colors: '#222', fontSize: '14px' } }
            },
            yaxis: {
                min: 0,
                title: { text: 'Total CRM', style: { color: '#222', fontSize: '14px' } },
                labels: { style: { colors: '#222', fontSize: '14px' } }
            },
            tooltip: { y: { formatter: val => val } },
            fill: { opacity: 1 },
            grid: { borderColor: '#e0e0e0' }
        };

        // CRM Summary
        crmSummary.value = response.dataCrm || {
            total_crm: 0,
            total_needfu: 0,
            total_followup: 0,
            total_closing: 0,
            total_lost: 0,
        };

        state.userAchievement = response.user_achievement || {};
        
        loading.value = false;
    };

    const handlePageChange = (page) => {
        getData(page)
    }


     // Filter states
    const selectedFilter = ref('today');
    const customStart = ref('');
    const customEnd = ref('');
       

    // Label filter
    const filterLabel = computed(() => {
        if (selectedFilter.value === 'today') return 'Hari ini';
        if (selectedFilter.value === 'month') return 'Bulan ini';
        if (selectedFilter.value === 'year') return 'Tahun ini';
        if (selectedFilter.value === 'custom') {
            if (customStart.value && customEnd.value) {
                return `${customStart.value} s/d ${customEnd.value}`;
            }
            return 'Custom Filter';
        }
        return '';
    });

    // Update handleFilterChange
    const filter = async () => {
        let params = {};
        if (selectedFilter.value === 'today') {
            params.filter = 'today';
        } else if (selectedFilter.value === 'month') {
            params.filter = 'month';
        } else if (selectedFilter.value === 'year') {
            params.filter = 'year';
        } else if (selectedFilter.value === 'custom') {
            params.filter = 'custom';
            params.start = customStart.value;
            params.end = customEnd.value;
        }
        // Panggil API dashboard dengan params filter
        await getData(params);
    };



    const exportReport = async () => {
        processing.value = true
        pesan.value="Harap Sabar, Lagi Proses Export"

        const response= await apiExportExcel("/dashboard/exportexcelReport", {
            year: selectedYear.value
        }, 'Summary Data Sales Askara Aktiv')

        if(response){
            processing.value = false
        }else{
            processing.value = false
        }
    };

    const calendarKey = ref(0)
    const calendarRef = ref(null)
    const calendarModalKey = ref(0)
    const calendarModalRef = ref(null)
    const modalCalendarFullscreen = ref(false)

    const calendarEvents = ref([]);
    const getSubRoomResourceId = (room, subRoom) => {
        const roomKey = room?.room_id || room?.id || room?.odata || 'room';
        const subRoomKey = subRoom?.odata || subRoom?.code_room || subRoom?.name_room || 'sub';
        return `sub-${roomKey}-${subRoomKey}`;
    };

    const normalizeStatus = (status) => String(status || '').toUpperCase();

    const isPreparedStatus = (status) => normalizeStatus(status) === 'PREPARED';
    const isBlockedStatus = (status) => normalizeStatus(status) === 'BLOCKED';
    const isBookedStatus = (status) => {
        const normalizedStatus = normalizeStatus(status);
        return ['PENDING', 'PAID', 'BOOKED', 'CONFIRMED'].includes(normalizedStatus);
    };
    const isAvailableStatus = (status) => {
        const normalizedStatus = normalizeStatus(status);
        return ['AVAILABLE', 'CANCELLED', 'EXPIRED', 'COMPLETED'].includes(normalizedStatus);
    };

    const isBookingDetailStatus = (status) => {
        const normalizedStatus = normalizeStatus(status);
        return isBookedStatus(normalizedStatus);
    };

    const getCalendarStatusPresentation = (status) => {
        const normalizedStatus = normalizeStatus(status);

        if (isPreparedStatus(normalizedStatus)) {
            return {
                title: 'Prepared',
                color: '#f59e0b'
            };
        }

        if (isBlockedStatus(normalizedStatus)) {
            return {
                title: 'Blocked (Offline/Other)',
                color: '#dc3545'
            };
        }

        if (isBookedStatus(normalizedStatus)) {
            return {
                title: 'Booked',
                color: '#007bff'
            };
        }

        return {
            title: 'Available',
            color: '#28a745'
        };
    };

    const calendarResources = computed(() => {
        if (!state.room_availability_calendar?.rooms) return [];
        return state.room_availability_calendar.rooms.map(room => ({
            id: String(room.room_id),
            title: room.room_name,
            children: (room.sub_rooms || []).map(sub => ({
                id: getSubRoomResourceId(room, sub),
                title: [sub.code_room, sub.name_room].filter(Boolean).join(' - ') || 'Sub Room'
            }))
        }));
    });

    const getBookingAvailability = async () => {
        loadingBooking.value = true;
        const params = {
            property_id: filterProperty.value,
        };
        const response = await apiGetData("/dashboard/booking-availability", params);
        state.room_availability_calendar = response.data.room_availability_calendar || [];
        calendarEvents.value = [];
        if (state.room_availability_calendar?.rooms) {
            state.room_availability_calendar.rooms.forEach(room => {
                const roomSubRooms = room.sub_rooms || [];
                const roomCalendar = room.calendar || [];

                if (roomSubRooms.length > 0) {
                    const eventMap = new Map();

                    roomCalendar.forEach((cell) => {
                        const endDate = dayjs(cell.date).add(1, 'day').format('YYYY-MM-DD');

                        roomSubRooms.forEach((subRoom) => {
                            const subResourceId = getSubRoomResourceId(room, subRoom);
                            const key = `${subResourceId}-${cell.date}`;
                            const roomLabel = room.room_name || '';
                            const subRoomLabel = [subRoom.code_room, subRoom.name_room].filter(Boolean).join(' - ');

                            if (!eventMap.has(key)) {
                                eventMap.set(key, {
                                    id: `${room.room_id}-${subRoom.odata || subRoom.code_room || subRoom.name_room}-${cell.date}`,
                                    resourceId: subResourceId,
                                    title: ['Available', roomLabel, subRoomLabel].filter(Boolean).join(' • '),
                                    start: cell.date,
                                    end: endDate,
                                    backgroundColor: '#28a745',
                                    borderColor: '#28a745',
                                    display: 'block',
                                    extendedProps: {
                                        room_id: room.room_id,
                                        room_name: room.room_name,
                                        status: 'available',
                                        sub_rooms: roomSubRooms,
                                        sub_room_odata: subRoom.odata || null,
                                        sub_room_code: subRoom.code_room || null,
                                        sub_room_name: subRoom.name_room || null,
                                        booking_user: null,
                                        can_block: cell.can_block,
                                        can_open: cell.can_open,
                                        type_booking: cell.type || 'online',
                                        can_cancel: false,
                                        booking_odata: null,
                                    }
                                });
                            }
                        });

                        if (!isAvailableStatus(cell.status)) {
                            const targetSubOdata = cell.sub_room_odata || null;
                            const targetSubRoom = roomSubRooms.find((subRoom) => {
                                return targetSubOdata && subRoom.odata === targetSubOdata;
                            });

                            const targetResourceId = targetSubRoom
                                ? getSubRoomResourceId(room, targetSubRoom)
                                : (targetSubOdata ? `sub-${room.room_id}-${targetSubOdata}` : null);

                            if (targetResourceId) {
                                const key = `${targetResourceId}-${cell.date}`;
                                const presentation = getCalendarStatusPresentation(cell.status);
                                let color = presentation.color;
                                const roomLabel = room.room_name || '';

                                const subRoomLabel = [cell.sub_room_code, cell.sub_room_name].filter(Boolean).join(' - ');
                                const title = [presentation.title, roomLabel, subRoomLabel].filter(Boolean).join(' • ')
                                    + (cell.booking_user ? ` • ${cell.booking_user}` : '');

                                eventMap.set(key, {
                                    id: `${room.room_id}-${targetSubOdata || key}-${cell.date}`,
                                    resourceId: targetResourceId,
                                    title,
                                    start: cell.date,
                                    end: endDate,
                                    backgroundColor: color,
                                    borderColor: color,
                                    display: 'block',
                                    extendedProps: {
                                        room_id: room.room_id,
                                        room_name: room.room_name,
                                        status: cell.status,
                                        sub_rooms: roomSubRooms,
                                        sub_room_odata: cell.sub_room_odata || null,
                                        sub_room_code: cell.sub_room_code || null,
                                        sub_room_name: cell.sub_room_name || null,
                                        booking_user: cell.booking_user,
                                        can_block: cell.can_block,
                                        can_open: cell.can_open,
                                        type_booking: cell.type || 'online',
                                        can_cancel: cell.type === 'offline',
                                        booking_odata: cell.booking_odata || null,
                                    }
                                });
                            }
                        }
                    });

                    calendarEvents.value.push(...Array.from(eventMap.values()));
                    return;
                }

                roomCalendar.forEach((cell) => {
                    const endDate = dayjs(cell.date).add(1, 'day').format('YYYY-MM-DD');
                    const presentation = getCalendarStatusPresentation(cell.status);
                    let color = presentation.color;
                    let title = presentation.title + (cell.booking_user ? ` (${cell.booking_user})` : '');

                    calendarEvents.value.push({
                        id: `${room.room_id}-${cell.date}`,
                        resourceId: String(room.room_id),
                        title,
                        start: cell.date,
                        end: endDate,
                        backgroundColor: color,
                        borderColor: color,
                        display: 'block',
                        extendedProps: {
                            room_id: room.room_id,
                            room_name: room.room_name,
                            status: cell.status,
                            sub_rooms: roomSubRooms,
                            sub_room_odata: cell.sub_room_odata || null,
                            sub_room_code: cell.sub_room_code || null,
                            sub_room_name: cell.sub_room_name || null,
                            booking_user: cell.booking_user,
                            can_block: cell.can_block,
                            can_open: cell.can_open,
                            type_booking: cell.type || 'online',
                            can_cancel: cell.type === 'offline',
                            booking_odata: cell.booking_odata || null,
                        }
                    });
                });
            });
        }
        loadingBooking.value = false;
    };

    const handleEventClick = async (info) => {
        const {
            room_id,
            room_name,
            status,
            can_block,
            can_open,
            booking_user,
            type_booking,
            can_cancel,
            booking_odata,
            sub_room_odata,
            sub_room_code,
            sub_room_name,
            sub_rooms,
        } = info.event.extendedProps;
        const date = info.event.startStr;

        state.selectedBookingCell = {
            room_id,
            room_name,
            date,
            status,
            booking_user,
            type_booking,
            can_cancel,
            sub_room_odata,
            sub_room_code,
            sub_room_name,
            sub_rooms: sub_rooms || [],
        };

        if (isAvailableStatus(status)) {
            await Swal.fire('Room Available', `Room ID: ${room_id}, Date: ${date}`, 'info');
        } else if (isBlockedStatus(status) || isPreparedStatus(status)) {
            const statusLabel = isPreparedStatus(status) ? 'PREPARED' : 'BLOCKED';
            await Swal.fire('Room Status', `Status ${statusLabel} (view only).\nRoom ID: ${room_id}, Date: ${date}`, 'info');
        } else if (isBookingDetailStatus(status)) {
            if (!booking_odata) {
                await Swal.fire('Detail Booking', 'Data booking tidak ditemukan untuk event ini.', 'info');
                return;
            }

            const params = {
                booking_odata
            };

            const response = await apiGetData('dashboard/booking-detail', params);
            state.bookingDetail = response.data;
            modalDetailBookingUsers.value = true;

        }
    };

    const handleEventHover = (info) => {
        const event = info.event;
        const props = event.extendedProps || {};
        const statusLabel = normalizeStatus(props.status || event.title || '');
        const roomLabel = props.room_name || '';
        const subRoomLabel = [props.sub_room_code, props.sub_room_name].filter(Boolean).join(' - ');
        const dateLabel = event.start ? dayjs(event.start).format('DD MMM YYYY') : '';
        const guestLabel = props.booking_user ? `Guest: ${props.booking_user}` : '';

        const hoverTitle = [statusLabel, roomLabel, subRoomLabel, dateLabel, guestLabel]
            .filter(Boolean)
            .join(' • ');

        info.el.setAttribute('title', hoverTitle || String(event.title || ''));
    };

    const calendarOptions = reactive({
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',

        plugins: [
            dayGridPlugin,
            resourceTimelinePlugin,
            interactionPlugin
        ],

        initialView: "resourceTimelineDay",

        selectable: true,
        selectMirror: true,
        eventOverlap: false,

        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "resourceTimelineDay,resourceTimelineWeek,resourceTimelineMonth,managerGridMonth"
        },

        views: {
            resourceTimelineDay: {
            type: 'resourceTimeline',
            duration: { days: 1 },
            slotDuration: { days: 1 }
            },
            resourceTimelineWeek: {
            type: 'resourceTimeline',
            duration: { weeks: 1 },
            slotDuration: { days: 1 }
            },
            resourceTimelineMonth: {
            type: 'resourceTimeline',
            duration: { months: 1 },
            slotDuration: { days: 1 }
            },
            managerGridMonth: {
            type: 'dayGridMonth',
            buttonText: 'grid month',
            dayMaxEvents: true
            }
        },

        resources: calendarResources,
        events: calendarEvents,

        eventClick: handleEventClick,
        eventDidMount: handleEventHover,

    })

    const openCalendarFullscreen = async () => {
        modalCalendarFullscreen.value = true;
        await nextTick();
        calendarModalKey.value++;
        await nextTick();
        calendarModalRef.value?.getApi()?.updateSize();
    };

   


    const getProperty = async () => {
        const response = await apiGetData("/properties/get_properties", {});
        state.listGetProperty = response.data || {};
    };
    const resetFilterBookingAvailability = () => {
        searchBooking.value = "";
        filterStatusRoom.value = [];
        filterProperty.value = [];
        filterDateBooking.value = dayjs();
    };

    onMounted(async() => {
        if (isStaff) {
            router.push({ name: "index_properties" });
            return; // Stop execution immediately
        }else if (isReceptionis) {
            router.push({ name: "index_booking" });
            return; // Stop execution immediately
        }else if (admincro) {
            router.push({ name: "crm" });
            return; // Stop execution immediately
        }
        
        timerSettings()
        Promise.all([
            getData(),
            getBookingAvailability(),
            getProperty()
        ]);
    })

    onUnmounted(() => {
        clearTimeout(interval.value);
    })

    watch([searchBooking, filterDateBooking, filterStatusRoom, filterProperty], () => {
        getBookingAvailability();
    });

    watch(calendarEvents, () => {
        const mainApi = calendarRef.value?.getApi()
        if (mainApi) {
            mainApi.removeAllEvents()
            calendarEvents.value.forEach(e => mainApi.addEvent(e))
        }

        const modalApi = calendarModalRef.value?.getApi()
        if (modalApi) {
            modalApi.removeAllEvents()
            calendarEvents.value.forEach(e => modalApi.addEvent(e))
        }
    })

    watch(modalCalendarFullscreen, async (isOpen) => {
        if (!isOpen) return;
        await nextTick();
        calendarModalRef.value?.getApi()?.updateSize();
    })

    watch(activeTab, (val) => {
        // Pastikan tab Booking Availability benar-benar berisi FullCalendar
        // Cek juga jika FullCalendar visible
        if (val === '1') {
            setTimeout(() => {
                calendarKey.value++;
            }, 100); // beri delay agar DOM sudah render
        }
    });

</script>

<style scoped>
    .properties-title {
        color: #222 !important;
    }

    /* FullCalendar resource timeline min-height */
    .fc-resource-timeline .fc-scrollgrid {
        min-height: 200px;
    }

</style>