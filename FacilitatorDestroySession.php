<!DOCTYPE html>
<html lang="en-us">
<head>
    <meta charset="utf-8">
    <title>Facilitator PHP Page</title>
    <link href="https://www.wisc-online.com/ARISE_Files/CSS/AriseMainCSS.css?random=wetasdfrer" rel="stylesheet">
    <style>

    </style>
</head>
<body class="noReport">
<main>
    <img src="https://www.wisc-online.com/ARISE_Files/PHP/00286.png" style="display: block; margin-left: auto; margin-right: auto; margin-top: 20px;">
    <?php
    /*
     * This page is used for destroying a session when the user is inside
     * a simulation in order to use different Medication Reconciliation
     * forms at different levels.
     */

    // Initialize the session.
    // If you are using session_name("something"), don't forget it now!
    session_start();

    // Unset all of the session variables.
    $_SESSION = array();

    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finally, destroy the session.
    session_destroy();
    ?>
</main>
</body>
</html>