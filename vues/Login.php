<!-- FORMULAIRE EN PHP -->
<?php
require_once('../src/Controllers/Login.php');
require_once('../src/Controllers/CheckAuth.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="/assets/css/login.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>

<body class="login-page">
    <header class="navbar">
        <div class="home">
            <a href="Home.twig.html"><img src="/assets/icons/home.svg" alt="Home"></a>
        </div>
        <div class="logo">
            <h1>INTERNITY</h1>
        </div>
    </header>
    <main>
        <div class="login-container">
            <form method="POST">
                <div class="form-content">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" required placeholder="Votre email">

                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required placeholder="Votre mot de passe">

                    <p class="error"> <?php echo $error_message ?> </p>

                    <div class="checkbox-container">
                        <input type="checkbox" id="remember-me">
                        <p>Se souvenir de moi</p>
                    </div>
                </div>

                <div class="spacer"></div>

                <button type="submit">Se connecter</button>
            </form>
        </div>
    </main>
    <footer>
        <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
        <p>© 2025 - Internity</p>
    </footer>
</body>

</html>
