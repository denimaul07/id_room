<template>
    <a-modal v-model:open="processing"  :footer="null" :closable=false   width="400px">
        <div style="align-items:center;justify-content: center;display: flex;" width="100px">
            <img class="img-fluid" :src="waitingicon" alt="vector women with leptop"/>
        </div>

        <div style="align-items:center;justify-content: center;display: flex;">
            {{ pesan }}
        </div>
    </a-modal>

    <a-modal v-model:open="modalView" :footer="null"  style="top: 20px" title="Approve Permintaan"  width="2000px">
        <div class="col-12">
            <!-- Dekstop -->
            <div class="table-responsive pt-2 d-md-block d-none">
                <table class="table">
                    <thead>
                        <tr class="border-bottom-primary">
                            <th class="bg-primary text-nowrap text-center">No</th>
                            <th class="bg-primary text-nowrap text-center">No Request</th>
                            <th class="bg-primary text-nowrap text-center">Tgl Permintaan</th>
                            <th class="bg-primary text-nowrap text-center">Divisi </th>
                            <th class="bg-primary text-nowrap text-center">Pemohon</th>
                            <th class="bg-primary text-nowrap text-center">Type SPBJ</th>
                            <th class="bg-primary text-nowrap sticky-column">Action</th>
                            
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-if="listpurchasepo.length==0">
                            <td class="text-center" colspan="13">Tidak Ada Data</td>
                        </tr>

                        
                        <tr v-if="loading"> 
                            <td class="text-center" colspan="13"><a-skeleton active /></td>
                        </tr>
                    

                        <tr v-for="(item, index) in listpurchasepo" :key="item.id" v-else>
                            <td class="text-center">{{ index+1 }}</td>
                            <td class="text-nowrap text-center">{{ item.no_req }}</td>
                            <td class="text-nowrap text-center">{{ dayjs(item.tgl_permintaan).format('D MMM YYYY') }}</td>
                            <td class="text-nowrap text-center">{{ item.divisi }}</td>
                            <td class="text-nowrap text-center">{{ item.pemohon }}</td>
                            <td class="text-nowrap text-center">
                                <span class="badge badge-success" v-if="item.spbj_urgent===0">Normal</span>
                                <span class="badge badge-danger" v-else>Urgent</span>
                                
                            </td>
                            <td class="text-nowrap sticky-column">
                                <button class="text-primary btn btn-link text-decoration-none" @click="edit(item.id_data)">
                                    <i class="fa fa-eye"></i> View
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile -->
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

                <div v-else v-for="(item, index) in listdata" :key="item.id" class="pb-2 card">
                    <div class="card-header border">
                        <div class="d-flex justify-content-between">
                            <b>No Request</b>
                            <small class="align-self-center">

                                <span class="badge badge-success">{{  item.no_req }}</span>
                            </small>
                        </div>
                    </div>
                    <div class="card-body border">

                        <div class="d-flex justify-content-between">
                            Tgl Permintaan
                            <small class="text-muted align-self-center">
                                {{ dayjs(item.tgl_permintaan).format('D MMM YYYY') }}
                            </small>
                        </div>


                        <div class="d-flex justify-content-between">
                            Pemohon
                            <small class="text-muted align-self-center">
                                {{ item.pemohon }}
                            </small>
                        </div>

                        <div class="d-flex justify-content-between">
                            Divisi
                            <small class="text-muted align-self-center">
                                {{ item.divisi }}
                            </small>
                        </div>

                    
                    </div>
            
                    <div class="py-1 card-footer d-flex justify-content-center border">
                        <button class="btn btn-link btn-xs text-decoration-none text-success"
                            @click="edit(item.id_data)">
                            <i class="fa fa-eye"></i> View
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <a-modal v-model:open="modaledit"  style="top: 20px" :title="'Detail Permintaan No Request '+state.data.noreq" width="2000px">

            <div class="col-12">
                <div class="row">
                    <div class="col-xxl-4 col-md-4 col-sm-12">
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label"> Tanggal Permintaan</label>
                            <div class="col-sm-8">
                                <input class="form-control"  v-model="state.data.tglreq" readonly>
                            </div>
                        </div>


                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label"> No Header PO</label>
                            <div class="col-sm-8">
                                <input class="form-control"  v-model="state.data.pemohon" readonly>
                            </div>
                        </div>

                    </div>

                    <div class="col-xxl-4 col-md-4 col-sm-12">
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label"> Divisi</label>
                            <div class="col-sm-8">
                                <input class="form-control"  v-model="state.data.divisi" readonly>
                            </div>
                        </div>


                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label"> Sub Budget</label>
                            <div class="col-sm-8">
                                <input class="form-control"  v-model="state.data.subbudget" readonly>
                            </div>
                        </div>

                    </div>

                    <div class="col-xxl-4 col-md-4 col-sm-12">
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label"> Approve By</label>
                            <div class="col-sm-8">
                                <input class="form-control"  v-model="state.data.approveatasan" readonly>
                            </div>
                        </div>

                    </div>
                    <!-- Dekstop -->
                    <div class="table-responsive pt-2">
                        <table class="table">
                        <thead>
                            <tr class="border-bottom-primary">
                                <th class="bg-primary text-nowrap">
                                    <button  class="btn btn-light text-white"  @click="editNoReq(state.data.id)">
                                        Edit All
                                    </button>
                                </th>
                                <th class="bg-primary text-nowrap">#</th>
                                <th class="bg-primary text-nowrap"> Action</th>
                                <th class="bg-primary text-nowrap"> Penawaran Harga</th>
                                <th class="bg-primary text-nowrap"> Nama Barang / Jasa </th>
                                <th class="bg-primary text-nowrap"> Nilai Estimasi Barang & Jasa </th>
                                <th class="bg-primary text-nowrap"> Qty Barang / Jasa </th>
                                <th class="bg-primary text-nowrap"> Jenis</th>
                                <th class="bg-primary text-nowrap"> Jenis PO</th>
                                <th class="bg-primary text-nowrap"> Supplier</th>
                                <th class="bg-primary text-nowrap"> Deskripsi</th>
                                <th class="bg-primary text-nowrap"> Link</th>
                                <th class="bg-primary text-nowrap"> Alasan Permohonan</th>
                                <th class="bg-primary text-nowrap"> PIC</th>
                            </tr>
                        </thead>

                        <tbody>
                        
                            <tr v-if="loading"> 
                                <td class="text-center" colspan="13"><a-skeleton active /></td>
                            </tr>

                            <tr v-else-if="state.listbarang.length==0">
                                <td class="text-center" colspan="13">Tidak Ada Data</td>
                            </tr>
                        

                            <tr v-for="(data, index) in state.listbarang" :key="data.id" v-else>
                                <td>
                                    <input type="checkbox" :checked="isChecked(data.id_data)" @change="toggleChecked(data.id_data)">
                                </td>
                                <td>{{ index+1 }}</td>
                                <td class="text-nowrap">
                                    <button class="btn btn-primary" @click="editData(data.id_data)">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </td>
                                <td class="text-nowrap">
                                    <a href="#" rel="noopener noreferrer" @click="download(data.id_data, data.penawaranharga)">{{ data.penawaranharga }}</a>
                                </td>
                                <td class="text-nowrap">{{ data.nmbarang }}</td>
                                <td>  {{ data.hargajasa.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -2) }}</td>
                                <td>{{ data.qty + ' ' + (data.unit ? data.unit : '') }}</td>
                                <td>{{ data.jenis }}</td>
                                <td>{{ data.jenispo }}</td>
                                <td class="text-nowrap">{{ data.nama }}</td>
                                <td class="text-nowrap">{{ data.desk }}</td>
                                <td class="text-nowrap"><a :href="data.link" target="_blank" rel="noopener noreferrer">Lihat Link</a></td>
                                <td class="text-nowrap">{{ data.alasan }}</td>
                                <td class="text-nowrap">{{ data.pic }}</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>

                    <div class="card-body btn-showcase">
                        <button class="btn btn-primary"  @click="cetakSpbj(state.data.odata)" v-if="state.data.spbj_urgent==1">
                            <i class="fa fa-print me-2"></i> CETAK SPBJ URGENT 
                        </button>
                    </div>

                    <div class="col-sm-12">
                        <div class="mb-3 pt-2 row">
                            <label class="col-sm-4 col-form-label"> Ket Revisi / Tolak</label>
                            <div class="col-sm-8">
                                <input class="form-control"  v-model="state.data.ket">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <template #footer>
                <button type="button" :disabled="loadingButton" class="btn btn-danger me-1" @click="tolak">
                    <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <i class="fa fa-times me-2" aria-hidden="true" v-else></i>
                    Tolak
                </button>

                <button type="button" :disabled="loadingButton" class="btn btn-warning me-1 " @click="revisi">
                    <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <i class="fa fa-reply me-2" aria-hidden="true" v-else></i>
                    Revisi
                </button>

                <button type="button" :disabled="loadingButton" class="btn btn-primary" @click="proses">
                    <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <i class="fa fa-check-square-o me-2" aria-hidden="true" v-else></i>
                    Proses
                </button>
            </template>

            <a-modal v-model:open="modalEditBarang" style="top: 20px" title="Update Data" width="700px">

                <div class="col-12">
                <div class="row">
                    <div class="col-sm-12">
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label"> Jenis</label>
                        <div class="col-sm-9">
                            <a-select v-model:value="state.data.jenis" style="width: 100%"  placeholder="Pilih Jenis">
                                <a-select-option value="">Pilih Jenis</a-select-option>
                                <a-select-option value="PO">PO</a-select-option>
                                <a-select-option value="Aksesoris">Aksesoris</a-select-option>
                                <a-select-option value="Cash Advance">Cash Advance</a-select-option>
                            </a-select>
                        </div>
                    </div>


                    <div class="mb-3 row" v-if="state.data.jenis!='Cash Advance'">
                        <label class="col-sm-3 col-form-label"> Jenis PO</label>
                        <div class="col-sm-9">
                            <a-select v-model:value="jenispo" style="width: 100%"  placeholder="Pilih Jenis PO">
                            <a-select-option  value="">Pilih PO</a-select-option>
                            <a-select-option  value="MD">MD</a-select-option>
                            <a-select-option  value="RETAIL">RETAIL</a-select-option>
                            <a-select-option  value="SENTUL">SENTUL</a-select-option>
                            <a-select-option  value="STAR">STAR</a-select-option>
                            </a-select>
                        </div>
                    </div>

                    <div class="mb-3 row"  v-if="state.data.jenis!='Cash Advance'">
                        <label class="col-sm-3 col-form-label"> Supplier</label>
                        <div class="col-sm-9">
                            <a-select v-model:value="state.data.supplier" show-search :filter-option="filter_supp" style="width: 100%"  placeholder="Pilih Supplier">
                                <a-select-option v-for="item in state.listsupplier" :key="item.id_vendor" :value="item.id_vendor.toString()" :label="item.nama">{{ item.nama }}</a-select-option>
                            </a-select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label"> Pic</label>
                        <div class="col-sm-9">
                            <a-select v-model:value="state.data.pic" show-search  style="width: 100%"  placeholder="Pilih PIC">
                            <a-select-option  v-for="item in state.listPic" :key="item.name"  :value="item.name">{{ item.name }}</a-select-option>
                            </a-select>
                        </div>
                    </div>

                    </div>
                </div>
            </div>

            <template #footer>
                <button type="button" :disabled="loadingButton" class="btn btn-primary" @click="update">
                    <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <i class="fa fa-send me-2" aria-hidden="true" v-else></i>
                    Update
                </button>
            </template>

            <a-modal v-model:open="processing"  :footer="null" :closable=false   width="400px">
            <div style="align-items:center;justify-content: center;display: flex;" width="100px">
                <img class="img-fluid" :src="waitingicon" alt="vector women with leptop"/>
            </div>

            <div style="align-items:center;justify-content: center;display: flex;">
                {{ pesan }}
            </div>
            </a-modal>

            </a-modal>

            <a-modal v-model:open="modalEditReq" style="top: 20px" title="Update Data" width="700px">

                <div class="col-12">
                    <div class="row">
                    <div class="col-sm-12">
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label"> Jenis</label>
                            <div class="col-sm-8">
                            <a-select v-model:value="state.data.jenis" style="width: 100%"  placeholder="Pilih Jenis">
                                <a-select-option value="">Pilih Jenis</a-select-option>
                                <a-select-option value="PO">PO</a-select-option>
                                <a-select-option value="Aksesoris">Aksesoris</a-select-option>
                                <a-select-option value="Cash Advance">Cash Advance</a-select-option>
                            </a-select>
                            </div>
                        </div>


                        <div class="mb-3 row" v-if="state.data.jenis!='Cash Advance'">
                            <label class="col-sm-4 col-form-label"> Jenis PO</label>
                            <div class="col-sm-8">
                            <a-select v-model:value="jenispo" style="width: 100%"  placeholder="Pilih Jenis PO">
                                <a-select-option  value="">Pilih PO</a-select-option>
                                <a-select-option  value="MD">MD</a-select-option>
                                <a-select-option  value="RETAIL">RETAIL</a-select-option>
                                <a-select-option  value="SENTUL">SENTUL</a-select-option>
                                <a-select-option  value="STAR">STAR</a-select-option>
                            </a-select>
                            </div>
                        </div>

                        <div class="mb-3 row" v-if="state.data.jenis!='Cash Advance'">
                            <label class="col-sm-4 col-form-label"> Supplier</label>
                            <div class="col-sm-8">
                            <a-select v-model:value="state.data.supplier" show-search :filter-option="filter_supp" style="width: 100%"  placeholder="Pilih Supplier">
                                <a-select-option v-for="item in state.listsupplier" :key="item.id_vendor" :value="item.id_vendor.toString()" :label="item.nama">{{ item.nama }}</a-select-option>
                            </a-select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label"> Pic</label>
                            <div class="col-sm-8">
                            <a-select v-model:value="state.data.pic" show-search  style="width: 100%"  placeholder="Pilih PIC">
                                <a-select-option  v-for="item in state.listPic" :key="item.name"  :value="item.name">{{ item.name }}</a-select-option>
                            </a-select>
                            </div>
                        </div>

                    </div>
                    </div>
                </div>

                <template #footer>
                    <button type="button" :disabled="loadingButton" class="btn btn-primary" @click="updatePONoReq">
                        <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <i class="fa fa-send me-2" aria-hidden="true" v-else></i>
                        Update
                    </button>
                </template>

                <a-modal v-model:open="processing"  :footer="null" :closable=false   width="400px">
                    <div style="align-items:center;justify-content: center;display: flex;" width="100px">
                        <img class="img-fluid" :src="waitingicon" alt="vector women with leptop"/>
                    </div>

                    <div style="align-items:center;justify-content: center;display: flex;">
                        {{ pesan }}
                    </div>
                </a-modal>
            </a-modal> 

            <a-modal v-model:open="modalCetakSPBJ" :footer="null" style="top: 20px" :closable=true  title="Cetak SPBJ" width="2000px">
                <div class="col-12">
                    <iframe :src="pdfUrl"  width="100%" height="700px"  fullscreen="true" />
                </div>
            </a-modal>
        </a-modal>
    </a-modal>

    <a-modal v-model:open="modalbarang" :footer="null"  style="top: 20px" title="Approve Barang"  width="2000px">
        <div class="product-wrapper-grid">
            <div class="row">
            
                <div class="col-xl-3 col-sm-3" v-for="item in 8" v-if="loading">
                    <div class="card">
                        <div class="product-box">
                            <div class="product-img">
                            
                                <div class="position-relative">
                                    <a-skeleton :active="true" />
                                </div>
                            
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-sm-12" v-else-if="listpurchasebarang.length==0">
                    <div class="card">
                        <div class="product-box">
                            <div class="product-img">
                            
                                <div class="position-relative text-center">
                                    <img class="img-fluid" :src="'https://portal.hondaimora.com/iss/api/storage/foto/ss.png'" >
                                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
                                </div>
                            </div>
                            <div class="product-details">
                                <h6> Yaahhh, Barang Yang Di Cari Tidak Ada Nih</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-sm-2" v-for="item in listpurchasebarang" :key="item.id_barang" v-else>
                    <div class="card">
                        <div class="product-box">
                            <div class="product-img">
                            
                                <div class="position-relative">
                                    <img class="img-fluid" :src="'https://portal.hondaimora.com/iss/api/storage/barang/'+item.file" style="object-fit: cover; height: 200px; width: 100%;" />
                                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
                                </div>
                                <div class="position-absolute bottom-0 ms-2">
                                    <h6 class="text-white">{{ item.namabarang }}</h6>
                                    <p  class="text-white">{{ item.kategori }}</p>
                                    <p  class="text-white">{{ item.request_name }}</p>
                                </div>
                            </div>
                            <div class="pt-2 pb-2 text-center">
                                <button class="flex items-center btn btn-link text-decoration-none" href="#" @click="lihatbarang(item.id_barang)">
                                    <i  class="icon-eye" /> Lihat
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a-modal v-model:open="modalbaranglihat"  style="top: 20px" title="Approve Barang"  width="1000px">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label"> Foto Barang</label>
                            <div class="col-sm-9">
                                <div class="row pb-3">
                                    <div class="card-body">
                                        <div class="row g-sm-4 g-3">
                                            <div class="col-xxl-3 col-md-6">
                                                <div class="prooduct-details-box">
                                                    <div class="media"><img class="align-self-center img-fluid img-100" :src="'https://portal.hondaimora.com/iss/api/storage/barang/'+state.databarang.file" alt="#">
                                                    
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <input type="file"  @change="onFileChange" accept="image/x-png,image/gif,image/jpeg" class="form-control"/>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label"> Nama Barang</label>
                            <div class="col-sm-9">
                                <input  type="text" v-model="state.databarang.name" class="form-control" placeholder="Masukan Nama Barang"  />
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label"> Kategori Barang</label>
                            <div class="col-sm-9">
                                <a-select v-model:value="state.databarang.kategori" style="width: 100%"  placeholder="Pilih Kategori">
                                    <a-select-option  v-for="item in state.listkategori" :key="item.id_kategori"  :value="item.id_kategori" :label="item.kategori">{{ item.kategori }}</a-select-option>
                                </a-select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label"> Deskripsi Barang</label>
                            <div class="col-sm-9">
                        
                                <ckeditor :editor="editor" v-model="state.databarang.editorData"></ckeditor>
                        
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label"> Varian Barang</label>
                
                            <div class="col-sm-9">
                

                                <div class="table-responsive pt-2 d-md-block d-none pb-3">
                                    <table class="table">
                                        <thead>
                                            <tr class="border-bottom-primary">
                                                <th class="bg-primary text-nowrap text-center">No</th>
                                                <th class="bg-primary text-nowrap text-center">Varian</th>
                                                <th class="bg-primary text-nowrap text-center">Harga</th>
                                                
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr v-if="state.listVarian.length==0">
                                                <td class="text-center" colspan="13">Tidak Ada Data</td>
                                            </tr>

                
                                        

                                            <tr v-for="(item, index) in state.listVarian" :key="item.id" v-else>
                                                <td class="text-center">{{ index+1 }}</td>
                                                <td class="text-nowrap text-center">{{ item.varian }}</td>
                                                <td class="text-nowrap text-center">{{ item.harga_barang }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label"> Gambar Varian</label>
                            <div class="col-sm-9">
                                <div class="row pb-3">
                                    <div class="card-body">
                                        <div class="row g-sm-4 g-3">
                                            <div class="col-xxl-3 col-md-6"  v-for="(items, index) in state.listgambar" :key="index">
                                                <div class="prooduct-details-box">
                                                    <div class="media"><img class="align-self-center img-fluid img-100" :src="'https://portal.hondaimora.com/iss/api/storage/barang/'+items.image" alt="#">
                                                        <div class="media-body ms-3">
                                            
                                                            <vue-feather class="close" type="x" @click="removeImage(items.id, items.id_barang)"></vue-feather>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <input type="file" multiple  @change="onFileChangeVarian" accept="image/x-png,image/gif,image/jpeg" class="form-control"/>
                        
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <template #footer>
                <button type="button" :disabled="loadingButton" class="btn btn-danger me-1" @click="tolakBarang">
                    <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <i class="fa fa-times me-2" aria-hidden="true" v-else></i>
                    Tolak 
                </button>

                <button type="button" :disabled="loadingButton" class="btn btn-primary" @click="approveBarang">
                    <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <i class="fa fa-check-square-o me-2" aria-hidden="true" v-else></i>
                    Approve 
                    
                </button>
            </template>
        </a-modal>
    </a-modal>

    <li class="onhover-dropdown" v-if="!isStaff">
        <div class="notification-box">
        <svg>
            <use href="@/assets/svg/icon-sprite.svg#notification" @click="notification_open()"></use>
        </svg>

        <span class="badge rounded-pill badge-secondary" v-if="isBod">{{ notif.length + notifikasiPembayaran.length + notifikasieBudget.length + notifikasiUangSaku.length }}</span>
        <span class="badge rounded-pill badge-secondary" v-else>{{ notif.length + notifadmin.length + notifikasiPembayaran.length + notifikasiTagih.length + notifikasiClaim.length + notifikasiUangSaku.length + notifikasieBudget.length + notifikasiBarangATK.length  }}</span>
        </div>
        <div
        class="onhover-show-div notification-dropdown"
        :class="{ active: notification }"
        >
        <h6 class="f-18 mb-0 dropdown-title">Notitications</h6>
        <ul>
            <li class="b-l-primary border-4" @click="purchasebod" v-if="isBod">
                <p class="fw-bold">Menunggu Approval SPBJ <span class="font-danger fw-bold">{{ notif.length }}</span></p>
            </li>
            <li class="b-l-primary border-4" @click="purchase" v-else>
                <p class="fw-bold">Menunggu Approval SPBJ <span class="font-danger fw-bold">{{ notif.length }}</span></p>
            </li>
            <li class="b-l-success border-4" @click="purchaseadmin" v-if="isAdminPur">
                <p class="fw-bold">Menunggu Approval Purchasing <span class="font-success fw-bold">{{ notifadmin.length }}</span></p>
            </li>
            <li class="b-l-info border-4" @click="pembayaranbod" v-if="isBod">
                <p class="fw-bold">Menunggu Approval Pembayaran<span class="font-info fw-bold">{{ notifikasiPembayaran.length }}</span></p>
            </li>

            <li class="b-l-info border-4" @click="pembayaran" v-if="isasManKeatas">
                <p class="fw-bold">Menunggu Approval Pembayaran<span class="font-info fw-bold">{{ notifikasiPembayaran.length }}</span></p>
            </li>
            <li class="b-l-secondary border-4" @click="tagih"  v-if="isasManKeatas || isBodUtama">
                <p class="fw-bold">Menunggu Approval Tagih<span class="font-secondary fw-bold">{{ notifikasiTagih.length }}</span></p>
            </li>
            <li class="b-l-success border-4" @click="claim"  v-if="isasManKeatas || isChief">
                <p class="fw-bold">Menunggu Approval Claim<span class="font-success fw-bold">{{ notifikasiClaim.length }}</span></p>
            </li>
            <li class="b-l-success border-4" @click="barangatk"  v-if="isasManKeatas || isChief">
                <p class="fw-bold">Menunggu Approval Barang<span class="font-success fw-bold">{{ notifikasiBarangATK.length }}</span></p>
            </li>
            <li class="b-l-danger border-4" @click="uangsaku"  v-if="isasManKeatas || isChief  || isBodUtama">
                <p class="fw-bold">Menunggu Approval Uang Saku<span class="font-danger fw-bold">{{ notifikasiUangSaku.length }}</span></p>
            </li>
            <li class="b-l-primary border-4" @click="uangsakuBOD" v-if="isBod2 || isBod">
                <p class="fw-bold">Menunggu Approval Uang Saku <span class="font-danger fw-bold">{{ notifikasiUangSaku.length }}</span></p>
            </li>
            <li class="b-l-warning border-4" @click="ebudget"  v-if="isasManKeatas || isBod">
                <p class="fw-bold">Menunggu Approval Budget<span class="font-warning fw-bold">{{ notifikasieBudget.length }}</span></p>
            </li>
        </ul>
        </div>
    </li>

    <li class="onhover-dropdown" v-if="isAdminPur">
        <div class="notification-box">
        <svg>
            <use href="@/assets/svg/icon-sprite.svg#stroke-bonus-kit" @click="notification_open()"></use>
        </svg>

        <span class="badge rounded-pill badge-warning">{{  notifbarang.length }}</span>
        </div>
        <div
        class="onhover-show-div notification-dropdown"
        :class="{ active: notification }"
        >
        <h6 class="f-18 mb-0 dropdown-title">Notitications</h6>
        <ul>
            <li class="b-l-primary border-4" @click="purchasebarang">
                <p>Menunggu Approval Barang <span class="font-danger">{{ notifbarang.length }}</span></p>
            </li>
        </ul>
        </div>
    </li>

    <li class="onhover-dropdown" v-if="isAdminCorpsec">
        <div class="notification-box">
        <svg>
            <use href="@/assets/svg/icon-sprite.svg#notification" @click="notification_open()"></use>
        </svg>

        <span class="badge rounded-pill badge-warning">{{  notifappsurat.length + notifappreqbarang.length + notifappsuratkirim.length }}</span>
        </div>
        <div
        class="onhover-show-div notification-dropdown"
        :class="{ active: notification }"
        >
        <h6 class="f-18 mb-0 dropdown-title">Notitications</h6>
        <ul>
            <li class="b-l-primary border-4" @click="suratkeluar">
                <p class="fw-bold">Menunggu Approval Surat <span class="font-danger">{{ notifappsurat.length }}</span></p>
            </li>

            <li class="b-l-primary border-4" @click="suratkeluarkirim">
                <p class="fw-bold">Menunggu Kirim Surat <span class="font-success">{{ notifappsuratkirim.length }}</span></p>
            </li>

            <li class="b-l-primary border-4" @click="approve_request">
                <p class="fw-bold">Menunggu Approval Request <span class="font-warning">{{ notifappreqbarang.length }}</span></p>
            </li>

            <!-- <li class="b-l-primary border-4" @click="approve_request">
                <p class="fw-bold">Menunggu Approval Uang Saku <span class="font-warning">{{ notifappreqbarang.length }}</span></p>
            </li> -->

        
        </ul>
        </div>
    </li>
</template>

<script setup>
    import { apiGetData, apiPostData, apiDownloadFile,apiCetakPDF, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
    import { Api } from '@/api/Api';
    import CKEditor from '@ckeditor/ckeditor5-vue';
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import { ref, onMounted, computed, reactive, watch } from 'vue'
    import {useStore } from "vuex";
    import { useRouter } from "vue-router";
    import checkRole from '@/store/modules/role.js';
    const editor = ClassicEditor
    const ckeditor = CKEditor.component
    const store = useStore()
    const router = useRouter();
    const user = store.getters["auth/currentUser"];
    const notification = ref(false)
    const listpurchase = ref({})
    const jenispo = ref("")
    const listpurchasepo = ref({})
    const listpurchasebarang = ref({})
    const listappsurat=ref({})
    const listappsuratkirim=ref({})
    const listreqbarang=ref({})
    const listdata = ref({})
    const listclaim=ref({})
    const listuangsaku=ref({})
    const listebudget=ref({})
    const listreqbarangatk=ref({})
    const modalView=ref(false)
    const modalEditReq=ref(false)
    const modaledit=ref(false)
    const modalEditBarang=ref(false)
    const modalbarang = ref(false)
    const modalbaranglihat = ref(false)
    const modalCetakSPBJ = ref(false)
    const pdfUrl = ref('')
    //Finance
    const listpembayaran = ref({})
    const listtagih = ref({})
    const notification_open = () => {
        notification.value = ! notification.value
    }
    const selected_startDate = dayjs('01-01-2022').format('YYYY-MM-DD');
    const selected_endDate = dayjs().format('YYYY-MM-DD');
    const rangeDate = ref(selected_startDate + ' - ' + selected_endDate)
    const notif = computed(() => store.state.notifikasi)
    const notifadmin = computed(() => store.state.notifikasipo)
    const notifbarang = computed(() => store.state.notifikasibarang)
    const notifappsurat = computed(() => store.state.notifappsurat)
    const notifappsuratkirim = computed(() => store.state.notifappsuratkirim)
    const notifappreqbarang = computed(() => store.state.notifappreqbarang)
    const notifikasiPembayaran = computed(() => store.state.notifikasiPembayaran)
    const notifikasiTagih= computed(() => store.state.notifikasiTagih)
    const notifikasiClaim= computed(() => store.state.notifikasiClaim)
    const notifikasiUangSaku= computed(() => store.state.notifikasiUangSaku)
    const notifikasieBudget= computed(() => store.state.notifikasieBudget)
    const notifikasiBarangATK = computed(() => store.state.notifikasiBarangATK)
    const state = reactive({
        data:{},
        listbarang : {},
        listsupplier : [],
        listkategor :{},
        listgambar  : {},
        listVarian  : {},
        listPic:{},
        datas : "",
        data : {
            id:"",
            iddata:"",
            tglreq:"",
            noreq:"",
            odata:"",
            pemohon:"",
            supplier:[],
            spbj_urgent:"",
            divisi:"",
            subbudget:"",
            approve:"",
            approveatasan:"",
            ket:"",
            jenis:"",
            pic:[]
        },
        tampilvarian : {},
        tampilimagevarian : {},
        databarang: {
            id:"",
            name : "",
            code : "",
            status : "1",
            image : "",
            datas : "",
            datasimage : "",
            kategori : "",
            editorData : "",
            tampilimage : "",
            file:""
        },
        datavarian: {
            idvarian: "",
            idbarang: "",
            varian: "",
            harga: ""
        },
    })

    const checkedItems = ref([]);

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


;

    const getData = async() => {

        const response = await apiGetData("/purchase/getNotifPurchase");
        listpurchase.value = response.data
        store.commit('notifikasi',  listpurchase.value)

        //finance

        const responsePembayaran = await apiGetData("/finance/getNotifPembayaran");
        listpembayaran.value = responsePembayaran.data
        store.commit('notifikasiPembayaran',  listpembayaran.value)

        const responseTagih = await apiGetData("/finance/getNotifTagih");
        listtagih.value = responseTagih.data
        store.commit('notifikasiTagih',  listtagih.value)

        const responseClaim = await apiGetData("/finance/getNotifClaim");
        listclaim.value = responseClaim.data
        store.commit('notifikasiClaim',  listclaim.value)

        const responseBarangATK = await apiGetData("/cis/notifikasiBarangATK");
        listreqbarangatk.value = responseBarangATK.data
        store.commit('notifikasiBarangATK',  listreqbarangatk.value)

        const responseEBudget= await apiGetData("/e_budget/getNotifEbudget");
        listebudget.value = responseEBudget.data
        store.commit('notifikasieBudget',  listebudget.value)

        const responseUangSaku = await apiGetData("/finance/getNotifUangSaku");
        listuangsaku.value = responseUangSaku.data
        store.commit('notifikasiUangSaku',  listuangsaku.value)

        
    

    }

    
    const getKategori = async() => {

        const response = await apiGetData("/purchase/kategori");
        state.listkategor = response.data

    }

    const getPurchase = async() => {
        const response = await apiGetData("/purchase/getNotifPurchaseAdmin");
        listpurchasepo.value = response.data
        store.commit('notifikasipo',  listpurchasepo.value)
        loading.value = false
    }

    const getbarang = async() => {
        const response = await apiGetData("/purchase/getNotifbarang");
        listpurchasebarang.value = response.data
        store.commit('notifikasibarang',  listpurchasebarang.value)
        loading.value = false
    }
    
    const getsurat= async() => {
        let response=''
        if(isAdminCorpsec){
            response = await apiGetData("/cis/getNotifAppSurat");
        }else{
            response = await apiGetData("/cis/getNotifAppSurat");
        }
        listappsurat.value = response.data
        listappsuratkirim.value = response.kirim
        store.commit('notifappsurat',  listappsurat.value)
        store.commit('notifappsuratkirim',  listappsuratkirim.value)
        loading.value = false
    }

    const getreqbarang= async() => {
        let response=''
        
        if(isAdminCorpsec){
            response = await apiGetData("/cis/getNotifReqBarang");
        }else{
            response = await apiGetData("/cis/getNotifReqBarang");
        }
        
        listreqbarang.value = response.data
        store.commit('notifappreqbarang',  listreqbarang.value)
        loading.value = false
    }

    const tampilData = async () => {
        loading.value = true
        const response = await apiGetData("/purchase/purchaseNotif");
        listdata.value = response.data
        loading.value = false
    }

    const purchase = () => {
        router.push({ 
        name: "detail_permintaan",
        query: { data: [9], tab: 1}
        });
    }

    const purchasebod = () => {
        router.push({ 
        name: "detail_permintaan",
        query: { data: [10], tab: 1}
        });
    }

    const pembayaran = () => {
        router.push({ 
            name: "detail_pembayaran",
            query: { halaman: 'dashboard', status: [1], tab: 1 }
        });
    }

    const tagih = () => {
        router.push({ 
            name: "detail_tagih",
            query: { halaman: 'dashboard', status: [0], tab: 1 }
        });
    }

    const claim = () => {
        router.push({ 
            name: "detail_claim",
            query: { halaman: 'dashboard', status: [1], tab: 1 }
        });
    }

    const uangsaku = () => {
        router.push({ 
            name: "detail_uang_saku",
            query: { halaman: 'dashboard', status: [0,1,2,3], tab: 1 }
        });
    }

    const uangsakuBOD = () => {
        router.push({ 
            name: "detail_uang_saku",
            query: { halaman: 'dashboard', status: [2], tab: 1 }
        });
    }

    const barangatk = () => {
        router.push({ 
        name: "permintaan_barang",
        });
    }

    const ebudget = () => {
        router.push({ 
            name: "dashboard_budget"
        });
    }
    const pembayaranbod = () => {
        router.push({ 
            name: "detail_pembayaran",
            query: {  halaman: 'dashboard', status: [1], tab: 1 }
        });
    }

    const suratkeluar = () => {
        router.push({ 
            name: "surat_keluar",
            query: { search:"", roles: 'admin', status: 0 }
        });
    }

    const suratkeluarkirim = () => {
        router.push({ 
            name: "surat_keluar",
            query: { search:"", roles: 'admin', status: 2 }
        });
    }

    const approve_request = () => {
        router.push({ 
            name: "approve_request"
        });
    }

    const purchaseadmin = () => {
        modalView.value = true
    }

    const purchasebarang = () => {
        modalbarang.value = true
    }

    const edit = async (id) => {
        loading.value=true
        const response = await apiGetData("/purchase/index", {id:id});
        state.datas = response.data
        state.data.id = state.datas[0].id_data
        state.data.odata = state.datas[0].odata_urgent
        state.data.spbj_urgent = state.datas[0].spbj_urgent
        state.data.tglreq = state.datas[0].tgl_permintaan
        state.data.noreq = state.datas[0].no_req
        state.data.pemohon = state.datas[0].pemohon
        state.data.divisi = state.datas[0].divisi
        state.data.subbudget = state.datas[0].subbudget
        state.data.approve = state.datas[0].apporvebod
        state.data.approveatasan = state.datas[0].tolakapprove

        const responses = await apiGetData("/purchase/getDataListBarang", {noreq:window.btoa(state.datas[0].no_req)});
        state.listbarang = responses.data
        loading.value=false
        modaledit.value = true
        

    }

    const editData = async (id) => {
        state.data.jenis=""
        state.data.iddata=""
        jenispo.value =""
        state.data.supplier=[]
        state.data.pic=[]
        processing.value = true
        pesan.value="Mohon Tunggu ya, Lagi Ambil Data"

        const response = await apiGetData("/purchase/index", {id:id});
        state.datas = response.data
        state.data.jenis = state.datas[0].jenis
        jenispo.value = state.datas[0].jenispo
        const responsePO = await apiGetData('/purchase/vendor?jenispo=' +  jenispo.value + '&jenis=' + state.data.jenis)
        state.listsupplier = responsePO.data
        state.data.supplier = state.datas[0].id_supplier
        state.data.pic = state.datas[0].pic
        state.data.iddata = state.datas[0].id_data

        const responses = await apiGetData("/purchase/purchaseGetPic");
        state.listPic = responses.data

        processing.value = false
        modalEditBarang.value = true

    }

    const editNoReq = async (id, editview) => {
        state.data.jenis=""
        state.data.iddata=""
        state.data.supplier=[]
        state.data.pic=[]
        jenispo.value =""
        processing.value = true
        pesan.value="Mohon Tunggu ya, Lagi Ambil Data"
        const response = await apiGetData("/purchase/index", {id:id});
        state.datas = response.data
        state.data.id = state.datas[0].id_data
        state.data.jenis = state.datas[0].jenis
        jenispo.value = state.datas[0].jenispo
        const responsePO = await apiGetData('/purchase/vendor?jenispo=' +  jenispo.value + '&jenis=' + state.data.jenis)
        state.listsupplier = responsePO.data
        state.data.supplier = state.datas[0].id_supplier
        state.data.pic = state.datas[0].pic

        const responses = await apiGetData("/purchase/purchaseGetPic");
        state.listPic = responses.data

        modalEditReq.value = true
        processing.value = false

    }

    const filter_supp= (input, option) => {
        return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    }

    const update = async () => {

        if (state.data.jenis!=='Cash Advance') {
            if(state.data.supplier.length===0) {
                Swal.fire({
                    icon: "error",
                    title: "Uppss Gagal Input",
                    text: "Vendor Harap Di Pilih",
                    confirmButtonColor: '#1e3a8a',
                    confirmButtonText: '<i class="fa fa-check"></i> OKE',
                    footer: '<a href="#">Why do I have this issue?</a>'
                });

                return false
            }   
        }

        processing.value = true
        pesan.value="Mohon Tunggu ya, Lagi Ambil Data"
        const token = localStorage.getItem('access_token_iss')
        Api.defaults.headers.common['Authorization'] = "Bearer " +token
        await Api.post('/purchase/purchaseUpdateData', {
            id : state.data.iddata,
            jenis : state.data.jenis,
            pic : state.data.pic,
            jenispo : jenispo.value,
            supplier : state.data.supplier,
        })
        .then(async response => {

            processing.value = false
            pesan.value = "Sukses Update Data"
            Swal.fire({
                icon: "success",
                title: "Yeyy Successs",
                text: pesan.value,
                confirmButtonColor: '#1e3a8a',
                confirmButtonText: '<i class="fa fa-check"></i> OKE',
                footer: '<a href="#">Why do I have this issue?</a>'
            });
            modalEditBarang.value = false
            const responses = await apiGetData("/purchase/getDataListBarang", {noreq:window.btoa(state.datas[0].no_req)});
            state.listbarang = responses.data

        }).catch(error => {
    
            if(error.response.data.status_code==403){
                router.push({name: '403'})
            }else{
                const object1 = error.response.data
                pesan.value =  Object.values(object1).toString()
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: pesan.value,
                    confirmButtonColor: '#1e3a8a',
                    confirmButtonText: '<i class="fa fa-check"></i> OKE',
                    footer: '<a href="#">Why do I have this issue?</a>'
                });
                processing.value=false
            }
        })
    }

    const updatePONoReq = async () => {
        if (checkedItems.value.length === 0) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "SPBJ Belum Di Pilih",
                confirmButtonColor: '#1e3a8a',
                confirmButtonText: '<i class="fa fa-check"></i> OKE',
                footer: '<a href="#">Why do I have this issue?</a>'
            });
            return;
        }

        processing.value = true
        pesan.value="Harap Sabar, Lagi Proses Simpan Data"
        await Api.post('/purchase/purchaseUpdateSupp', {
            id : checkedItems.value,
            pic : state.data.pic,
            jenis : state.data.jenis,
            jenispo : jenispo.value,
            supplier : state.data.supplier,

        }).then(async (response) => {
            processing.value = false
            pesan.value = "Data Success Updated"
            Swal.fire({
                icon: "success",
                title: "Yeyy Successs",
                text: pesan.value,
                confirmButtonColor: '#1e3a8a',
                confirmButtonText: '<i class="fa fa-check"></i> OKE',
                footer: '<a href="#">Why do I have this issue?</a>'
            });
            modalEditReq.value = false
            checkedItems.value = []
            const responses = await apiGetData("/purchase/getDataListBarang", {noreq:window.btoa(state.datas[0].no_req)});
            state.listbarang = responses.data
        }).catch(error => {
            console.log(error);
            const object1 = error.response.data
            pesan.value =  Object.values(object1).toString()
            setWarningModalPreview(true)
        })
    }

    const download = async (ids, fileName) => {
        const response = await apiDownloadFile('/purchase/downloadPenawaran/', { id: ids })

        if(response){
            const url = window.URL.createObjectURL(new Blob([response]));
            const link = document.createElement("a");
            link.href = url;
            link.setAttribute("download", fileName);
            document.body.appendChild(link);
            link.click();
            link.remove();
        }else{

        }
    }

    const revisi = async () => {
            processing.value = true
            pesan.value="Mohon Tunggu ya, Lagi Ambil Data"
            const token = localStorage.getItem('access_token_iss')
            Api.defaults.headers.common['Authorization'] = "Bearer " +token
            await Api.post('/purchase/purchaseRevisiData', {
                id : state.data.id,
                noreq : state.data.noreq,
                ket : state.data.ket,
            })
            .then(async response => {
                processing.value = false
                pesan.value = "Data Sukses Di Revisi"
                Swal.fire({
                    icon: "success",
                    title: "Yeyy Successs",
                    text: pesan.value,
                    confirmButtonColor: '#1e3a8a',
                    confirmButtonText: '<i class="fa fa-check"></i> OKE',
                    footer: '<a href="#">Why do I have this issue?</a>'
                });
                await tampilData()
                await getData()
                await getPurchase()
                modaledit.value = false
                modalView.value = false
        

            }).catch(error => {
                console.log(error);
                if(error.response.data.status_code==403){
                    router.push({name: '403'})
                }else{
                    const object1 = error.response.data
                    pesan.value =  Object.values(object1).toString()
                    Swal.fire({
                    icon: "error",
                    title: "Oppss..",
                    text: pesan.value,
                    confirmButtonColor: '#1e3a8a',
                    confirmButtonText: '<i class="fa fa-check"></i> OKE',
                    footer: '<a href="#">Why do I have this issue?</a>'
                });
                processing.value = false
                }
            })
    }

    const tolak = async () => {
        processing.value = true
        pesan.value="Mohon Tunggu ya, Lagi Ambil Data"
        const token = localStorage.getItem('access_token_iss')
        Api.defaults.headers.common['Authorization'] = "Bearer " +token
        await Api.post('/purchase/purchaseTolakData', {
            id : state.data.id,
            noreq : state.data.noreq,
            ket : state.data.ket,
        })
        .then(async response => {
    
            processing.value = false
            pesan.value = "Data Sukses Di Tolak"
            Swal.fire({
                icon: "success",
                title: "Yeyy Successs",
                text: pesan.value,
                confirmButtonColor: '#1e3a8a',
                confirmButtonText: '<i class="fa fa-check"></i> OKE',
                footer: '<a href="#">Why do I have this issue?</a>'
            });
    
            await tampilData()
            await getData()
            await getPurchase()
            modaledit.value = false
            modalView.value = false
    

        }).catch(error => {
            console.log(error);
            if(error.response.data.status_code==403){
                router.push({name: '403'})
            }else{
                const object1 = error.response.data
                pesan.value =  Object.values(object1).toString()
                Swal.fire({
                    icon: "error",
                    title: "Oppss..",
                    text: pesan.value,
                    confirmButtonColor: '#1e3a8a',
                    confirmButtonText: '<i class="fa fa-check"></i> OKE',
                    footer: '<a href="#">Why do I have this issue?</a>'
                });
                processing.value = false
            }
        })
    }

    const proses = async () => {
        processing.value = true
        
        pesan.value="Mohon Tunggu ya, Lagi Ambil Data"
        const token = localStorage.getItem('access_token_iss')
        Api.defaults.headers.common['Authorization'] = "Bearer " +token
        await Api.post('/purchase/purchaseApproveData', {
            id : state.data.id,
            noreq : state.data.noreq,
        })
        .then(async response => {
    
            processing.value = false
            pesan.value = "Permintaan Di Approve"
            Swal.fire({
                    icon: "success",
                    title: "Yeyy Successs",
                    text: pesan.value,
                    confirmButtonColor: '#1e3a8a',
                    confirmButtonText: '<i class="fa fa-check"></i> OKE',
                    footer: '<a href="#">Why do I have this issue?</a>'
                });
            await tampilData()
            await getData()
            await getPurchase()
            modaledit.value = false
            modalView.value = false

            await Api.post('/purchase/kirim_notif_approvePur', {
                id : state.data.id,
                noreq : state.data.noreq,
            })
    

        }).catch(error => {
            console.log(error);
            if(error.response.data.status_code==403){
                router.push({name: '403'})
            }else{
                const object1 = error.response.data
                pesan.value =  Object.values(object1).toString()
                setWarningModalPreview(true)
            }
        })
    }


    const lihatbarang = async (id) => {
        processing.value=true
        const response = await apiGetData("/purchase/barang", {id:id});
        state.datas = response.data
        state.databarang.id = id
        state.databarang.name = state.datas[0].namabarang
        state.databarang.kategori = state.datas[0].id_kategori
        state.databarang.editorData = state.datas[0].spesifikasi
        state.databarang.file = state.datas[0].file
        const responses = await apiGetData("/purchase/barangImage", {id:state.datas[0].id_barang});

        state.listgambar = responses.data

        const responsess = await apiGetData("/purchase/barangVarian", {id:state.datas[0].id_barang});

        state.listVarian = responsess.data

        modalbaranglihat.value = true
        processing.value=false
        
    }


    const removeImage = (id, idbarang) => {
        Swal.fire({
            title: "Apa Anda Yakin?",
            text: "Ingin Hapus Barang Ini",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#db3700",
            cancelButtonColor: "#868991",
            confirmButtonText: "Ya, Hapus !"
        }).then(async (result) => {
            if (result.isConfirmed) {
                pesan.value="Harap Sabar Ya, Lagi Proses Approve Permintaan dan Kirim Notifikasi"
                processing.value = true

                const response = await apiPostData("/purchase/deletevarianImage", {
                    id : id,
                })

                if (response) {
                    pesan.value = "Gambar Di Hapus"
                    Swal.fire({
                        icon: "success",
                        title: "Yeyy Successs",
                        text: pesan.value,
                        confirmButtonColor: '#1e3a8a',
                        confirmButtonText: '<i class="fa fa-check"></i> OKE',
                        footer: '<a href="#">Why do I have this issue?</a>'
                    });
                    const responses = await apiGetData("/purchase/barangImage", {id:idbarang});

                    state.listgambar = responses.data

                    const responsess = await apiGetData("/purchase/barangVarian", {id:idbarang});

                    state.listVarian = responsess.data

                    processing.value = false
                
                }else{
                    processing.value = false
                }
                
            }
        });
    }

    const tolakBarang = async (values) => {

        Swal.fire({
            title: "Apa Anda Yakin?",
            text: "Ingin Tolak Barang Ini",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#db3700",
            cancelButtonColor: "#868991",
            confirmButtonText: "Ya, Tolak !"
        }).then(async (result) => {
            if (result.isConfirmed) {
                pesan.value="Harap Sabar Ya, Lagi Proses Approve Permintaan dan Kirim Notifikasi"
                processing.value = true

                const response = await apiPostData("/purchase/tolakBarang", {
                    id : state.databarang.id
                })

                if (response) {
                    pesan.value = "Barang Di Tolak"
                    Swal.fire({
                        icon: "success",
                        title: "Yeyy Successs",
                        text: pesan.value,
                        confirmButtonColor: '#1e3a8a',
                        confirmButtonText: '<i class="fa fa-check"></i> OKE',
                        footer: '<a href="#">Why do I have this issue?</a>'
                    });
                    getbarang()
                    modalbaranglihat.value = false
                    processing.value = false
                
                }else{
                    processing.value = false
                }
                
            }
        });

    }

    const approveBarang = async (values) => {

        Swal.fire({
            title: "Apa Anda Yakin?",
            text: "Ingin Approve Barang Ini",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#1e3a8a",
            cancelButtonColor: "#868991",
            confirmButtonText: "Ya, Approve !"
        }).then(async (result) => {
            if (result.isConfirmed) {
                pesan.value="Harap Sabar Ya, Lagi Proses Approve Permintaan dan Kirim Notifikasi"
                processing.value = true

                const response = await apiPostData("/purchase/approveBarang", {
                    id : state.databarang.id
                })

                if (response) {
                    pesan.value = "Barang Di Approve"
                    Swal.fire({
                        icon: "success",
                        title: "Yeyy Successs",
                        text: pesan.value,
                        confirmButtonColor: '#1e3a8a',
                        confirmButtonText: '<i class="fa fa-check"></i> OKE',
                        footer: '<a href="#">Why do I have this issue?</a>'
                    });
                    getbarang()
                    modalbaranglihat.value = false
                    processing.value = false
                
                }else{
                    processing.value = false
                }
                
            }
        });

    }

    const cetakSpbj =  async(item) =>  {

        
            processing.value = true
            pesan.value="Sabar Dikit ya, Lagi Proses Generate  Data"
            const response = await apiCetakPDF('/purchase/generatePDFSpbjUrgent', { odata: item})

            if (response) {
                processing.value = false;
                modalCetakSPBJ.value = true;
                pdfUrl.value = URL.createObjectURL(new Blob([response], { type: 'application/pdf' }));
            } else {
                processing.value = false;
                // Handle the error case if needed
            }

    };

    //Finance


    const isAdminCorpsec = checkRole(['adminCorpsec','superAdmin']);
    const isAdminPur= checkRole(['adminPurchaseApproval','adminPurchase','superAdmin']);
    const isChief = checkRole(['chief']);
    const isStaff = checkRole(['staff']);
    const isasManKeatas= checkRole(['manager','multimanager']);
    const isBod= checkRole(['bod','bod1','bod2']);
    const isBodUtama= checkRole(['bod']);
    const isBod2= checkRole(['bod2']);
    onMounted(async () => {
        await getData();

        const promises = [];

        if (isAdminPur) {
            promises.push(tampilData(), getPurchase());
        }

        if (isAdminCorpsec || isChief || isasManKeatas) {
            promises.push(getsurat(), getreqbarang());
        }

        promises.push(getbarang(), getKategori());

        await Promise.all(promises);
    });


    watch(jenispo, async(newQuestion, oldQuestion) => {
        processing.value = true
        pesan.value="Mohon Tunggu ya, Lagi Ambil Vendor Banyak Soal nya"
        const response = await apiGetData('/purchase/vendor?jenispo=' +  jenispo.value + '&jenis=' + state.data.jenis)
        if(response){
            state.listsupplier = response.data
            processing.value = false
        }else{
            processing.value = false
        }
    })

</script>
