<?php
/**
 * section_loader.php — Fixed version
 *
 * Bug: the file was returning ALL sections' HTML at once instead of
 * only the requested section.
 *
 * Fix: read the ?section= parameter and include only the matching
 * partial file. Each section lives in its own file under
 * sections/<name>.php (or sections/<lang>/<name>.php when a lang is
 * provided).
 */

// ── Security: only allow known section names ──────────────────────────────────
$ALLOWED_SECTIONS = ['services', 'clients', 'contact', 'projets'];

// ── Read & validate inputs ────────────────────────────────────────────────────
$section = isset($_GET['section']) ? trim($_GET['section']) : '';
$lang    = isset($_GET['lang'])    ? trim($_GET['lang'])    : '';

if (!in_array($section, $ALLOWED_SECTIONS, true)) {
    http_response_code(400);
    echo '<!-- Invalid or missing section -->';
    exit;
}

// ── Only allow safe lang values (letters and hyphens, max 10 chars) ───────────
if ($lang !== '' && !preg_match('/^[a-zA-Z\-]{1,10}$/', $lang)) {
    $lang = '';
}

// ── Resolve the partial file path ─────────────────────────────────────────────
// Convention:
//   sections/<lang>/<section>.php   (when lang is provided)
//   sections/<section>.php          (fallback / default language)
//
// Adjust SECTIONS_DIR if your partials live somewhere else.
define('SECTIONS_DIR', __DIR__ . '/sections/');

$file = '';

if ($lang !== '') {
    $candidate = SECTIONS_DIR . $lang . '/' . $section . '.php';
    if (is_file($candidate)) {
        $file = $candidate;
    }
}

// Fallback to the default (non-lang) partial
if ($file === '') {
    $candidate = SECTIONS_DIR . $section . '.php';
    if (is_file($candidate)) {
        $file = $candidate;
    }
}

if ($file === '') {
    http_response_code(404);
    echo '<!-- Section partial not found: ' . htmlspecialchars($section) . ' -->';
    exit;
}

// ── Output the requested section only ────────────────────────────────────────
header('Content-Type: text/html; charset=UTF-8');
// Prevent the browser from caching stale content during development;
// tighten this header in production as needed.
header('Cache-Control: no-cache');

include $file;
