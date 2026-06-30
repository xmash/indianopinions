<?php

namespace App\Support;

final class AppUrl
{
    /**
     * Normalize APP_URL for Laravel console boot (SetRequestForConsole).
     */
    public static function normalize(?string $appUrl, ?string $allowedHosts = null): string
    {
        foreach (self::parseUrlCandidates($appUrl) as $candidate) {
            if (self::isValidAppUrl($candidate)) {
                return self::ensureScheme($candidate);
            }
        }

        foreach (self::parseAllowedHosts($allowedHosts) as $host) {
            return 'https://'.$host;
        }

        return 'http://localhost';
    }

    /**
     * Comma-separated APP_URL values (full URLs or hostnames).
     *
     * @return list<string>
     */
    public static function parseUrlCandidates(?string $value): array
    {
        if ($value === null || trim($value) === '') {
            return [];
        }

        $candidates = [];

        foreach (explode(',', $value) as $part) {
            $part = self::trim($part);

            if ($part === '' || self::looksLikeTemplate($part)) {
                continue;
            }

            if (preg_match('#^https?://#i', $part)) {
                $candidates[] = $part;
                continue;
            }

            if (self::isValidHostname($part)) {
                $candidates[] = 'https://'.$part;
            }
        }

        return $candidates;
    }

    /**
     * Hostnames from APP_ALLOWED_HOSTS plus any extra hosts in a comma-separated APP_URL.
     *
     * @return list<string>
     */
    public static function allAllowedHosts(?string $appUrl, ?string $allowedHosts): array
    {
        $hosts = self::parseAllowedHosts($allowedHosts);

        foreach (self::parseUrlCandidates($appUrl) as $url) {
            $host = parse_url(self::ensureScheme($url), PHP_URL_HOST);
            if (is_string($host) && self::isValidHostname($host)) {
                $hosts[] = $host;
            }
        }

        return array_values(array_unique($hosts));
    }

    /**
     * @return list<string>
     */
    public static function parseAllowedHosts(?string $allowedHosts): array
    {
        if ($allowedHosts === null || trim($allowedHosts) === '') {
            return [];
        }

        $hosts = [];

        foreach (explode(',', $allowedHosts) as $part) {
            $host = self::trim($part);

            if ($host === '' || self::looksLikeTemplate($host)) {
                continue;
            }

            $host = preg_replace('#^https?://#i', '', $host) ?? $host;
            $host = rtrim($host, '/');

            if (self::isValidHostname($host)) {
                $hosts[] = $host;
            }
        }

        return array_values(array_unique($hosts));
    }

    private static function trim(?string $value): string
    {
        return trim((string) $value, " \t\n\r\0\x0B\"'");
    }

    private static function looksLikeTemplate(string $value): bool
    {
        return str_contains($value, '${{') || str_contains($value, '${');
    }

    private static function isValidAppUrl(string $url): bool
    {
        $withScheme = self::ensureScheme($url);
        $host = parse_url($withScheme, PHP_URL_HOST);

        return is_string($host) && self::isValidHostname($host);
    }

    private static function ensureScheme(string $url): string
    {
        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }

        return 'https://'.ltrim($url, '/');
    }

    private static function isValidHostname(string $host): bool
    {
        if ($host === '' || str_contains($host, ',') || str_contains($host, ' ')) {
            return false;
        }

        return (bool) preg_match('/^[a-zA-Z0-9]([a-zA-Z0-9.-]*[a-zA-Z0-9])?$/', $host);
    }
}
