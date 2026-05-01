import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { useAuthStore } from '../store/useAuthStore';
import { CloudLightning, Rocket, Sparkles, Smile } from 'lucide-react';
import api from '../lib/axios';

const Register: React.FC = () => {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const login = useAuthStore(state => state.login);
  const navigate = useNavigate();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setLoading(true);
    
    try {
      const response = await api.post('/register', { 
        name, 
        email, 
        password, 
        password_confirmation: passwordConfirmation 
      });
      login(response.data.token, response.data.user);
      navigate('/');
    } catch (err: any) {
      if (err.response?.data?.errors) {
        setError(Object.values(err.response.data.errors).flat().join(', '));
      } else {
        setError(err.response?.data?.message || 'Initialize failed. Try another entry point.');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div style={{ minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', backgroundColor: 'var(--bg-app)', padding: '24px' }}>
      {/* Background Orbs */}
      <div style={{ position: 'fixed', top: '-5%', right: '-5%', width: '45vw', height: '45vw', background: 'var(--secondary-gradient)', filter: 'blur(120px)', opacity: 0.12, zIndex: 0 }}></div>
      <div style={{ position: 'fixed', bottom: '-10%', left: '-5%', width: '35vw', height: '35vw', background: 'var(--accent-gradient)', filter: 'blur(100px)', opacity: 0.1, zIndex: 0 }}></div>

      <div style={{ width: '100%', maxWidth: '520px', position: 'relative', zIndex: 1 }} className="animate-bento">
        <div style={{ padding: '48px', backgroundColor: 'white', borderRadius: '40px', boxShadow: 'var(--shadow-vibrant)', border: '1px solid rgba(255,255,255,0.8)' }}>
          <div style={{ textAlign: 'center', marginBottom: '40px' }}>
            <div style={{ 
              display: 'inline-flex', 
              background: 'var(--secondary-gradient)', 
              padding: '16px', 
              borderRadius: '24px', 
              marginBottom: '24px',
              boxShadow: '0 15px 30px rgba(59, 130, 246, 0.3)'
            }}>
              <CloudLightning size={40} color="white" />
            </div>
            <h1 style={{ fontSize: '36px', fontWeight: 800, letterSpacing: '-1.5px', marginBottom: '12px' }}>
              Welcome <span className="text-gradient">Explorer</span>
            </h1>
            <p style={{ color: 'var(--text-dim)', fontSize: '16px', fontWeight: 500 }}>Create your unique identification code.</p>
          </div>

          {error && (
            <div style={{ background: '#fff1f2', color: '#e11d48', padding: '16px', borderRadius: '16px', marginBottom: '24px', textAlign: 'center', fontWeight: 600, border: '1px solid #ffe4e6' }}>
              {error}
            </div>
          )}

          <form onSubmit={handleSubmit}>
            <div className="form-group">
              <label className="form-label">Full Identity</label>
              <input
                type="text"
                className="form-control"
                value={name}
                onChange={e => setName(e.target.value)}
                placeholder="Capt. John Doe"
                required
              />
            </div>

            <div className="form-group">
              <label className="form-label">Interstellar Email</label>
              <input
                type="email"
                className="form-control"
                value={email}
                onChange={e => setEmail(e.target.value)}
                placeholder="explorer@galaxy.com"
                required
              />
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '16px' }}>
              <div className="form-group">
                <label className="form-label">Secret Code</label>
                <input
                  type="password"
                  className="form-control"
                  value={password}
                  onChange={e => setPassword(e.target.value)}
                  placeholder="••••••••"
                  required
                />
              </div>

              <div className="form-group">
                <label className="form-label">Repeat Code</label>
                <input
                  type="password"
                  className="form-control"
                  value={passwordConfirmation}
                  onChange={e => setPasswordConfirmation(e.target.value)}
                  placeholder="••••••••"
                  required
                />
              </div>
            </div>

            <button type="submit" className="btn btn-primary" style={{ width: '100%', padding: '16px', fontSize: '18px', marginTop: '10px' }} disabled={loading}>
              {loading ? 'Initializing Core...' : 'Launch into Space'}
              {!loading && <Rocket size={20} />}
            </button>
          </form>

          <div style={{ marginTop: '32px', textAlign: 'center' }}>
            <p style={{ color: 'var(--text-dim)', fontSize: '14px', fontWeight: 500 }}>
              Already identified? <Link to="/login" style={{ color: 'var(--primary)', fontWeight: 800, textDecoration: 'none' }}>Re-connect <Smile size={14} style={{ display: 'inline' }} /></Link>
            </p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Register;
