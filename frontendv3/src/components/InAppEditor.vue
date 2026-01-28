<template>
  <div class="container-fluid d-flex flex-column justify-content-center align-items-center bg-light min-vh-100">
  
    <div class=" d-flex justify-content-center align-items-center">

      <button @click="savePDF" class="btn btn-primary"
          :class="[(pages.length === 0 || saving || !pdfFile) ? 'disabled' : '']">
          {{ saving ? 'Saving' : 'Save' }}
      </button>
    </div>
    <div v-if="addingDrawing">
      <a-modal v-model:open="PrintTTDPreview"  title="Masukan Tanda Tangan Anda" width="300px">
        <div class="col-12">
        
          <div class="row">
            <div class="col-sm-12">
                <div class="mb-3 row">
                    <label class="col-sm-12 col-form-label"> TTD Di Sini</label>
                    <div class="col-sm-12">
                      <Vue3Signature  ref="signature1" :sigOption="option" :w="'100%'" :h="'200px'" class="example"></Vue3Signature>
                    </div>
                </div>
            </div>
          </div>
        </div>

        <template #footer>
            <button type="button" :disabled="loadingButton" class="btn btn-warning" @click="clearTTD">
                <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                    <span class="sr-only">Loading...</span>
                </div>
                <i class="fa fa-check me-2" aria-hidden="true" v-else></i>
                Clear
            </button>
            <button type="button" :disabled="loadingButton" class="btn btn-primary" @click="onFinishDrawingCanvas('image/png')">
                <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                    <span class="sr-only">Loading...</span>
                </div>
                <i class="fa fa-check me-2" aria-hidden="true" v-else></i>
                TTD
            </button>
        </template>
      </a-modal>

      
    </div>
    <div v-if="addingText">
      <a-modal v-model:open="PrintTextPreview"  title="Masukan Nama Anda" width="500px">
        <div class="col-12">
          <div class="row">
            <div class="col-sm-12">
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">  Masukan Nama Anda</label>
                    <div class="col-sm-8">
                        <input class="form-control sig1" v-model="nama">
                    </div>
                </div>
            </div>
          </div>
        </div>

        <template #footer>
            <button type="button" :disabled="loadingButton" class="btn btn-primary" @click="onFinishTextCanvas('image/png')">
                <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                    <span class="sr-only">Loading...</span>
                </div>
                <i class="fa fa-check me-2" aria-hidden="true" v-else></i>
                Add
            </button>
        </template>
      </a-modal>
  
      
    </div>


    <div v-if="pages.length" class="container-fluid">
      <div class="row">
        <div v-for="(page, pIndex) in pages" :key="pIndex" class="col-md-6">
      <div
        class="w-100 d-flex flex-column align-items-center overflow-hidden" style="padding:0;"
        @mousedown="selectPage(pIndex)"
        @touchstart="selectPage(pIndex)">
            <div
                class="shadow-lg mb-3 w-100"
                :class="[pIndex === selectedPageIndex ? 'border border-primary' : '']">
              <input type="file" id="image" name="image" class="d-none" @change="onUploadImage"/>

              <div class="d-flex bg-secondary rounded-sm overflow-hidden">
                <label
                    class="flex justify-center h-100 w-25 hover-bg-dark cursor-pointer"
                    for="image"
                    :class="[selectedPageIndex < 0 ? 'cursor-not-allowed bg-gray-500' : '']">
                  <img src="/image.svg" alt="An icon for adding images" class="img-fluid"/>
                </label>
                <label
                    class="flex justify-center h-100 w-25 hover-bg-dark cursor-pointer"
                    @click="onAddDrawing"
                    :class="[selectedPageIndex < 0 ? 'cursor-not-allowed bg-gray-500' : '']">
                  <img src="/gesture.svg" alt="An icon for adding drawing" class="img-fluid"/>
                </label>
                <label
                    class="flex justify-center h-100 w-25 hover-bg-dark cursor-pointer"
                    @click="onAddText"
                    :class="[selectedPageIndex < 0 ? 'cursor-not-allowed bg-gray-500' : '']">
                  <img src="/text.svg" alt="An icon for adding drawing" class="img-fluid"/>
                </label>
              </div>

              <PDFPage
                  :ref="`page${pIndex}`"
                  :data-key="pIndex"
                  @onMeasure="onMeasure($event, pIndex)"
                  :page="page"/>
              <div
                  class="position-absolute top-0 start-0 transform-origin-start"
                  :style="{transform: `scale(${pagesScale[pIndex]})`}">
                <div v-for="(object, oIndex) in allObjects[pIndex]" :key="oIndex">
                  <div>
                    <div v-if="object.type === 'image'">
                      <ImageItem
                          @onUpdate="updateObject(object.id, $event)"
                          @onDelete="deleteObject(object.id)"
                          :file="object.file"
                          :payload="object.payload"
                          :x="object.x"
                          :y="object.y"
                          :width="object.width"
                          :height="object.height"
                          :originWidth="object.originWidth"
                          :originHeight="object.originHeight"
                          :pageScale="pagesScale[pIndex]"/>
                    </div>
                    <div v-else-if="object.type === 'text'">
                      <TextItem
                          @onUpdate="updateObject(object.id, $event)"
                          @onDelete="deleteObject(object.id)"
                          @onSelectFont="selectFontFamily"
                          :text="object.text"
                          :x="object.x"
                          :y="object.y"
                          :size="object.size"
                          :lineHeight="object.lineHeight"
                          :fontFamily="object.fontFamily"
                          :pageScale="pagesScale[pIndex]"/>
                    </div>
                    <div v-else-if="object.type === 'drawing'">
                      <Drawing
                          @onUpdate="updateObject(object.id, $event)"
                          @onDelete="deleteObject(object.id)"
                          :path="object.path"
                          :x="object.x"
                          :y="object.y"
                          :width="object.width"
                          :originWidth="object.originWidth"
                          :originHeight="object.originHeight"
                          :pageScale="pagesScale[pIndex]"/>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-else>
      <div class="container-fluid d-flex flex-grow justify-content-center align-items-center">
        <span class="font-weight-bold text-center text-3xl text-secondary">Drag something here</span>
      </div>
    </div>

  </div>



</template>

<script setup>
  import "pdfjs-dist/web/pdf_viewer.css";

  import { PDFJS } from 'pdfjs-dist'
  import { GlobalWorkerOptions } from 'pdfjs-dist';
  GlobalWorkerOptions.workerSrc = "pdfjs-dist/build/pdf.worker";

  // import * as PDFLib from 'pdf-lib';
  import { Api } from '@/api/Api';
  import {getAsset} from "@/utils/prepareAssets";
  import { onMounted, ref, reactive , watch, computed } from "vue"
  import { useStore } from "vuex";
  import { useRouter } from 'vue-router'
  import Swal from 'sweetalert2';
  import PDFPage from "../components/PDFPage.vue";
  import ImageItem from "../components/Image.vue";
  import TextItem from "../components/TextItem.vue";
  import Drawing from "../components/Drawing.vue";
  import DrawingCanvas from "../components/DrawingCanvas.vue";
  import {fetchFont} from "../utils/prepareAssets.js";
  import {
    readAsImage,
    readAsPDF,
    readAsDataURL
  } from "@/utils/asyncReader.js";
  import {save} from "@/utils/PDF.js";
  import { defineProps  } from 'vue';
  import '../assets/font/style.css';

  const props = defineProps(['dataFromPage', 'dataAnother', 'bulan', 'tahun']);

  getAsset('makeTextPDF');
  const PrintTTDPreview = ref(false);
  const setPrintTTDModalPreview = (value) => {
      PrintTTDPreview.value = value;
  };
  const PrintTextPreview = ref(false);
  const setPrintTextModalPreview = (value) => {
      PrintTextPreview.value = value;
  };
  const router = useRouter()
  const pdfFile = ref(null);
  const pdfName = ref(props.dataFromPage);
  const numPages = ref(null);
  const pdfDocument = ref(null);
  const pages = ref([]);
  const pagesScale = ref([]);
  const loadingButton = ref(false)
  const loadingSubmit = ref(false)
  const allObjects = ref([]);
  const currentFont = ref("Times-Roman");
  const selectedPageIndex = ref(-1);
  const saving = ref(false);
  const pageRefs = ["page"];
  const addingDrawing = ref(false);
  const addingText= ref(false);
  const signature1=ref("")
  const nama=ref("")
  const loading = ref(true)
  const loadingsycn = ref("")
  const pesan = ref("")
  // const DEBUG_LINK = "http://localhost:8000/storage/pdf/"+ props.dataFromPage;
  const DEBUG_LINK = "https://portal.hondaimora.com/iss/api/storage/pdf/"+ props.dataFromPage;


  const option = ref({
    penColor:"rgb(0, 0, 0)",
    backgroundColor:"rgb(255,255,255)",
    thickness : 1
  })
  const genID = (() => {
    let id = 0;
    return () => id++;
  })();

  const init = async () => {
    try {
      const res = await fetch(DEBUG_LINK);
      const pdfBlob = await res.blob();
      await addPDF(pdfBlob);
      selectedPageIndex.value = 0;
      setTimeout(() => {
        fetchFont(currentFont.value);
        // prepareAssets();
      }, 5000);
    } catch (e) {
      console.log(e);
    }
  };

  const onFinishDrawingCanvas = (e) => {
    const imageUrl = signature1.value.save(e);
    
    fetch(imageUrl)
      .then(res => res.blob())
      .then(blob => {
        // Membuat objek berkas dengan informasi yang diberikan
        const file = new File([blob], "honda.png", {
          type: blob.type,
          lastModified: Date.now(), // Menggunakan waktu sekarang
        });

        if (file && selectedPageIndex.value >= 0) {
          // Membaca file sebagai gambar
          const reader = new FileReader();
          reader.onload = async () => {
            const img = new Image();
            img.src = reader.result;

            img.onload = () => {
              // Membuat elemen canvas
              const canvas = document.createElement("canvas");
              const ctx = canvas.getContext("2d");

              // Mengatur ukuran canvas sesuai dengan gambar
              canvas.width = img.width;
              canvas.height = img.height;

              // Menggambar gambar ke dalam canvas
              ctx.drawImage(img, 0, 0);

              // Mendapatkan data piksel dari canvas
              const imageData = ctx.getImageData(0, 0, img.width, img.height);
              const data = imageData.data;

              // Mengubah piksel putih menjadi piksel transparan
              for (let i = 0; i < data.length; i += 4) {
                const red = data[i];
                const green = data[i + 1];
                const blue = data[i + 2];

                // Cek apakah piksel adalah warna putih (nilai RGB = 255, 255, 255)
                if (red === 255 && green === 255 && blue === 255) {
                  // Mengatur tingkat transparansi (alpha channel) menjadi 0
                  data[i + 3] = 0;
                }
              }

              // Menempatkan data piksel yang telah diubah kembali ke dalam canvas
              ctx.putImageData(imageData, 0, 0);

              // Mengambil hasil canvas sebagai blob
              canvas.toBlob(transBlob => {
                // Membuat File baru dari blob yang telah diubah
                const transparentFile = new File([transBlob], "honda_transparent.png", {
                  type: transBlob.type,
                  lastModified: Date.now(),
                });

                // Menambahkan gambar dengan latar belakang transparan
                addImage(transparentFile);
              }, 'image/png');
            };
          };
          reader.readAsDataURL(file);
        }
      });

    PrintTTDPreview.value = false;
  };

  const onFinishTextCanvas = (e) => {
    const sentenceText = nama.value; // Assuming nama is an input element or some text input
    const canvasElement = document.createElement("canvas");
    const ctx = canvasElement.getContext('2d');

    // Set the canvas size
    canvasElement.width = 300; // Set the width to an appropriate value
    canvasElement.height = 70; // Set the height to an appropriate value

    // Set font and text color
    ctx.font = '30px "Signature"';
    ctx.fillStyle = 'black';

    // Clear the canvas
    ctx.clearRect(0, 0, canvasElement.width, canvasElement.height);

    // Draw text on the canvas
    ctx.fillText(sentenceText, 10, 30); // Adjust the position as needed

    // Convert the canvas to a data URL (PNG)
    const imageUrl = canvasElement.toDataURL('image/png');

  
    fetch(imageUrl)
      .then(res => res.blob())
      .then(blob => {
      // Membuat objek berkas dengan informasi yang diberikan
      const file = new File([blob], "honda.png", {
        type: blob.type,
        lastModified: Date.now(), // Menggunakan waktu sekarang
      });

      if (file && selectedPageIndex.value >= 0) {
        // Membaca file sebagai gambar
        const reader = new FileReader();
        reader.onload = async () => {
          const img = new Image();
          img.src = reader.result;

          img.onload = () => {
            // Membuat elemen canvas
            const canvas = document.createElement("canvas");
            const ctx = canvas.getContext("2d");

            // Mengatur ukuran canvas sesuai dengan gambar
            canvas.width = img.width;
            canvas.height = img.height;

            // Menggambar gambar ke dalam canvas
            ctx.drawImage(img, 0, 0);

            // Mendapatkan data piksel dari canvas
            const imageData = ctx.getImageData(0, 0, img.width, img.height);
            const data = imageData.data;

            // Mengubah piksel putih menjadi piksel transparan
            for (let i = 0; i < data.length; i += 4) {
              const red = data[i];
              const green = data[i + 1];
              const blue = data[i + 2];

              // Cek apakah piksel adalah warna putih (nilai RGB = 255, 255, 255)
              if (red === 255 && green === 255 && blue === 255) {
                // Mengatur tingkat transparansi (alpha channel) menjadi 0
                data[i + 3] = 0;
              }
            }

            // Menempatkan data piksel yang telah diubah kembali ke dalam canvas
            ctx.putImageData(imageData, 0, 0);

            // Mengambil hasil canvas sebagai blob
            canvas.toBlob(transBlob => {
              // Membuat File baru dari blob yang telah diubah
              const transparentFile = new File([transBlob], "honda_transparent.png", {
                type: transBlob.type,
                lastModified: Date.now(),
              });

              // Menambahkan gambar dengan latar belakang transparan
              addImage(transparentFile);
            }, 'image/png');
          };
        };
        reader.readAsDataURL(file);
      }
    });

    PrintTextPreview.value = false;
  };


  const onCancelDrawingCanvas = () => {
    addingDrawing.value = false;
  };

  const onUploadPDF= async (e) => {
    const files = e.target.files || (e.dataTransfer && e.dataTransfer.files);
    const file = files[0];
    if (!file || file.type !== "application/pdf") return;
    selectedPageIndex.value = -1;
    try {
      await addPDF(file);
      selectedPageIndex.value = 0;
    } catch (e) {
      console.log(e);
    }
  }

  const resetDefaultState = async () => {
    pdfFile.value = null;
    pdfName.value = "";
    numPages.value = null;
    pdfDocument.value = null;
    pages.value = [];
    pagesScale.value = [];
    allObjects.value = [];
  }

  const getPdfDocument = async (file) =>{
    const blob = new Blob([file]);
    const url = window.URL.createObjectURL(blob);
    return PDFJS.getDocument(url).promise;
  }

  const addPDF = async (file) =>{
    try {
      resetDefaultState();

      pdfFile.value = file;
      pdfName.value = file.name;

      pdfDocument.value = await readAsPDF(file);
      if (pdfDocument.value) {
        numPages.value = pdfDocument.value.numPages;
        pages.value = Array(numPages.value)
            .fill()
            .map((_, i) => pdfDocument.value.getPage(i + 1));
        allObjects.value = pages.value.map(() => []);
        pagesScale.value = Array(numPages.value).fill(1);
      }
    } catch (e) {
      console.log("Failed to add pdf.");
      throw e;
    }
  }

  const onUploadImage = async (e) =>{
    // console.log(e.target.files[0]);
      const file = e.target.files[0];
      if (file && selectedPageIndex.value >= 0) {
        await addImage(file);
      }
      e.target.value = null;
  }

  const clearTTD = async () => {

    signature1.value.clear()
  }


  const addImage = async (file) =>{
      // console.log(file);
      try {
        // get dataURL to prevent canvas from tainted
        const url = await readAsDataURL(file);
        const img = await readAsImage(url);
        const id = genID();
        const {width, height} = img;

        const object = {
          id,
          type: "image",
          width,
          height,
          originWidth: width,
          originHeight: height,
          canvasWidth: 70,
          canvasHeight: 70,
          x: 0,
          y: 0,
          payload: img,
          file
        };
        allObjects.value = allObjects.value.map((objects, pIndex) =>
            pIndex === selectedPageIndex.value ? [...objects, object] : objects
        );
      } catch (e) {
        console.log(`Fail to add image.`, e);
      }
  }

  const onAddTextField = async () =>{
    if (selectedPageIndex.value >= 0) {
      addTextField();
    }
  }

  const addTextField  = async (text = "New Text Field") =>{
      const id = genID();
      fetchFont(currentFont);
      const object = {
        id,
        text,
        type: "text",
        size: 16,
        width: 0, // recalculate after editing
        lineHeight: 1.4,
        fontFamily: currentFont,
        x: 0,
        y: 0
      };
      allObjects.value = allObjects.value.map((objects, pIndex) =>
          pIndex === selectedPageIndex.value ? [...objects, object] : objects
      );
  }

  const onAddDrawing = async () =>{
    if (selectedPageIndex.value >= 0) {
        addingDrawing.value = true;
        PrintTTDPreview.value = true
    }
  }

  const onAddText = async () =>{
    if (selectedPageIndex.value >= 0) {
        addingText.value = true;
        PrintTextPreview.value = true
    }
  }



  const addDrawing = async (originWidth, originHeight, path, scale = 1) => {
    const id = genID();
    const object = {
      id,
      path,
      type: "drawing",
      x: 0,
      y: 0,
      originWidth,
      originHeight,
      width: originWidth * scale,
      scale
    };
    allObjects.value = allObjects.value.map((objects, pIndex) =>
        pIndex === selectedPageIndex.value ? [...objects, object] : objects
    );

    console.log( allObjects.value);
  }

  const selectFontFamily = async (event) => {
    const name = event.name;
    fetchFont(name);
    currentFont.value = name;
  }

  const selectPage = async (index) =>{
    selectedPageIndex.value = index;
  }

  const  updateObject = async (objectId, payload) => {
    allObjects.value = allObjects.value.map((objects, pIndex) =>
        pIndex === selectedPageIndex.value
            ? objects.map(object =>
                object.id === objectId ? {...object, ...payload} : object
            )
            : objects
    );
  }

  const  deleteObject = async (objectId) => {
    allObjects.value = allObjects.value.map((objects, pIndex) =>
        pIndex === selectedPageIndex.value
            ? objects.filter(object => object.id !== objectId)
            : objects
    );
  }

  const  onMeasure = async  (e, i) => {
      pagesScale[i] = e.scale;
  }
// FIXME: Should wait all objects finish their async work
const savePDF = async () => {
  if (!pdfFile.value || saving.value || !pages.value.length) return;
  saving.value = true;
  try {
    // Ambil ukuran asli PDF (viewport default scale 1)
    const pageSizes = await Promise.all(
      pages.value.map(async (page) => {
        const viewport = (await page).getViewport({ scale: 1 });
        return { width: viewport.width, height: viewport.height };
      })
    );

    // Ambil ukuran canvas DOM
    const canvasInfos = await Promise.all(
      pages.value.map(async (page, pIndex) => {
        let w = pageSizes[pIndex].width;
        let h = pageSizes[pIndex].height;
        let offsetX = 0;
        let offsetY = 0;
        try {
          const pageRef = document.querySelector(`[data-key='${pIndex}'] canvas`);
          if (pageRef) {
            w = pageRef.width;
            h = pageRef.height;
            const rect = pageRef.getBoundingClientRect();
            offsetX = rect.left;
            offsetY = rect.top;
          }
        } catch (e) {
          console.warn("Canvas info error:", e);
        }
        return { width: w, height: h, offsetX, offsetY };
      })
    );

    // Mapping objek ke koordinat PDF, koreksi visual scale (pagesScale)
    // Mapping objek ke koordinat PDF, tanpa koreksi padding (padding sudah dihilangkan di DOM)
    const mappedObjects = allObjects.value.map((objects, pIndex) => {
      const pdfW = pageSizes[pIndex].width;
      const pdfH = pageSizes[pIndex].height;
      const canvasW = canvasInfos[pIndex].width;
      const canvasH = canvasInfos[pIndex].height;
      const scaleX = pdfW / canvasW;
      const scaleY = pdfH / canvasH;
      const scaleVisual = pagesScale.value[pIndex] || 1;
      return objects.map((obj) => {
        const newObj = { ...obj };
        if (typeof obj.x === "number") newObj.x = (obj.x / scaleVisual) * scaleX;
        if (typeof obj.y === "number") newObj.y = (obj.y / scaleVisual) * scaleY;
        if (typeof obj.width === "number") newObj.width = (obj.width / scaleVisual) * scaleX;
        if (typeof obj.height === "number") newObj.height = (obj.height / scaleVisual) * scaleY;
        if (typeof obj.size === "number") newObj.size = (obj.size / scaleVisual) * scaleY;
        if (typeof obj.lineHeight === "number") newObj.lineHeight = obj.lineHeight;
        return newObj;
      });
    });

    // Save PDF
    const byte = await save(
      pdfFile.value,
      mappedObjects,
      pdfName.value,
      pagesScale.value
    );

    // Upload ke API
    const blob = new File([new Uint8Array(byte)], "document.pdf", {
      type: "application/pdf",
    });
    const token = localStorage.getItem("access_token_iss");
    Api.defaults.headers.common["Authorization"] = "Bearer " + token;
    Api.defaults.headers.post["Content-Type"] = "multipart/form-data";

    const formdatas = new FormData();
    formdatas.append("file", blob);
    formdatas.append("fileName", props.dataFromPage);
    formdatas.append("no_surat", props.dataAnother);

    await Api.post("/staff/ttdsignature", formdatas)
      .then(async (response) => {
        pesan.value = "Document Success Di Tanda Tangan";
        Swal.fire({
          icon: "success",
          title: "Yeyy Successs",
          text: pesan.value,
          confirmButtonColor: "#1e3a8a",
          confirmButtonText: '<i class="fa fa-check"></i> OKE',
          footer: '<a href="#">Why do I have this issue?</a>',
        });
        window.location.reload();
      })
      .catch((error) => {
        console.error("Upload error:", error);
      });
  } catch (e) {
    console.error("Save PDF error:", e);
  } finally {
    saving.value = false;
  }
};


  onMounted(async() => {
    await init()
  })


  

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .shadowOutline {
      box-shadow: 0 0 0 3px rgb(66 153 225 / 50%);
    }

    .noTouchAction {
      touch-action: none
    }

    h3 {
      margin: 40px 0 0;
    }

    ul {
      list-style-type: none;
      padding: 0;
    }

    li {
      display: inline-block;
      margin: 0 10px;
    }

    a {
      color: #42b983;
    }
    .sig1 {
      font-family: 'Signature', cursive;
      font-size: 1.8em;
    }
</style>
