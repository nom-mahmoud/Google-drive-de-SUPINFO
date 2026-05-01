import React from 'react';
import { Outlet, Link, useLocation } from 'react-router-dom';
import { useAuthStore } from '../store/useAuthStore';
import { 
  FolderOpen, 
  LayoutDashboard, 
  Share2, 
  LogOut, 
  CloudLightning, 
  Search, 
  Smile 
} from 'lucide-react';

const AppLayout: React.FC = () => {
  const { user, logout } = useAuthStore();
  const location = useLocation();

  const navItems = [
    { name: 'Dashboard', path: '/dashboard', icon: <LayoutDashboard size={24} /> },
    { name: 'My Core', path: '/', icon: <FolderOpen size={24} /> },
    { name: 'Universe', path: '/shared', icon: <Share2 size={24} /> },
  ];

  return (
    <div className="app-layout">
      {/* 2026 Floating Navigation */}
      <aside className="sidebar">
        <div style={{ marginBottom: '40px' }}>
          <div style={{ 
            background: 'var(--primary-gradient)', 
            padding: '12px', 
            borderRadius: '16px', 
            boxShadow: 'var(--shadow-vibrant)',
            transform: 'rotate(-5deg)'
          }}>
            <CloudLightning size={28} color="white" />
          </div>
        </div>

        <nav style={{ display: 'flex', flexDirection: 'column', gap: '8px' }}>
          {navItems.map((item) => {
            const isActive = location.pathname === item.path || (item.path === '/' && location.pathname.startsWith('/folder'));
            return (
              <Link
                key={item.name}
                to={item.path}
                className={`sidebar-link ${isActive ? 'active' : ''}`}
                title={item.name}
              >
                {item.icon}
              </Link>
            );
          })}
        </nav>

        <div style={{ marginTop: 'auto', display: 'flex', flexDirection: 'column', gap: '20px', alignItems: 'center' }}>
          <button className="sidebar-link" onClick={() => logout()} style={{ color: 'var(--danger)', background: 'rgba(239, 68, 68, 0.05)' }}>
            <LogOut size={22} />
          </button>
          
          <div style={{ 
            width: '48px', 
            height: '48px', 
            borderRadius: '14px', 
            background: 'var(--accent-gradient)',
            display: 'flex', 
            alignItems: 'center', 
            justifyContent: 'center',
            color: 'white',
            fontWeight: 800,
            fontSize: '18px',
            boxShadow: 'var(--shadow-soft)'
          }}>
            {user?.name?.charAt(0).toUpperCase()}
          </div>
        </div>
      </aside>

      {/* 2026 Fluid Container */}
      <main className="main-container">
        <header className="topbar">
          <div style={{ display: 'flex', alignItems: 'center', gap: '16px' }}>
             <h2 className="text-gradient" style={{ fontSize: '24px', fontWeight: 800, letterSpacing: '-1px' }}>SUPFile</h2>
          </div>

          <div style={{ 
            display: 'flex', 
            alignItems: 'center', 
            background: '#f1f5f9', 
            padding: '12px 20px', 
            borderRadius: '20px', 
            width: '450px',
            border: '2px solid transparent',
            transition: 'border-color 0.2s'
          }}>
            <Search size={18} color="var(--text-dim)" />
            <input 
              type="text" 
              placeholder="Search your universe..." 
              style={{ background: 'transparent', border: 'none', color: 'var(--text-main)', width: '100%', outline: 'none', marginLeft: '12px', fontSize: '15px', fontWeight: 500 }} 
            />
          </div>

          <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
            <div style={{ textAlign: 'right', display: 'none' }}>
               <p style={{ fontSize: '14px', fontWeight: 700 }}>{user?.name}</p>
               <p style={{ fontSize: '12px', color: 'var(--text-muted)' }}>Explorer</p>
            </div>
            <button className="btn btn-icon"><Smile size={22} /></button>
          </div>
        </header>

        <div className="content-area">
          <Outlet />
        </div>
      </main>
    </div>
  );
};

export default AppLayout;
