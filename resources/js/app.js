import {createApp} from "vue";
import "../css/app.css"
import "./echo.js";
import App from "./pages/App.vue";
import router from "./router.js";
import axios from 'axios'
import pinia from "./stores/initPinia.js";
import Toast from "vue-toastification";
import {options} from "./toast.js";
import "vue-toastification/dist/index.css";

axios.defaults.baseURL = 'http://localhost:8000'
axios.defaults.withCredentials = true


const app = createApp(App)
app.use(router)
app.use(pinia)
app.use(Toast, options)
app.mount("#app");
