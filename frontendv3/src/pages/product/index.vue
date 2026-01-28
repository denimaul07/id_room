<template>
    <div>
        <div class="container-fluid pb-5">
            <div class="row">
                <Breadcrumbs main="Product" title="List Product" />

                <div class="product-grid">
                    <div class="feature-products">
                    
                        <div class="row">
                            <Tabs value="0" class="p-tab-active">
                                <TabList  class="p-tab-active">
                                    <Tab value="0">  <i class="fa fa-list" /> Product List</Tab>
                                    <Tab value="1"> <i class="fa fa-info-circle"></i> Detail Product List</Tab>
                                    <Tab value="2"> <i class="fa fa-stop-circle-o"></i> Detail Store Product List</Tab>
                                    <Tab value="3"> <i class="fa fa-sticky-note-o"></i> List Product In Store</Tab>
                                </TabList>
                                <TabPanels>
                                    <TabPanel value="0">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="row align-items-center">
                                                <div class="col-12 col-md-2">
                                                    <button class="btn btn-primary btn-large w-100" style="height: 50px;" @click="add">
                                                        Add Product
                                                    </button>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <form>
                                                        <div class="form-group m-0 position-relative">
                                                            <input
                                                                class="form-control"
                                                                type="search"
                                                                placeholder="Search.."
                                                                v-model="search"
                                                                title=""    
                                                            />
                                                            <i class="fa fa-search"></i>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="product-wrapper-grid">
                                                <div class="row">
                                                    <div class="col-xl-2 col-sm-2" v-for="data in 12" v-if="loading">
                                                        <div class="card">
                                                            <div class="product-box">
                                                                <div class="product-img">
                                                                    <a-skeleton-image size="large" />
                                                                </div>
                                                                <div class="product-details">
                                                                    <h4><a-skeleton-input :active="true" /></h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12" v-else-if="state.product.total===0">
                                                        <a-result
                                                            status="404"
                                                            title="Data Ditemukan"
                                                            sub-title="Tidak ada data produk yang sesuai dengan pencarian Anda."
                                                        />
                                                    </div>
                                                    <div class="col-xl-2 col-sm-2"
                                                        v-for="(product, index) in state.product.data" :key="index" v-else>
                                                        <div class="card">
                                                            <div class="product-box">
                                                                <div class="product-img">
                                                                    <div v-if="product.status_product === '0'" class="ribbon ribbon-success">Aktif</div>
                                                                    <div v-else class="ribbon ribbon-danger">Tidak Aktif</div>
                                                                    <div class="ribbon bg-dark ribbon-right">
                                                                        <a class="text-white cursor-pointer" @click="view(product)">Edit</a>
                                                                    </div>

                                                                    <a-image  :width="250" :height="200" 
                                                                        :src="`${pathUrl}storage/barang/${product.file}`" 
                                                                        fallback="data:image/png;base64,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"
                                                                        />
                                                                </div>
                                                                <div class="product-details">
                                                                    <h4>{{ product.product_code }}</h4>
                                                                    <p>{{ product.product_name }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row py-2">
                                                    <div class="card">
                                                        <div class="card-body row p-2">
                                                            <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                                                                Showing {{ state.product.from }} to {{ state.product.to }} of {{ state.product.total }} entries
                                                            </div>
                                                            <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                                                <a-pagination :current="state.product.current_page" :total="state.product.total" v-model:pageSize="pagging" @change="handlePageChange" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </TabPanel>
                                    <TabPanel value="1">
                                        <div class="row">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="d-flex gap-2">
                                                    <Button
                                                        label="Export Detail Product"
                                                        severity="primary"
                                                        icon="pi pi-file-excel" 
                                                        class="flex-auto btn btn-dark" 
                                                        size="small"
                                                        :loading="loadingSubmit"
                                                        :disabled="loadingButton"
                                                        @click="exportDetailProduct()"
                                                    />
                                                </div>

                                                <div class="ms-auto">
                                                    <a-input-search
                                                        v-model:value="searchList"
                                                        placeholder="Cari Data"
                                                        style="width: 300px"
                                                    />
                                                </div>
                                                <ProgressBar mode="indeterminate" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
                                            </div>

                                            <div class="table-responsive pt-2  d-md-block d-none">
                                                <table class="table">
                                                    <thead>
                                                        
                                                        <tr class="border-bottom-primary">
                                                            <th class="bg-primary text-nowrap text-center sticky-column">No</th>
                                                            <th class="bg-primary text-nowrap text-center">Product Code</th>
                                                            <th class="bg-primary text-nowrap text-center">Product Name</th>
                                                            <th class="bg-primary text-nowrap text-center">Gambar</th>
                                                            <th class="bg-primary text-nowrap  text-center sticky-columnAction">Action</th>
                                                            
                                                        </tr>
                                                    </thead>

                                                    <tbody>

                                                        <tr v-if="loading"> 
                                                            <td class="text-center" colspan="13"><a-skeleton active /></td>
                                                        </tr>

                                                        <tr v-else-if="state.productList.total==0">
                                                            <td class="text-center" colspan="13"><a-empty/></td>
                                                        </tr>
                                                        <tr v-for="(item, index) in state.productList.data" :key="item.odata" v-else>
                                                            <td class="text-center sticky-column">{{ state.productList.from + index }}</td>
                                                            <td class="text-nowrap text-center">{{ item.product_code }}</td>
                                                            <td class="text-nowrap text-center">{{ item.product_name }}</td>
                                                            <td class="text-nowrap text-center">
                                                                <a-image
                                                                    :width="50"
                                                                    :src="`${pathUrl}storage/barang/${item.file}`"
                                                                    fallback="data:image/png;base64,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"
                                                                />
                                                            </td>
                                                            <td class="text-nowrap text-center  sticky-columnAction">
                                                                <a-tooltip title="Edit Barang">
                                                                    <a-button type="primary" size="small" @click="viewProductList(item)" class="bg-dark">
                                                                        <template #icon>
                                                                            <EyeOutlined />
                                                                        </template>
                                                                    </a-button>
                                                                </a-tooltip>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="p-2 d-md-none d-block">
                                                <div v-if="loading" v-for="index in 3">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between">
                                                            <a-skeleton-input :active="true" />
                                                            <a-skeleton-input :active="true" size="small" class="align-self-center" />
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between">
                                                            <a-skeleton-input size="small" :active="true" />
                                                            <a-skeleton-input size="small" :active="true" />
                                                        </div>
                                                        <a-skeleton-input class="mt-2" :active="true" />
                                                    </div>
                                                    <div
                                                        class="py-2 card-footer d-flex justify-content-between text-primary text-opacity-75 small">
                                                        <a-skeleton-button :active="true" size="small" block="block" />
                                                    </div>
                                                    <div class="py-2 card-footer">
                                                        <a-skeleton-button :active="true" size="small" block="block" />
                                                    </div>
                                                    <div class="py-1 card-footer">
                                                        <a-skeleton-button :active="true" size="small" block="block" />
                                                    </div>
                                                </div>

                                                <div v-else-if="state.productList.total==0">
                                                    <div class="card-body">
                                                        <a-empty/>
                                                    </div>
                                                </div>

                                                <div v-else v-for="(item, index) in state.productList.data" :key="item.id" class="pb-2 card">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between">
                                                            <b>Product Code</b>
                                                            <small class="align-self-center">
                                                                <span class="badge badge-sm badge-primary text-white fw-semibold">{{ item.product_code }}</span>
                                                            
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between">
                                                            Product Name
                                                            <small class="text-muted align-self-center">
                                                                {{ item.product_name }}
                                                            </small>
                                                        </div>

                                                    
                                                    
                                                    </div>
                                            
                                                    <div class="py-1 card-footer d-flex justify-content-center">
                                                        <a-tooltip title="Edit Barang">
                                                            <a-button type="primary" size="small" @click="viewProductList(item)" class="bg-success me-3">
                                                                <template #icon>
                                                                    <EditOutlined />
                                                                </template>
                                                            </a-button>
                                                        </a-tooltip>
                                                    
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="row py-2">
                                                <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                                                    Showing {{ state.productList.from }} to {{ state.productList.to }} of {{ state.productList.total }} entries
                                                </div>
                                                <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                                    <a-pagination :current="state.productList.current_page" :total="state.productList.total" v-model:pageSize="paggingList" @change="handlePageChangeList" />
                                                </div>
                                            </div>
                                        </div>
                                    </TabPanel>
                                    <TabPanel value="2">
                                        <div class="row">
                                            <div class="d-flex justify-content-end mb-3">
                                                <a-input-search
                                                    v-model:value="searchStore"
                                                    placeholder="Cari Data"
                                                    style="width: 300px"
                                                />
                                            </div>

                                            <div class="table-responsive pt-2">
                                                <table class="table">
                                                    <thead>
                                                        
                                                        <tr class="border-bottom-primary">
                                                            <th class="bg-primary text-nowrap text-center sticky-column">No</th>
                                                            <th class="bg-primary text-nowrap text-center">Store Code</th>
                                                            <th class="bg-primary text-nowrap text-center">Store Name</th>
                                                            <th class="bg-primary text-nowrap text-center">Total Product Ready Add To Store</th>
                                                            <th class="bg-primary text-nowrap  text-center">Total Product In Display</th>
                                                            <th class="bg-primary text-nowrap  text-center">Total Return Product</th>
                                                            <th class="bg-primary text-nowrap  text-center sticky-columnAction">Action</th>
                                                            
                                                        </tr>
                                                    </thead>

                                                    <tbody>

                                                        <tr v-if="loading"> 
                                                            <td class="text-center" colspan="13"><a-skeleton active /></td>
                                                        </tr>

                                                        <tr v-else-if="state.store.total==0">
                                                            <td class="text-center" colspan="13"><a-empty/></td>
                                                        </tr>
                                                        <tr v-for="(item, index) in state.store.data" :key="item.odata" v-else>
                                                            <td class="text-center sticky-column">{{ state.store.from + index }}</td>
                                                            <td class="text-nowrap text-center">{{ item.kode }}</td>
                                                            <td class="text-nowrap text-center">{{ item.deptname}}</td>
                                                            <td class="text-nowrap text-center">{{ item.addproduct}}</td>
                                                            <td class="text-nowrap text-center">{{ item.total}}</td>
                                                            <td class="text-nowrap text-center">{{ item.returproduct}}</td>
                                                            <td class="text-nowrap text-center  sticky-columnAction">
                                                                <a-tooltip title="Detail">
                                                                    <a-button type="primary" size="small" @click="viewStore(item)" class="bg-dark">
                                                                        <template #icon>
                                                                            <EyeOutlined />
                                                                        </template>
                                                                    </a-button>
                                                                </a-tooltip>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="row py-2">
                                                <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                                                    Showing {{ state.store.from }} to {{ state.store.to }} of {{ state.store.total }} entries
                                                </div>
                                                <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                                    <a-pagination :current="state.store.current_page" :total="state.store.total" v-model:pageSize="paggingStore" @change="handlePageChangeStore" />
                                                </div>
                                            </div>
                                        </div>
                                    </TabPanel>
                                    <TabPanel value="3">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="d-flex gap-2">

                                            </div>

                                            <div class="ms-auto">
                                                <a-input-search
                                                    v-model:value="searchProductStore"
                                                    placeholder="Cari Data"
                                                    style="width: 300px"
                                                />
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center bg-dark">No</th>
                                                            <th class="text-center bg-dark">Image</th>
                                                            <th class="text-center bg-dark">Product Code</th>
                                                            <th class="text-center bg-dark">Product Name</th>
                                                            <th class="text-center bg-dark">Product Varian</th>
                                                            <th class="text-center bg-dark">Product Size</th>
                                                            <th class="text-center bg-dark">Product SKU</th>
                                                            <th class="text-center bg-dark">Stock</th>
                                                            <th class="text-center bg-dark">Store</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                
                                                        <tr v-if="loadingProductStore"> 
                                                            <td class="text-center" colspan="13"><a-skeleton active /></td>
                                                        </tr>

                                                        <tr v-else-if="state.productStoreList.total==0">
                                                            <td class="text-center" colspan="13"><a-empty/></td>
                                                        </tr>
                                                    
                                                        <tr v-for="(data, index) in state.productStoreList.data" :key="index" v-else>
                                                            <td class="text-center">{{ index + state.productStoreList.from }}</td>
                                                            <td class="text-center">
                                                                <a-image  :width="50" :height="50" 
                                                                    :src="`${pathUrl}storage/barang/${data.product_varian.image}`" 
                                                                    fallback="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMIAAADDCAYAAADQvc6UAAABRWlDQ1BJQ0MgUHJvZmlsZQAAKJFjYGASSSwoyGFhYGDIzSspCnJ3UoiIjFJgf8LAwSDCIMogwMCcmFxc4BgQ4ANUwgCjUcG3awyMIPqyLsis7PPOq3QdDFcvjV3jOD1boQVTPQrgSkktTgbSf4A4LbmgqISBgTEFyFYuLykAsTuAbJEioKOA7DkgdjqEvQHEToKwj4DVhAQ5A9k3gGyB5IxEoBmML4BsnSQk8XQkNtReEOBxcfXxUQg1Mjc0dyHgXNJBSWpFCYh2zi+oLMpMzyhRcASGUqqCZ16yno6CkYGRAQMDKMwhqj/fAIcloxgHQqxAjIHBEugw5sUIsSQpBobtQPdLciLEVJYzMPBHMDBsayhILEqEO4DxG0txmrERhM29nYGBddr//5/DGRjYNRkY/l7////39v///y4Dmn+LgeHANwDrkl1AuO+pmgAAADhlWElmTU0AKgAAAAgAAYdpAAQAAAABAAAAGgAAAAAAAqACAAQAAAABAAAAwqADAAQAAAABAAAAwwAAAAD9b/HnAAAHlklEQVR4Ae3dP3PTWBSGcbGzM6GCKqlIBRV0dHRJFarQ0eUT8LH4BnRU0NHR0UEFVdIlFRV7TzRksomPY8uykTk/zewQfKw/9znv4yvJynLv4uLiV2dBoDiBf4qP3/ARuCRABEFAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghgg0Aj8i0JO4OzsrPv69Wv+hi2qPHr0qNvf39+iI97soRIh4f3z58/u7du3SXX7Xt7Z2enevHmzfQe+oSN2apSAPj09TSrb+XKI/f379+08+A0cNRE2ANkupk+ACNPvkSPcAAEibACyXUyfABGm3yNHuAECRNgAZLuYPgEirKlHu7u7XdyytGwHAd8jjNyng4OD7vnz51dbPT8/7z58+NB9+/bt6jU/TI+AGWHEnrx48eJ/EsSmHzx40L18+fLyzxF3ZVMjEyDCiEDjMYZZS5wiPXnyZFbJaxMhQIQRGzHvWR7XCyOCXsOmiDAi1HmPMMQjDpbpEiDCiL358eNHurW/5SnWdIBbXiDCiA38/Pnzrce2YyZ4//59F3ePLNMl4PbpiL2J0L979+7yDtHDhw8vtzzvdGnEXdvUigSIsCLAWavHp/+qM0BcXMd/q25n1vF57TYBp0a3mUzilePj4+7k5KSLb6gt6ydAhPUzXnoPR0dHl79WGTNCfBnn1uvSCJdegQhLI1vvCk+fPu2ePXt2tZOYEV6/fn31dz+shwAR1sP1cqvLntbEN9MxA9xcYjsxS1jWR4AIa2Ibzx0tc44fYX/16lV6NDFLXH+YL32jwiACRBiEbf5KcXoTIsQSpzXx4N28Ja4BQoK7rgXiydbHjx/P25TaQAJEGAguWy0+2Q8PD6/Ki4R8EVl+bzBOnZY95fq9rj9zAkTI2SxdidBHqG9+skdw43borCXO/ZcJdraPWdv22uIEiLA4q7nvvCug8WTqzQveOH26fodo7g6uFe/a17W3+nFBAkRYENRdb1vkkz1CH9cPsVy/jrhr27PqMYvENYNlHAIesRiBYwRy0V+8iXP8+/fvX11Mr7L7ECueb/r48eMqm7FuI2BGWDEG8cm+7G3NEOfmdcTQw4h9/55lhm7DekRYKQPZF2ArbXTAyu4kDYB2YxUzwg0gi/41ztHnfQG26HbGel/crVrm7tNY+/1btkOEAZ2M05r4FB7r9GbAIdxaZYrHdOsgJ/wCEQY0J74TmOKnbxxT9n3FgGGWWsVdowHtjt9Nnvf7yQM2aZU/TIAIAxrw6dOnAWtZZcoEnBpNuTuObWMEiLAx1HY0ZQJEmHJ3HNvGCBBhY6jtaMoEiJB0Z29vL6ls58vxPcO8/zfrdo5qvKO+d3Fx8Wu8zf1dW4p/cPzLly/dtv9Ts/EbcvGAHhHyfBIhZ6NSiIBTo0LNNtScABFyNiqFCBChULMNNSdAhJyNSiECRCjUbEPNCRAhZ6NSiAARCjXbUHMCRMjZqBQiQIRCzTbUnAARcjYqhQgQoVCzDTUnQIScjUohAkQo1GxDzQkQIWejUogAEQo121BzAkTI2agUIkCEQs021JwAEXI2KoUIEKFQsw01J0CEnI1KIQJEKNRsQ80JECFno1KIABEKNdtQcwJEyNmoFCJAhELNNtScABFyNiqFCBChULMNNSdAhJyNSiECRCjUbEPNCRAhZ6NSiAARCjXbUHMCRMjZqBQiQIRCzTbUnAARcjYqhQgQoVCzDTUnQIScjUohAkQo1GxDzQkQIWejUogAEQo121BzAkTI2agUIkCEQs021JwAEXI2KoUIEKFQsw01J0CEnI1KIQJEKNRsQ80JECFno1KIABEKNdtQcwJEyNmoFCJAhELNNtScABFyNiqFCBChULMNNSdAhJyNSiECRCjUbEPNCRAhZ6NSiAARCjXbUHMCRMjZqBQiQIRCzTbUnAARcjYqhQgQoVCzDTUnQIScjUohAkQo1GxDzQkQIWejUogAEQo121BzAkTI2agUIkCEQs021JwAEXI2KoUIEKFQsw01J0CEnI1KIQJEKNRsQ80JECFno1KIABEKNdtQcwJEyNmoFCJAhELNNtScABFyNiqFCBChULMNNSdAhJyNSiEC/wGgKKC4YMA4TAAAAABJRU5ErkJggg=="
                                                                />
                                                            </td>
                                                            <td class="text-center">{{ data.product_varian.product.product_name }}</td>
                                                            <td class="text-center">{{ data.product_varian.product.product_code }}</td>
                                                            <td class="text-center">{{ data.product_varian.product_varian }}</td>
                                                            <td class="text-center">{{ data.product_varian.product_size }}</td>
                                                            <td class="text-center">{{ data.product_varian.product_sku }}</td>
                                                            <td class="text-center">{{ data.stock }}</td>
                                                            <td class="text-center">{{ data.department.deptname }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="row py-2">
                                                <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                                                    Showing {{ state.productStoreList.from }} to {{ state.productStoreList.to }} of {{ state.productStoreList.total }} entries
                                                </div>
                                                <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                                    <a-pagination :current="state.productStoreList.current_page" :total="state.productStoreList.total" v-model:pageSize="paggingStoreList" @change="handlePageChangeStoreList" />
                                                </div>
                                            </div>
                                        </div>
                                    </TabPanel>
                                </TabPanels>
                            </Tabs>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a-drawer v-model:open="modalAdd" :title="action + ' Product'" placement="right" width="1600px">
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> Product Code</label>
                <div class="col-sm-8">
                    <a-input v-model:value="state.form.product_code" placeholder="Product Code" />
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> Product Name</label>
                <div class="col-sm-8">
                    <a-input v-model:value="state.form.product_name" placeholder="Product Name" />
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> Product Photos</label>
                <div class="col-sm-8">
                    <input
                        type="file"
                        class="form-control"
                        @change="onFileChange"
                        accept="image/x-png,image/jpg,image/jpeg"
                    />

                    <a-image  :width="250" :height="200"  v-if="state.form.file"
                        :src="`${pathUrl}storage/barang/${state.form.file}`" 
                        fallback="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMIAAADDCAYAAADQvc6UAAABRWlDQ1BJQ0MgUHJvZmlsZQAAKJFjYGASSSwoyGFhYGDIzSspCnJ3UoiIjFJgf8LAwSDCIMogwMCcmFxc4BgQ4ANUwgCjUcG3awyMIPqyLsis7PPOq3QdDFcvjV3jOD1boQVTPQrgSkktTgbSf4A4LbmgqISBgTEFyFYuLykAsTuAbJEioKOA7DkgdjqEvQHEToKwj4DVhAQ5A9k3gGyB5IxEoBmML4BsnSQk8XQkNtReEOBxcfXxUQg1Mjc0dyHgXNJBSWpFCYh2zi+oLMpMzyhRcASGUqqCZ16yno6CkYGRAQMDKMwhqj/fAIcloxgHQqxAjIHBEugw5sUIsSQpBobtQPdLciLEVJYzMPBHMDBsayhILEqEO4DxG0txmrERhM29nYGBddr//5/DGRjYNRkY/l7////39v///y4Dmn+LgeHANwDrkl1AuO+pmgAAADhlWElmTU0AKgAAAAgAAYdpAAQAAAABAAAAGgAAAAAAAqACAAQAAAABAAAAwqADAAQAAAABAAAAwwAAAAD9b/HnAAAHlklEQVR4Ae3dP3PTWBSGcbGzM6GCKqlIBRV0dHRJFarQ0eUT8LH4BnRU0NHR0UEFVdIlFRV7TzRksomPY8uykTk/zewQfKw/9znv4yvJynLv4uLiV2dBoDiBf4qP3/ARuCRABEFAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghgg0Aj8i0JO4OzsrPv69Wv+hi2qPHr0qNvf39+iI97soRIh4f3z58/u7du3SXX7Xt7Z2enevHmzfQe+oSN2apSAPj09TSrb+XKI/f379+08+A0cNRE2ANkupk+ACNPvkSPcAAEibACyXUyfABGm3yNHuAECRNgAZLuYPgEirKlHu7u7XdyytGwHAd8jjNyng4OD7vnz51dbPT8/7z58+NB9+/bt6jU/TI+AGWHEnrx48eJ/EsSmHzx40L18+fLyzxF3ZVMjEyDCiEDjMYZZS5wiPXnyZFbJaxMhQIQRGzHvWR7XCyOCXsOmiDAi1HmPMMQjDpbpEiDCiL358eNHurW/5SnWdIBbXiDCiA38/Pnzrce2YyZ4//59F3ePLNMl4PbpiL2J0L979+7yDtHDhw8vtzzvdGnEXdvUigSIsCLAWavHp/+qM0BcXMd/q25n1vF57TYBp0a3mUzilePj4+7k5KSLb6gt6ydAhPUzXnoPR0dHl79WGTNCfBnn1uvSCJdegQhLI1vvCk+fPu2ePXt2tZOYEV6/fn31dz+shwAR1sP1cqvLntbEN9MxA9xcYjsxS1jWR4AIa2Ibzx0tc44fYX/16lV6NDFLXH+YL32jwiACRBiEbf5KcXoTIsQSpzXx4N28Ja4BQoK7rgXiydbHjx/P25TaQAJEGAguWy0+2Q8PD6/Ki4R8EVl+bzBOnZY95fq9rj9zAkTI2SxdidBHqG9+skdw43borCXO/ZcJdraPWdv22uIEiLA4q7nvvCug8WTqzQveOH26fodo7g6uFe/a17W3+nFBAkRYENRdb1vkkz1CH9cPsVy/jrhr27PqMYvENYNlHAIesRiBYwRy0V+8iXP8+/fvX11Mr7L7ECueb/r48eMqm7FuI2BGWDEG8cm+7G3NEOfmdcTQw4h9/55lhm7DekRYKQPZF2ArbXTAyu4kDYB2YxUzwg0gi/41ztHnfQG26HbGel/crVrm7tNY+/1btkOEAZ2M05r4FB7r9GbAIdxaZYrHdOsgJ/wCEQY0J74TmOKnbxxT9n3FgGGWWsVdowHtjt9Nnvf7yQM2aZU/TIAIAxrw6dOnAWtZZcoEnBpNuTuObWMEiLAx1HY0ZQJEmHJ3HNvGCBBhY6jtaMoEiJB0Z29vL6ls58vxPcO8/zfrdo5qvKO+d3Fx8Wu8zf1dW4p/cPzLly/dtv9Ts/EbcvGAHhHyfBIhZ6NSiIBTo0LNNtScABFyNiqFCBChULMNNSdAhJyNSiECRCjUbEPNCRAhZ6NSiAARCjXbUHMCRMjZqBQiQIRCzTbUnAARcjYqhQgQoVCzDTUnQIScjUohAkQo1GxDzQkQIWejUogAEQo121BzAkTI2agUIkCEQs021JwAEXI2KoUIEKFQsw01J0CEnI1KIQJEKNRsQ80JECFno1KIABEKNdtQcwJEyNmoFCJAhELNNtScABFyNiqFCBChULMNNSdAhJyNSiECRCjUbEPNCRAhZ6NSiAARCjXbUHMCRMjZqBQiQIRCzTbUnAARcjYqhQgQoVCzDTUnQIScjUohAkQo1GxDzQkQIWejUogAEQo121BzAkTI2agUIkCEQs021JwAEXI2KoUIEKFQsw01J0CEnI1KIQJEKNRsQ80JECFno1KIABEKNdtQcwJEyNmoFCJAhELNNtScABFyNiqFCBChULMNNSdAhJyNSiECRCjUbEPNCRAhZ6NSiAARCjXbUHMCRMjZqBQiQIRCzTbUnAARcjYqhQgQoVCzDTUnQIScjUohAkQo1GxDzQkQIWejUogAEQo121BzAkTI2agUIkCEQs021JwAEXI2KoUIEKFQsw01J0CEnI1KIQJEKNRsQ80JECFno1KIABEKNdtQcwJEyNmoFCJAhELNNtScABFyNiqFCBChULMNNSdAhJyNSiEC/wGgKKC4YMA4TAAAAABJRU5ErkJggg=="
                        />
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> Status Product</label>
                <div class="col-sm-8">
                    <a-select v-model:value="state.form.status_product" style="width: 100%" placeholder="Select Status Product" >
                        <a-select-option :value="0">Aktif</a-select-option>
                        <a-select-option :value="1">Tidak Aktif</a-select-option>
                    </a-select>
                </div>
            </div>

            <h6 class="text-center pt-2">Product Varian</h6>
            <hr/>

            <div class="col-12 mb-3">
                <Button type="primary" class="btn btn-dark" @click="addVariant" v-if="action == 'Lihat'">
                    <i class="fa fa-plus" /> Add Product Varian
                </Button>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center bg-dark">No</th>
                            <th class="text-center bg-dark">Product Variant</th>
                            <th class="text-center bg-dark">Product Size</th>
                            <th class="text-center bg-dark">Product SKU</th>
                            <th class="text-center bg-dark">Harga</th>
                            <th class="text-center bg-dark">Status Varian</th>
                            <th class="text-center bg-dark">File Varian</th>
                            <th class="text-center bg-dark"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(data, index) in productVariant" :key="index">
                            <td class="text-center">{{ index + 1 }}</td>
                            <td>
                                <a-input v-model:value="data.product_varian" placeholder="Product Variant" />
                            </td>
                            <td>
                                <a-select
                                    v-model:value="data.product_size"
                                    style="width: 100%"
                                    placeholder="Select Product Size"
                                    @change="val => data.product_sku = `${state.form.product_code}-${data.product_varian}-${val}`"
                                >
                                    <a-select-option :value="'XS'">XS</a-select-option>
                                    <a-select-option :value="'S'">S</a-select-option>
                                    <a-select-option :value="'M'">M</a-select-option>
                                    <a-select-option :value="'L'">L</a-select-option>
                                    <a-select-option :value="'XL'">XL</a-select-option>
                                    <a-select-option :value="'XXL'">XXL</a-select-option>
                                    <a-select-option :value="'All Size'">All Size</a-select-option>
                                </a-select>
                            </td>
                            <td>
                                <a-input v-model:value="data.product_sku" placeholder="Product SKU" readonly/>
                            </td>
                            <td>
                                <a-input-number
                                    v-model:value="data.harga"
                                    style="width: 100%"
                                    :formatter="value => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                                    :parser="value => value.replace(/Rp\s?|(,*)/g, '')"
                                    placeholder="Harga"
                                />
                            </td>
                            <td>
                                <a-select v-model:value="data.status_product_varian" style="width: 100%" placeholder="Select Status Varian" >
                                    <a-select-option :value="0">Aktif</a-select-option>
                                    <a-select-option :value="1">Tidak Aktif</a-select-option>
                                </a-select>
                            </td>
                            <td>
                                <input
                                    type="file"
                                    @change="e => data.image = e.target.files[0]"
                                    accept="image/x-png,image/jpg,image/jpeg"
                                />

                                <div v-if="data.image && action === 'Lihat'">
                                    <a-image  :width="150" :height="100" 
                                    :src="`${pathUrl}storage/barang/${data.image}`" 
                                    fallback="data:image/png;base64,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"
                                    />

                                </div>
                            </td>
                            <td class="text-center" v-if="action==='Tambah'">
                                <button 
                                    class="btn btn-dark" 
                                    @click="addValue()" 
                                    v-if="index === 0"
                                >
                                    Add
                                </button>
                                <button 
                                    class="btn btn-danger" 
                                    @click="removeValue(index)" 
                                    v-if="index > 0"
                                >
                                    Remove
                                </button>
                            </td>

                            <td class="text-center" v-else>
                                <a-tooltip title="Edit Varian">
                                    <a-button type="primary" size="small" @click="editVarian(data)" class="bg-success me-3">
                                        <template #icon>
                                            <EditOutlined />
                                        </template>
                                    </a-button>
                                </a-tooltip>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <template #footer>
                <div class="d-flex justify-content-end gap-2 mb-2">
                    <Button 
                        label="Simpan" 
                        icon="pi pi-save" 
                        class="flex-auto btn btn-dark" 
                        severity="primary" 
                        :loading="loadingSubmit" 
                        :disabled="loadingButton" 
                        @click="addProduct()" v-if="action==='Tambah'">
                    </Button>

                    <Button 
                        label="Update" 
                        icon="pi pi-save" 
                        class="flex-auto btn btn-dark"
                        severity="primary"
                        :loading="loadingSubmit"
                        :disabled="loadingButton"
                        @click="updateProduct()" v-else>
                    </Button>
                </div>
                <ProgressBar mode="indeterminate" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
            </template>

            <a-modal v-model:open="modalVarian" :title="actionVarian + ' Product Varian'"  width="600px">
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label"> Product Variant</label>
                    <div class="col-sm-8">
                        <a-input v-model:value="state.varian.product_varian" placeholder="Product Variant"@change="val => state.varian.product_sku = `${state.form.product_code}-${state.varian.product_varian}-${val}`" />
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label"> Product Size</label>
                    <div class="col-sm-8">
                        <a-select
                            v-model:value="state.varian.product_size"
                            style="width: 100%"
                            placeholder="Select Product Size"
                            @change="val => state.varian.product_sku = `${state.form.product_code}-${state.varian.product_varian}-${val}`"
                        >
                            <a-select-option :value="'XS'">XS</a-select-option>
                            <a-select-option :value="'S'">S</a-select-option>
                            <a-select-option :value="'M'">M</a-select-option>
                            <a-select-option :value="'L'">L</a-select-option>
                            <a-select-option :value="'XL'">XL</a-select-option>
                            <a-select-option :value="'XXL'">XXL</a-select-option>
                            <a-select-option :value="'All Size'">All Size</a-select-option>
                        </a-select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label"> Product SKU</label>
                    <div class="col-sm-8">
                        <a-input v-model:value="state.varian.product_sku" placeholder="Product SKU"  readonly/>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label"> Harga</label>
                    <div class="col-sm-8">
                        <a-input-number
                            v-model:value="state.varian.harga"
                            style="width: 100%"
                            :formatter="value => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                            :parser="value => value.replace(/Rp\s?|(,*)/g, '')"
                            placeholder="Harga"
                        />
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label"> Status Varian</label>
                    <div class="col-sm-8">
                        <a-select v-model:value="state.varian.status_product_varian" style="width: 100%" placeholder="Select Status Varian" >
                            <a-select-option :value="0">Aktif</a-select-option>
                            <a-select-option :value="1">Tidak Aktif</a-select-option>
                        </a-select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label"> File Varian</label>
                    <div class="col-sm-8">
                        <input
                            type="file"
                            class="form-control"
                            @change="e => state.varian.image = e.target.files[0]"
                            accept="image/x-png,image/jpg,image/jpeg"
                        />

                        <a-image  :width="150" :height="100" 
                            :src="`${pathUrl}storage/barang/${state.varian.image}`" 
                            fallback="data:image/png;base64,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"
                            />

                    </div>
                </div>

                <template #footer>
                    <div class="d-flex justify-content-end gap-2 mb-2">
                        <Button v-if="actionVarian==='Tambah Varian'" 
                            label="Simpan Varian" 
                            icon="pi pi-save" 
                            class="flex-auto btn btn-dark" 
                            severity="primary" 
                            :loading="loadingSubmitVarian" 
                            :disabled="loadingButtonVarian" 
                            @click="saveVarian()">
                        </Button>

                        <Button v-else 
                            label="Update Varian" 
                            icon="pi pi-save" 
                            class="flex-auto btn btn-dark"
                            severity="primary"
                            :loading="loadingSubmitVarian"
                            :disabled="loadingButtonVarian"
                            @click="updateVarian()">    
                        </Button>
                    </div>
                    <ProgressBar mode="indeterminate" style="height: 6px" v-if="loadingSubmitVarian"></ProgressBar>
                </template>
            </a-modal>
        </a-drawer>

        <a-modal v-model:open="modalProductList" :title="'List Product Varian'" style="top:10px"  width="1000px" :footer="false">
            <div class="mb-3 row">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center bg-dark">No</th>
                                <th class="text-center bg-dark">Image</th>
                                <th class="text-center bg-dark">Product Code</th>
                                <th class="text-center bg-dark">Product Name</th>
                                <th class="text-center bg-dark">Product Varian</th>
                                <th class="text-center bg-dark">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(data, index) in state.productListDetail" :key="index">
                                <td class="text-center">{{ index + 1 }}</td>
                                
                                <td class="text-center">
                                    <a-image  :width="150" :height="100" 
                                    :src="`${pathUrl}storage/barang/${data.image}`" 
                                    fallback="data:image/png;base64,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"
                                    />
                                </td>
                                <td class="text-center">{{ data.product.product_code }}</td>
                                <td class="text-center">{{ data.product.product_name }}</td>
                                <td class="text-center">{{ data.product_varian }}</td>
                                <td class="text-center">
                                    <a-tooltip title="Pilih Varian">
                                        <a-button type="primary" size="small" @click="selectProductVarian(data)" class="bg-dark">
                                            Select
                                        </a-button>
                                    </a-tooltip>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
        </a-modal>

        <a-modal v-model:open="modalProductListVarian" :title="'Detail Product Varian'"  width="900px" :footer="false">
            <div class="mb-3 row">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center bg-dark">No</th>
                                <th class="text-center bg-dark">Image</th>
                                <th class="text-center bg-dark">Product Varian</th>
                                <th class="text-center bg-dark">Product Size</th>
                                <th class="text-center bg-dark">Product SKU</th>
                                <th class="text-center bg-dark">Stok</th>
                                <th class="text-center bg-dark" v-if="isSuperAdmin">Cogs</th>
                                <th class="text-center bg-dark">Harga Jual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(data, index) in state.productListVarian" :key="index">
                                <td class="text-center">{{ index + 1 }}</td>
                                
                                <td class="text-center">
                                    <a-image  :width="50" :height="50" 
                                    :src="`${pathUrl}storage/barang/${data.image}`" 
                                    fallback="data:image/png;base64,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"
                                    />
                                </td>
                                <td class="text-center">{{ data.product_varian }}</td>
                                <td class="text-center">{{ data.product_size }}</td>
                                <td class="text-center">{{ data.product_sku }}</td>
                                <td class="text-center">{{ (data.transaksi && data.transaksi[0] && data.transaksi[0].stok) ? data.transaksi[0].stok : 0 }}</td>
                                <td class="text-center" v-if="isSuperAdmin">{{ (1 * data.harga_cogs).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3)}}</td>
                                <td class="text-center">{{ (1* data.harga).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </a-modal>

        <a-modal v-model:open="modalStore" :title="'List Store Product '+namaToko" style="top:10px"  width="1600px" :footer="false">
            <div class="row">
                <div class="d-flex justify-content-end mb-3">
                    <a-input-search
                        v-model:value="searchProduct"
                        placeholder="Cari Data"
                        style="width: 300px"
                    />
                </div>

                <div class="table-responsive pt-2  d-md-block d-none">
                    <table class="table">
                        <thead>
                            
                            <tr class="border-bottom-primary">
                                <th class="bg-primary text-nowrap text-center sticky-column">No</th>
                                <th class="bg-primary text-nowrap text-center">Product Code</th>
                                <th class="bg-primary text-nowrap text-center">Product Name</th>
                                <th class="bg-primary text-nowrap text-center">Product Varian</th>
                                <th class="bg-primary text-nowrap text-center">Product Size</th>
                                <th class="bg-primary text-nowrap text-center">Product SKU</th>
                                <th class="bg-primary text-nowrap text-center">Stok</th>
                                <th class="bg-primary text-nowrap  text-center sticky-columnAction">Action</th>
                                
                            </tr>
                        </thead>

                        <tbody>

                            <tr v-if="loadingStore"> 
                                <td class="text-center" colspan="13"><a-skeleton active /></td>
                            </tr>

                            <tr v-else-if="state.productAktif.total==0">
                                <td class="text-center" colspan="13"><a-empty/></td>
                            </tr>

                            <tr v-for="(item, index) in state.productAktif.data" :key="item.odata" v-else>
                                <td class="text-center sticky-column">{{ state.productAktif.from + index }}</td>
                                <td class="text-nowrap text-center">{{ item.product.product_code }}</td>
                                <td class="text-nowrap text-center">{{ item.product.product_name }}</td>
                                <td class="text-nowrap text-center">{{ item.product_varian }}</td>
                                <td class="text-nowrap text-center">{{ item.product_size }}</td>
                                <td class="text-nowrap text-center">{{ item.product_sku }}</td>
                                <td class="text-nowrap text-center">
                                    {{ item.store_product[0].stock ? item.store_product[0].stock : 0 }}
                                </td>
                                <td class="text-nowrap text-center  sticky-columnAction">
                                    <a-tooltip title="Set Add Product To Store" v-if="item.store_product[0].stock > 0">
                                        <a-button type="primary" size="small" @click="setToStore(item)" class="bg-dark">
                                            <template #icon>
                                                <CheckCircleOutlined />
                                            </template>
                                        </a-button>
                                    </a-tooltip>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row py-2">
                    <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                        Showing {{ state.productAktif.from }} to {{ state.productAktif.to }} of {{ state.productAktif.total }} entries
                    </div>
                    <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                        <a-pagination :current="state.productAktif.current_page" :total="state.productAktif.total" v-model:pageSize="paggingProduct" @change="handlePageChangeProduct" />
                    </div>
                </div>
            </div>

            <a-modal v-model:open="modalAddStore" :title="'Add Product To Store'"  width="500px" :footer="false">
                <div class="mb-3 row">
                    <div class="col-md-12">
                        <label for="toko_id" class="form-label">Store</label>
                        <a-select
                            v-model:value="state.productStore.toko_id"
                            style="width: 100%;"
                            placeholder="Pilih Store">
                            <a-select-option
                                v-for="store in state.store.data"
                                :key="store.kode"
                                :value="store.kode"
                            >
                                {{ store.deptname }}
                            </a-select-option>
                        </a-select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-12">
                        <label for="qty" class="form-label">Quantity</label>
                        <a-input-number
                            v-model:value="state.productStore.qty"
                            style="width: 100%;"
                            :min="1"
                            :max="state.productStore.stock"
                            placeholder="Masukkan Quantity"
                        />
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mb-2">
                    <Button 
                        label="Simpan" 
                        icon="pi pi-save" 
                        class="flex-auto btn btn-dark" 
                        severity="primary" 
                        :loading="loadingSubmit" 
                        :disabled="loadingButton" 
                        @click="saveProductToStore()">
                    </Button>
                </div>
                <ProgressBar mode="indeterminate" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
            </a-modal>
        </a-modal>

        <a-modal v-model:open="modalToko" :title="'List Product Store '+namaToko" width="1600px" :footer="false">
            <Tabs value="0" class="p-tab-active">
                <TabList  class="p-tab-active">
                    <Tab value="0"><i class="fa fa-hand-paper-o" /> Tanda Terima Product</Tab>
                    <Tab value="1"> <i class="fa fa-desktop" /> Product Display</Tab>
                    <Tab value="2"><i class="fa fa-undo" /> Product Return</Tab>
                </TabList>
                <TabPanels>
                    <TabPanel value="0">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <Button 
                                label="Print Tanda Terima" 
                                icon="pi pi-print" 
                                class="flex-auto btn btn-dark me-2" 
                                severity="primary" 
                                size="small"
                                :loading="loadingSubmit" 
                                :disabled="loadingSubmit" 
                                @click="printTandaTerima()">
                            </Button>

                            <a-input-search
                                v-model:value="searchTandaTerima"
                                placeholder="Cari Data"
                                style="width: 300px"
                            />
                        </div>
                        <div class="mb-3 row">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center bg-dark">No</th>
                                            <th class="text-center bg-dark">
                                                <input type="checkbox" :checked="isSelectAllChecked" @change="toggleSelectAll"> Select All
                                            </th>
                                            <th class="text-center bg-dark">Image</th>
                                            <th class="text-center bg-dark">Product Code</th>
                                            <th class="text-center bg-dark">Product Name</th>
                                            <th class="text-center bg-dark">Product Varian</th>
                                            <th class="text-center bg-dark">Product Size</th>
                                            <th class="text-center bg-dark">Product SKU</th>
                                            <th class="text-center bg-dark">Qty</th>
                                            <th class="text-center bg-dark">Create Name</th>
                                            <th class="text-center bg-dark">Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="loadingTandaTerima"> 
                                            <td class="text-center" colspan="13"><a-skeleton active /></td>
                                        </tr>

                                        <tr v-else-if="state.productTerima.total==0">
                                            <td class="text-center" colspan="13"><a-empty/></td>
                                        </tr>
                                        
                                        <tr v-for="(data, index) in state.productTerima.data" :key="index" v-else>
                                            <td class="text-center">{{ index + state.productTerima.from }}</td>
                                            <td class="text-center">
                                                <input type="checkbox" :checked="isChecked(data.id)" @change="toggleChecked(data.id)">
                                                <a-tooltip title="Batal Product">
                                                    <a-button type="danger" size="small" @click="batalProductTerima(data)" class="bg-dark ms-2">
                                                        <template #icon>
                                                            <CloseCircleOutlined />
                                                        </template>
                                                    </a-button>
                                                </a-tooltip>
                                                    
                                            </td>
                                            <td class="text-center">
                                                <a-image  :width="50" :height="50" 
                                                    :src="`${pathUrl}storage/barang/${data.product_varian.image}`" 
                                                    fallback="data:image/png;base64,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"
                                                />
                                            </td>
                                            <td class="text-center">{{ data.product_varian.product.product_name }}</td>
                                            <td class="text-center">{{ data.product_varian.product.product_code }}</td>
                                            <td class="text-center">{{ data.product_varian.product_varian }}</td>
                                            <td class="text-center">{{ data.product_varian.product_size }}</td>
                                            <td class="text-center">{{ data.product_varian.product_sku }}</td>
                                            <td class="text-center">{{ data.stock }}</td>
                                            <td class="text-center">{{ data.create_name }}</td>
                                            <td class="text-center">{{ dayjs(data.created_at).format('DD-MM-YYYY HH:mm:ss') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row py-2">
                                <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                                    Showing {{ state.productTerima.from }} to {{ state.productTerima.to }} of {{ state.productTerima.total }} entries
                                </div>
                                <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                    <a-pagination :current="state.productTerima.current_page" :total="state.productTerima.total" v-model:pageSize="paggingTandaTerima" @change="handlePageChangeTandaTerima" />
                                </div>
                            </div>
                        </div>
                    </TabPanel>

                    <TabPanel value="1">
                        <div class="d-flex align-items-center mb-3">
                            <div class="d-flex gap-2">
                                <Button 
                                    label="Export Tanda Terima Barang" 
                                    icon="pi pi-print" 
                                    class="flex-auto btn btn-success me-2" 
                                    severity="primary" 
                                    size="small"
                                    :loading="loadingSubmit" 
                                    :disabled="loadingSubmit" 
                                    @click="exportTandaTerima()">
                                </Button>

                                <Button :label="'Total Product To Return: ' + state.cart.length" icon="pi pi-shopping-cart" class="p-button-sm btn-dark" @click="getProductInCart()" :loading="loading" />

                                <!-- <Button 
                                    label="Cetak Pengembalian Barang" 
                                    icon="pi pi-print" 
                                    class="flex-auto btn btn-dark me-2" 
                                    severity="primary" 
                                    size="small"
                                    :loading="loadingSubmit" 
                                    :disabled="loadingSubmit" 
                                    @click="printPengembalian()">
                                </Button> -->
                            </div>

                            <div class="ms-auto">
                                <a-input-search
                                    v-model:value="searchDisplay"
                                    placeholder="Cari Data"
                                    style="width: 300px"
                                />
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center bg-dark">No</th>
                                            <th class="text-center bg-dark">
                                                Action
                                            </th>
                                            <th class="text-center bg-dark">Image</th>
                                            <th class="text-center bg-dark">Product Code</th>
                                            <th class="text-center bg-dark">Product Name</th>
                                            <th class="text-center bg-dark">Product Varian</th>
                                            <th class="text-center bg-dark">Product Size</th>
                                            <th class="text-center bg-dark">Product SKU</th>
                                            <th class="text-center bg-dark">Stock</th>
                                            <th class="text-center bg-dark">Disc</th>
                                            <th class="text-center bg-dark">Create Name</th>
                                            <th class="text-center bg-dark">Delivery Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="loadingDisplay"> 
                                            <td class="text-center" colspan="13"><a-skeleton active /></td>
                                        </tr>

                                        <tr v-else-if="state.productDisplay.total==0">
                                            <td class="text-center" colspan="13"><a-empty/></td>
                                        </tr>
                                        
                                        <tr v-for="(data, index) in state.productDisplay.data" :key="index" v-else>
                                            <td class="text-center">{{ index + state.productDisplay.from }}</td>
                                            <td class="text-center">
                                                <a-tooltip title="Tambah Produk ke Pengembalian">
                                                    <a-button type="primary" size="small" class="bg-dark me-2" @click="addReturn(data)">
                                                        <template #icon>
                                                            <CheckOutlined />
                                                        </template>
                                                    </a-button>
                                                </a-tooltip>
                                            </td>
                                            <td class="text-center">
                                                <a-image  :width="50" :height="50" 
                                                    :src="`${pathUrl}storage/barang/${data.product_varian.image}`" 
                                                    fallback="data:image/png;base64,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"
                                                />
                                            </td>
                                            <td class="text-center">{{ data.product_varian.product.product_name }}</td>
                                            <td class="text-center">{{ data.product_varian.product.product_code }}</td>
                                            <td class="text-center">{{ data.product_varian.product_varian }}</td>
                                            <td class="text-center">{{ data.product_varian.product_size }}</td>
                                            <td class="text-center">{{ data.product_varian.product_sku }}</td>
                                            <td class="text-center">{{ data.stock }}</td>
                                            <td class="text-center">
                                                <Button 
                                                    :label="`${data.disc} %`" 
                                                    icon="pi pi-tag" 
                                                    class="flex-auto btn btn-dark" 
                                                    severity="primary" 
                                                    size="small"
                                                    @click="openModalDisc(data)">
                                                </Button>
                                            </td>
                                            <td class="text-center">{{ data.create_name }}</td>
                                            <td class="text-center">{{ dayjs(data.created_at).format('DD-MM-YYYY HH:mm:ss') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row py-2">
                                <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                                    Showing {{ state.productDisplay.from }} to {{ state.productDisplay.to }} of {{ state.productDisplay.total }} entries
                                </div>
                                <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                    <a-pagination :current="state.productDisplay.current_page" :total="state.productDisplay.total" v-model:pageSize="paggingDisplay" @change="handlePageChangeDisplay" />
                                </div>
                            </div>
                        </div>
                    </TabPanel>

                    <TabPanel value="2">
                        <div class="d-flex align-items-center mb-3">
                            <div class="d-flex gap-2">
                                <Button 
                                    label="Export Retur Barang" 
                                    icon="pi pi-print" 
                                    class="flex-auto btn btn-success me-2" 
                                    severity="primary" 
                                    size="small"
                                    :loading="loadingSubmit" 
                                    :disabled="loadingSubmit" 
                                    @click="exportReturBarang()">
                                </Button>
                            </div>

                            <div class="ms-auto">
                                <a-input-search
                                    v-model:value="searchRetur"
                                    placeholder="Cari Data"
                                    style="width: 300px"
                                />
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center bg-dark">No</th>
                                            <th class="text-center bg-dark">
                                                <input type="checkbox" :checked="isSelectAllCheckedRetur" @change="toggleSelectAllRetur"> Select All
                                            </th>
                                            <th class="text-center bg-dark">Image</th>
                                            <th class="text-center bg-dark">Product Code</th>
                                            <th class="text-center bg-dark">Product Name</th>
                                            <th class="text-center bg-dark">Product Varian</th>
                                            <th class="text-center bg-dark">Product Size</th>
                                            <th class="text-center bg-dark">Product SKU</th>
                                            <th class="text-center bg-dark">Stock</th>
                                            <th class="text-center bg-dark">Create Name</th>
                                            <th class="text-center bg-dark">Return Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="loadingRetur"> 
                                            <td class="text-center" colspan="13"><a-skeleton active /></td>
                                        </tr>

                                        <tr v-else-if="state.productRetur.total==0">
                                            <td class="text-center" colspan="13"><a-empty/></td>
                                        </tr>
                                        
                                        <tr v-for="(data, index) in state.productRetur.data" :key="index" v-else>
                                            <td class="text-center">{{ index + state.productRetur.from }}</td>
                                            <td class="text-center">
                                                <input type="checkbox" :checked="isCheckedRetur(data.id)" @change="toggleCheckedRetur(data.id)">
                                            </td>
                                            <td class="text-center">
                                                <a-image  :width="50" :height="50" 
                                                    :src="`${pathUrl}storage/barang/${data.product_varian.image}`" 
                                                    fallback="data:image/png;base64,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"
                                                />
                                            </td>
                                            <td class="text-center">{{ data.product_varian.product.product_name }}</td>
                                            <td class="text-center">{{ data.product_varian.product.product_code }}</td>
                                            <td class="text-center">{{ data.product_varian.product_varian }}</td>
                                            <td class="text-center">{{ data.product_varian.product_size }}</td>
                                            <td class="text-center">{{ data.product_varian.product_sku }}</td>
                                            <td class="text-center">{{ data.stock }}</td>
                                            <td class="text-center">{{ data.create_name }}</td>
                                            <td class="text-center">{{ dayjs(data.created_at).format('DD-MM-YYYY HH:mm:ss') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row py-2">
                                <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                                    Showing {{ state.productRetur.from }} to {{ state.productRetur.to }} of {{ state.productRetur.total }} entries
                                </div>
                                <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                    <a-pagination :current="state.productRetur.current_page" :total="state.productRetur.total" v-model:pageSize="paggingRetur" @change="handlePageChangeRetur" />
                                </div>
                            </div>
                        </div>
                    </TabPanel>
                </TabPanels>
            </Tabs>

            <a-modal v-model:open="modalExportTandaTerima" :title="'Export Tanda Terima Barang '+namaToko" width="600px" :footer="false">
                <div class="mb-3 row">
                    <div class="col-md-12">
                        <label class="form-label">Pilih Data</label>
                        <a-radio-group v-model:value="exportTandaTerimaType" style="margin-bottom: 10px;">
                            <a-radio :value="'all'">All Data</a-radio>
                            <a-radio :value="'date'">Tanggal Delivery</a-radio>
                        </a-radio-group>
                    </div>
                </div>
                <div class="mb-3 row" v-if="exportTandaTerimaType === 'date'">
                    <div class="col-md-12">
                        <label for="tanggal_delivery" class="form-label">Tanggal Delivery</label>
                        <a-range-picker
                            v-model:value="TanggalDelivery"
                            style="width: 100%;"
                            format="DD-MM-YYYY"
                            :placeholder="['Tanggal Mulai', 'Tanggal Selesai']"
                            :allowClear="true"
                        />
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mb-2">
                    <Button 
                        label="Export" 
                        icon="pi pi-save" 
                        class="flex-auto btn btn-dark" 
                        severity="primary" 
                        :loading="loadingSubmit" 
                        :disabled="loadingButton" 
                        @click="exportTandaTerimaStore()">
                    </Button>
                </div>
                <ProgressBar mode="indeterminate" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
            </a-modal>  

            <a-modal v-model:open="modalExportRetur" :title="'Export Retur Barang '+namaToko" width="600px" :footer="false">
                <div class="mb-3 row">
                    <div class="col-md-12">
                        <label for="tanggal_delivery" class="form-label">Tanggal Delivery</label>
                
                        <a-range-picker
                            v-model:value="TanggalDelivery"
                            style="width: 100%;"
                            format="DD-MM-YYYY"
                            :placeholder="['Tanggal Mulai', 'Tanggal Selesai']"
                            :allowClear="true"
                        />
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mb-2">
                    <Button 
                        label="Export" 
                        icon="pi pi-save" 
                        class="flex-auto btn btn-dark" 
                        severity="primary" 
                        :loading="loadingSubmit" 
                        :disabled="loadingButton" 
                        @click="exportRetur()">
                    </Button>
                </div>
                <ProgressBar mode="indeterminate" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
            </a-modal> 

            <a-modal v-model:open="modaldisc" :title="'Atur Disc Produk '+state.productStore.product_sku" width="400px" :footer="false">
                <div class="mb-3 row">
                    <div class="col-md-12">
                        <label for="disc" class="form-label">Disc (%)</label>
                
                        <a-input-number
                            v-model:value="state.productStore.disc"
                            style="width: 100%;"
                            :min="0"
                            :max="100"
                            :formatter="value => `${value}%`"
                            :parser="value => value.replace('%', '')"
                        />
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mb-2">
                    <Button 
                        label="Simpan" 
                        icon="pi pi-save" 
                        class="flex-auto btn btn-dark" 
                        severity="primary" 
                        :loading="loadingButtonVarian" 
                        :disabled="loadingButtonVarian" 
                        @click="simpanDisc()">
                    </Button>
                </div>  
            </a-modal>

            <a-modal v-model:open="modalCart"  style="top: 20px"  title="Return Product" width="1700px">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-center bg-dark">No</th>
                                            <th class="text-center bg-dark">Action</th>
                                            <th class="text-center bg-dark">Image</th>
                                            <th class="text-center bg-dark">Product Code</th>
                                            <th class="text-center bg-dark">Product Name</th>
                                            <th class="text-center bg-dark">Product Varian</th>
                                            <th class="text-center bg-dark">Product Size</th>
                                            <th class="text-center bg-dark">Product SKU</th>
                                            <th class="text-center bg-dark">Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(data, index) in state.cart" :key="index">
                                            <td class="text-center">{{ index + 1 }}</td>
                                            <td class="text-center">
                                                <a-tooltip title="Batal Product">
                                                    <a-button type="primary" size="small" class="bg-dark me-2" @click="batalProductReturn(data)">
                                                        <template #icon>
                                                            <CloseCircleOutlined />
                                                        </template>
                                                    </a-button>
                                                </a-tooltip>
                                            </td>
                                            <td class="text-center">
                                                <a-image  :width="50" :height="50" 
                                                        :src="`${pathUrl}storage/barang/${data.product_varian.image}`" 
                                                        fallback="data:image/png;base64,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"
                                                    />
                                            </td>
                                            <td class="text-center">{{ data.product_varian.product.product_code }}</td>
                                            <td class="text-center">{{ data.product_varian.product.product_name }}</td>
                                            <td class="text-center">{{ data.product_varian.product_varian }}</td>
                                            <td class="text-center">{{ data.product_varian.product_size }}</td>
                                            <td class="text-center">{{ data.product_varian.product_sku }}</td>
                                            <td class="text-center" width="50px">
                                                <a-input-number v-model:value="data.qty" style="width: 100%;" min="1" :max="data.stock" />
                                            </td>

                                        </tr>
                                    </tbody>    
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <template #footer>
                    <div class="text-end">
                        <a-button type="primary" class="bg-dark" @click="printPengembalian" :loading="loadingSubmit">
                            <template #icon>
                                <CheckCircleOutlined />
                            </template>
                            Proses
                        </a-button>
                    </div>
                </template>
                <br></br>
                <ProgressBar mode="indeterminate" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
            </a-modal>

            <a-modal v-model:open="processing"  :footer="null" :closable=false   width="400px">
                <div style="align-items:center;justify-content: center;display: flex;" width="100px">
                    <img class="img-fluid" :src="waitingicon" alt="vector women with leptop"/>
                </div>

                <div style="align-items:center;justify-content: center;display: flex;">
                    {{ pesan }}
                </div>
            </a-modal>

        </a-modal>

        <a-modal v-model:open="processing"  :footer="null" :closable=false   width="400px">
            <div style="align-items:center;justify-content: center;display: flex;" width="100px">
                <img class="img-fluid" :src="waitingicon" alt="vector women with leptop"/>
            </div>

            <div style="align-items:center;justify-content: center;display: flex;">
                {{ pesan }}
            </div>
        </a-modal>
    </div>
</template>

<script setup>
    import { apiGetData, apiCetakPDF, apiPostData,apiExportExcel, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
    import axios from 'axios';
    import { useDebounceFn } from '@vueuse/core'
    import { ref, reactive, onUnmounted, onMounted, computed , watch} from 'vue'
    import { useStore } from "vuex";
    import { useRouter } from "vue-router";
    import iconSpritePath from '@/assets/svg/icon-sprite.svg';
    import {
        EditOutlined,
        EyeOutlined,
        CheckCircleOutlined,
        CloseCircleOutlined,
        CheckOutlined
    } from '@ant-design/icons-vue';
    import Button from 'primevue/button';
    import ProgressBar from 'primevue/progressbar';
    import Tabs from 'primevue/tabs';
    import TabList from 'primevue/tablist';
    import Tab from 'primevue/tab';
    import TabPanels from 'primevue/tabpanels';
    import TabPanel from 'primevue/tabpanel';
    import checkRole from '@/store/modules/role.js';
    const isSuperAdmin = checkRole(['superAdmin']);
    const store = useStore();
    const router = useRouter();
    const user = store.getters["auth/currentUser"];
    const loadingSubmitVarian = ref(false);
    const loadingButtonVarian = ref(false);
    const pagging = ref(12);
    const search = ref('');
    const modalAdd=ref(false);
    const action=ref('Tambah');
    const actionVarian=ref('Tambah Varian');
    const pathUrl = import.meta.env.VITE_PATH_FILE_BASE_URL;
    const modalVarian=ref(false);
    const searchList=ref('');
    const paggingList = ref(10);
    const modalProductList=ref(false);
    const modalProductListVarian=ref(false);
    const searchStore=ref('');
    const paggingStore = ref(20);
    const modalStore=ref(false);
    const paggingProduct = ref(10);
    const searchProduct=ref('');
    const loadingStore = ref(false);
    const modalAddStore=ref(false);
    const modalToko=ref(false);
    const paggingTandaTerima = ref(10);
    const searchTandaTerima = ref('');
    const loadingTandaTerima = ref(false);
    const checkedItems = ref([]);
    const checkedItemsDisplay = ref([]);
    const checkedItemsRetur = ref([]);
    const modalPDF = ref(false);
    const pdfUrl = ref("");
    const namaToko = ref("");
    const paggingDisplay = ref(10);
    const searchDisplay = ref('');
    const loadingDisplay = ref(false);
    const modalExportTandaTerima = ref(false);
    const modalExportRetur = ref(false);
    const TanggalDelivery = ref(dayjs());
    const loadingRetur = ref(false);
    const paggingRetur = ref(10);
    const searchRetur = ref('');
    const paggingStoreList = ref(10);
    const searchProductStore = ref('');
    const loadingProductStore = ref(false);
    const modaldisc=ref(false);
    const exportTandaTerimaType=ref('all');

    const state = reactive({
        product: {},
        productList:{},
        productListDetail:{},
        productListVarian:{},
        store: {},
        productAktif: {},
        productTerima: {},
        productDisplay: {},
        productRetur: {},
        productStoreList:{},
        cart: [],
        form: {
            id: '',
            product_code: '',
            product_name: '',
            file: null,
            status_product: [],
        },
        varian:{
            id: '',
            product_id: '',
            product_varian: '',
            product_size: [],
            harga:0,
            product_sku: '',
            status_product_varian: [],
            image: '',
        },
        productStore:{
            id: '',
            product_sku: '',
            toko_id: '',
            id_toko_product: '',
            stock: 0,
            qty:1,
        },
        tandaTerima:{
            toko_id: '',
        }
    });



    const fetchProducts = async (page = state.product.current_page || 1) => {
        loading.value = true;
        const params = {page: page, per_page: pagging.value, search: search.value };
        
        const response = await apiGetData("/product/index", params);
        state.product = response.data;
        loading.value = false;
    };

    const handlePageChange = async (page) => {
        await fetchProducts(page);
    };

    const add = () => {
        modalAdd.value = true;
        action.value = 'Tambah';
        state.form = {
            id: '',
            product_code: '',
            product_name: '',
            file: null,
            status_product: [],
        };
        productVariant.value = [
            { 
                product_varian: '',
                product_size: [],
                product_sku: '',
                harga:0,
                status_product_varian: [],
                image: '',
    
            }
        ];
    };

    const productVariant = ref([
        { 
            product_varian: '',
            product_size: [],
            product_sku: '',
            harga:0,
            status_product_varian: [],
            image: '',

        }
    ])

    const addValue = () => {
        productVariant.value.push({
            product_varian: '',
            product_size: [],
            product_sku: '',
            harga:0,
            status_product_varian: [],
            image: '',
        });
    };

    const removeValue = (index) => {
        productVariant.value.splice(index, 1);
    };

    const imageFile = ref("");
    const onFileChange = (e) => {
        imageFile.value = e.target.files[0];
    }

    const addProduct = async () => {
        loadingSubmit.value = true;
        const formdata = new FormData();
        formdata.append("file", imageFile.value);
        formdata.append("product_code", state.form.product_code);
        formdata.append("product_name", state.form.product_name);
        formdata.append("status_product", state.form.status_product);
        
        // Append each variant with its image file
        productVariant.value.forEach((variant, index) => {
            formdata.append(`product_variant[${index}][product_varian]`, variant.product_varian);
            formdata.append(`product_variant[${index}][product_size]`, variant.product_size);
            formdata.append(`product_variant[${index}][product_sku]`, variant.product_sku);
            formdata.append(`product_variant[${index}][harga]`, variant.harga);
            formdata.append(`product_variant[${index}][status_product_varian]`, variant.status_product_varian);
            if (variant.image) {
                formdata.append(`product_variant[${index}][image]`, variant.image);
            }
        });
        
        const response = await apiPostData("/product/store", formdata, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        
        
        if(response){
            modalAdd.value = false;
            await fetchProducts();
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Product berhasil ditambahkan',
                confirmButtonColor: '#2c323f',
                confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',
            });
            loadingSubmit.value = false;
        }else{
            loadingSubmit.value = false;
        }
        
    
    };

    const updateProduct = async () => {
        loadingSubmit.value = true;
        const formdata = new FormData();
        formdata.append("id", state.form.id);
        formdata.append("file", imageFile.value);
        formdata.append("product_code", state.form.product_code);
        formdata.append("product_name", state.form.product_name);
        formdata.append("status_product", state.form.status_product);
        
        const response = await apiPostData("/product/update", formdata, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        
        
        if(response){
            modalAdd.value = false;
            await fetchProducts();
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Product berhasil diupdate',
                confirmButtonColor: '#2c323f',
                confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',
            });
            loadingSubmit.value = false;
        }else{
            loadingSubmit.value = false;
        }
        
    
    };

    const view = async (data) => {
        processing.value = true;
        state.form = {
            id: data.id,
            product_code: data.product_code,
            product_name: data.product_name,
            file: data.file,
            status_product: data.status_product,
        }

        const response = await apiGetData(`/product/getVariant`,{ product_id: data.id });
        productVariant.value = response.data;
        modalAdd.value = true;
        action.value = 'Lihat';
        processing.value = false;
    };

    const editVarian = (data) => {
        modalVarian.value = true;
        actionVarian.value = 'Edit Varian';
        state.varian = {
            id: data.id,
            product_id: data.id_product,
            product_varian: data.product_varian,
            product_size: data.product_size,
            harga: data.harga,
            product_sku: data.product_sku,
            status_product_varian: data.status_product_varian,
            image: data.image,
        }
    };

    const updateVarian = async () => {
        loadingSubmitVarian.value = true;
        const formdata = new FormData();
        formdata.append("id", state.varian.id);
        formdata.append("product_id", state.varian.product_id);
        formdata.append("product_varian", state.varian.product_varian);
        formdata.append("product_size", state.varian.product_size);
        formdata.append("product_sku", state.varian.product_sku);
        formdata.append("harga", state.varian.harga);
        formdata.append("status_product_varian", state.varian.status_product_varian);
        formdata.append("image", state.varian.image);
        
        const response = await apiPostData("/product/updateVariant", formdata, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        
        
        if(response){
            modalVarian.value = false;
            const response = await apiGetData(`/product/getVariant`,{ product_id: state.form.id });
            productVariant.value = response.data;
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Product Varian berhasil diupdate',
                confirmButtonColor: '#2c323f',
                confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',
            });
            loadingSubmitVarian.value = false;
        }else{
            loadingSubmitVarian.value = false;
        }
        
    
    };

    const addVariant = () => {
        modalVarian.value = true;
        actionVarian.value = 'Tambah Varian';
        state.varian = {
            id: '',
            product_id: state.form.id,
            product_varian: '',
            product_size: [],
            product_sku: '',
            harga:0,
            status_product_varian: [],
            image: '',
        }
    };

    const saveVarian = async () => {
        loadingSubmitVarian.value = true;
        const formdata = new FormData();
        formdata.append("product_id", state.varian.product_id);
        formdata.append("product_varian", state.varian.product_varian);
        formdata.append("product_size", state.varian.product_size);
        formdata.append("product_sku", state.varian.product_sku);
        formdata.append("harga", state.varian.harga);
        formdata.append("status_product_varian", state.varian.status_product_varian);
        formdata.append("image", state.varian.image);
        
        const response = await apiPostData("/product/storeVariant", formdata, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        
        
        if(response){
            modalVarian.value = false;
            const response = await apiGetData(`/product/getVariant`,{ product_id: state.form.id });
            productVariant.value = response.data;
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Product Varian berhasil ditambahkan',
                confirmButtonColor: '#2c323f',
                confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',
            });
            loadingSubmitVarian.value = false;
        }else{
            loadingSubmitVarian.value = false;
        }
        
    
    };

    //Product List

    const fetchProductsList = async (page = state.productList.current_page || 1) => {
        loading.value = true;
        const params = {page: page, per_page: paggingList.value, search: searchList.value };
        
        const response = await apiGetData("/product/list", params);
        state.productList = response.data;
        loading.value = false;
    };

    const handlePageChangeList = async (page) => {
        await fetchProductsList(page);
    };

    const viewProductList = async (data) => {
        processing.value = true;
        pesan.value = 'Memuat Data Product List...';
        const params = { product_id: data.id };
        const response = await apiGetData("/product/detail", params);
        state.productListDetail = response.data;
        modalProductList.value = true;
        processing.value = false;

    };

    const selectProductVarian = async(data) => {
        processing.value = true;
        pesan.value = 'Memuat Data Product List Varian...';
        const params = { product_id: data.id_product, varian: data.product_varian };
        const response = await apiGetData("/product/detailVarian", params);
        state.productListVarian = response.data;
        modalProductListVarian.value = true;
        processing.value = false;
    };

    //Store Producrt

    const fetchStore = async (page = state.store.current_page || 1) => {
        loading.value = true;
        const params = {page: page, per_page: paggingStore.value, search: searchStore.value };
        const response = await apiGetData("/product/getStoreProduct", params);
        state.store = response.data;
        loading.value = false;
    };

    const handlePageChangeStore = async (page) => {
        await fetchStore(page);
    };

    const tandaterima = async (page = state.productTerima.current_page || 1) => {
        loadingTandaTerima.value = true;
        const params = {page: page, per_page: paggingTandaTerima.value, search: searchTandaTerima.value, toko_id: state.tandaTerima.toko_id };
        const response = await apiGetData("/product/getProductTerima", params);
        state.productTerima = response.data;
        loadingTandaTerima.value = false;
    };

    const handlePageChangeTandaTerima = async (page) => {
        loadingTandaTerima.value = true;
        await tandaterima(page);
        loadingTandaTerima.value = false;
    };

    const toggleChecked = (itemId) => {
        if (isChecked(itemId)) {
            checkedItems.value = checkedItems.value.filter((id) => id !== itemId);
        } else {
            checkedItems.value.push(itemId);
        }
    };

    const isChecked = (itemId) => {
        return checkedItems.value.includes(itemId);
    };

    const isSelectAllChecked = computed(() => {
        return checkedItems.value.length === state.productTerima.to;

    });

    const toggleSelectAll = () => {
        // console.log(isSelectAllChecked.value);
        if (isSelectAllChecked.value) {
            // Unselect all items
            checkedItems.value = [];
        
        } else {
            // Select all items
            checkedItems.value = state.productTerima.data.map((data) => data.id);
        }
    };

    const productDisplay = async (page = state.productDisplay.current_page || 1) => {
        loadingDisplay.value = true;
        const params = {page: page, per_page: paggingDisplay.value, search: searchDisplay.value, toko_id: state.tandaTerima.toko_id };
        const response = await apiGetData("/product/getProductDisplay", params);
        state.productDisplay = response.data;
        loadingDisplay.value = false;
    };

    const handlePageChangeDisplay = async (page) => {
        loadingDisplay.value = true;
        await productDisplay(page);
        loadingDisplay.value = false;
    };

    const modalCart = ref(false);

    const addReturn = (item) => {
        if (state.cart.find(cartItem => cartItem.id === item.id)) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'Product ' + item.product_varian.product_sku + ' sudah ada di keranjang',
                confirmButtonColor: '#2c323f',
                confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',

            });
            return;
        }

        if (item.stock <= 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Upps',
                text: 'Product ' + item.product_varian.product_sku + ' stok habis',
                confirmButtonColor: '#2c323f',
                confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',
            });
            return;
        }

        const cartItem = { ...item, qty: item.qty ?? 1 };
        state.cart.push(cartItem);
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Product ' + item.product_varian.product_sku + ' added to cart',
            confirmButtonColor: '#2c323f',
            confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',
        });
    };

    const getProductInCart = () => {
        if (state.cart.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Upps',
                text: 'Keranjang masih kosong, silahkan pilih product terlebih dahulu',
                confirmButtonColor: '#2c323f',
                confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',
            });
            return false;
        }

        modalCart.value = true;
    };

    const batalProductReturn = (data) => {
        state.cart = state.cart.reduce((acc, item) => {
            if (item.id !== data.id) {
                acc.push(item);
            }
            return acc;
        }, []);

    };


    const productRetur = async (page = state.productRetur.current_page || 1) => {
        loadingRetur.value = true;
        const params = {page: page, per_page: paggingRetur.value, search: searchRetur.value, toko_id: state.tandaTerima.toko_id };
        const response = await apiGetData("/product/getProductRetur", params);
        state.productRetur = response.data;
        loadingRetur.value = false;
    };

    const handlePageChangeRetur = async (page) => {
        loadingRetur.value = true;
        await productRetur(page);
        loadingRetur.value = false;
    };

    const toggleCheckedRetur = (itemId) => {
        if (isCheckedRetur(itemId)) {
            checkedItemsRetur.value = checkedItemsRetur.value.filter((id) => id !== itemId);
        } else {
            checkedItemsRetur.value.push(itemId);
        }
    };

    const isCheckedRetur = (itemId) => {
        return checkedItemsRetur.value.includes(itemId);
    };

    const isSelectAllCheckedRetur = computed(() => {
        return checkedItemsRetur.value.length === state.productRetur.to;

    });

    const toggleSelectAllRetur = () => {
        // console.log(isSelectAllChecked.value);
        if (isSelectAllCheckedRetur.value) {
            // Unselect all items
            checkedItemsRetur.value = [];
        
        } else {
            // Select all items
            checkedItemsRetur.value = state.productRetur.data.map((data) => data.id);
        }
    };


    const viewStore = async (data) => {
        processing.value = true;
        pesan.value = 'Memuat Data Store Product...';
        namaToko.value = data.deptname;
        if(data.kode==100){
            const page = 1;
            const params = {page: page, per_page: paggingProduct.value, search: searchProduct.value };
            const response = await apiGetData("/product/getProductVarian", params);
            state.productAktif = response.data; 
            modalStore.value = true;
        }else{
            state.tandaTerima.toko_id = data.kode;
            await tandaterima()
            await productDisplay()
            await productRetur()
            modalToko.value = true;
        }
        processing.value = false;
        
    };


    const handlePageChangeProduct = async (page) => {
        loadingStore.value = true;
        const params = {page: page, per_page: paggingProduct.value, search: searchProduct.value };
        const response = await apiGetData("/product/getProductVarian", params);
        state.productAktif = response.data;
        loadingStore.value = false;
    };

    const setToStore = async (item) => {
        modalAddStore.value = true;
        state.productStore = {
            id: item.id,
            toko_id: [],
            product_sku: item.product_sku,
            stock: item.store_product[0] ? item.store_product[0].stock : 0,
            id_toko_product: item.store_product[0] ? item.store_product[0].id : '',
            qty:1,
        }
    };

    const saveProductToStore = async () => {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan menambahkan " + state.productStore.product_sku + " ke store!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2c323f',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Tambah!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                loadingSubmit.value = true;

                const response = await apiPostData("/product/storeToStore", {
                    id: state.productStore.id,
                    toko_id: state.productStore.toko_id,
                    id_toko_product: state.productStore.id_toko_product,
                    qty: state.productStore.qty,
                })
                
                
                if(response){
                    modalAddStore.value = false;
                    const page = state.productAktif.current_page ?? 1;
                    const params = {page: page, per_page: paggingProduct.value, search: searchProduct.value };
                    const response = await apiGetData("/product/getProductVarian", params);
                    state.productAktif = response.data; 
                    await fetchStore()
                    await getProductStoreList();
                    loadingSubmit.value = false;
                }else{
                    loadingSubmit.value = false;
                }
            }
        });
        
    
    };

    const openModalDisc = (data) => {
        modaldisc.value = true;
        state.productStore = {
            id: data.id,
            product_sku: data.product_varian.product_sku,
            disc: data.disc,
        }
    };

    const simpanDisc = async () => {
        loadingButtonVarian.value = true;

        const response = await apiPostData("/product/simpanDiscStore", {
            id: state.productStore.id,
            disc: state.productStore.disc,
        })
        
        
        if(response){
            modaldisc.value = false;
            await productDisplay();
            loadingButtonVarian.value = false;
        }else{
            loadingButtonVarian.value = false;
        }
        
    
    };

    const batalProductTerima = async (data) => {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan membatalkan penerimaan product " + data.product_varian.product.product_name + " - " + data.product_varian.product_varian + "!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2c323f',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Batalkan!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                loadingSubmit.value = true;

                const response = await apiPostData("/product/batalProductTerima", {
                    id: data.id,
                })
                
                
                if(response){
                    await tandaterima();
                    await fetchStore();
                    loadingSubmit.value = false;
                }else{
                    loadingSubmit.value = false;
                }
            }
        });
        
    
    };

    const printTandaTerima = async () => {
        if(checkedItems.value.length === 0){
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Silahkan pilih minimal 1 data untuk dicetak!',
                confirmButtonColor: '#2c323f',
                confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',
            });
            return;
        }

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan mencetak tanda terima untuk data yang dipilih!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2c323f',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Download!'
        }).then( async (result) => {
            if (result.isConfirmed) {
                loadingSubmit.value = true;
                const params = { ids: checkedItems.value, toko_id: state.tandaTerima.toko_id };
                const response = await apiCetakPDF("/product/printTandaTerima", params);
                
                if(response){
                    // modalPDF.value = true;
                    // pdfUrl.value = URL.createObjectURL(new Blob([response], { type: 'application/pdf' }));
                    const url = window.URL.createObjectURL(response);

                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'tanda_terima.pdf';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);

                    window.URL.revokeObjectURL(url);
                    window.URL.revokeObjectURL(url);

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Tanda terima berhasil Di Download',
                        confirmButtonColor: '#2c323f',
                        confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',
                    });
                    checkedItems.value = [];
                    await tandaterima();
                    await fetchStore();
                    await getProductStoreList();
                    loadingSubmit.value = false;
                }else{
                    loadingSubmit.value = false;
                }
            }
        });
    };


    const exportTandaTerima = async () => {
        modalExportTandaTerima.value = true;
    };

    const exportTandaTerimaStore = async () => {
        processing.value = true
        pesan.value="Harap Sabar, Lagi Proses Export"

        const response= await apiExportExcel("/product/exportTandaTerima", {
            toko_id: state.tandaTerima.toko_id,
            type: exportTandaTerimaType.value,
            tanggal_delivery_start: TanggalDelivery.value && TanggalDelivery.value[0] ? dayjs(TanggalDelivery.value[0]).format('YYYY-MM-DD') : '',
            tanggal_delivery_end: TanggalDelivery.value && TanggalDelivery.value[1] ? dayjs(TanggalDelivery.value[1]).format('YYYY-MM-DD') : '',
        }, 'Data Invoice Sales')

        if(response){
            processing.value = false
        }else{
            processing.value = false
        }
    }

    const printPengembalian = async () => {
    
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan mencetak pengembalian untuk data yang dipilih!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2c323f',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Download!'
        }).then( async (result) => {
            if (result.isConfirmed) {
                loadingSubmit.value = true;
                const params = { ids: state.cart, toko_id: state.tandaTerima.toko_id };
                const response = await apiCetakPDF("/product/printPengembalian", params);
                
                if(response){
                    const url = window.URL.createObjectURL(response);

                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'pengembalian.pdf';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);

                    window.URL.revokeObjectURL(url);
                    window.URL.revokeObjectURL(url);

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Pengembalian berhasil Di Download',
                        confirmButtonColor: '#2c323f',
                        confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',
                    });
                    checkedItemsDisplay.value = [];
                    await productDisplay();
                    await productRetur();
                    await fetchStore();
                    await getProductStoreList();
                    state.cart = [];
                    modalCart.value = false;
                    loadingSubmit.value = false;
                }else{
                    loadingSubmit.value = false;
                }
            }
        });
    };

    const exportRetur = async () => {
        processing.value = true
        pesan.value="Harap Sabar, Lagi Proses Export"

        const response= await apiExportExcel("/product/exportReturBarang", {
            toko_id: state.tandaTerima.toko_id,
            tanggal_return_start: TanggalDelivery.value && TanggalDelivery.value[0] ? dayjs(TanggalDelivery.value[0]).format('YYYY-MM-DD') : '',
            tanggal_return_end: TanggalDelivery.value && TanggalDelivery.value[1] ? dayjs(TanggalDelivery.value[1]).format('YYYY-MM-DD') : '',
        }, 'Data Retur Barang')

        if(response){
            processing.value = false
        }else{
            processing.value = false
        }
    }

    const exportDetailProduct = async () => {
        loadingSubmit.value = true;
        loadingButton.value = true;
        pesan.value="Harap Sabar, Lagi Proses Export"

        const response= await apiExportExcel("/product/exportDetailProduct", {}, 'Data Detail Product')

        if(response){
            loadingSubmit.value = false;
            loadingButton.value = false;
        }else{
            loadingSubmit.value = false;
            loadingButton.value = false;
        }
    }

    const getProductStoreList = async (page = state.productStoreList.current_page || 1) => {
        loadingProductStore.value = true;
        const params = {page: page, per_page: paggingStoreList.value, search: searchProductStore.value };
        const response = await apiGetData("/product/getProductStoreList", params);
        state.productStoreList = response.data;
        loadingProductStore.value = false;
    };

    const handlePageChangeStoreList = async (page) => {
        await getProductStoreList(page);
    };

    onMounted(async() => {
        await Promise.all([
            fetchProducts(),
            fetchProductsList(),
            fetchStore(),
            getProductStoreList(),
        ]);
    });

    const exportReturBarang = async () => {
        modalExportRetur.value = true;
    }

    watch(search, (newQuestion, oldQuestion) => {
        searchdata()
    })

    const searchdata = useDebounceFn(() => {
        fetchProducts(1)
    }, 500)

    watch(searchList, (newQuestion, oldQuestion) => {
        searchdataList()
    })

    const searchdataList = useDebounceFn(() => {
        fetchProductsList(1)
    }, 500)

    watch(searchStore, (newQuestion, oldQuestion) => {
        searchdataStore()
    })

    const searchdataStore = useDebounceFn(() => {
        fetchStore(1)
    }, 500)

    watch(searchProduct, (newQuestion, oldQuestion) => {
        searchdataProduct()
    })

    const searchdataProduct = useDebounceFn(async () => {
        loadingStore.value = true;
        const page = 1;
        const params = {page: page, per_page: paggingProduct.value, search: searchProduct.value };
        const response = await apiGetData("/product/getProductVarian", params);
        state.productAktif = response.data; 
        loadingStore.value = false;
    }, 500)

    watch(searchTandaTerima, (newQuestion, oldQuestion) => {
        searchdataTandaTerima()
    })

    const searchdataTandaTerima = useDebounceFn(async () => {
        loadingTandaTerima.value = true;
        const page = 1;
        await tandaterima(page);
        loadingTandaTerima.value = false;
    }, 500)

    watch(searchDisplay, (newQuestion, oldQuestion) => {
        searchdataProductDisplay()
    })

    const searchdataProductDisplay = useDebounceFn(async () => {
        loadingDisplay.value = true;
        const page = 1;
        await productDisplay(page);
        loadingDisplay.value = false;
    }, 500)

    watch(searchRetur, (newQuestion, oldQuestion) => {
        searchdataProductRetur()
    })

    const searchdataProductRetur = useDebounceFn(async () => {
        loadingRetur.value = true;
        const page = 1;
        await productRetur(page);
        loadingRetur.value = false;
    }, 500)

    watch(searchProductStore, (newQuestion, oldQuestion) => {
        searchdataProductStore()
    })

    const searchdataProductStore = useDebounceFn(() => {
        getProductStoreList(1)
    }, 500)

</script>

<style scoped>
    .cursor-pointer {
        cursor: pointer;
    }

    .p-tab-active {
        border-color: #2c323f;
        color:#2c323f;
    }
</style>