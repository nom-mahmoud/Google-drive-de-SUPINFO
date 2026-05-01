import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import axios from 'axios';
import { Download, Lock, FileText, CloudLightning, ShieldAlert, Zap } from 'lucide-react';

const PublicShare: React.FC = () => {
    const { token } = useParams();
    const [password, setPassword] = useState('');
    const [item, setItem] = useState<any>(null);
    const [type, setType] = useState<'file' | 'folder' | null>(null);
    const [error, setError] = useState<string | null>(null);
    const [loading, setLoading] = useState(false);
    const [needsPassword, setNeedsPassword] = useState(false);

    const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';

    const accessLink = async () => {
        setLoading(true);
        setError(null);
        try {
            const res = await axios.post(`${API_URL}/share-links/access/${token}`, {}, {
                headers: { 'X-Share-Password': password }
            });
            setItem(res.data.item);
            setType(res.data.type);
            setNeedsPassword(false);
        } catch (err: any) {
            if (err.response?.status === 403) {
                setNeedsPassword(true);
            } else if (err.response?.status === 410) {
                setError('Link has expired.');
            } else {
                setError('Invalid or broken teleport link.');
            }
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        accessLink();
    }, [token]);

    const handleDownload = () => {
        if (!item) return;
        const downloadUrl = `${API_URL}/files/${item.id}/download?token=${token}`;
        window.open(downloadUrl, '_blank');
    };

    return (
        <div style={{ minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', backgroundColor: '#f4f7fe', padding: '24px' }}>
            <div style={{ position: 'fixed', top: '-10%', left: '-5%', width: '40vw', height: '40vw', background: 'linear-gradient(135deg, #6366f1 0%, #a855f7 100%)', filter: 'blur(100px)', opacity: 0.1, zIndex: 0 }}></div>
            
            <div style={{ width: '100%', maxWidth: '440px', position: 'relative', zIndex: 1 }} className="animate-bento">
                <div style={{ padding: '48px', backgroundColor: 'white', borderRadius: '40px', boxShadow: '0 20px 50px rgba(0,0,0,0.1)', textAlign: 'center' }}>
                    
                    <div style={{ marginBottom: '32px' }}>
                        <div style={{ background: 'linear-gradient(135deg, #6366f1 0%, #a855f7 100%)', padding: '16px', borderRadius: '24px', display: 'inline-flex', marginBottom: '20px', boxShadow: '0 10px 20px rgba(99, 102, 241, 0.3)' }}>
                            <CloudLightning size={32} color="white" />
                        </div>
                        <h1 style={{ fontSize: '28px', fontWeight: 800, letterSpacing: '-1px' }}>SUPFile <span className="text-gradient">Portal</span></h1>
                    </div>

                    {needsPassword && (
                        <div>
                            <div style={{ background: '#fef2f2', padding: '16px', borderRadius: '16px', marginBottom: '24px' }}>
                                <Lock size={20} color="#ef4444" style={{ marginBottom: '8px' }} />
                                <p style={{ fontSize: '14px', fontWeight: 600, color: '#ef4444' }}>This signal is protected.</p>
                            </div>
                            <div className="form-group">
                                <input 
                                    type="password" 
                                    className="form-control" 
                                    style={{ textAlign: 'center' }}
                                    placeholder="Enter Access Key"
                                    value={password}
                                    onChange={e => setPassword(e.target.value)}
                                />
                            </div>
                            <button className="btn btn-primary" style={{ width: '100%' }} onClick={accessLink}>
                                {loading ? 'Checking...' : 'Decrypt & Enter'}
                            </button>
                        </div>
                    )}

                    {error && (
                        <div style={{ padding: '20px' }}>
                            <ShieldAlert size={48} color="var(--danger)" style={{ marginBottom: '12px' }} />
                            <p style={{ fontWeight: 700, color: 'var(--text-main)' }}>{error}</p>
                        </div>
                    )}

                    {item && !needsPassword && !error && (
                        <div>
                            <div style={{ background: '#f8fafc', padding: '24px', borderRadius: '24px', marginBottom: '32px', border: '1px solid #e1e7ef' }}>
                                <FileText size={48} color="var(--primary)" style={{ marginBottom: '16px' }} />
                                <h3 style={{ fontSize: '18px', fontWeight: 800, marginBottom: '8px', wordBreak: 'break-all' }}>{item.original_name || item.name}</h3>
                                <p style={{ fontSize: '14px', color: 'var(--text-dim)', fontWeight: 600 }}>Ready for transmission</p>
                            </div>
                            
                            <button className="btn btn-primary" style={{ width: '100%', padding: '16px', fontSize: '16px' }} onClick={handleDownload}>
                                <Download size={20} /> Start Download
                            </button>
                        </div>
                    )}

                    {loading && !needsPassword && !item && !error && (
                        <div className="animate-spin" style={{ width: '40px', height: '40px', border: '4px solid #f1f5f9', borderTopColor: 'var(--primary)', borderRadius: '50%', margin: '0 auto' }}></div>
                    )}
                </div>

                <p style={{ marginTop: '32px', textAlign: 'center', fontSize: '13px', color: 'var(--text-dim)', fontWeight: 600 }}>
                    Powered by SupFile Interstellar Storage
                </p>
            </div>
        </div>
    );
};

export default PublicShare;
