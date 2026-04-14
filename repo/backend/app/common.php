<?php

// Application common functions

function sanitizeHtml(string $input): string
{
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

function sanitizeInput(array $data): array
{
    foreach ($data as $key => $value) {
        if (is_string($value)) {
            $data[$key] = sanitizeHtml($value);
        } elseif (is_array($value)) {
            $data[$key] = sanitizeInput($value);
        }
    }
    return $data;
}

function csrfToken(): string
{
    $session = session();
    if (!$session->has('csrf_token')) {
        $session->set('csrf_token', bin2hex(random_bytes(32)));
    }
    return $session->get('csrf_token');
}

function verifyCsrfToken(string $token): bool
{
    $session = session();
    return $session->has('csrf_token') && hash_equals($session->get('csrf_token'), $token);
}