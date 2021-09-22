<?php
    session_start();
    require('src/connection.php');
    // Inscription
    if (!empty($_POST['pseudo']) && !empty($_POST['mail']) && !empty($_POST['password']) && !empty($_POST['confirmPassword'])) {
        // variables
        $pseudo       = $_POST['pseudo'];
        $mail         = $_POST['mail'];
        $password     = $_POST['password'];
        $pass_confirm = $_POST['confirmPassword'];
        // pass == passConfirm
        if ($password != $pass_confirm) {
            header('location: ./?error=1&pass=1');
        }
        // mail exists ?
        $req = $db->prepare('SELECT count(*) as numberEmail FROM users WHERE email = ?;');

        $req->execute(array($mail));

        while ($email_verification = $req->fetch()) {
            if ($email_verification['numberEmail'] != 0) {
                header('location: ./?error=1&email=1');
            }
        }

        // secret (hash)
        $secret = sha1($mail).time();
        $secret = sha1($secret).time().time();

        // encryptage password

        $password = 'aq1'.sha1($password.'1254').'25';

        // envoi de la requête

        $req = $db->prepare('INSERT INTO users (pseudo, email, password, secret) VALUES (?, ?, ?, ?);');

        $req->execute(array($pseudo, $mail, $password, $secret));

        header('location: ./?success=1');

    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" type="text/css" href="./design/default.css">
</head>
<body>
    <header>
        <h1>Inscription</h1>
    </header>
    <div class="container">
        <?php if (!isset($_SESSION['connect'])) { ?> 
            <p id="info">Bienvenue, inscrivez-vous. Sinon <a href="connexion.php">connectez-vous</a>.</p>
            <?php
                if (isset($_GET['error'])) {
                    
                    if (isset($_GET['pass'])) {
                        echo '<p id="error">Les mots de passe ne sont pas identiques.</p>';
                    }
                    else if (isset($_GET['email'])) {
                        echo '<p id="error">Ce mail est déjà pris.</p>';
                    }
                }
                else if (isset($_GET['success'])) {
                    echo '<p id="success">Compte créé.</p>';
                }
            ?>
            <div id="form">
                <form action="index.php" method="post">
                    <table>
                        <tr>
                            <td>
                                <label for="pseudo">Pseudo</label>
                            </td>
                            <td>
                                <input type="text" name="pseudo" placeholder="Entrez votre pseudo" id="pseudo" required>
                            </td>
                        </tr>
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
                        <tr>
                            <td>
                                <label for="confirmation">Confirmation mot de passe</label>
                            </td>
                            <td>
                                <input type="password" name="confirmPassword" placeholder="*****" id="confirmPassword" required>
                            </td>
                        </tr>

                    </table>
                    <div id="button">
                        <button type="submit">Inscription</button>
                    </div>
                </form>
            </div>
        <?php } else {?>
            <p id="info">
                Bonjour <?= $_SESSION['pseudo']?>
                <br>
                <a href="./disconnection.php">Déconnexion</a>
            </p>
        <?php } ?> 
    </div>
</body>
</html>