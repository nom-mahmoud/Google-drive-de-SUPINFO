import React, { useState, useEffect } from 'react';
import { View, Text, FlatList, TouchableOpacity, StyleSheet, ActivityIndicator } from 'react-native';
import { useAuthStore } from '../store/useAuthStore';
import api from '../lib/axios';
import { Folder, FileText, Download, LogOut, ChevronRight } from 'lucide-react-native';
import * as DocumentPicker from 'expo-document-picker';
import * as FileSystem from 'expo-file-system';
import * as Sharing from 'expo-sharing';

export default function FileExplorerScreen({ route, navigation }: any) {
  const { folderId, folderName } = route.params || {};
  const [folders, setFolders] = useState([]);
  const [files, setFiles] = useState([]);
  const [loading, setLoading] = useState(true);
  const logout = useAuthStore(state => state.logout);

  const fetchItems = async () => {
    setLoading(true);
    try {
      const parentIdQuery = folderId ? `?parent_id=${folderId}` : '';
      const response = await api.get(`/folders${parentIdQuery}`);
      setFolders(response.data.folders);
      setFiles(response.data.files);
    } catch (e) {
      console.error(e);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchItems();
  }, [folderId]);

  const handleUpload = async () => {
    try {
      const result = await DocumentPicker.getDocumentAsync({ copyToCacheDirectory: false });
      if (result.canceled) return;
      
      const file = result.assets[0];
      const formData = new FormData();
      formData.append('files[]', {
        uri: file.uri,
        name: file.name,
        type: file.mimeType || 'application/octet-stream'
      } as any);

      if (folderId) formData.append('folder_id', folderId);

      setLoading(true);
      await api.post('/files', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
      fetchItems();
    } catch (e) {
      console.error("Upload failed", e);
      setLoading(false);
    }
  };

  const handleDownload = async (file: any) => {
    try {
      const token = useAuthStore.getState().token;
      const downloadUrl = `${api.defaults.baseURL}/files/${file.id}/download`;
      const fileUri = FileSystem.documentDirectory + file.original_name;
      
      const { uri } = await FileSystem.downloadAsync(downloadUrl, fileUri, {
        headers: { Authorization: `Bearer ${token}` }
      });
      
      if (await Sharing.isAvailableAsync()) {
        await Sharing.shareAsync(uri);
      }
    } catch (e) {
      console.error('Download failed', e);
    }
  };

  const renderItem = ({ item }: { item: any }) => {
    const isFolder = item.name !== undefined;
    
    if (isFolder) {
      return (
        <TouchableOpacity 
          style={styles.item}
          onPress={() => navigation.push('FileExplorer', { folderId: item.id, folderName: item.name })}
        >
          <View style={styles.itemLeft}>
            <Folder size={24} color="#3b82f6" />
            <Text style={styles.itemText}>{item.name}</Text>
          </View>
          <ChevronRight size={20} color="#64748b" />
        </TouchableOpacity>
      );
    }

    return (
      <View style={styles.item}>
        <View style={styles.itemLeft}>
          <FileText size={24} color="#94a3b8" />
          <Text style={styles.itemText} numberOfLines={1}>{item.original_name}</Text>
        </View>
        <TouchableOpacity style={styles.actionBtn} onPress={() => handleDownload(item)}>
          <Download size={20} color="#3b82f6" />
        </TouchableOpacity>
      </View>
    );
  };

  return (
    <View style={styles.container}>
      <View style={styles.header}>
        <Text style={styles.title}>{folderName || 'My Files'}</Text>
        <TouchableOpacity onPress={logout}>
          <LogOut size={24} color="#ef4444" />
        </TouchableOpacity>
      </View>

      <TouchableOpacity style={styles.uploadBtn} onPress={handleUpload}>
        <Text style={styles.uploadBtnText}>+ Upload File</Text>
      </TouchableOpacity>

      {loading ? (
        <ActivityIndicator size="large" color="#3b82f6" style={{ marginTop: 50 }} />
      ) : (
        <FlatList
          data={[...folders, ...files] as any}
          keyExtractor={(item: any, index: number) => `${item.id}-${index}`}
          renderItem={renderItem}
          ListEmptyComponent={<Text style={styles.emptyText}>Empty</Text>}
        />
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#0f172a', padding: 20, paddingTop: 50 },
  header: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 20 },
  title: { fontSize: 24, fontWeight: 'bold', color: '#fff' },
  uploadBtn: { backgroundColor: '#3b82f6', padding: 12, borderRadius: 8, alignItems: 'center', marginBottom: 20 },
  uploadBtnText: { color: '#fff', fontWeight: 'bold' },
  item: { backgroundColor: '#1e293b', padding: 15, borderRadius: 8, flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between', marginBottom: 10 },
  itemLeft: { flexDirection: 'row', alignItems: 'center', flex: 1, marginRight: 10 },
  itemText: { color: '#fff', fontSize: 16, marginLeft: 15, flex: 1 },
  actionBtn: { padding: 5 },
  emptyText: { color: '#94a3b8', textAlign: 'center', marginTop: 40 }
});
