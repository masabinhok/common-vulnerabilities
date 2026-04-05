<?php
declare(strict_types=1);

$dbFile = __DIR__ . '/lab5.sqlite';

try {
    $db = new PDO('sqlite:' . $dbFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec('DROP TABLE IF EXISTS users');
    $db->exec('CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        role TEXT NOT NULL
    )');

    $stmt = $db->prepare('INSERT INTO users (username, role) VALUES (?, ?)');
    $seedUsers = [
        ['alice', 'admin'],
        ['bob', 'student'],
        ['charlie', 'teacher'],
    ];

    foreach ($seedUsers as $user) {
        $stmt->execute([$user[0], $user[1]]);
    }

    echo '<h2>Database initialized successfully.</h2>';
    echo '<p>Created file: ' . htmlspecialchars($dbFile, ENT_QUOTES, 'UTF-8') . '</p>';
    echo '<p><a href="index.php">Go to Lab 5 Home</a></p>';
} catch (Throwable $e) {
    http_response_code(500);
    echo '<h2>Failed to initialize database.</h2>';
    echo '<pre>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre>';
}
