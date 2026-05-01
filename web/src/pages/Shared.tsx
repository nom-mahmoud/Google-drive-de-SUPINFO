import React, { useState, useEffect } from 'react';
import api from '../lib/axios';
import { Share2, Trash2, Calendar, Link as LinkIcon, ExternalLink } from 'lucide-react';

interface ShareLink {
  id: number;
  token: string;
  expires_at: string | null;
  file?: { original_name: string };
  folder?: { name: string };
  created_at: string;
}

const Shared: React.FC = () => {
    const [links, setLinks] = useState<ShareLink[]>([]);
    const [loading, setLoading] = useState(true);

    const fetchLinks = async () => {
        setLoading(true);
        try {
            const res = await api.get('/share-links');
            setLinks(res.data);
        } catch (e) { console.error(e); }
        finally { setLoading(false); }
    };

    useEffect(() => {
        fetchLinks();
    }, []);

    const revokeLink = async (id: number) => {
        if (!confirm('Are you sure you want to revoke this link?')) return;
        try {
            await api.delete(`/share-links/${id}`);
            fetchLinks();
        } catch (e) { console.error(e); }
    };

    const copyLink = (token: string) => {
        const url = `${window.location.origin}/s/${token}`;
        navigator.clipboard.writeText(url);
        alert('Link copied to clipboard!');
    };

    return (
        <div className="animate-bento">
            <div style={{ marginBottom: '32px' }}>
              <h1 style={{ fontSize: '32px', fontWeight: 800, letterSpacing: '-1.5px' }}>
                Active <span className="text-gradient">Signals</span>
              </h1>
              <p style={{ color: 'var(--text-dim)', fontSize: '16px', fontWeight: 500 }}>Manage all files being transmitted to the universe.</p>
            </div>

            {loading ? (
                <div style={{ height: '300px', display: 'flex', justifyContent: 'center', alignItems: 'center' }}>
                    <div className="animate-spin" style={{ width: '40px', height: '40px', border: '4px solid #fff', borderTopColor: 'var(--primary)', borderRadius: '50%' }}></div>
                </div>
            ) : (
                <div className="table-container">
                    <table className="data-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Created</th>
                                <th>Expires</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {links.map(link => (
                                <tr key={link.id}>
                                    <td>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
                                            <div style={{ background: 'var(--primary-gradient)', padding: '8px', borderRadius: '10px' }}>
                                                <Share2 size={16} color="white" />
                                            </div>
                                            <span style={{ fontWeight: 700 }}>{link.file?.original_name || link.folder?.name}</span>
                                        </div>
                                    </td>
                                    <td style={{ color: 'var(--text-dim)', fontWeight: 600 }}>{new Date(link.created_at).toLocaleDateString()}</td>
                                    <td style={{ color: link.expires_at ? 'var(--accent-gradient)' : 'var(--text-muted)', fontWeight: 600 }}>
                                        {link.expires_at ? new Date(link.expires_at).toLocaleDateString() : 'Infinite'}
                                    </td>
                                    <td>
                                        <div style={{ display: 'flex', gap: '8px' }}>
                                            <button className="btn btn-icon btn-ghost" onClick={() => copyLink(link.token)} title="Copy Signal"><LinkIcon size={18} /></button>
                                            <a href={`/s/${link.token}`} target="_blank" className="btn btn-icon btn-ghost" title="View Portal"><ExternalLink size={18} /></a>
                                            <button className="btn btn-icon btn-ghost" style={{ color: 'var(--danger)' }} onClick={() => revokeLink(link.id)} title="Revoke Link"><Trash2 size={18} /></button>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                            {links.length === 0 && (
                                <tr>
                                    <td colSpan={4} style={{ padding: '80px', textAlign: 'center', color: 'var(--text-muted)' }}>
                                        No active signals detected. Start sharing to see them here.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            )}
        </div>
    );
};

export default Shared;
