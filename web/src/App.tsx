import { useEffect } from 'react';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { useAuthStore } from './store/useAuthStore';
import Login from './pages/Login';
import Register from './pages/Register';
import AppLayout from './layouts/AppLayout';
import FileExplorer from './pages/FileExplorer';
import Dashboard from './pages/Dashboard';
import Shared from './pages/Shared';
import PublicShare from './pages/PublicShare';

function App() {
  const checkAuth = useAuthStore((state) => state.checkAuth);
  const isLoading = useAuthStore((state) => state.isLoading);
  const isAuthenticated = useAuthStore((state) => state.isAuthenticated);

  useEffect(() => {
    checkAuth();
  }, [checkAuth]);

  if (isLoading) {
    return (
      <div style={{ height: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', backgroundColor: '#f4f7fe' }}>
        <div className="animate-spin" style={{ width: '40px', height: '40px', border: '3px solid #e2e8f0', borderTopColor: '#6366f1', borderRadius: '50%' }}></div>
      </div>
    );
  }

  return (
    <BrowserRouter>
      <Routes>
        <Route path="/login" element={!isAuthenticated ? <Login /> : <Navigate to="/" />} />
        <Route path="/register" element={!isAuthenticated ? <Register /> : <Navigate to="/" />} />
        
        {/* Public Routes */}
        <Route path="/s/:token" element={<PublicShare />} />

        {/* Private Routes */}
        <Route element={isAuthenticated ? <AppLayout /> : <Navigate to="/login" />}>
          <Route path="/" element={<FileExplorer />} />
          <Route path="/dashboard" element={<Dashboard />} />
          <Route path="/folder/:id" element={<FileExplorer />} />
          <Route path="/shared" element={<Shared />} />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}

export default App;
