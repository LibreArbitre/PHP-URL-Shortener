<?php
header('Content-Type: application/json');

$dbFile = 'urlshortener.db';
$token = "xxxxxxxxxxxxxxxxxxxxxxxxxx";

if (!isset($_GET['token']) || $_GET['token'] !== $token) {
    http_response_code(401);
    echo json_encode(['error' => 'Token invalide']);
    exit;
}

if (!isset($_GET['code'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Paramètre code manquant']);
    exit;
}

$shortUrl = $_GET['code'];

try {
    $db = new PDO('sqlite:' . $dbFile);
    $stmt = $db->prepare('DELETE FROM urls WHERE short_url = :short_url;');
    $stmt->bindParam(':short_url', $shortUrl);
    $result = $stmt->execute();

    if ($result) {
        http_response_code(200);
        echo json_encode(['success' => 'URL raccourcie supprimée avec succès']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'URL raccourcie introuvable']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de base de données']);
}
?>
