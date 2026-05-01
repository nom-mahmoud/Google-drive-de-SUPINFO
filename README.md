# SUPFile Platform

SUPFile is a comprehensive, self-hosted cloud storage solution providing scalable, secure file management. It is designed to rival industry standards like Dropbox and Google Drive, offering robust web and mobile interfaces powered by a robust containerized backend.

## Architecture & Technology Stack

The project operates within a fully containerized environment (Docker/Docker Compose) consisting of three principal components:

1. **Backend API (Laravel 11, PHP 8.4):** 
   - REST API handling authentication (Sanctum/Socialite), file routing, folder hierarchies, and metadata logic.
   - Powered by PostgreSQL for relational data persistence.
   - High-performance proxying through Nginx.
2. **Web Frontend (React 18 + Vite + TypeScript):** 
   - Responsive, modern SPA built with a custom Vanilla CSS Glassmorphism design system.
   - Robust state management with Zustand and Lucide React icons.
3. **Mobile Client (React Native + Expo):** 
   - Cross-platform application (iOS/Android/Web) ensuring ubiquitous accessibility.
   - Direct integration with local device file systems for uploading and native sharing capabilities.

## Installation & Setup

1. **Prerequisites:** Docker and Docker Compose must be installed on your host machine. Node.js is recommended for running local dev servers.
2. **Start the Infrastructure:**
   ```bash
   # Launch the infrastructure containers in the background (Postgres, PHP-FPM, Nginx, Node)
   docker compose up -d
   ```
3. **Initialize the Backend:**
   ```bash
   # Install backend dependencies
   docker compose exec api composer install
   
   # Apply database schemas
   docker compose exec api php artisan migrate
   ```
4. **Access the Web Interface:**
   The Web SPA is automatically started via the `web` container. Navigate your browser to `http://localhost:5173`.

5. **Start the Mobile Application:**
   ```bash
   cd mobile
   npm install
   npx expo start
   ```

## Features Implemented

*   ✅ **User Authentication:** Registration, login, session management via Laravel Sanctum.
*   ✅ **File & Folder Management:** Create directories, navigate breadcrumbs, and manipulate structures.
*   ✅ **Storage & Quota Tracking:** Interactive Dashboard reflecting disk space usage categorized by MIME types against a standard 30GB allocated quota.
*   ✅ **File Uploads/Downloads:** Streamlined multi-part upload processes on Web and Mobile, complete with recursive folder ZIP streaming algorithms.
*   ✅ **Search:** Multi-criteria string matching on file/folder names.
*   ✅ **URL Sharing:** Logic structures to create temporary/permanent restricted share links (token-based).
*   ✅ **Security:** Proper endpoint relationship validation, database foreign key constraints, robust error handling, and rigid stateless REST API principles.

## Developer Documentation

### Database Structure

The project utilizes a relational PostgreSQL model.
- `users`: Core authentication identity, quotas, and OAuth mappings.
- `folders`: Hierarchical self-referencing relationship mapping virtual directories.
- `files`: File metadata, logical sizes, mime_types pointing to physical disks.
- `share_links`: Ephemeral and persistent public access grants mapping to polymorphism (file/folder targets).

### Design Aesthetics
The web interface operates without TailwindCSS as requested. A fully custom `index.css` acts as the primary Design System providing:
- Glassmorphism effects using `backdrop-filter`.
- Root CSS variables bridging color themes.
- Micro-animations and hover transitions for optimal UX.

---
*Built as the final delivery for the SUPFile cloud storage project tender.*
