<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

$file      = __DIR__ . '/updates.json';
$uploadDir = __DIR__ . '/uploads/';

// Create uploads dir if missing, fix permissions if needed
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0775, true);
}
// Ensure it's writable
if (!is_writable($uploadDir)) {
    chmod($uploadDir, 0775);
}

function loadPosts($file) {
    if (!file_exists($file)) return [];
    $data = json_decode(file_get_contents($file), true);
    return is_array($data) ? $data : [];
}

function savePosts($file, $posts) {
    file_put_contents($file, json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function savePhoto($base64, $uploadDir, $uploadUrl) {
    if (!preg_match('/^data:(image\/[\w+]+);base64,(.+)$/s', $base64, $m)) return null;
    $mime = $m[1];
    $extMap = ['image/jpeg'=>'jpg','image/jpg'=>'jpg','image/png'=>'png','image/gif'=>'gif','image/webp'=>'webp'];
    $ext = isset($extMap[$mime]) ? $extMap[$mime] : 'jpg';
    $filename = uniqid('photo_', true) . '.' . $ext;
    $data = base64_decode($m[2]);
    if (!$data) return null;
    $result = file_put_contents($uploadDir . $filename, $data);
    if ($result === false) return null;
    return $uploadUrl . $filename;
}

$method = $_SERVER['REQUEST_METHOD'];

$proto     = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host      = $_SERVER['HTTP_HOST'];
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
$uploadUrl = $proto . '://' . $host . $scriptDir . '/uploads/';

if ($method === 'GET') {
    echo json_encode(loadPosts($file));

} elseif ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || empty($input['title'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing title']);
        exit;
    }

    $photoUrls  = [];
    $photoErrors = [];
    foreach (($input['photos'] ?? []) as $i => $base64) {
        $url = savePhoto($base64, $uploadDir, $uploadUrl);
        if ($url) {
            $photoUrls[] = $url;
        } else {
            $photoErrors[] = 'Photo ' . ($i+1) . ' failed. Dir writable: '
                . (is_writable($uploadDir) ? 'yes' : 'no');
        }
    }

    $posts = loadPosts($file);
    $post  = [
        'id'       => time() . rand(100, 999),
        'title'    => $input['title'],
        'category' => $input['category'] ?? 'News',
        'date'     => $input['date'] ?? date('j F Y'),
        'body'     => $input['body'] ?? '',
        'photos'   => $photoUrls,
    ];
    array_unshift($posts, $post);
    savePosts($file, $posts);
    echo json_encode(['ok' => true, 'post' => $post, 'photo_errors' => $photoErrors]);

} elseif ($method === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if (!$id) { http_response_code(400); echo json_encode(['error' => 'Missing id']); exit; }
    $posts = loadPosts($file);
    foreach ($posts as $p) {
        if ($p['id'] == $id) {
            foreach (($p['photos'] ?? []) as $url) {
                $path = $uploadDir . basename($url);
                if (file_exists($path)) unlink($path);
            }
        }
    }
    $posts = array_values(array_filter($posts, fn($p) => $p['id'] != $id));
    savePosts($file, $posts);
    echo json_encode(['ok' => true]);

} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
