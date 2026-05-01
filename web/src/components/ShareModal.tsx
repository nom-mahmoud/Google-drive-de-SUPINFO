import React, { useState } from 'react';
import api from '../lib/axios';
import { X, Copy, Check, Lock } from 'lucide-react';

interface ShareModalProps {
  itemId: number;
  itemType: 'file' | 'folder';
  onClose: () => void;
}

const ShareModal: React.FC<ShareModalProps> = ({ itemId, itemType, onClose }) => {
  const [password, setPassword] = useState('');
  const [expiresAt, setExpiresAt] = useState('');
  const [shareLink, setShareLink] = useState('');
  const [loading, setLoading] = useState(false);
  const [copied, setCopied] = useState(false);

  const handleShare = async () => {
    setLoading(true);
    try {
      const payload: any = {
        expires_at: expiresAt || null,
        password: password || null,
      };
      if (itemType === 'file') payload.file_id = itemId;
      else payload.folder_id = itemId;

      const response = await api.post('/share-links', payload);
      const url = `${window.location.origin}/s/${response.data.token}`;
      setShareLink(url);
    } catch (e) {
      console.error(e);
      alert('Failed to generate link');
    } finally {
      setLoading(false);
    }
  };

  const copyToClipboard = () => {
    navigator.clipboard.writeText(shareLink);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  return (
    <div style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,0.5)', backdropFilter: 'blur(4px)', display: 'flex', alignItems: 'center', justifyContent: 'center', zIndex: 1000 }}>
      <div style={{ background: 'white', width: '100%', maxWidth: '400px', borderRadius: '32px', padding: '32px', boxShadow: 'var(--shadow-hover)' }} className="animate-bento">
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '24px' }}>
          <h2 style={{ fontSize: '20px', fontWeight: 800 }}>Share {itemType}</h2>
          <button className="btn btn-icon btn-ghost" onClick={onClose}><X size={20} /></button>
        </div>

        {!shareLink ? (
          <div>
            <div className="form-group">
              <label className="form-label">Password Protection (Optional)</label>
              <div style={{ position: 'relative' }}>
                <Lock size={16} style={{ position: 'absolute', left: '12px', top: '50%', transform: 'translateY(-50%)', color: 'var(--text-muted)' }} />
                <input 
                  type="password" 
                  className="form-control" 
                  style={{ paddingLeft: '38px' }}
                  placeholder="Set a secret key..." 
                  value={password}
                  onChange={e => setPassword(e.target.value)}
                />
              </div>
            </div>

            <div className="form-group">
              <label className="form-label">Expiry Date (Optional)</label>
              <input 
                type="datetime-local" 
                className="form-control" 
                value={expiresAt}
                onChange={e => setExpiresAt(e.target.value)}
              />
            </div>

            <button className="btn btn-primary" style={{ width: '100%', marginTop: '10px' }} onClick={handleShare} disabled={loading}>
              {loading ? 'Generating...' : 'Create Teleport Link'}
            </button>
          </div>
        ) : (
          <div>
            <div style={{ background: '#f8fafc', padding: '16px', borderRadius: '16px', marginBottom: '24px', position: 'relative' }}>
              <p style={{ fontSize: '12px', color: 'var(--text-dim)', marginBottom: '8px', fontWeight: 700 }}>TELEPORT LINK</p>
              <p style={{ wordBreak: 'break-all', fontSize: '14px', fontWeight: 600, color: 'var(--primary)' }}>{shareLink}</p>
              <button 
                onClick={copyToClipboard}
                style={{ position: 'absolute', right: '12px', top: '12px', border: 'none', background: 'white', padding: '8px', borderRadius: '8px', cursor: 'pointer', boxShadow: '0 2px 4px rgba(0,0,0,0.05)' }}
              >
                {copied ? <Check size={16} color="var(--success)" /> : <Copy size={16} />}
              </button>
            </div>
            <button className="btn btn-outline" style={{ width: '100%' }} onClick={onClose}>Done</button>
          </div>
        )}
      </div>
    </div>
  );
};

export default ShareModal;
