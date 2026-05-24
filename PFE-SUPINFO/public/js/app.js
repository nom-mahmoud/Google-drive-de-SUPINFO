/**
 * SUPFile - app.js
 * Étudiant 2 - Semaine 2: File Manager Interactions
 */

document.addEventListener('DOMContentLoaded', () => {

    // =========================================================
    // 1. DRAG & DROP UPLOAD ZONE
    // =========================================================
    const uploadZone = document.getElementById('upload-zone');
    const fileInput = document.getElementById('file-input');
    const progressContainer = document.getElementById('upload-progress-container');
    const progressFill = document.getElementById('upload-progress-fill');
    const uploadText = document.getElementById('upload-text');

    function uploadFiles(files) {
        if (!progressContainer || !progressFill || !uploadText) return;
        
        progressContainer.style.display = 'block';
        uploadText.textContent = `Préparation de l'envoi...`;
        progressFill.style.width = '0%';
        progressFill.style.backgroundColor = '';

        const formData = new FormData();
        
        // Add files
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }
        
        // Add CSRF token
        const csrfToken = document.querySelector('input[name="_token"]')?.value;
        if (csrfToken) {
            formData.append('_token', csrfToken);
        }

        // Add folder_id if present
        const folderIdInput = document.querySelector('input[name="folder_id"]');
        if (folderIdInput) {
            formData.append('folder_id', folderIdInput.value);
        }

        const xhr = new XMLHttpRequest();
        const uploadForm = document.getElementById('upload-form') || document.getElementById('mobile-upload-form');
        const uploadUrl = uploadForm ? uploadForm.action : '/files';

        xhr.open('POST', uploadUrl, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        // Track progress
        xhr.upload.addEventListener('progress', (e) => {
            if (e.lengthComputable) {
                const percentComplete = Math.round((e.loaded / e.total) * 100);
                progressFill.style.width = percentComplete + '%';
                if (percentComplete === 100) {
                    uploadText.textContent = `Enregistrement et traitement par le serveur...`;
                } else {
                    uploadText.textContent = `Envoi en cours... ${percentComplete}%`;
                }
            }
        });

        // Response handling
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 400) {
                // Parse the response to check if it contains error elements
                const parser = new DOMParser();
                const doc = parser.parseFromString(xhr.responseText, 'text/html');
                const hasError = doc.querySelector('.error-alert-container');
                
                if (hasError) {
                    uploadText.textContent = `❌ Erreur lors de l'envoi.`;
                    progressFill.style.backgroundColor = 'var(--danger)';
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    uploadText.textContent = `✓ Fichiers envoyés avec succès !`;
                    progressFill.style.width = '100%';
                    setTimeout(() => {
                        window.location.reload();
                    }, 800);
                }
            } else {
                uploadText.textContent = `❌ Erreur lors de l'envoi. Espace insuffisant ou fichier trop volumineux.`;
                progressFill.style.backgroundColor = 'var(--danger)';
            }
        };

        xhr.onerror = function() {
            uploadText.textContent = `❌ Erreur de connexion au serveur.`;
            progressFill.style.backgroundColor = 'var(--danger)';
        };

        xhr.send(formData);
    }

    if (uploadZone) {
        // Click to open file picker
        uploadZone.addEventListener('click', (e) => {
            // Avoid click trigger recursion if clicking child buttons
            if (e.target.closest('input, button, a')) return;
            if (fileInput) fileInput.click();
        });

        // Drag events
        uploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadZone.classList.add('dragover');
        });
        uploadZone.addEventListener('dragleave', () => {
            uploadZone.classList.remove('dragover');
        });
        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadZone.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) uploadFiles(files);
        });

        // File input change
        if (fileInput) {
            fileInput.addEventListener('change', () => {
                if (fileInput.files.length > 0) uploadFiles(fileInput.files);
            });
        }
    }

    // =========================================================
    // 2. FILE CARD SELECTION (click pour sélectionner)
    // =========================================================
    const fileCards = document.querySelectorAll('.file-card');
    fileCards.forEach(card => {
        card.addEventListener('click', (e) => {
            // Don't trigger selection when clicking action buttons
            if (e.target.closest('.file-actions')) return;
            // Toggle selected state
            card.classList.toggle('selected');
        });
    });

    // =========================================================
    // 3. NEW FOLDER MODAL
    // =========================================================
    const newFolderBtn = document.getElementById('btn-new-folder');
    const folderModal = document.getElementById('folder-modal');
    const folderModalClose = document.getElementById('folder-modal-close');

    if (newFolderBtn && folderModal) {
        newFolderBtn.addEventListener('click', () => {
            folderModal.classList.add('active');
        });
        folderModalClose && folderModalClose.addEventListener('click', () => {
            folderModal.classList.remove('active');
        });
        // Close on backdrop click
        folderModal.addEventListener('click', (e) => {
            if (e.target === folderModal) folderModal.classList.remove('active');
        });
    }

    // =========================================================
    // 4. VIEW TOGGLE (Grid / List)
    // =========================================================
    const viewGrid = document.getElementById('view-grid');
    const viewList = document.getElementById('view-list');
    const fileGrid = document.getElementById('file-grid');

    if (viewGrid && viewList && fileGrid) {
        viewGrid.addEventListener('click', () => {
            fileGrid.classList.remove('list-view');
            viewGrid.classList.add('active-view');
            viewList.classList.remove('active-view');
        });
        viewList.addEventListener('click', () => {
            fileGrid.classList.add('list-view');
            viewList.classList.add('active-view');
            viewGrid.classList.remove('active-view');
        });
    }

    // =========================================================
    // 5. SEARCH FILTER (Live search on file names)
    // =========================================================
    const searchInput = document.getElementById('file-search');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase();
            document.querySelectorAll('.file-card, .mobile-list-item').forEach(card => {
                const name = card.querySelector('.file-name')?.textContent.toLowerCase() || '';
                card.style.display = name.includes(query) ? '' : 'none';
            });
        });
    }

    // =========================================================
    // 6. MOBILE FAB - trigger file input
    // =========================================================
    const fab = document.getElementById('fab-upload');
    const mobileFileInput = document.getElementById('mobile-file-input');
    if (fab && mobileFileInput) {
        fab.addEventListener('click', () => mobileFileInput.click());
        mobileFileInput.addEventListener('change', () => {
            if (mobileFileInput.files.length > 0) uploadFiles(mobileFileInput.files);
        });
    }

    // =========================================================
    // 7. THEME TOGGLE (Light / Dark)
    // =========================================================
    const themeToggle = document.getElementById('theme-toggle');
    const themeToggleMobile = document.getElementById('theme-toggle-mobile');

    function toggleTheme() {
        const isDark = document.documentElement.classList.toggle('dark-theme');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        updateThemeIcons();
    }

    function updateThemeIcons() {
        const isDark = document.documentElement.classList.contains('dark-theme');
        const sunIcons = document.querySelectorAll('#sun-icon, .sun-icon');
        const moonIcons = document.querySelectorAll('#moon-icon, .moon-icon');

        sunIcons.forEach(icon => icon.style.display = isDark ? 'block' : 'none');
        moonIcons.forEach(icon => icon.style.display = isDark ? 'none' : 'block');
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }
    if (themeToggleMobile) {
        themeToggleMobile.addEventListener('click', toggleTheme);
    }

    // Initialize icons state
    updateThemeIcons();
});
