import { Api } from '@/api/Api';
import { ref  } from 'vue'
import waitingicon from '@/assets/images/logo/logo_idroom.png'
import Swal from 'sweetalert2';
import dayjs from 'dayjs'
const token = localStorage.getItem('token_id_room')
Api.defaults.headers.common['Authorization'] = "Bearer " +token
const pesan=ref("")
const processing=ref(false)
const loading = ref(true)
const loadingButton = ref(false)
const loadingSubmit = ref(false)

const apiGetData = async (url = "", paramsData = {}) => {
    loading.value=true
    Api.defaults.headers.common["Authorization"] = "Bearer " + token;
    return await Api.get(url, { params: paramsData })
    .then((response) => {
        loading.value=false
        return response.data;
    })
    .catch((error) => {  
        
        const ResObj = error.response.data?.message || Object.values(error.response.data);
        const errorId = error.response.data?.error_id || 'Unknown';
        const title = error.response.data?.title || 'Oops... !';

        sweetError(title, ResObj.toString(), errorId);
        return false;
    });
};

const apiPostData = async (url = "", paramsData = "", notif=true) => {
    Api.defaults.headers.common["Authorization"] = "Bearer " + token;
    return await Api.post(url, paramsData )
    .then((response) => {
    
        if (notif==true) {
            sweetSuccess(null, response.data.message)
            return true;
        } else {
            return true;
        }
        
    })
    .catch((error) => {
        const ResObj = error.response.data?.message || Object.values(error.response.data);
        const errorId = error.response.data?.error_id || 'Unknown';
        const title = error.response.data?.title || 'Oops... !';

        sweetError(title, ResObj.toString(), errorId);
        return false;
    });
};

const apiPostDataWithReturn = async (url = "", paramsData = "", axiosConfig = {}, notif = true) => {
    Api.defaults.headers.common["Authorization"] = "Bearer " + token;
    
    return await Api.post(url, paramsData, axiosConfig)
        .then((response) => {
            if (notif === true) {
                sweetSuccess(null, response.data.message)
                return {
                    success: true,
                    data: response.data,
                };
            } else {
                return {
                    success: true,
                    data: response.data,
                };
            }
        })
        .catch((error) => {
            const ResObj = error.response.data?.message || Object.values(error.response.data);
            const errorId = error.response.data?.error_id || 'Unknown';
            const title = error.response.data?.title || 'Oops... !';

            sweetError(title, ResObj.toString(), errorId);
            return { success: false };
        });
};


const apiPostDataNotif = async (url = "", paramsData = "", notif=true) => {
    Api.defaults.headers.common["Authorization"] = "Bearer " + token;
    return await Api.post(url, paramsData )
    .then((response) => {
    
        if (notif==true) {
            sweetSuccess(null, response.data.message)
            return true;
        } else {
            return true;
        }
        
    })
    .catch((error) => {
        const ResObj = error.response.data?.message || Object.values(error.response.data);
        const errorId = error.response.data?.error_id || 'Unknown';
        const title = error.response.data?.title || 'Oops... !';

        // sweetError(title, ResObj.toString(), errorId);
        return false;
    });
};

const apiPutData = async (url = "", paramsData = "", notif=true) => {
    console.log('Nilai notif:', notif);
    Api.defaults.headers.common["Authorization"] = "Bearer " + token;
    return await Api.put(url, paramsData )
    .then((response) => {

        
        if (notif==true) {
            sweetSuccess(null, response.data.message)
            return true;
        } else {
            return true;
        }
    })
    .catch((error) => {
        const ResObj = error.response.data?.message || Object.values(error.response.data);
        const errorId = error.response.data?.error_id || 'Unknown';
        const title = error.response.data?.title || 'Oops... !';

        sweetError(title, ResObj.toString(), errorId);
        return false;
    });
};

const apiDeleteData = async (url = "", paramsData = {}) => {
    loading.value=true
    Api.defaults.headers.common["Authorization"] = "Bearer " + token;
    return await Api.delete(url, { params: paramsData })
    .then((response) => {
        loading.value=false
    
        sweetSuccess(null, response.data.message)
        return true;
    
    })
    .catch((error) => {
        const ResObj = error.response.data?.message || Object.values(error.response.data);
        const errorId = error.response.data?.error_id || 'Unknown';
        const title = error.response.data?.title || 'Oops... !';

        sweetError(title, ResObj.toString(), errorId);
        return false;
    });
};

const apiExportExcel = async (url = "", paramsData = {}, title = 'Data') => {

    Api.defaults.headers.common['Authorization'] = "Bearer " + token
    await Api.get(url, {
        params: paramsData,
        responseType: 'blob',
    }).then(response => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', title + '.xlsx');
        document.body.appendChild(link);
        link.click();
    }).catch((error) => {
        const ResObj = error.response.data?.message || Object.values(error.response.data);
        const errorId = error.response.data?.error_id || 'Unknown';
        const title = error.response.data?.title || 'Oops... !';

        sweetError(title, ResObj.toString(), errorId);
        return false;
    });
}

const apiCetakPDF = async (url = "", paramsData = {}) => {
    const pesan = ref('');
    Api.defaults.headers.common['Authorization'] = "Bearer " + token;
    
    try {
        const response = await Api.get(url, {
            params: paramsData,
            responseType: 'blob',
        });
        return response.data; // Mengembalikan data response ke pemanggil
    } catch (error) {
        let errorMessage = "Terjadi kesalahan saat mengunduh file.";
        let errorId = "Unknown";
        let title = "Oops... !";

        if (error.response && error.response.data) {
            const contentType = error.response.headers['content-type'] || "";

            if (contentType.includes("application/json")) {
                try {
                    // Konversi Blob ke JSON
                    const errorText = await error.response.data.text();
                    const jsonError = JSON.parse(errorText);

                    errorMessage = jsonError.message || "Terjadi kesalahan saat mengunduh file.";
                    errorId = jsonError.error_id || "Unknown";
                    title = jsonError.title || "Oops... !";
                } catch (jsonError) {
                    console.error("Error parsing JSON from Blob:", jsonError);
                }
            } else if (contentType.includes("text/plain")) {
                try {
                    // Jika response dalam bentuk teks biasa
                    errorMessage = await error.response.data.text();
                } catch (textError) {
                    console.error("Error reading text response:", textError);
                }
            }
        }

        sweetError(title, errorMessage, errorId);
        return false;
    }
}

const apiDownloadFile = async (url = "", paramsData = {}) => {
    const pesan = ref('');
    Api.defaults.headers.common['Authorization'] = "Bearer " + token;
    
    try {
        const response = await Api.get(url, {
            params: paramsData,
            responseType: 'blob',
        });
        return response.data; // Mengembalikan data response ke pemanggil
    } catch (error) {
        const ResObj = error.response.data?.message || Object.values(error.response.data);
        const errorId = error.response.data?.error_id || 'Unknown';
        const title = error.response.data?.title || 'Oops... !';

        sweetError(title, ResObj.toString(), errorId);
        return false;
    }
}

const sweetError = (title, pesan, errorId = "") => {
    Swal.fire({
        icon: "error",
        title: title ?? 'Oops... !',
        html: pesan ?? 'Harap hubungi tim IT',
        confirmButtonColor: '#d9534f',
        confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',
        footer: `<strong>Error ID:</strong> ${errorId}`,

    });
}

const sweetSuccess = (title, pesan) => {
    Swal.fire({
        icon: "success",
        title: title ?? 'Yeeyy, Success !',
        html: pesan ?? 'Success',
        confirmButtonColor: '#5cb85c',
        confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',
    });
}



export { 
    apiGetData, 
    apiPostData, 
    apiPutData, 
    apiPostDataWithReturn,
    processing,
    apiDeleteData, 
    loadingButton, 
    loadingSubmit, 
    Swal,
    loading,
    pesan,
    dayjs,
    waitingicon,
    Api,
    apiPostDataNotif,
    apiExportExcel,
    apiCetakPDF,
    apiDownloadFile
};
