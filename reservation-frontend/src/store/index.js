// import Vue from "vue";
import { createStore } from "vuex";
import auth from "./auth";


export default createStore({
  state() {
    return {
      errors: []
    };
  },

  getters: {
    errors: state => state.errors
  },

  mutations: {
    setErrors(state, errors) {
      state.errors = errors;
    }
  },

  actions: {},

  modules: {
    auth
  }
});
