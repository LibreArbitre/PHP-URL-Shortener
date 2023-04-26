<?php
header('Content-Type: application/json');

$token = "XXXXXXXXXXXXXXXXXXXXXX";
$dbFile = 'urlshortener.db';

if (!isset($_GET['token']) || $_GET['token'] !== $token) {
    http_response_code(401);
    echo json_encode(['error' => 'Token invalide']);
    exit;
}

if (!isset($_GET['url'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Paramètre URL manquant']);
    exit;
}

$longUrl = $_GET['url'];
$expires = isset($_GET['expires']) ? (int)$_GET['expires'] : null;

try {
    $db = new PDO('sqlite:' . $dbFile);
    $db->exec('CREATE TABLE IF NOT EXISTS urls (id INTEGER PRIMARY KEY, long_url TEXT UNIQUE, short_url TEXT, expires INTEGER, created_at INTEGER);');

    $stmt = $db->prepare('SELECT * FROM urls WHERE long_url = :long_url;');
    $stmt->bindParam(':long_url', $longUrl);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        $shortUrl = generateShortUrl();
        $createdAt = time();
        $stmt = $db->prepare('INSERT INTO urls (long_url, short_url, expires, created_at) VALUES (:long_url, :short_url, :expires, :created_at);');
        $stmt->bindParam(':long_url', $longUrl);
        $stmt->bindParam(':short_url', $shortUrl);
        $stmt->bindParam(':expires', $expires);
        $stmt->bindParam(':created_at', $createdAt);
        $stmt->execute();
    } else {
        $shortUrl = $result['short_url'];
    }

    echo json_encode(['short_url' => $shortUrl]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de base de données']);
}

function generateShortUrl() {
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);
}
?>
