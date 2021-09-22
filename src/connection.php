<?php 
    try {
        $db = new PDO('your info', 'your name', 'your password');
    } catch (Exception $e) {
        die('Erreur: '.$e->getMessage());
    }
?>