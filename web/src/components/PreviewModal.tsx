import React from 'react';
import { X, Download } from 'lucide-react';
import api from '../lib/axios';

interface PreviewModalProps {
  file: {
    id: number;
    original_name: string;
    mime_type: string;
  };
  onClose: () => void;
}

const PreviewModal: React.FC<PreviewModalProps> = ({ file, onClose }) => {
  const downloadUrl = `${api.defaults.baseURL}/files/${file.id}/download`;
  const handleDownload = () => window.open(downloadUrl, '_blank');

  return (
    <div style={{ position: 'fixed', inset: 0, backgroundColor: 'rgba(0,0,0,0.8)', zIndex: 50, display: 'flex', alignItems: 'center', justifyContent: 'center', padding: '2rem' }}>
      <div className="glass" style={{ width: '100%', maxWidth: '800px', borderRadius: 'var(--radius-lg)', overflow: 'hidden' }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', padding: '1rem', borderBottom: '1px solid var(--border-color)' }}>
          <h3 style={{ fontWeight: 600, margin: 0, whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis', maxWidth: '80%' }}>
            {file.original_name}
          </h3>
          <div style={{ display: 'flex', gap: '0.5rem' }}>
            <button className="btn-icon" onClick={handleDownload} title="Download">
              <Download size={20} />
            </button>
            <button className="btn-icon" onClick={onClose} title="Close">
              <X size={20} />
            </button>
          </div>
        </div>
        <div style={{ padding: '1.5rem', display: 'flex', justifyContent: 'center', alignItems: 'center', minHeight: '300px', background: 'rgba(0,0,0,0.3)' }}>
            <div style={{ padding: '2rem', textAlign: 'center', color: 'var(--text-secondary)' }}>
                <p>Preview for this specific file type may depend on the server URL configuration.</p>
                <button className="btn btn-primary" style={{ marginTop: '1rem' }} onClick={handleDownload}>
                    Download to view
                </button>
            </div>
        </div>
      </div>
    </div>
  );
};

export default PreviewModal;
