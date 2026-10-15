<?php

declare(strict_types=1);

/**
 * Reload events table from Seeds/data/events.json without wiping other tables.
 */
$root = dirname(__DIR__);
$dbPath = $root . '/writable/dms.db';
$jsonPath = $root . '/app/Database/Seeds/data/events.json';

if (! is_file($dbPath) || ! is_file($jsonPath)) {
    fwrite(STDERR, "Missing db or events.json\n");
    exit(1);
}

$events = json_decode((string) file_get_contents($jsonPath), true);
if (! is_array($events)) {
    fwrite(STDERR, "Invalid events.json\n");
    exit(1);
}

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$now = date('Y-m-d H:i:s');

$pdo->exec('DELETE FROM event_media');
$pdo->exec('DELETE FROM events');

$stmt = $pdo->prepare(
    'INSERT INTO events (slug, title, excerpt, body_html, body_text, category, cover_image, event_date, location, status, created_at, updated_at)
     VALUES (:slug, :title, :excerpt, :body_html, :body_text, :category, :cover_image, :event_date, :location, :status, :created_at, :updated_at)'
);

foreach ($events as $e) {
    $stmt->execute([
        ':slug' => $e['slug'],
        ':title' => $e['title'],
        ':excerpt' => $e['excerpt'] ?? null,
        ':body_html' => $e['body_html'] ?? null,
        ':body_text' => $e['body_text'] ?? null,
        ':category' => $e['category'] ?? null,
        ':cover_image' => $e['cover_image'] ?? null,
        ':event_date' => $e['event_date'] ?? null,
        ':location' => $e['location'] ?? null,
        ':status' => $e['status'] ?? 'published',
        ':created_at' => $now,
        ':updated_at' => $now,
    ]);
}

echo 'Reloaded ' . count($events) . " DMS events\n";
