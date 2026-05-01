import React, { useState, useEffect } from 'react';
import api from '../lib/axios';
import { HardDrive, FileText, Image as ImageIcon, Video, Zap, Activity, Globe, PieChart } from 'lucide-react';

const Dashboard: React.FC = () => {
    const [data, setData] = useState<any>(null);
    const [error, setError] = useState(false);

    useEffect(() => {
        api.get('/dashboard')
            .then(res => {
                if (typeof res.data === 'string' && res.data.includes('<!doctype html>')) {
                    setError(true);
                } else {
                    setData(res.data);
                }
            })
            .catch(() => setError(true));
    }, []);

    if (error) return (
        <div style={{ padding: '60px', textAlign: 'center' }} className="animate-bento">
            <div style={{ background: 'white', padding: '40px', borderRadius: '40px', boxShadow: 'var(--shadow-soft)' }}>
                <Activity size={48} color="var(--danger)" style={{ marginBottom: '20px' }} />
                <h2 className="text-gradient" style={{ fontSize: '24px', fontWeight: 800 }}>Core Sync Error</h2>
                <p style={{ color: 'var(--text-dim)', marginBottom: '24px' }}>The digital galaxy is currently unreachable. Please verify your connection.</p>
                <button className="btn btn-primary" onClick={() => window.location.reload()}>Retry Connection</button>
            </div>
        </div>
    );

    if (!data) return (
      <div style={{ height: '300px', display: 'flex', justifyContent: 'center', alignItems: 'center' }}>
        <div className="animate-spin" style={{ width: '44px', height: '44px', border: '4px solid #e2e8f0', borderTopColor: 'var(--primary)', borderRadius: '50%' }}></div>
      </div>
    );

    const percentage = data?.quota?.used ? Math.min(100, (data.quota.used / (data?.quota?.max || 1)) * 100) : 0;

    const formatSize = (bytes: number) => {
      if (!bytes) return '0 B';
      const g = 1024 * 1024 * 1024;
      if (bytes < g) return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
      return (bytes / g).toFixed(1) + ' GB';
    };

    const getStatSize = (type: string) => {
        return data?.storage_by_type?.find?.((t: any) => t.type === type)?.total_size || 0;
    };

    return (
        <div className="animate-bento">
            <div style={{ marginBottom: '40px' }}>
              <h1 style={{ fontSize: '32px', fontWeight: 800, letterSpacing: '-1.5px' }}>
                Good system, <span className="text-gradient">Commander</span>
              </h1>
              <p style={{ color: 'var(--text-dim)', fontSize: '16px', fontWeight: 500 }}>Here's what happening in your digital galaxy.</p>
            </div>
            
            <div className="bento-grid">
                {/* Main Storage Bento */}
                <div className="bento-card" style={{ gridColumn: 'span 2', position: 'relative', overflow: 'hidden' }}>
                    <div style={{ position: 'absolute', top: '-20px', right: '-20px', opacity: 0.1 }}>
                       <HardDrive size={160} color="var(--primary)" />
                    </div>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '12px', marginBottom: '32px' }}>
                        <div style={{ background: 'var(--primary-gradient)', padding: '10px', borderRadius: '12px', boxShadow: '0 8px 16px rgba(99, 102, 241, 0.2)' }}>
                          <HardDrive size={24} color="white" />
                        </div>
                        <h2 style={{ fontSize: '18px', fontWeight: 800 }}>Core Storage</h2>
                    </div>

                    <div style={{ marginBottom: '16px' }}>
                        <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '12px', fontSize: '15px' }}>
                          <span style={{ color: 'var(--text-dim)', fontWeight: 600 }}>Energy Level</span>
                          <span style={{ fontWeight: 800, color: 'var(--primary)' }}>{formatSize(data?.quota?.used)} / 30 GB</span>
                        </div>
                        <div style={{ width: '100%', height: '14px', background: '#f1f5f9', borderRadius: '20px', overflow: 'hidden', padding: '3px' }}>
                            <div style={{ width: `${percentage}%`, height: '100%', background: 'var(--primary-gradient)', borderRadius: '20px', boxShadow: '0 0 15px rgba(99, 102, 241, 0.4)' }}></div>
                        </div>
                    </div>
                </div>

                {/* mini Stats */}
                <div className="bento-card" style={{ background: 'var(--secondary-gradient)', color: 'white' }}>
                    <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '20px' }}>
                         <div style={{ background: 'rgba(255,255,255,0.2)', padding: '8px', borderRadius: '10px' }}><Globe size={20} /></div>
                    </div>
                    <p style={{ fontSize: '14px', fontWeight: 600, opacity: 0.9 }}>Digital Presence</p>
                    <h3 style={{ fontSize: '28px', fontWeight: 800 }}>Online</h3>
                </div>

                <div className="bento-card" style={{ background: 'var(--accent-gradient)', color: 'white' }}>
                    <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '20px' }}>
                         <div style={{ background: 'rgba(255,255,255,0.2)', padding: '8px', borderRadius: '10px' }}><Zap size={20} /></div>
                    </div>
                    <p style={{ fontSize: '14px', fontWeight: 600, opacity: 0.9 }}>Core Latency</p>
                    <h3 style={{ fontSize: '28px', fontWeight: 800 }}>0.8ms</h3>
                </div>

                {/* Media Breakdown */}
                <div className="bento-card" style={{ gridColumn: 'span 2' }}>
                  <div style={{ display: 'flex', alignItems: 'center', gap: '12px', marginBottom: '24px' }}>
                      <PieChart size={20} color="var(--primary)" />
                      <h2 style={{ fontSize: '16px', fontWeight: 800 }}>Media Explorer</h2>
                  </div>
                  <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: '16px' }}>
                        {[
                          { name: 'Images', icon: <ImageIcon size={20} />, color: '#6366f1', size: getStatSize('image') },
                          { name: 'Videos', icon: <Video size={20} />, color: '#f59e0b', size: getStatSize('video') },
                          { name: 'Docs', icon: <FileText size={20} />, color: '#10b981', size: getStatSize('text') },
                        ].map(item => (
                          <div key={item.name} style={{ background: '#f8fafc', padding: '16px', borderRadius: '20px', textAlign: 'center' }}>
                             <div style={{ color: item.color, marginBottom: '8px', display: 'flex', justifyContent: 'center' }}>{item.icon}</div>
                             <p style={{ fontSize: '12px', fontWeight: 700, color: 'var(--text-muted)' }}>{item.name}</p>
                             <p style={{ fontSize: '14px', fontWeight: 800 }}>{formatSize(item.size)}</p>
                          </div>
                        ))}
                  </div>
                </div>

                {/* Recent Items */}
                <div className="bento-card" style={{ gridColumn: 'span 2' }}>
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
                    <h2 style={{ fontSize: '16px', fontWeight: 800 }}>Recent Signals</h2>
                  </div>
                  <div style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
                    {Array.isArray(data?.recent_files) && data.recent_files.map((file: any) => (
                      <div key={file.id} style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '12px', background: '#f8fafc', borderRadius: '16px' }}>
                        <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
                           <div style={{ background: 'white', padding: '8px', borderRadius: '10px' }}><FileText size={16} color="var(--primary)" /></div>
                           <span style={{ fontSize: '14px', fontWeight: 600 }}>{file.original_name}</span>
                        </div>
                        <span style={{ fontSize: '12px', color: 'var(--text-muted)', fontWeight: 600 }}>{formatSize(file.size)}</span>
                      </div>
                    ))}
                    {(!Array.isArray(data?.recent_files) || data.recent_files.length === 0) && (
                        <p style={{ textAlign: 'center', color: 'var(--text-muted)', fontSize: '14px', padding: '20px' }}>No signals detected.</p>
                    )}
                  </div>
                </div>
            </div>
        </div>
    );
};

export default Dashboard;
