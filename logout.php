<?php
// Unset the JWT cookie
setcookie('token', '', time() - 3600, '/');

// Optionally, destroy the session if you use sessions
// session_start();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="refresh" content="0;url=index.php">
    <script>
        // Remove JWT from localStorage if present
        localStorage.removeItem('authToken');
        // Redirect to login page
        window.location.href = 'index.php';
    </script>
</head>
<body>
    <p>Logging out...</p>
</body>
</html>
