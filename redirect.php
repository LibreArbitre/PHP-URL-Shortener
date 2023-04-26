<?php
$dbFile = 'urlshortener.db';

if (!isset($_GET['code'])) {
    http_response_code(400);
    echo 'Paramètre code manquant';
    exit;
}

$shortUrl = $_GET['code'];

try {
    $db = new PDO('sqlite:' . $dbFile);
    $stmt = $db->prepare('SELECT * FROM urls WHERE short_url = :short_url;');
    $stmt->bindParam(':short_url', $shortUrl);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        http_response_code(404);
        echo 'URL raccourcie introuvable';
    } else {
        if ($result['expires'] !== null && (time() - $result['created_at']) > $result['expires']) {
            http_response_code(410);
            echo 'URL raccourcie expirée';
        } else {
            header('Location: ' . $result['long_url']);
        }
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo 'Erreur de base de données';
}
?>
