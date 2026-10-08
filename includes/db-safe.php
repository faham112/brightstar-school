<?php
function db_configured(): bool {
    $name = env('DB_NAME');
    $user = env('DB_USER');
    $pass = env('DB_PASS');
    if ($name === '' || $user === '' || $pass === '') { return false; }
    foreach ([$name, $user, $pass] as $value) {
        if (str_contains($value, 'paste-')) { return false; }
    }
    return true;
}
function rows(string $sql): array {
    if (!db_configured()) { return []; }
    try { return db()->query($sql)->fetchAll(); }
    catch (Throwable $e) { return []; }
}
