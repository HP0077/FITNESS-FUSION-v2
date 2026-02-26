<?php
/**
 * Login & Registration Handler
 * 
 * POST target for login.html forms.
 * Uses the centralized includes/ layer — no direct DB config here.
 */

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

// Already logged in? Skip straight to dashboard.
if (isLoggedIn()) {
    header('Location: ' . BASE_URL . '/dashboard/dashboard.php');
    exit();
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $conn   = getDB();

    // ── Sign In ────────────────────────────────────────────
    if ($action === 'signin') {
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            $error = 'Please fill in both email and password.';
        } else {
            $stmt = $conn->prepare('SELECT id, name, email, password FROM users WHERE email = :email LIMIT 1');
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Set up authenticated session
                loginUser((int) $user['id'], $user['name'], $user['email']);

                // Redirect to intended page or default to dashboard
                $redirect = $_SESSION['redirect_after_login'] ?? BASE_URL . '/dashboard/dashboard.php';
                unset($_SESSION['redirect_after_login']);

                header('Location: ' . $redirect);
                exit();
            } else {
                // Generic message — never reveal which field was wrong
                $error = 'Invalid email or password.';
            }
        }

    // ── Sign Up ────────────────────────────────────────────
    } elseif ($action === 'signup') {
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($name === '' || $email === '' || $password === '') {
            $error = 'Please fill in all fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } elseif (strlen($password) < 8) {
            $error = 'Password must be at least 8 characters.';
        } else {
            // Check for existing email
            $stmt = $conn->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
            $stmt->execute([':email' => $email]);

            if ($stmt->fetch()) {
                $error = 'This email is already registered. Please log in.';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                $stmt = $conn->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
                $stmt->execute([
                    ':name'     => $name,
                    ':email'    => $email,
                    ':password' => $hashedPassword,
                ]);

                if ($stmt->rowCount() === 1) {
                    $success = 'Account created successfully. Please log in.';
                } else {
                    $error = 'Registration failed. Please try again.';
                }
            }
        }
    }
}

// ── Render Feedback on login.html ──────────────────────────
// If there's an error or success, store in session and redirect back.
// login.html can read these via a small PHP snippet or be converted to login.php later.
if ($error !== '' || $success !== '') {
    $_SESSION['login_error']   = $error;
    $_SESSION['login_success'] = $success;
}

header('Location: ' . BASE_URL . '/login.html');
exit();

