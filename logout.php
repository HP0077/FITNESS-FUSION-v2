<?php
/**
 * Logout Handler
 * 
 * Destroys session and redirects to login page.
 * All heavy lifting is in auth.php's logout().
 */

require_once __DIR__ . '/includes/auth.php';

logout();
