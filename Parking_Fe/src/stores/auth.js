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
    setAdmin(user) {
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
    setUser(user) {
      this.user = user;
      localStorage.setItem('user_user', JSON.stringify(user));
    },
    setTokenUser(token) {
      this.token = token;
      localStorage.setItem('token_user', token);
    },
    getTokenUser() {
      return localStorage.getItem('token_user');
    },
    getUserUser() {
      return JSON.parse(localStorage.getItem('user_user'));
    },
    logoutUser() {
      this.user = null;
      this.token = null;
      localStorage.removeItem('token_user');
      localStorage.removeItem('user_user');
    }
  },

  // 👇 Dòng này giúp lưu state vào localStorage
  persist: true
})
