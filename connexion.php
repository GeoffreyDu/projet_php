<?php
    session_start();

    if (isset($_SESSION['connect'])) {
        header('location: ./index.php');
    }
    require('src/connection.php');

    // Connexion
    if (!empty($_POST['mail']) && !empty($_POST['password'])) {
        // variables
        $mail     = $_POST['mail'];
        $password = $_POST['password'];
        $error    = 1;

        // Crypter le password
        $password = 'aq1'.sha1($password.'1254').'25';

        // vérif mail
        $req = $db->prepare('SELECT * FROM users WHERE email = ?');

        $req->execute(array($mail));

        while ($user = $req->fetch()) {
            if ($password == $user['password']) {
                $error = 0;
                $_SESSION['connect'] = 1;
                $_SESSION['pseudo'] = $user['pseudo'];

                if (isset($_POST['connect'])) {
                    setcookie('log', $user['secret'], time() + 24 * 3600 * 365, '/', null, false, true);
                }

                header('location: ./connexion.php?success=1');  
            }
        }

        if ($error == 1) {
            header('location: ./connexion.php?error=1');
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" type="text/css" href="./design/default.css">
</head>
<body>
    <header>
        <h1>Connexion</h1>
    </header>
    <div class="container">
        <p id="info">Bienvenue, si vous n'avez pas de compte, <a href="index.php">inscrivez-vous</a>.</p>
        <?php 
            if (isset($_GET['error'])) {
                echo '<p id="error">Nous ne pouvons pas vous authentifier.</p>';
            }
            else if (isset($_GET['success'])) {
                echo '<p id="success">Connexion réussie.</p>';
            }
        ?>
        <div id="form">
            <form action="connexion.php" method="post">
                <table>
                    <tr>
                        <td>
                            <label for="mail">E-mail</label>
                        </td>
                        <td>
                            <input type="email" name="mail" placeholder="Entrez votre e-mail" id="mail" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="password">Mot de passe</label>
                        </td>
                        <td>
                            <input type="password" name="password" placeholder="*****" id="password" required>
                        </td>
                    </tr>
                </table>
                <p><label for="connect"><input type="checkbox" name="connect" id="connect" checked>Connexion automatique</label></p>
                <div id="button">
                    <button type="submit">Connexion</button>   
                </div>
            </form>
        </div>
    </div>
</body>
</html>