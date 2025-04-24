// src/stores/auth.js
import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null,
  }),

  getters: {
    isLoggedIn: (state) => !!state.token,
  },

  actions: {
    setUser(user) {
      this.user = user;
      localStorage.setItem('user', JSON.stringify(user)); 
    },
    setToken(token) {
      this.token = token;
      localStorage.setItem('token', token);
    },
    getUser() {
      return JSON.parse(localStorage.getItem('user'));
    },
    getToken() {
      return localStorage.getItem('token');
    },
    logout() {
      this.user = null;
      this.token = null;
      localStorage.removeItem('token');
      localStorage.removeItem('user');

    },
  },

  // 👇 Dòng này giúp lưu state vào localStorage
  persist: true
})
