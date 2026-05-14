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

    if (uploadZone) {
        // Click to open file picker
        uploadZone.addEventListener('click', () => fileInput && fileInput.click());

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
            if (files.length > 0) simulateUpload(files[0].name);
        });

        // File input change
        if (fileInput) {
            fileInput.addEventListener('change', () => {
                if (fileInput.files.length > 0) simulateUpload(fileInput.files[0].name);
            });
        }
    }

    function simulateUpload(filename) {
        if (!progressContainer || !progressFill || !uploadText) return;
        progressContainer.style.display = 'block';
        if (uploadText) uploadText.textContent = `Envoi de "${filename}"...`;
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 15 + 5;
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
                if (uploadText) uploadText.textContent = `✓ "${filename}" envoyé avec succès !`;
            }
            progressFill.style.width = progress + '%';
        }, 200);
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
