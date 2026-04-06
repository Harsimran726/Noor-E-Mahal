<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(401);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../includes/db.php';

$action = $_GET['action'] ?? '';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'content') {
        $data = json_decode(file_get_contents('php://input'), true);
        $key = $data['key'] ?? '';
        $value = $data['value'] ?? '';

        if ($key) {
            $stmt = $db->prepare("SELECT * FROM site_content WHERE `key` = ?");
            $stmt->execute([$key]);
            if ($stmt->fetch()) {
                $stmt = $db->prepare("UPDATE site_content SET `value` = ? WHERE `key` = ?");
                $stmt->execute([$value, $key]);
            } else {
                $stmt = $db->prepare("INSERT INTO site_content (`key`, `value`) VALUES (?, ?)");
                $stmt->execute([$key, $value]);
            }
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error']);
        }
        exit;
    }

    if ($action === 'content_delete') {
        $data = json_decode(file_get_contents('php://input'), true);
        $key = $data['key'] ?? '';

        if ($key) {
            $stmt = $db->prepare("DELETE FROM site_content WHERE `key` = ?");
            $stmt->execute([$key]);
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Key is required']);
        }
        exit;
    }
    
    if ($action === 'image_update') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? '';
        $url = trim($data['url'] ?? '');
        $url = str_replace(["\n", "\r", "\t"], '', $url);
        $alt = trim($data['alt_text'] ?? '');
        
        if ($id) {
            $stmt = $db->prepare("UPDATE image_assets SET url = ?, alt_text = ? WHERE id = ?");
            if ($stmt->execute([$url, $alt, $id])) {
                 echo json_encode(['status' => 'success']);
            } else {
                 echo json_encode(['status' => 'error', 'message' => 'Not found']);
            }
        }
        exit;
    }
    
    if ($action === 'image_upload') {
        $image_id = $_POST['image_id'] ?? '';
        
        if (empty($_FILES['file'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'No file received. It might exceed the server upload_max_filesize limit.']);
            exit;
        }
        
        if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Upload error code: ' . $_FILES['file']['error']]);
            exit;
        }

        if ($image_id) {
            $uploadDir = __DIR__ . '/../static/uploads/';
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' => 'Could not create upload directory.']);
                    exit;
                }
            }
            
            $fileInfo = pathinfo($_FILES['file']['name']);
            $ext = isset($fileInfo['extension']) ? $fileInfo['extension'] : 'jpg';
            $filename = uniqid() . '.' . $ext;
            $dest = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
                $relative_path = 'static/uploads/' . $filename;
                
                $stmt = $db->prepare("UPDATE image_assets SET url = ? WHERE id = ?");
                $stmt->execute([$relative_path, $image_id]);
                
                // Calculate formatted size
                $bytes = filesize($dest);
                $formattedSize = ($bytes >= 1048576) ? number_format($bytes / 1048576, 2) . ' MB' : number_format($bytes / 1024, 2) . ' KB';

                echo json_encode(['status' => 'success', 'url' => $relative_path, 'file_size' => $formattedSize]);
            } else {
                $error = error_get_last();
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to move file: ' . ($error['message'] ?? 'Unknown error')]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Missing image ID.']);
        }
        exit;
    }

    if ($action === 'image_add') {
        $data = json_decode(file_get_contents('php://input'), true);
        $url = $data['url'] ?? '';
        $category = $data['category'] ?? '';
        $alt = $data['alt_text'] ?? '';
        
        if ($url && $category) {
            $stmt = $db->prepare("INSERT INTO image_assets (url, category, alt_text) VALUES (?, ?, ?)");
            $stmt->execute([$url, $category, $alt]);
            echo json_encode(['status' => 'success', 'id' => $db->lastInsertId()]);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'URL and Category are required']);
        }
        exit;
    }

    if ($action === 'image_delete') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? '';
        if ($id) {
            $stmt = $db->prepare("DELETE FROM image_assets WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID is required']);
        }
        exit;
    }

    if ($action === 'image_create_and_upload') {
        $category = trim($_POST['category'] ?? '');
        $alt = trim($_POST['alt_text'] ?? '');
        
        if (empty($_FILES['file']) || !$category) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'File and Category are required.']);
            exit;
        }

        $uploadDir = __DIR__ . '/../static/uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $fileInfo = pathinfo($_FILES['file']['name']);
        $filename = uniqid() . '.' . ($fileInfo['extension'] ?? 'jpg');
        $dest = $uploadDir . $filename;
        
        if (move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
            $relative_path = 'static/uploads/' . $filename;
            $stmt = $db->prepare("INSERT INTO image_assets (url, category, alt_text) VALUES (?, ?, ?)");
            $stmt->execute([$relative_path, $category, $alt]);
            echo json_encode(['status' => 'success', 'id' => $db->lastInsertId(), 'url' => $relative_path]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to save file.']);
        }
        exit;
    }

    if ($action === 'image_create_or_update') {
        $category = trim($_POST['category'] ?? '');
        $image_id = trim($_POST['image_id'] ?? '');
        
        if (empty($_FILES['file']) || !$category) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'File and Category are required.']);
            exit;
        }

        $uploadDir = __DIR__ . '/../static/uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $fileInfo = pathinfo($_FILES['file']['name']);
        $filename = uniqid() . '.' . ($fileInfo['extension'] ?? 'jpg');
        $dest = $uploadDir . $filename;
        
        if (move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
            $relative_path = 'static/uploads/' . $filename;
            
            if ($image_id) {
                // Update existing
                $stmt = $db->prepare("UPDATE image_assets SET url = ? WHERE id = ? AND category = ?");
                $stmt->execute([$relative_path, $image_id, $category]);
                echo json_encode(['status' => 'success', 'id' => $image_id, 'url' => $relative_path]);
            } else {
                // Create new
                $stmt = $db->prepare("INSERT INTO image_assets (url, category, alt_text) VALUES (?, ?, ?)");
                $stmt->execute([$relative_path, $category, '']);
                echo json_encode(['status' => 'success', 'id' => $db->lastInsertId(), 'url' => $relative_path]);
            }
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to save file.']);
        }
        exit;
    }

    if ($action === 'facility_image_upload') {
        $id = $_POST['id'] ?? '';
        if (empty($_FILES['file']) || !$id) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'File and Facility ID are required.']);
            exit;
        }

        if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Upload error code: ' . $_FILES['file']['error']]);
            exit;
        }

        $uploadDir = __DIR__ . '/../static/uploads/';
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Could not create upload directory.']);
            exit;
        }
        if (!is_writable($uploadDir)) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Upload directory is not writable.']);
            exit;
        }
        
        $fileInfo = pathinfo($_FILES['file']['name']);
        $filename = uniqid() . '.' . ($fileInfo['extension'] ?? 'jpg');
        $dest = $uploadDir . $filename;
        
        if (move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
            $relative_path = 'static/uploads/' . $filename;
            $stmt = $db->prepare("UPDATE facilities SET image_url = ? WHERE id = ?");
            $stmt->execute([$relative_path, $id]);
            echo json_encode(['status' => 'success', 'url' => $relative_path]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to save file.']);
        }
        exit;
    }

    if ($action === 'facility_icon_upload') {
        $id = $_POST['id'] ?? '';
        if (empty($_FILES['file']) || !$id) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'File and Facility ID are required.']);
            exit;
        }

        if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Upload error code: ' . $_FILES['file']['error']]);
            exit;
        }

        $uploadDir = __DIR__ . '/../static/uploads/';
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Could not create upload directory.']);
            exit;
        }
        if (!is_writable($uploadDir)) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Upload directory is not writable.']);
            exit;
        }

        $fileInfo = pathinfo($_FILES['file']['name']);
        $filename = uniqid('icon_') . '.' . ($fileInfo['extension'] ?? 'png');
        $dest = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
            $relative_path = 'static/uploads/' . $filename;
            $stmt = $db->prepare("UPDATE facilities SET icon_url = ? WHERE id = ?");
            $stmt->execute([$relative_path, $id]);
            echo json_encode(['status' => 'success', 'url' => $relative_path]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to save file.']);
        }
        exit;
    }

    if ($action === 'facility_add') {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $name = trim($data['name'] ?? 'New Facility');
            $desc = trim($data['desc'] ?? '');
            $icon_class = trim($data['icon_class'] ?? 'fas fa-star');
            $image_url = trim($data['image_url'] ?? '');
            $icon_url = trim($data['icon_url'] ?? '');
            $image_url = str_replace(["\n", "\r", "\t"], '', $image_url);
            $icon_url = str_replace(["\n", "\r", "\t"], '', $icon_url);
            
            // Try with 'desc' first
            try {
                $stmt = $db->prepare("INSERT INTO facilities (name, `desc`, icon_class, image_url, icon_url) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $desc, $icon_class, $image_url, $icon_url]);
                echo json_encode(['status' => 'success', 'id' => $db->lastInsertId()]);
            } catch (Exception $e1) {
                // If 'desc' fails, try 'description'
                $stmt = $db->prepare("INSERT INTO facilities (name, description, icon_class, image_url, icon_url) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $desc, $icon_class, $image_url, $icon_url]);
                echo json_encode(['status' => 'success', 'id' => $db->lastInsertId()]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
        exit;
    }

    if ($action === 'facility_update') {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'] ?? '';
            $name = trim($data['name'] ?? '');
            $desc = trim($data['desc'] ?? '');
            $icon_class = trim($data['icon_class'] ?? '');
            $image_url = trim($data['image_url'] ?? '');
            $icon_url = trim($data['icon_url'] ?? '');
            $image_url = str_replace(["\n", "\r", "\t"], '', $image_url);
            $icon_url = str_replace(["\n", "\r", "\t"], '', $icon_url);
            
            if ($id) {
                // Try with 'desc' first
                try {
                    $stmt = $db->prepare("UPDATE facilities SET name = ?, `desc` = ?, icon_class = ?, image_url = ?, icon_url = ? WHERE id = ?");
                    $stmt->execute([$name, $desc, $icon_class, $image_url, $icon_url, $id]);
                    echo json_encode(['status' => 'success']);
                } catch (Exception $e1) {
                    // If 'desc' fails, try 'description'
                    $stmt = $db->prepare("UPDATE facilities SET name = ?, description = ?, icon_class = ?, image_url = ?, icon_url = ? WHERE id = ?");
                    $stmt->execute([$name, $desc, $icon_class, $image_url, $icon_url, $id]);
                    echo json_encode(['status' => 'success']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'ID is required']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
        exit;
    }

    if ($action === 'facility_delete') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? '';
        if ($id) {
            $stmt = $db->prepare("DELETE FROM facilities WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID is required']);
        }
        exit;
    }

    if ($action === 'gallery_category_add') {
        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['name'] ?? '';
        $slug = $data['slug'] ?? '';
        
        if ($name && $slug) {
            $stmt = $db->prepare("INSERT INTO gallery_categories (slug, name) VALUES (?, ?)");
            $stmt->execute([$slug, $name]);
            echo json_encode(['status' => 'success', 'id' => $db->lastInsertId()]);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Name and Slug are required']);
        }
        exit;
    }

    if ($action === 'gallery_category_delete') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? '';
        if ($id) {
            $stmt = $db->prepare("DELETE FROM gallery_categories WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID is required']);
        }
        exit;
    }
}

http_response_code(400);
echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
?>
