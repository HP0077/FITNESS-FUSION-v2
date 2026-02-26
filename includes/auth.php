<?php
/**
 * Authentication Guard & Session Management
 * 
 * Provides reusable auth functions for all protected pages.
 * Loads config.php for session settings and BASE_URL for redirects.
 */

// Prevent direct access
if (basename($_SERVER['PHP_SELF']) === 'auth.php') {
    http_response_code(403);
    exit('Direct access not allowed.');
}

// Load config (session ini settings are applied there before session starts)
require_once __DIR__ . '/config.php';

// Start session safely — config.php already set cookie params
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if the current user is logged in.
 *
 * @return bool
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Guard function — require on any page that needs authentication.
 * Redirects to login.php if user is not authenticated.
 *
 * Usage at top of any protected file:
 *   require_once __DIR__ . '/../includes/auth.php';
 *   requireAuth();
 *
 * @return void
 */
function requireAuth(): void
{
    if (!isLoggedIn()) {
        // Store the page user was trying to reach (for post-login redirect)
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];

        header('Location: ' . BASE_URL . '/login.php');
        exit();
    }

    // ── Session Fixation Protection ────────────────────────
    // Regenerate session ID once after login, tracked by a flag.
    // This ensures an attacker who knew the pre-login session ID
    // cannot hijack the authenticated session.
    if (!isset($_SESSION['_regenerated'])) {
        session_regenerate_id(true);   // true = delete old session file
        $_SESSION['_regenerated'] = true;
    }
}

/**
 * Initialize an authenticated session after successful credential check.
 * Regenerates session ID immediately to prevent fixation.
 *
 * @param int    $userId
 * @param string $name
 * @param string $email
 * @return void
 */
function loginUser(int $userId, string $name, string $email): void
{
    // Regenerate ID right at login — the critical fixation window
    session_regenerate_id(true);

    $_SESSION['user_id']      = $userId;
    $_SESSION['user_name']    = $name;
    $_SESSION['user_email']   = $email;
    $_SESSION['_regenerated'] = true;  // skip duplicate regen in requireAuth()
}

/**
 * Get value from session safely.
 *
 * @param  string $key
 * @param  mixed  $default
 * @return mixed
 */
function sessionGet(string $key, $default = null)
{
    return $_SESSION[$key] ?? $default;
}

/**
 * Destroy session completely and redirect to login page.
 * Safe against session fixation — clears everything.
 *
 * @return void
 */
function logout(): void
{
    // Unset all session variables
    $_SESSION = [];

    // Delete the session cookie from the browser
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    // Destroy the session file on the server
    session_destroy();

    header('Location: ' . BASE_URL . '/login.php');
    exit();
}
