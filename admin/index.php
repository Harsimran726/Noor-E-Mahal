<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . '/../includes/db.php';
$content = $db->query("SELECT * FROM site_content")->fetchAll();
$images = $db->query("SELECT * FROM image_assets")->fetchAll();
$facilities = $db->query("SELECT * FROM facilities")->fetchAll();
$services = $db->query("SELECT * FROM services")->fetchAll();
$faqs = $db->query("SELECT * FROM faqs")->fetchAll();
$gallery_categories = $db->query("SELECT * FROM gallery_categories")->fetchAll();

// Calculate file sizes for images
foreach ($images as &$img) {
    $filePath = __DIR__ . '/../' . $img['url'];
    if (file_exists($filePath)) {
        $bytes = filesize($filePath);
        if ($bytes >= 1048576) {
            $img['file_size'] = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $img['file_size'] = number_format($bytes / 1024, 2) . ' KB';
        } else {
            $img['file_size'] = $bytes . ' B';
        }
    } else {
        $img['file_size'] = 'File not found';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — Noor E Mahal</title>
    <!-- Bootstrap CSS for Admin -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #1a1a1a;
            --sidebar-text: #adb5bd;
            --sidebar-active: #c5a355;
            --maroon: #6B0F1A;
            --cream: #FDF8EF;
            --gold: #C5A355;
            --white: #ffffff;
        }

        body,
        html {
            height: 100%;
            margin: 0;
            overflow: hidden;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #f1f3f5;
        }

        /* Layout */
        .admin-wrapper {
            display: flex;
            height: 100vh;
            width: 100vw;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            z-index: 100;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-logo {
            height: 40px;
            filter: brightness(0) invert(1);
            margin-bottom: 10px;
        }

        .sidebar-menu {
            flex-grow: 1;
            padding: 20px 0;
            overflow-y: auto;
        }

        .menu-item {
            padding: 12px 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s;
            color: var(--sidebar-text);
            text-decoration: none;
            font-weight: 500;
            border-left: 3px solid transparent;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
        }

        .menu-item.active {
            color: var(--sidebar-active);
            background: rgba(197, 163, 85, 0.1);
            border-left-color: var(--sidebar-active);
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Main Content */
        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: row;
            overflow: hidden;
        }

        /* Editor Section */
        .editor-pane {
            width: 45%;
            min-width: 500px;
            background: white;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #dee2e6;
        }

        .editor-header {
            padding: 20px 25px;
            border-bottom: 1px solid #eee;
            background: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .editor-header h2 {
            margin: 0;
            font-size: 1.25rem;
            color: var(--maroon);
            font-weight: 700;
        }

        .editor-scroll {
            flex-grow: 1;
            padding: 25px;
            overflow-y: auto;
        }

        /* Preview Section */
        .preview-pane {
            flex-grow: 1;
            background: #e9ecef;
            display: flex;
            flex-direction: column;
        }

        .preview-toolbar {
            height: 45px;
            background: white;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            padding: 0 15px;
            justify-content: space-between;
            font-size: 0.8rem;
            color: #6c757d;
        }

        .preview-frame {
            flex-grow: 1;
            border: none;
            background: white;
        }

        /* Components */
        .content-card {
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background: #fafafa;
        }

        .label-key {
            font-family: monospace;
            font-size: 0.7rem;
            color: #999;
            display: block;
            margin-bottom: 5px;
        }

        .save-banner {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            display: none;
        }

        .img-thumb-container {
            position: relative;
            border-radius: 4px;
            overflow: hidden;
            background: #eee;
            border: 1px solid #eee;
        }

        .img-thumb {
            width: 100%;
            height: 120px;
            object-fit: cover;
            display: block;
        }

        .upload-btn-overlay {
            position: absolute;
            inset: 0;
            background: rgba(107, 15, 26, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s ease;
            cursor: pointer;
            color: white;
            font-size: 1.5rem;
            z-index: 2;
        }

        .img-thumb-container:hover .upload-btn-overlay {
            opacity: 1;
            background: rgba(107, 15, 26, 0.7);
        }

        .bg-gold {
            background-color: var(--gold-dark) !important;
        }

        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 30px;
            right: 30px;
            z-index: 9999;
            pointer-events: none;
        }

        .royal-toast {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-left: 4px solid var(--gold);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 15px 25px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            transform: translateX(120%);
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            pointer-events: all;
            min-width: 300px;
        }

        .royal-toast.show {
            transform: translateX(0);
        }

        .royal-toast.error {
            border-left-color: #d32f2f;
        }

        .royal-toast i {
            font-size: 1.5rem;
            color: var(--gold-dark);
        }

        .royal-toast.error i {
            color: #d32f2f;
        }

        .toast-content {
            flex-grow: 1;
        }

        .toast-title {
            font-family: 'Cinzel', serif;
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--maroon);
            margin-bottom: 2px;
        }

        .toast-msg {
            font-size: 0.8rem;
            color: #666;
        }

        /* Loading Spinner */
        .loading-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            opacity: 0;
            visibility: hidden;
            transition: 0.3s;
        }

        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .btn-gold {
            background-color: var(--gold);
            color: white;
            border: none;
        }
        .btn-gold:hover {
            background-color: #b08d3e;
            color: white;
        }
        .btn-outline-gold {
            border: 1px solid var(--gold);
            color: var(--gold);
            background: transparent;
        }
        .btn-outline-gold:hover {
            background: var(--gold);
            color: white;
        }
        .h-btn {
            padding: 8px 20px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
            border-radius: 4px;
        }

        /* Force modal confirm button to always display */
        #modal-confirm {
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
            pointer-events: auto !important;
            color: white !important;
        }

        #modal-confirm:hover {
            color: white !important;
        }
    </style>
</head>

<body>

    <div class="toast-container" id="toast-container"></div>

    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../static/Noor_e_mahal_ png (6).png" class="sidebar-logo" alt="">
                <div class="fw-bold text-white small">PALACE ADMIN</div>
            </div>

            <nav class="sidebar-menu">
                <div class="menu-item active" onclick="switchPage('home', '../index.php')">
                    <i class="fas fa-home"></i> Homepage
                </div>
                <div class="menu-item" onclick="switchPage('about', '../about.php')">
                    <i class="fas fa-history"></i> About Us
                </div>
                <div class="menu-item" onclick="switchPage('facilities', '../facilities.php')">
                    <i class="fas fa-concierge-bell"></i> Facilities
                </div>
                <div class="menu-item" onclick="switchPage('gallery', '../gallery.php')">
                    <i class="fas fa-images"></i> Gallery
                </div>
                <div class="menu-item" onclick="switchPage('contact', '../contact.php')">
                    <i class="fas fa-paper-plane"></i> Contact Details
                </div>
                <hr style="border-color: rgba(255,255,255,0.1);">
                <div class="menu-item" onclick="switchPage('common', '../index.php')">
                    <i class="fas fa-cog"></i> Global Settings
                </div>
            </nav>

            <div class="sidebar-footer">
                <a href="logout.php" class="menu-item text-danger border-0 p-0">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">

            <!-- Editor Pane -->
            <section class="editor-pane">
                <div class="editor-header">
                    <h2 id="page-title">Homepage Editor</h2>
                    <button class="btn btn-sm btn-dark" onclick="refreshPreview()"><i
                            class="fas fa-sync-alt"></i></button>
                </div>

                <div class="editor-scroll" id="editor-container">
                    <!-- Dynamic content will be injected here via JS -->
                    <div class="text-center py-5 text-muted">Loading editor...</div>
                </div>
            </section>

            <!-- Preview Pane -->
            <section class="preview-pane">
                <div class="preview-toolbar">
                    <div id="preview-url">Viewing: /</div>
                    <div><i class="fas fa-desktop"></i> Tablet & Mobile responsive view available on resize</div>
                </div>
                <iframe src="/" class="preview-frame" id="preview-iframe"></iframe>
            </section>

        </main>
    </div>

    <!-- Hidden Data Storage (for JS access) -->
    <script id="site-data" type="application/json">
{
    "content": <?= json_encode($content) ?>,
    "images": <?= json_encode($images) ?>,
    "facilities": <?= json_encode($facilities) ?>,
    "services": <?= json_encode($services) ?>,
    "faqs": <?= json_encode($faqs) ?>,
    "gallery_categories": <?= json_encode($gallery_categories) ?>
}
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const store = JSON.parse(document.getElementById('site-data').textContent);
        let currentPage = 'home';

        function switchPage(pageId, route) {
            currentPage = pageId;
            document.querySelectorAll('.menu-item').forEach(el => el.classList.remove('active'));
            // Match text with some flexibility for global/common
            const activeItem = Array.from(document.querySelectorAll('.menu-item')).find(el => {
                const text = el.innerText.toLowerCase();
                if (pageId === 'common' && text.includes('global')) return true;
                return text.includes(pageId);
            });
            if (activeItem) activeItem.classList.add('active');

            document.getElementById('page-title').innerText = pageId.charAt(0).toUpperCase() + pageId.slice(1) + ' Editor';
            document.getElementById('preview-url').innerText = 'Viewing: ' + route;
            document.getElementById('preview-iframe').src = route;

            renderEditor();
        }

        function renderEditor() {
            const container = document.getElementById('editor-container');
            container.innerHTML = '';

            // 1. Render Text Content for this page
            const pageContent = store.content.filter(c => c.key.startsWith(currentPage + '_') || (currentPage === 'common' && (c.key.startsWith('common_') || c.key.startsWith('contact_'))));

            if (pageContent.length > 0) {
                const textSection = document.createElement('div');
                textSection.innerHTML = '<h5 class="mb-3">Page Text & Titles</h5>';
                pageContent.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'content-card';
                    div.innerHTML = `
                        <span class="label-key">${item.key}</span>
                        <div class="mb-2">
                            <textarea class="form-control form-control-sm" id="input_${item.key}" rows="3">${item.value}</textarea>
                        </div>
                        <button class="btn btn-sm btn-outline-primary" onclick="updateText(this, '${item.key}')">Save Text</button>
                    `;
                    textSection.appendChild(div);
                });
                container.appendChild(textSection);
            }

            // 1.5. Render Hero Imagery (Top of setiap page)
            const heroImages = store.images.filter(img => img.category === currentPage + '_hero_1' || img.category === currentPage + '_hero_2');
            if (heroImages.length > 0) {
                const heroSection = document.createElement('div');
                heroSection.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                        <h5 class="m-0 text-gold"><i class="fas fa-crown"></i> Page Hero Imagery</h5>
                    </div>
                `;
                const heroGrid = document.createElement('div');
                heroGrid.className = 'row g-3 mb-4';
                heroImages.forEach(img => {
                    let thumbUrl = img.url;
                    if (!thumbUrl.startsWith('http') && !thumbUrl.startsWith('/')) thumbUrl = '../' + thumbUrl;
                    heroGrid.innerHTML += `
                        <div class="col-md-6">
                            <div class="content-card border-gold">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="label-key">${img.category.replace('_', ' ').toUpperCase()}</span>
                                </div>
                                <div class="img-thumb-container mb-2 position-relative">
                                    <img src="${thumbUrl}" class="img-thumb" style="height: 100px; object-fit: cover; width: 100%;">
                                    <label for="file_input_${img.id}" class="upload-btn-overlay"><i class="fas fa-upload"></i></label>
                                    <input type="file" id="file_input_${img.id}" class="d-none" accept="image/*" onchange="uploadImage(this, ${img.id})">
                                </div>
                                <div id="progress_${img.id}" class="progress d-none mb-2" style="height: 4px;"><div class="progress-bar bg-gold" style="width:0%"></div></div>
                                <input type="text" class="form-control form-control-sm" id="img_url_${img.id}" value="${img.url}" onchange="updateImg(null, ${img.id})">
                            </div>
                        </div>
                    `;
                });
                container.appendChild(heroSection);
                container.appendChild(heroGrid);
            }

            // 2.5. Special Case: Palace Facilities Card Images (Home Page Only)
            if (currentPage === 'home') {
                const palaceFacSection = document.createElement('div');
                palaceFacSection.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                        <h5 class="m-0"><i class="fas fa-image text-gold"></i> Palace Facilities Card Images</h5>
                        <small class="text-muted">(${store.facilities.length} cards)</small>
                    </div>
                `;
                
                const facImgGrid = document.createElement('div');
                facImgGrid.style.display = 'grid';
                facImgGrid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(200px, 1fr))';
                facImgGrid.style.gap = '15px';
                facImgGrid.style.marginBottom = '30px';
                
                store.facilities.forEach((f, index) => {
                    const facNum = index + 1;
                    const imgCategory = 'home_facility_' + facNum;
                    const existingImg = store.images.find(img => img.category === imgCategory);
                    let imgUrl = existingImg?.url || 'static/Noor_e_mahal_ png (6).png';
                    if (!imgUrl.startsWith('http') && !imgUrl.startsWith('/')) imgUrl = '../' + imgUrl;
                    
                    const facImgCard = document.createElement('div');
                    facImgCard.className = 'content-card';
                    facImgCard.innerHTML = `
                        <div class="mb-2">
                            <small class="text-muted"><strong>${f.name}</strong></small>
                        </div>
                        <div class="img-thumb-container mb-2 position-relative" style="height: 120px;">
                            <img src="${imgUrl}" class="img-thumb" style="height: 100%; object-fit: cover; width: 100%; border-radius: 4px;">
                            <label for="fac_card_file_${facNum}" class="upload-btn-overlay"><i class="fas fa-upload"></i></label>
                            <input type="file" id="fac_card_file_${facNum}" class="d-none" accept="image/*" onchange="uploadFacilityCardImage(this, '${imgCategory}', ${existingImg?.id || 'null'})">
                        </div>
                        <div id="fac_card_progress_${facNum}" class="progress d-none" style="height: 3px; margin-bottom: 8px;"><div class="progress-bar bg-gold" style="width:0%"></div></div>
                        <input type="text" class="form-control form-control-sm" id="fac_card_url_${facNum}" value="${existingImg?.url || ''}" placeholder="Image URL">
                    `;
                    facImgGrid.appendChild(facImgCard);
                });
                
                palaceFacSection.appendChild(facImgGrid);
                container.appendChild(palaceFacSection);
            }

            // 2. Render Image Assets (Grouped and organized)
            const pageImages = store.images.filter(img => {
                const cat = (img.category || '').toLowerCase();
                // Exclude heroes from main imagery list
                if (cat.includes('_hero_')) return false; 
                
                if (currentPage === 'common') return true;
                if (currentPage === 'home') return cat.startsWith('home');
                if (currentPage === 'about') return cat.startsWith('about');
                if (currentPage === 'facilities' || currentPage === 'services') return cat.startsWith('facilities') || cat.startsWith('services');
                if (currentPage === 'contact') return cat.startsWith('contact');
                if (currentPage === 'gallery') {
                    const others = ['home', 'about', 'facilities', 'services', 'contact'];
                    return cat.startsWith('gallery') || !others.some(o => cat.startsWith(o));
                }
                return false;
            });

            if (pageImages.length > 0 || currentPage === 'gallery') {
                const imgSection = document.createElement('div');
                imgSection.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                        <h5 class="m-0">Palace Imagery (${pageImages.length} Slots)</h5>
                        <div class="d-flex gap-2">
                           ${currentPage === 'gallery' ? '<button class="btn btn-sm btn-outline-gold" onclick="manageSections()"><i class="fas fa-list"></i> Manage Sections</button>' : ''}
                           <button class="btn btn-sm btn-success" onclick="addImagePrompt()"><i class="fas fa-plus"></i> Add Image</button>
                        </div>
                    </div>
                `;
                const grid = document.createElement('div');
                grid.className = 'row g-3';
                pageImages.forEach(img => {
                    // Clean up display name: home_hero_1 -> Hero 1
                    let displayName = img.category.replace(currentPage + '_', '').replace(/_/g, ' ');
                    displayName = displayName.charAt(0).toUpperCase() + displayName.slice(1);

                    if (currentPage === 'gallery') {
                        const cat = store.gallery_categories.find(c => c.slug === img.category);
                        if (cat) displayName = cat.name;
                    }

                    let thumbUrl = img.url;
                    if (!thumbUrl.startsWith('http') && !thumbUrl.startsWith('/')) {
                        thumbUrl = '../' + thumbUrl;
                    }
                    grid.innerHTML += `
                        <div class="col-md-6 col-lg-4">
                            <div class="content-card h-100 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="label-key text-truncate m-0" title="${img.category}">${displayName}</span>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge ${parseFloat(img.file_size) > 1 && img.file_size.includes('MB') ? 'bg-danger' : 'bg-light text-dark'} border" style="font-size: 0.65rem;">
                                            ${img.file_size || 'N/A'}
                                        </span>
                                        <button class="btn btn-link text-danger p-0" onclick="deleteImage(${img.id})" title="Delete Image">
                                            <i class="fas fa-trash-alt" style="font-size: 0.8rem;"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="img-thumb-container mb-2 position-relative">
                                    <img src="${thumbUrl}" class="img-thumb" style="height: 120px; object-fit: cover; width: 100%;" onerror="this.src='../static/Noor_e_mahal_%20png%20(1).png'">
                                    <label for="file_input_${img.id}" class="upload-btn-overlay">
                                        <i class="fas fa-upload"></i>
                                    </label>
                                    <input type="file" id="file_input_${img.id}" class="d-none" accept="image/*" onchange="uploadImage(this, ${img.id})">
                                </div>
                                <div class="mt-auto">
                                    <div class="input-group input-group-sm mb-2">
                                        <input type="text" class="form-control" id="img_url_${img.id}" value="${img.url}" placeholder="Image URL">
                                        <button class="btn btn-outline-primary" onclick="updateImg(this, ${img.id})">Save URL</button>
                                    </div>
                                    <div id="progress_${img.id}" class="progress d-none mb-2" style="height: 4px;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-gold" role="progressbar" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                imgSection.appendChild(grid);
                container.appendChild(imgSection);
            }


            if (currentPage === 'home') {
                const homeAboutImage = store.images.find(img => img.category === 'home_about_image');
                const homeAboutSection = document.createElement('div');
                homeAboutSection.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                        <h5 class="m-0"><i class="fas fa-image text-gold"></i> Home About Us Image</h5>
                        <small class="text-muted">Manage the image shown in the homepage About Us block</small>
                    </div>
                `;

                const homeAboutCard = document.createElement('div');
                homeAboutCard.className = 'content-card';
                const homeAboutUrl = homeAboutImage?.url || 'static/Noor_e_mahal_ png (1).png';
                const homeAboutThumb = homeAboutUrl.startsWith('http') || homeAboutUrl.startsWith('/') ? homeAboutUrl : '../' + homeAboutUrl;
                homeAboutCard.innerHTML = `
                    <div class="img-thumb-container mb-2 position-relative" style="height: 220px;">
                        <img src="${homeAboutThumb}" class="img-thumb" style="height: 100%; object-fit: cover; width: 100%; border-radius: 4px;">
                        <label for="home_about_file" class="upload-btn-overlay"><i class="fas fa-upload"></i></label>
                        <input type="file" id="home_about_file" class="d-none" accept="image/*" onchange="uploadNamedImage(this, 'home_about_image', ${homeAboutImage?.id || 'null'})">
                    </div>
                    <div class="d-flex justify-content-between align-items-center gap-2">
                        <input type="text" class="form-control form-control-sm" id="home_about_url" value="${homeAboutImage?.url || ''}" placeholder="Image URL">
                        <button class="btn btn-sm btn-outline-primary" onclick="updateNamedImageUrl('home_about_image', ${homeAboutImage?.id || 'null'}, 'home_about_url')">Save URL</button>
                    </div>
                    <div class="mt-2 d-flex justify-content-between align-items-center">
                        <small class="text-muted">Category: home_about_image</small>
                        ${homeAboutImage ? `<button class="btn btn-sm btn-outline-danger" onclick="deleteImage(${homeAboutImage.id})"><i class="fas fa-trash"></i></button>` : ''}
                    </div>
                `;
                homeAboutSection.appendChild(homeAboutCard);
                container.appendChild(homeAboutSection);
            }

            if (currentPage === 'about') {
                const aboutImageDefs = [
                    { key: 'about_hero_1', label: 'About Hero Image 1' },
                    { key: 'about_hero_2', label: 'About Hero Image 2' },
                    { key: 'about_section_1', label: 'About Story Image' },
                    { key: 'about_section_3', label: 'About Legacy Image' },
                ];
                const aboutImages = aboutImageDefs.map(def => ({ ...def, item: store.images.find(img => img.category === def.key) }));
                const aboutSection = document.createElement('div');
                aboutSection.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                        <h5 class="m-0"><i class="fas fa-photo-film text-gold"></i> About Page Images</h5>
                        <small class="text-muted">Upload or replace the detailed About page images</small>
                    </div>
                `;

                const aboutGrid = document.createElement('div');
                aboutGrid.style.display = 'grid';
                aboutGrid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(280px, 1fr))';
                aboutGrid.style.gap = '20px';
                aboutGrid.style.marginBottom = '30px';

                aboutImages.forEach(({ key, label, item }) => {
                    const card = document.createElement('div');
                    card.className = 'content-card';
                    const thumbUrl = (item?.url || 'static/Noor_e_mahal_ png (1).png');
                    const resolvedUrl = thumbUrl.startsWith('http') || thumbUrl.startsWith('/') ? thumbUrl : '../' + thumbUrl;
                    card.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">${label}</small>
                            ${item ? `<button class="btn btn-sm btn-outline-danger" onclick="deleteImage(${item.id})"><i class="fas fa-trash"></i></button>` : ''}
                        </div>
                        <div class="img-thumb-container mb-2 position-relative" style="height: 180px;">
                            <img src="${resolvedUrl}" class="img-thumb" style="height: 100%; object-fit: cover; width: 100%; border-radius: 4px;">
                            <label for="about_file_${key}" class="upload-btn-overlay"><i class="fas fa-upload"></i></label>
                            <input type="file" id="about_file_${key}" class="d-none" accept="image/*" onchange="uploadNamedImage(this, '${key}', ${item?.id || 'null'})">
                        </div>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="about_url_${key}" value="${item?.url || ''}" placeholder="Image URL">
                            <button class="btn btn-outline-primary" onclick="updateNamedImageUrl('${key}', ${item?.id || 'null'}, 'about_url_${key}')">Save URL</button>
                        </div>
                        <small class="text-muted d-block mt-2">Category: ${key}</small>
                    `;
                    aboutGrid.appendChild(card);
                });

                aboutSection.appendChild(aboutGrid);
                container.appendChild(aboutSection);
            }
            // 3. Special Case: Facilities
            if (currentPage === 'facilities' || currentPage === 'home') {
                const facSection = document.createElement('div');
                facSection.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                if (cat.includes('_hero_')) return false;
                if (currentPage === 'home' && cat === 'home_about_image') return false;
                if (currentPage === 'about' && ['about_hero_1', 'about_hero_2', 'about_section_1', 'about_section_3'].includes(cat)) return false;
                        <button class="btn btn-sm btn-success" onclick="addFacility()"><i class="fas fa-plus"></i> Add Facility</button>
                    </div>
                `;
                
                // Create a grid layout for better visibility
                const facGrid = document.createElement('div');
                facGrid.style.display = 'grid';
                facGrid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(300px, 1fr))';
                facGrid.style.gap = '20px';
                facGrid.style.marginBottom = '30px';
                
                store.facilities.forEach((f, index) => {
                    let thumbUrl = f.image_url || 'static/Noor_e_mahal_%20png%20(1).png';
                    if (!thumbUrl.startsWith('http') && !thumbUrl.startsWith('/')) thumbUrl = '../' + thumbUrl;
                    
                    const facCard = document.createElement('div');
                    facCard.className = 'content-card';
                    facCard.style.display = 'flex';
                    facCard.style.flexDirection = 'column';
                    facCard.innerHTML = `
                        <div class="mb-2">
                            <small class="text-muted">Facility ${index + 1} of ${store.facilities.length}</small>
                        </div>
                        <div class="img-thumb-container mb-2 position-relative" style="height: 150px; flex-shrink: 0;">
                            <img src="${thumbUrl}" class="img-thumb" style="height: 100%; object-fit: cover; width: 100%; border-radius: 4px;">
                            <label for="fac_file_${f.id}" class="upload-btn-overlay"><i class="fas fa-camera"></i></label>
                            <input type="file" id="fac_file_${f.id}" class="d-none" accept="image/*" onchange="uploadFacilityImage(this, ${f.id})">
                        </div>
                        <div id="fac_progress_${f.id}" class="progress d-none mb-2" style="height: 3px;"><div class="progress-bar bg-gold" style="width:0%"></div></div>
                        
                        <label class="form-label text-muted small mb-1">Facility Name</label>
                        <input type="text" class="form-control form-control-sm fw-bold mb-2" id="fac_name_${f.id}" value="${f.name}">
                        
                        <label class="form-label text-muted small mb-1">Description</label>
                        <textarea class="form-control form-control-sm mb-2" id="fac_desc_${f.id}" rows="2" placeholder="Facility Description" style="resize: vertical; min-height: 60px;">${f.desc}</textarea>
                        
                        <label class="form-label text-muted small mb-1">Icon Class</label>
                        <div class="input-group input-group-sm mb-2">
                            <span class="input-group-text bg-light"><i class="fas fa-icons"></i></span>
                            <input type="text" class="form-control" id="fac_icon_class_${f.id}" value="${f.icon_class}">
                        </div>
                        
                        <label class="form-label text-muted small mb-1">Image URL</label>
                        <input type="text" class="form-control form-control-sm mb-3" id="fac_image_${f.id}" value="${f.image_url || ''}" placeholder="https://example.com/image.jpg">
                        
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-primary flex-grow-1" onclick="updateFacility(this, ${f.id})">Save</button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteFacility(${f.id})"><i class="fas fa-trash"></i></button>
                        </div>
                    `;
                    facGrid.appendChild(facCard);
                });
                
                facSection.appendChild(facGrid);
                container.appendChild(facSection);
            }

            // 4. Special Case: FAQs
            if (currentPage === 'home' || currentPage === 'contact') {
                const faqSection = document.createElement('div');
                faqSection.innerHTML = '<h5 class="mt-4 mb-3">Frequently Asked Questions</h5>';
                store.faqs.forEach(q => {
                    faqSection.innerHTML += `
                        <div class="content-card">
                            <input type="text" class="form-control form-control-sm fw-bold mb-2" value="${q.question}">
                            <textarea class="form-control form-control-sm mb-2" rows="2">${q.answer}</textarea>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary flex-grow-1" onclick="showRoyalToast('Feature Notice', 'FAQ sorting & updates coming soon.')">Update FAQ</button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    `;
                });
                container.appendChild(faqSection);
            }

            if (currentPage === 'home') {
                const testimonialEntries = [];
                for (let i = 1; i <= 12; i++) {
                    const quoteKey = `home_testimonial_${i}_quote`;
                    const authorKey = `home_testimonial_${i}_author`;
                    const quoteItem = store.content.find(c => c.key === quoteKey);
                    const authorItem = store.content.find(c => c.key === authorKey);
                    if (quoteItem && authorItem) {
                        testimonialEntries.push({ index: i, quoteItem, authorItem });
                    }
                }

                const testimonialSection = document.createElement('div');
                testimonialSection.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                        <h5 class="m-0">Manage Home Testimonials (${testimonialEntries.length})</h5>
                        <button class="btn btn-sm btn-success" onclick="addTestimonial()"><i class="fas fa-plus"></i> Add Testimonial</button>
                    </div>
                `;

                if (testimonialEntries.length === 0) {
                    testimonialSection.innerHTML += '<div class="text-muted small">No custom testimonials yet. Add one to replace the homepage fallback content.</div>';
                }

                const testimonialGrid = document.createElement('div');
                testimonialGrid.style.display = 'grid';
                testimonialGrid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(320px, 1fr))';
                testimonialGrid.style.gap = '20px';
                testimonialGrid.style.marginBottom = '30px';

                testimonialEntries.forEach(({ index, quoteItem, authorItem }) => {
                    const card = document.createElement('div');
                    card.className = 'content-card';
                    card.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">Testimonial ${index}</small>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteTestimonial(${index})"><i class="fas fa-trash"></i></button>
                        </div>
                        <label class="form-label text-muted small mb-1">Quote</label>
                        <textarea class="form-control form-control-sm mb-2" id="testimonial_quote_${index}" rows="4">${quoteItem.value}</textarea>
                        <label class="form-label text-muted small mb-1">Author</label>
                        <input type="text" class="form-control form-control-sm mb-3" id="testimonial_author_${index}" value="${authorItem.value}">
                        <button class="btn btn-sm btn-outline-primary" onclick="updateTestimonial(${index})">Save Testimonial</button>
                    `;
                    testimonialGrid.appendChild(card);
                });

                testimonialSection.appendChild(testimonialGrid);
                container.appendChild(testimonialSection);
            }

            if (currentPage === 'common') {
                const footerSection = document.createElement('div');
                footerSection.innerHTML = '<h5 class="mt-4 mb-3">Footer Editor</h5>';

                const footerCard = document.createElement('div');
                footerCard.className = 'content-card';
                const footerLogo = store.images.find(img => img.category === 'footer_logo');
                const footerLogoUrl = footerLogo?.url || 'static/Noor_e_mahal_ png (6).png';
                const footerLogoThumb = footerLogoUrl.startsWith('http') || footerLogoUrl.startsWith('/') ? footerLogoUrl : '../' + footerLogoUrl;
                footerCard.innerHTML = `
                    <div class="mb-3">
                        <span class="label-key">footer_logo</span>
                        <div class="img-thumb-container mb-2 position-relative" style="height: 220px;">
                            <img src="${footerLogoThumb}" class="img-thumb" style="height: 100%; object-fit: contain; width: 100%; background: #111;">
                            <label for="footer_logo_file" class="upload-btn-overlay"><i class="fas fa-upload"></i></label>
                            <input type="file" id="footer_logo_file" class="d-none" accept="image/*" onchange="uploadNamedImage(this, 'footer_logo', ${footerLogo?.id || 'null'})">
                        </div>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="footer_logo_url" value="${footerLogo?.url || ''}" placeholder="Image URL">
                            <button class="btn btn-outline-primary" onclick="updateNamedImageUrl('footer_logo', ${footerLogo?.id || 'null'}, 'footer_logo_url')">Save URL</button>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Footer Quote</label>
                            <textarea class="form-control form-control-sm" id="input_common_footer_quote" rows="3">${store.content.find(c => c.key === 'common_footer_quote')?.value || 'Where every celebration becomes a royal legacy.'}</textarea>
                            <button class="btn btn-sm btn-outline-primary mt-2" onclick="updateText(this, 'common_footer_quote')">Save Text</button>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Footer Address</label>
                            <textarea class="form-control form-control-sm" id="input_common_footer_address" rows="3">${store.content.find(c => c.key === 'common_footer_address')?.value || 'Bathinda Road, Near Village Bhaini Bagha (Mansa)'}</textarea>
                            <button class="btn btn-sm btn-outline-primary mt-2" onclick="updateText(this, 'common_footer_address')">Save Text</button>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Website URL</label>
                            <input type="text" class="form-control form-control-sm" id="input_common_footer_website" value="${store.content.find(c => c.key === 'common_footer_website')?.value || 'https://www.nooremahal.com'}">
                            <button class="btn btn-sm btn-outline-primary mt-2" onclick="updateText(this, 'common_footer_website')">Save Text</button>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Footer Contact Page Link Text</label>
                            <input type="text" class="form-control form-control-sm" id="input_common_footer_cta" value="${store.content.find(c => c.key === 'common_footer_cta')?.value || 'Book A Tour'}">
                            <button class="btn btn-sm btn-outline-primary mt-2" onclick="updateText(this, 'common_footer_cta')">Save Text</button>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Instagram Link</label>
                            <input type="text" class="form-control form-control-sm" id="input_common_footer_instagram" value="${store.content.find(c => c.key === 'common_footer_instagram')?.value || 'https://www.instagram.com/nooremahal_mansa/'}">
                            <button class="btn btn-sm btn-outline-primary mt-2" onclick="updateText(this, 'common_footer_instagram')">Save Text</button>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Facebook Link</label>
                            <input type="text" class="form-control form-control-sm" id="input_common_footer_facebook" value="${store.content.find(c => c.key === 'common_footer_facebook')?.value || 'https://www.facebook.com/people/Noor-E-Mahal/61586134415662/'}">
                            <button class="btn btn-sm btn-outline-primary mt-2" onclick="updateText(this, 'common_footer_facebook')">Save Text</button>
                        </div>
                    </div>
                `;
                footerSection.appendChild(footerCard);
                container.appendChild(footerSection);
            }
        }

        async function updateText(btn, key) {
            const input = document.getElementById('input_' + key);
            const val = input.value;

            setLoading(btn, true);
            try {
                const res = await fetch('api.php?action=content', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ key, value: val })
                });

                if (res.ok) {
                    const item = store.content.find(c => c.key === key);
                    if (item) item.value = val;
                    else store.content.push({ key, value: val });
                    refreshPreview();
                    showRoyalToast('Success', 'Palace text has been updated safely.');
                } else {
                    showRoyalToast('Update Failed', 'Could not save changes to the vault.', true);
                }
            } catch (e) {
                showRoyalToast('Network Error', 'The connection was lost.', true);
            } finally {
                setLoading(btn, false);
            }
        }

        async function updateTestimonial(index) {
            const quote = document.getElementById(`testimonial_quote_${index}`).value;
            const author = document.getElementById(`testimonial_author_${index}`).value;

            try {
                const quoteRes = await fetch('api.php?action=content', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ key: `home_testimonial_${index}_quote`, value: quote })
                });
                const authorRes = await fetch('api.php?action=content', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ key: `home_testimonial_${index}_author`, value: author })
                });

                if (quoteRes.ok && authorRes.ok) {
                    const upsert = (key, value) => {
                        const item = store.content.find(c => c.key === key);
                        if (item) item.value = value;
                        else store.content.push({ key, value });
                    };
                    upsert(`home_testimonial_${index}_quote`, quote);
                    upsert(`home_testimonial_${index}_author`, author);
                    refreshPreview();
                    showRoyalToast('Success', 'Testimonial updated.');
                } else {
                    showRoyalToast('Error', 'Could not save testimonial.', true);
                }
            } catch (e) {
                showRoyalToast('Network Error', 'Connection lost.', true);
            }
        }

        async function addTestimonial() {
            let nextIndex = 1;
            for (let i = 1; i <= 12; i++) {
                const exists = store.content.some(c => c.key === `home_testimonial_${i}_quote` || c.key === `home_testimonial_${i}_author`);
                if (!exists) {
                    nextIndex = i;
                    break;
                }
                nextIndex = i + 1;
            }

            if (nextIndex > 12) {
                return showRoyalToast('Limit Reached', 'You can manage up to 12 testimonials.', true);
            }

            try {
                const quoteRes = await fetch('api.php?action=content', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ key: `home_testimonial_${nextIndex}_quote`, value: 'Write the testimonial quote here.' })
                });
                const authorRes = await fetch('api.php?action=content', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ key: `home_testimonial_${nextIndex}_author`, value: 'Guest Name' })
                });

                if (quoteRes.ok && authorRes.ok) {
                    store.content.push(
                        { key: `home_testimonial_${nextIndex}_quote`, value: 'Write the testimonial quote here.' },
                        { key: `home_testimonial_${nextIndex}_author`, value: 'Guest Name' }
                    );
                    renderEditor();
                    refreshPreview();
                    showRoyalToast('Success', 'Testimonial added.');
                } else {
                    showRoyalToast('Error', 'Could not add testimonial.', true);
                }
            } catch (e) {
                showRoyalToast('Network Error', 'Connection lost.', true);
            }
        }

        async function deleteTestimonial(index) {
            if (!confirm('Delete this testimonial from the homepage?')) return;

            try {
                const keys = [`home_testimonial_${index}_quote`, `home_testimonial_${index}_author`];
                for (const key of keys) {
                    await fetch('api.php?action=content_delete', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ key })
                    });
                }

                store.content = store.content.filter(c => !keys.includes(c.key));
                renderEditor();
                refreshPreview();
                showRoyalToast('Success', 'Testimonial removed.');
            } catch (e) {
                showRoyalToast('Network Error', 'Connection lost.', true);
            }
        }

        async function updateImg(btn, id) {
            const url = document.getElementById('img_url_' + id).value;
            setLoading(btn, true);
            try {
                const res = await fetch('api.php?action=image_update', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, url, alt_text: '' })
                });

                if (res.ok) {
                    const img = store.images.find(i => i.id === id);
                    if (img) img.url = url;
                    renderEditor(); // Refresh to show new image in thumb
                    refreshPreview();
                    showRoyalToast('Success', 'The royal imagery has been refreshed.');
                } else {
                    showRoyalToast('Error', 'The image could not be updated.', true);
                }
            } catch (e) {
                showRoyalToast('Network Error', 'Connection lost.', true);
            } finally {
                setLoading(btn, false);
            }
        }

        async function updateNamedImageUrl(category, imageId, inputId) {
            const url = document.getElementById(inputId).value.trim();
            if (!url) return showRoyalToast('Error', 'Image URL is required.', true);

            try {
                if (imageId) {
                    const res = await fetch('api.php?action=image_update', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: imageId, url, alt_text: '' })
                    });
                    if (!res.ok) throw new Error('Failed to update image');
                } else {
                    const res = await fetch('api.php?action=image_add', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ url, category, alt_text: '' })
                    });
                    if (!res.ok) throw new Error('Failed to create image slot');
                }

                renderEditor();
                refreshPreview();
                showRoyalToast('Success', 'Image URL saved.');
            } catch (e) {
                showRoyalToast('Error', e.message, true);
            }
        }

        async function uploadNamedImage(input, category, imageId) {
            if (!input.files || !input.files[0]) return;

            const formData = new FormData();
            formData.append('file', input.files[0]);
            formData.append('category', category);
            formData.append('image_id', imageId || '');

            try {
                const res = await fetch('api.php?action=image_create_or_update', {
                    method: 'POST',
                    body: formData
                });

                if (res.ok) {
                    const data = await res.json();
                    if (imageId) {
                        const img = store.images.find(i => i.id === imageId);
                        if (img) img.url = data.url;
                    } else {
                        store.images.push({ id: data.id, url: data.url, category, file_size: '' });
                    }
                    renderEditor();
                    refreshPreview();
                    showRoyalToast('Success', 'Image uploaded.');
                } else {
                    showRoyalToast('Error', 'Upload failed.', true);
                }
            } catch (e) {
                showRoyalToast('Network Error', 'Connection lost.', true);
            }
        }

        async function addImagePrompt() {
            let options = store.gallery_categories.map(c => `<option value="${c.slug}">${c.name}</option>`).join('');
            if (options === '') options = '<option value="gallery_outside">Exterior (Default)</option>';
            
            const html = `
                <div class="mb-3 text-start">
                    <label class="form-label text-white-50 small">Gallery Section</label>
                    <select id="new_img_cat" class="form-select bg-dark text-white border-secondary">
                        ${options}
                    </select>
                </div>
                <div class="mb-3 text-start">
                    <label class="form-label text-white-50 small">Select Image File</label>
                    <input type="file" id="new_img_file" class="form-control bg-dark text-white border-secondary" accept="image/*">
                </div>
                <div class="mb-1 text-center text-white-50 small">-- OR --</div>
                <div class="mb-3 text-start">
                    <label class="form-label text-white-50 small">Paste Image URL</label>
                    <input type="text" id="new_img_url" class="form-control bg-dark text-white border-secondary" placeholder="https://example.com/image.jpg">
                </div>
            `;
            
            const confirmed = await showRoyalPrompt('Add Gallery Image', html, false, 'Add & Upload Image');
            if (!confirmed) return;
            
            const category = document.getElementById('new_img_cat').value;
            const fileInput = document.getElementById('new_img_file');
            const urlInput = document.getElementById('new_img_url');
            
            if (fileInput.files && fileInput.files[0]) {
                // Direct Upload
                const formData = new FormData();
                formData.append('file', fileInput.files[0]);
                formData.append('category', category);
                formData.append('alt_text', '');
                
                try {
                    const res = await fetch('api.php?action=image_create_and_upload', {
                        method: 'POST',
                        body: formData
                    });
                    if (res.ok) {
                        const data = await res.json();
                        store.images.push({ id: data.id, url: data.url, category: category, file_size: 'Uploading...' });
                        renderEditor();
                        showRoyalToast('Success', 'Gallery image uploaded and added.');
                    }
                } catch(e) { showRoyalToast('Error', 'Upload failed.', true); }
            } else if (urlInput.value) {
                // URL based add
                await performAddImage(urlInput.value, category);
            }
        }

        async function performAddImage(url, cat) {
            try {
                const res = await fetch('api.php?action=image_add', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ url, category: cat, alt_text: '' })
                });

                if (res.ok) {
                    const data = await res.json();
                    store.images.push({ id: data.id, url, category: cat, file_size: '0 B' });
                    renderEditor();
                    refreshPreview();
                    showRoyalToast('Success', 'New image slot created.');
                } else {
                    showRoyalToast('Error', 'Failed to add image slot.', true);
                }
            } catch (e) {
                showRoyalToast('Network Error', 'Connection lost.', true);
            }
        }

        async function manageSections() {
            let listHtml = store.gallery_categories.map(c => `
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 border border-secondary rounded">
                    <div>
                        <strong>${c.name}</strong><br>
                        <small class="text-white-50">${c.slug}</small>
                    </div>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteCategory(${c.id})"><i class="fas fa-trash"></i></button>
                </div>
            `).join('');

            const html = `
                <div class="mb-4">
                    <h6>Existing Sections</h6>
                    ${listHtml || '<p class="text-muted small">No sections yet.</p>'}
                </div>
                <hr class="border-secondary">
                <h6>Add New Section</h6>
                <div class="mb-2">
                    <input type="text" id="new_cat_name" class="form-control form-control-sm bg-dark text-white border-secondary" placeholder="Display Name (e.g. Roof Garden)">
                </div>
                <div class="mb-3">
                    <input type="text" id="new_cat_slug" class="form-control form-control-sm bg-dark text-white border-secondary" placeholder="Slug (e.g. gallery_roof)">
                </div>
                <button class="btn btn-sm btn-success w-100" onclick="addCategory()">Create Section</button>
            `;

            showRoyalPrompt('Gallery Sections', html, true);
        }

        window.addCategory = async function() {
            const name = document.getElementById('new_cat_name').value;
            let slug = document.getElementById('new_cat_slug').value;
            if (!name || !slug) return showRoyalToast('Error', 'Name and Slug are required', true);
            
            if (!slug.startsWith('gallery_')) slug = 'gallery_' + slug;

            try {
                const res = await fetch('api.php?action=gallery_category_add', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name, slug })
                });

                if (res.ok) {
                    const data = await res.json();
                    store.gallery_categories.push({ id: data.id, name, slug });
                    showRoyalToast('Success', 'Section added successfully.');
                    manageSections(); // Re-render modal
                    refreshPreview();
                }
            } catch (e) {
                showRoyalToast('Error', 'Failed to add section', true);
            }
        };

        window.deleteCategory = async function(id) {
            if (!confirm('Are you sure? Images in this section will still exist in the database but might not show up if this section is gone.')) return;

            try {
                const res = await fetch('api.php?action=gallery_category_delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });

                if (res.ok) {
                    store.gallery_categories = store.gallery_categories.filter(c => c.id !== id);
                    showRoyalToast('Success', 'Section removed.');
                    manageSections(); // Re-render modal
                    refreshPreview();
                }
            } catch (e) {
                showRoyalToast('Error', 'Failed to delete section', true);
            }
        };

        // UI Helpers for Dynamic Modals
        function showRoyalPrompt(title, html, isLarge = false, confirmText = 'Confirm') {
            return new Promise((resolve) => {
                const modalId = 'royalModal';
                let existing = document.getElementById(modalId);
                if (existing) existing.remove();

                const modalHtml = `
                    <div class="modal fade" id="${modalId}" tabindex="-1">
                        <div class="modal-dialog ${isLarge ? 'modal-lg' : ''} modal-dialog-centered">
                            <div class="modal-content bg-dark text-white border-secondary">
                                <div class="modal-header border-secondary">
                                    <h5 class="modal-title font-heading">${title}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    ${html}
                                </div>
                                <div class="modal-footer border-secondary">
                                    <button type="button" class="btn btn-secondary h-btn" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-gold h-btn" id="modal-confirm" style="display: inline-block !important;">${confirmText}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                const modal = new bootstrap.Modal(document.getElementById(modalId));
                
                // Removed the old display:none logic as it's now handled in the template string

                document.getElementById('modal-confirm').addEventListener('click', () => {
                    modal.hide();
                    resolve(true);
                });

                document.getElementById(modalId).addEventListener('hidden.bs.modal', () => {
                    resolve(false);
                });

                modal.show();
            });
        }

        async function deleteImage(id) {
            if (!confirm("Are you sure you want to delete this image slot?")) return;

            try {
                const res = await fetch('api.php?action=image_delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });

                if (res.ok) {
                    store.images = store.images.filter(img => img.id !== id);
                    renderEditor();
                    refreshPreview();
                    showRoyalToast('Success', 'Image slot removed.');
                } else {
                    showRoyalToast('Error', 'Failed to delete image slot.', true);
                }
            } catch (e) {
                showRoyalToast('Network Error', 'Connection lost.', true);
            }
        }

        async function addFacility() {
            const html = `
                <div class="mb-3 text-start">
                    <label class="form-label text-white-50 small">Facility Name</label>
                    <input type="text" id="new_fac_name" class="form-control bg-dark text-white border-secondary" placeholder="e.g., AC Bride & Groom Room">
                </div>
                <div class="mb-3 text-start">
                    <label class="form-label text-white-50 small">Description</label>
                    <textarea id="new_fac_desc" class="form-control bg-dark text-white border-secondary" rows="3" placeholder="Facility description..."></textarea>
                </div>
                <div class="mb-3 text-start">
                    <label class="form-label text-white-50 small">Icon Class (FontAwesome)</label>
                    <input type="text" id="new_fac_icon_class" class="form-control bg-dark text-white border-secondary" placeholder="e.g., fas fa-snowflake" value="fas fa-star">
                </div>
                <div class="mb-3 text-start">
                    <label class="form-label text-white-50 small">Image URL</label>
                    <input type="text" id="new_fac_image" class="form-control bg-dark text-white border-secondary" placeholder="https://example.com/image.jpg">
                </div>
            `;
            
            const confirmed = await showRoyalPrompt('Add New Facility', html, false, 'Add Facility');
            if (!confirmed) return;
            
            const name = document.getElementById('new_fac_name').value.trim();
            const desc = document.getElementById('new_fac_desc').value.trim();
            const icon_class = document.getElementById('new_fac_icon_class').value.trim() || 'fas fa-star';
            const image_url = document.getElementById('new_fac_image').value.trim();
            
            if (!name) {
                return showRoyalToast('Error', 'Facility name is required', true);
            }
            
            try {
                const res = await fetch('api.php?action=facility_add', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name, desc, icon_class, image_url })
                });

                const responseData = await res.json();
                
                if (res.ok && responseData.status === 'success') {
                    store.facilities.push({ id: responseData.id, name, desc, icon_class, image_url });
                    renderEditor();
                    refreshPreview();
                    showRoyalToast('Success', 'New facility added successfully.');
                } else {
                    const errorMsg = responseData.message || 'Failed to add facility';
                    showRoyalToast('Error', errorMsg, true);
                }
            } catch (e) {
                showRoyalToast('Error', 'Network error: ' + e.message, true);
            }
        }

        async function updateFacility(btn, id) {
            const name = document.getElementById('fac_name_' + id).value;
            const desc = document.getElementById('fac_desc_' + id).value;
            const icon_class = document.getElementById('fac_icon_class_' + id).value;
            const image_url = document.getElementById('fac_image_' + id).value;

            setLoading(btn, true);
            try {
                const res = await fetch('api.php?action=facility_update', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, name, desc, icon_class, image_url })
                });

                if (res.ok) {
                    const fac = store.facilities.find(f => f.id === id);
                    if (fac) {
                        fac.name = name;
                        fac.desc = desc;
                        fac.icon_class = icon_class;
                        fac.image_url = image_url;
                    }
                    refreshPreview();
                    showRoyalToast('Success', 'Facility updated.');
                }
            } catch (e) {
                showRoyalToast('Error', 'Network error.', true);
            } finally {
                setLoading(btn, false);
            }
        }

        async function deleteFacility(id) {
            if (!confirm("Delete this facility?")) return;
            try {
                const res = await fetch('api.php?action=facility_delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });

                if (res.ok) {
                    store.facilities = store.facilities.filter(f => f.id !== id);
                    renderEditor();
                    refreshPreview();
                    showRoyalToast('Success', 'Facility removed.');
                }
            } catch (e) {
                showRoyalToast('Error', 'Network error.', true);
            }
        }

        async function uploadImage(input, id) {
            if (!input.files || !input.files[0]) return;

            const file = input.files[0];
            const formData = new FormData();
            formData.append('image_id', id);
            formData.append('file', file);

            const progressWrap = document.getElementById('progress_' + id);
            const progressBar = progressWrap.querySelector('.progress-bar');

            progressWrap.classList.remove('d-none');
            progressBar.style.width = '0%';

            try {
                const res = await fetch('api.php?action=image_upload', {
                    method: 'POST',
                    body: formData
                });

                if (res.ok) {
                    const data = await res.json();
                    const img = store.images.find(i => i.id === id);
                    if (img) {
                        img.url = data.url;
                        img.file_size = data.file_size; // Update file size
                    }

                    const urlInput = document.getElementById('img_url_' + id);
                    if (urlInput) urlInput.value = data.url;
                    
                    renderEditor();
                    refreshPreview();
                    showRoyalToast('Palace Updated', 'Your image has been uploaded to the Royal Vault.');
                } else {
                    let errMsg = 'The royal treasury could not accept this file.';
                    try {
                        const errData = await res.json();
                        if (errData.message) errMsg = errData.message;
                    } catch(err) {}
                    showRoyalToast('Upload Failed', errMsg, true);
                }
            } catch (e) {
                showRoyalToast('Network Error', 'The connection was interrupted.', true);
            } finally {
                setTimeout(() => {
                    progressWrap.classList.add('d-none');
                    progressBar.style.width = '0%';
                }, 1000);
            }
        }

        async function uploadFacilityImage(input, id) {
            if (!input.files || !input.files[0]) return;
            const formData = new FormData();
            formData.append('id', id);
            formData.append('file', input.files[0]);

            const progressWrap = document.getElementById('fac_progress_' + id);
            if (progressWrap) progressWrap.classList.remove('d-none');

            try {
                const res = await fetch('api.php?action=facility_image_upload', {
                    method: 'POST',
                    body: formData
                });
                if (res.ok) {
                    const data = await res.json();
                    const fac = store.facilities.find(f => f.id === id);
                    if (fac) fac.image_url = data.url;
                    renderEditor();
                    refreshPreview();
                    showRoyalToast('Success', 'Facility image updated.');
                }
            } catch(e) { showRoyalToast('Error', 'Upload failed.', true); }
        }

        async function uploadFacilityCardImage(input, category, imageId) {
            if (!input.files || !input.files[0]) return;
            
            const file = input.files[0];
            const facNum = category.split('_')[2];
            const progressWrap = document.getElementById('fac_card_progress_' + facNum);
            const urlInput = document.getElementById('fac_card_url_' + facNum);
            
            if (progressWrap) {
                progressWrap.classList.remove('d-none');
                progressWrap.querySelector('.progress-bar').style.width = '0%';
            }

            try {
                // First upload the file
                const formData = new FormData();
                formData.append('file', file);
                formData.append('category', category);
                formData.append('image_id', imageId || '');
                
                const res = await fetch('api.php?action=image_create_or_update', {
                    method: 'POST',
                    body: formData
                });

                if (res.ok) {
                    const data = await res.json();
                    
                    // Update store
                    if (imageId) {
                        const img = store.images.find(i => i.id === imageId);
                        if (img) img.url = data.url;
                    } else {
                        store.images.push({ id: data.id, url: data.url, category, file_size: '' });
                    }
                    
                    if (urlInput) urlInput.value = data.url;
                    renderEditor();
                    refreshPreview();
                    showRoyalToast('Success', 'Facility card image uploaded.');
                } else {
                    showRoyalToast('Error', 'Upload failed', true);
                }
            } catch(e) { 
                showRoyalToast('Error', 'Upload error: ' + e.message, true); 
            } finally {
                if (progressWrap) progressWrap.classList.add('d-none');
            }
        }

        function showRoyalToast(title, msg, isError = false) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `royal-toast ${isError ? 'error' : ''}`;
            toast.innerHTML = `
                <i class="fas ${isError ? 'fa-exclamation-circle' : 'fa-check-circle'}"></i>
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-msg">${msg}</div>
                </div>
            `;
            container.appendChild(toast);
            setTimeout(() => toast.classList.add('show'), 100);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 600);
            }, 4000);
        }

        function setLoading(btn, active) {
            if (!btn || btn.tagName !== 'BUTTON') return;
            if (active) {
                btn.dataset.original = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                btn.disabled = true;
            } else {
                btn.innerHTML = btn.dataset.original || 'Save';
                btn.disabled = false;
            }
        }

        function refreshPreview() {
            document.getElementById('preview-iframe').contentWindow.location.reload();
            showRoyalToast('Refresh', 'Live preview has been reloaded.');
        }

        switchPage('home', '../index.php');
    </script>
</body>

</html>
```