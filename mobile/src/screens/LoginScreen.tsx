import React, { useState } from 'react';
import { View, Text, TextInput, TouchableOpacity, StyleSheet, ActivityIndicator } from 'react-native';
import { useAuthStore } from '../store/useAuthStore';
import api from '../lib/axios';

export default function LoginScreen() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const login = useAuthStore(state => state.login);

  const handleLogin = async () => {
    setError('');
    setLoading(true);
    try {
      const response = await api.post('/login', { email, password });
      await login(response.data.token, response.data.user);
    } catch (err: any) {
      if (err.response?.data?.errors) {
        setError(Object.values(err.response.data.errors).flat().join(', '));
      } else {
        setError(err.response?.data?.message || 'Login failed.');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>SUPFile</Text>
      <Text style={styles.subtitle}>Sign in to your account</Text>
      
      {error !== '' && <Text style={styles.error}>{error}</Text>}

      <TextInput
        style={styles.input}
        placeholder="Email"
        placeholderTextColor="#888"
        value={email}
        onChangeText={setEmail}
        autoCapitalize="none"
        keyboardType="email-address"
      />
      
      <TextInput
        style={styles.input}
        placeholder="Password"
        placeholderTextColor="#888"
        value={password}
        onChangeText={setPassword}
        secureTextEntry
      />

      <TouchableOpacity style={styles.button} onPress={handleLogin} disabled={loading}>
        {loading ? <ActivityIndicator color="#fff" /> : <Text style={styles.buttonText}>Sign In</Text>}
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1, 
    justifyContent: 'center', 
    padding: 20, 
    backgroundColor: '#0f172a'
  },
  title: {
    fontSize: 32, 
    fontWeight: 'bold', 
    color: '#3b82f6', 
    textAlign: 'center', 
    marginBottom: 10
  },
  subtitle: {
    fontSize: 16, 
    color: '#94a3b8', 
    textAlign: 'center', 
    marginBottom: 30
  },
  input: {
    backgroundColor: '#1e293b', 
    color: '#fff', 
    padding: 15, 
    borderRadius: 8, 
    marginBottom: 15, 
    borderWidth: 1, 
    borderColor: '#334155'
  },
  button: {
    backgroundColor: '#3b82f6', 
    padding: 15, 
    borderRadius: 8, 
    alignItems: 'center', 
    marginTop: 10
  },
  buttonText: {
    color: '#fff', 
    fontWeight: 'bold', 
    fontSize: 16
  },
  error: {
    color: '#ef4444', 
    marginBottom: 15, 
    textAlign: 'center'
  }
});
