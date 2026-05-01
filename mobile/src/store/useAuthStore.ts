import { create } from 'zustand';
import api from '../lib/axios';
import AsyncStorage from '@react-native-async-storage/async-storage';

interface User {
  id: number;
  name: string;
  email: string;
}

interface AuthState {
  user: User | null;
  token: string | null;
  isAuthenticated: boolean;
  isLoading: boolean;
  login: (token: string, user: User) => Promise<void>;
  logout: () => Promise<void>;
  checkAuth: () => Promise<void>;
}

export const useAuthStore = create<AuthState>((set) => ({
  user: null,
  token: null,
  isAuthenticated: false,
  isLoading: true,
  
  login: async (token, user) => {
    await AsyncStorage.setItem('token', token);
    set({ token, user, isAuthenticated: true });
  },
  
  logout: async () => {
    await AsyncStorage.removeItem('token');
    api.post('/logout').catch(() => {});
    set({ token: null, user: null, isAuthenticated: false });
  },
  
  checkAuth: async () => {
    const token = await AsyncStorage.getItem('token');
    if (!token) {
      set({ isLoading: false, isAuthenticated: false });
      return;
    }
    
    try {
      const response = await api.get('/user');
      set({ user: response.data, isAuthenticated: true, isLoading: false, token });
    } catch (error) {
      await AsyncStorage.removeItem('token');
      set({ token: null, user: null, isAuthenticated: false, isLoading: false });
    }
  }
}));
