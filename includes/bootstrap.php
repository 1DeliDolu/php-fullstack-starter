<?php

// Load .env-style files into process environment before config constants are defined.
function loadDotEnv(string $filePath): void
{
    if (!file_exists($filePath)) {
        return;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        if (
            (strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) ||
            (strpos($value, "'") === 0 && strrpos($value, "'") === strlen($value) - 1)
        ) {
            $value = substr($value, 1, -1);
        }

        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
}

loadDotEnv(__DIR__ . '/../.env');
loadDotEnv(__DIR__ . '/../.env.local');
