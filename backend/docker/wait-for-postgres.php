<?php

/**
 * Wait-for-postgres probe for docker/entrypoint.sh.
 * Railway DATABASE_URL uses postgresql:// — PDO requires pgsql:host=...;port=...;dbname=...
 */

$url = getenv('DATABASE_URL') ?: getenv('DB_URL');

try {
    if ($url) {
        [$dsn, $user, $password] = postgresPdoFromUrl($url);
    } else {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $port = getenv('DB_PORT') ?: '5432';
        $database = getenv('DB_DATABASE') ?: 'postgres';
        $user = getenv('DB_USERNAME') ?: 'postgres';
        $password = getenv('DB_PASSWORD') ?: '';
        $dsn = "pgsql:host={$host};port={$port};dbname={$database}";
    }

    new PDO($dsn, $user, $password, [
        PDO::ATTR_TIMEOUT => 5,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    fwrite(STDOUT, "ok\n");
    exit(0);
} catch (Throwable $e) {
    fwrite(STDERR, $e->getMessage().PHP_EOL);
    exit(1);
}

/** @return array{0: string, 1: string, 2: string} */
function postgresPdoFromUrl(string $url): array
{
    $parts = parse_url($url);

    if ($parts === false || ! isset($parts['host'])) {
        throw new InvalidArgumentException('Invalid DATABASE_URL');
    }

    $scheme = $parts['scheme'] ?? 'pgsql';
    if (in_array($scheme, ['postgresql', 'postgres'], true)) {
        $scheme = 'pgsql';
    }

    if ($scheme !== 'pgsql') {
        throw new InvalidArgumentException("Unsupported database scheme: {$scheme}");
    }

    $host = $parts['host'];
    $port = $parts['port'] ?? 5432;
    $database = ltrim($parts['path'] ?? '', '/') ?: 'postgres';
    $user = rawurldecode($parts['user'] ?? '');
    $password = rawurldecode($parts['pass'] ?? '');

    $dsn = "pgsql:host={$host};port={$port};dbname={$database}";

    if (! empty($parts['query'])) {
        parse_str($parts['query'], $query);
        if (isset($query['sslmode'])) {
            $dsn .= ';sslmode='.$query['sslmode'];
        }
    }

    return [$dsn, $user, $password];
}
