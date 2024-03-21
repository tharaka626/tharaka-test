import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from "./store";
import PrimeVue from 'primevue/config';
import Lara from '@/presets/lara';
import axios from "axios";
  
axios.interceptors.response.use(
  response => response,
  error => {
    if (error.response.status === 422) {
      store.commit("setErrors", error.response.data.errors);
    } else if (error.response.status === 401) {
      store.commit("auth/setUserData", null);
      localStorage.removeItem("authToken");
      router.push({ name: "Login" });
    } else {
      return Promise.reject(error);
    }
  }
);

axios.interceptors.request.use(function(config) {
  config.headers.Authorization = `Bearer ${localStorage.getItem("authToken")}`;
  // config.headers.Content-Type = 'application/json';
  // config.headers.Accept = 'application/json';
  // config.headers.common = {
  //   Authorization: `Bearer ${localStorage.getItem("authToken")}`,
  //   // Authorization: `Bearer ${localStorage.getItem("authToken")}`,
  //   "Content-Type": "application/json",
  //   Accept: "application/json"
  // };

  return config;
});

const app = createApp(App)

app.use(router)
app.use(store)
app.use(PrimeVue, {
    ripple: true,
    unstyled: true,
    pt: Lara
});
app.config.globalProperties.$hostname = 'http://127.0.0.1:8000'
app.mount('#app')


