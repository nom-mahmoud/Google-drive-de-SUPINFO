import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { 
  FolderSearch, 
  FileText, 
  MoreVertical, 
  Rocket, 
  FolderPlus, 
  ChevronRight,
  Image as ImageIcon,
  Video,
  Download,
  Share2,
  Trash2,
  Sparkles
} from 'lucide-react';
import api from '../lib/axios';
import ShareModal from '../components/ShareModal';

interface Breadcrumb {
  id: number | null;
  name: string;
}

interface ServerFolder {
  id: number;
  name: string;
  parent_id: number | null;
  updated_at: string;
}

interface ServerFile {
  id: number;
  original_name: string;
  mime_type: string;
  size: number;
  updated_at: string;
}

const getFileIcon = (mimeType: string) => {
  if (mimeType.startsWith('image/')) return <ImageIcon size={22} color="#6366f1" />;
  if (mimeType.startsWith('video/')) return <Video size={22} color="#f59e0b" />;
  if (mimeType.startsWith('text/')) return <FileText size={22} color="#10b981" />;
  return <FileText size={22} color="#94a3b8" />;
};

const formatSize = (bytes: number) => {
  if (bytes === 0) return '0 B';
  const k = 1024;
  const sizes = ['B', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
};

const FileExplorer: React.FC = () => {
  const { id } = useParams();
  const [folders, setFolders] = useState<ServerFolder[]>([]);
  const [files, setFiles] = useState<ServerFile[]>([]);
  const [breadcrumbs, setBreadcrumbs] = useState<Breadcrumb[]>([]);
  const [loading, setLoading] = useState(true);
  const [shareModalData, setShareModalData] = useState<{ id: number; type: 'file' | 'folder' } | null>(null);

  const fetchItems = async () => {
    setLoading(true);
    try {
      const parentIdQuery = id ? `?parent_id=${id}` : '';
      const response = await api.get(`/folders${parentIdQuery}`);
      setFolders(response.data.folders);
      setFiles(response.data.files);
      setBreadcrumbs(response.data.breadcrumbs);
    } catch (e) {
      console.error(e);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchItems();
  }, [id]);

  const handleUploadClick = () => {
    const input = document.createElement('input');
    input.type = 'file';
    input.multiple = true;
    input.onchange = async (e: any) => {
      const selectedFiles = e.target.files;
      if (!selectedFiles.length) return;

      const formData = new FormData();
      for (let i = 0; i < selectedFiles.length; i++) {
         formData.append('files[]', selectedFiles[i]);
      }
      if (id) formData.append('folder_id', id);

      try {
        await api.post('/files', formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
        fetchItems();
      } catch (e) { console.error('Upload failed'); }
    };
    input.click();
  };

  const handleCreateFolder = async () => {
    const name = prompt('Folder identity:');
    if (!name) return;
    try {
      await api.post('/folders', { name, parent_id: id || null });
      fetchItems();
    } catch(e) { console.error('Failed to create folder'); }
  };

  return (
    <div className="animate-bento">
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '32px' }}>
        {/* Futuristic Breadcrumbs */}
        <div style={{ display: 'flex', alignItems: 'center', gap: '10px', background: 'white', padding: '10px 20px', borderRadius: '20px', boxShadow: 'var(--shadow-soft)' }}>
          {breadcrumbs.map((crumb, index) => (
            <React.Fragment key={index}>
              {index > 0 && <ChevronRight size={16} color="var(--text-muted)" />}
              <Link 
                to={crumb.id ? `/folder/${crumb.id}` : '/'} 
                style={{ 
                  color: index === breadcrumbs.length - 1 ? 'var(--primary)' : 'var(--text-dim)', 
                  textDecoration: 'none',
                  fontSize: '16px',
                  fontWeight: index === breadcrumbs.length - 1 ? 800 : 600
                }}
              >
                {crumb.name}
              </Link>
            </React.Fragment>
          ))}
        </div>

        {/* 2026 Actions */}
        <div style={{ display: 'flex', gap: '12px' }}>
          <button className="btn btn-outline" style={{ borderRadius: '20px' }} onClick={handleCreateFolder}>
            <FolderPlus size={18} /> New Cell
          </button>
          <button className="btn btn-primary" style={{ borderRadius: '20px' }} onClick={handleUploadClick}>
            <Rocket size={18} /> Teleport Files
          </button>
        </div>
      </div>

      {loading ? (
        <div style={{ height: '300px', display: 'flex', justifyContent: 'center', alignItems: 'center' }}>
          <div className="animate-spin" style={{ width: '40px', height: '40px', border: '4px solid #fff', borderTopColor: 'var(--primary)', borderRadius: '50%' }}></div>
        </div>
      ) : (
        <div style={{ padding: '8px' }}>
          <table className="data-table">
            <thead>
              <tr>
                <th>Identity</th>
                <th>Modified</th>
                <th>Volume</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              {folders.map(folder => (
                <tr key={`folder-${folder.id}`}>
                  <td>
                    <Link to={`/folder/${folder.id}`} style={{ display: 'flex', alignItems: 'center', gap: '16px', textDecoration: 'none', color: 'var(--text-main)' }}>
                      <div style={{ 
                        background: 'rgba(99, 102, 241, 0.1)', 
                        padding: '10px', 
                        borderRadius: '12px',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center'
                      }}>
                        <FolderSearch size={24} color="var(--primary)" />
                      </div>
                      <span style={{ fontWeight: 700, fontSize: '16px' }}>{folder.name}</span>
                    </Link>
                  </td>
                  <td><span style={{ color: 'var(--text-dim)', fontWeight: 600 }}>{new Date(folder.updated_at).toLocaleDateString()}</span></td>
                  <td><span style={{ color: 'var(--text-muted)', fontWeight: 600 }}>Cell</span></td>
                  <td style={{ textAlign: 'center' }}>
                    <div style={{ display: 'flex', justifyContent: 'center', gap: '4px' }}>
                      <button className="btn-icon btn-ghost" onClick={() => setShareModalData({ id: folder.id, type: 'folder' })} title="Beam"><Share2 size={20} /></button>
                      <button className="btn-icon btn-ghost"><MoreVertical size={20} /></button>
                    </div>
                  </td>
                </tr>
              ))}
              
              {files.map(file => (
                <tr key={`file-${file.id}`}>
                  <td>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '16px' }}>
                      <div style={{ background: '#f8fafc', padding: '10px', borderRadius: '12px' }}>
                        {getFileIcon(file.mime_type)}
                      </div>
                      <span style={{ fontWeight: 700, fontSize: '16px' }}>{file.original_name}</span>
                    </div>
                  </td>
                  <td><span style={{ color: 'var(--text-dim)', fontWeight: 600 }}>{new Date(file.updated_at).toLocaleDateString()}</span></td>
                  <td><span style={{ color: 'var(--text-dim)', fontWeight: 700 }}>{formatSize(file.size)}</span></td>
                  <td>
                    <div style={{ display: 'flex', gap: '6px' }}>
                       <button className="btn-icon btn-ghost" title="Extract"><Download size={20} /></button>
                       <button className="btn-icon btn-ghost" title="Beam" onClick={() => setShareModalData({ id: file.id, type: 'file' })}><Share2 size={20} /></button>
                       <button className="btn-icon btn-ghost"><MoreVertical size={20} /></button>
                    </div>
                  </td>
                </tr>
              ))}

              {folders.length === 0 && files.length === 0 && (
                <tr>
                  <td colSpan={4} style={{ padding: '100px 0', textAlign: 'center', background: 'transparent', boxShadow: 'none' }}>
                    <div style={{ 
                      display: 'inline-flex', 
                      flexDirection: 'column', 
                      alignItems: 'center', 
                      gap: '24px',
                      background: 'white',
                      padding: '48px',
                      borderRadius: '40px',
                      boxShadow: 'var(--shadow-soft)' 
                    }}>
                      <div style={{ background: 'var(--bg-app)', padding: '24px', borderRadius: '30px' }}>
                        <Sparkles size={48} color="var(--primary)" />
                      </div>
                      <div>
                        <p style={{ fontWeight: 800, fontSize: '20px', marginBottom: '8px' }}>Empty Singularity</p>
                        <p style={{ color: 'var(--text-dim)', fontWeight: 500 }}>Upload files to populate this space.</p>
                      </div>
                    </div>
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      )}

      {shareModalData && (
        <ShareModal 
          itemId={shareModalData.id} 
          itemType={shareModalData.type} 
          onClose={() => setShareModalData(null)} 
        />
      )}
    </div>
  );
};

export default FileExplorer;
