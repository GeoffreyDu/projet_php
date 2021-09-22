<?php
    session_start(); /* Initialise la session */
    session_unset(); /* Désactive la session */
    session_destroy(); /* Détruit la session */
    setcookie('log', '', time() - 22, '/', null, false, true); /* Détruit le cookie */

    header('location: ./connexion.php');
?>