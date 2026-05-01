import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { useAuthStore } from '../store/useAuthStore';
import { CloudLightning, Zap, ArrowRight, Sparkles } from 'lucide-react';
import api from '../lib/axios';

const Login: React.FC = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const login = useAuthStore(state => state.login);
  const navigate = useNavigate();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setLoading(true);
    try {
      const response = await api.post('/login', { email, password });
      login(response.data.token, response.data.user);
      navigate('/');
    } catch (err: any) {
      setError(err.response?.data?.message || 'Oops! Entry denied.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div style={{ minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', backgroundColor: 'var(--bg-app)', padding: '24px' }}>
      {/* Organic Blobs in background */}
      <div style={{ position: 'fixed', top: '-10%', left: '-5%', width: '40vw', height: '40vw', background: 'var(--primary-gradient)', filter: 'blur(100px)', opacity: 0.1, zIndex: 0 }}></div>
      <div style={{ position: 'fixed', bottom: '-5%', right: '-5%', width: '30vw', height: '30vw', background: 'var(--accent-gradient)', filter: 'blur(80px)', opacity: 0.1, zIndex: 0 }}></div>

      <div style={{ width: '100%', maxWidth: '480px', position: 'relative', zIndex: 1 }} className="animate-bento">
        <div style={{ padding: '48px', backgroundColor: 'white', borderRadius: '40px', boxShadow: 'var(--shadow-vibrant)', border: '1px solid rgba(255,255,255,0.8)' }}>
          <div style={{ textAlign: 'center', marginBottom: '40px' }}>
            <div style={{ 
              display: 'inline-flex', 
              background: 'var(--primary-gradient)', 
              padding: '16px', 
              borderRadius: '24px', 
              marginBottom: '24px',
              boxShadow: 'var(--shadow-vibrant)'
            }}>
              <CloudLightning size={40} color="white" />
            </div>
            <h1 style={{ fontSize: '36px', fontWeight: 800, letterSpacing: '-1.5px', marginBottom: '12px' }}>
              Jump into <span className="text-gradient">SUPFile</span>
            </h1>
            <p style={{ color: 'var(--text-dim)', fontSize: '16px', fontWeight: 500 }}>The futuristic vault for your digital universe.</p>
          </div>

          {error && (
            <div style={{ background: '#fef2f2', color: '#ef4444', padding: '16px', borderRadius: '16px', marginBottom: '24px', textAlign: 'center', fontWeight: 600, border: '1px solid #fee2e2' }}>
              {error}
            </div>
          )}

          <form onSubmit={handleSubmit}>
            <div className="form-group">
              <label className="form-label">Email Explorer</label>
              <input
                type="email"
                className="form-control"
                value={email}
                onChange={e => setEmail(e.target.value)}
                placeholder="you@universe.com"
                required
              />
            </div>

            <div className="form-group" style={{ marginBottom: '32px' }}>
              <label className="form-label">Secret Key</label>
              <input
                type="password"
                className="form-control"
                value={password}
                onChange={e => setPassword(e.target.value)}
                placeholder="••••••••"
                required
              />
            </div>

            <button type="submit" className="btn btn-primary" style={{ width: '100%', padding: '16px', fontSize: '18px' }} disabled={loading}>
              {loading ? 'Powering up...' : 'Connect to Core'}
              {!loading && <Zap size={20} />}
            </button>
          </form>

          <div style={{ marginTop: '32px', textAlign: 'center' }}>
            <p style={{ color: 'var(--text-dim)', fontSize: '14px', fontWeight: 500 }}>
              New Explorer? <Link to="/register" style={{ color: 'var(--primary)', fontWeight: 800, textDecoration: 'none' }}>Initialize Account <Sparkles size={14} style={{ display: 'inline' }} /></Link>
            </p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Login;
