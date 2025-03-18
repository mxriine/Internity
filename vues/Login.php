<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
<<<<<<< HEAD
    <title>Login</title>
    <link rel="stylesheet" href="/assets/styles2.css">
</head>
<body>
    <div class="top-bar">
        <div class="logo">
            <!-- Remplacez "LOGO" par votre logo -->
            LOGO
        </div>
        <div class="home">
            <a href="#">Maison</a>
        </div>
    </div>
    <div class="login-container">
        <form action="login_process.php" method="post">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <label>
                    <input type="checkbox" name="remember_me"> Se souvenir de moi
                </label>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
=======
=======
>>>>>>> Nono
    <title>Login Page</title>
    <link rel="stylesheet" href="/assets/styles.css">
    <link rel="stylesheet" href="/assets/stylesLogin.css">
</head>
<body>
    <header class="navbar">
        <div class="logo">LOGO</div>
        <div class="home">
            <a href="#"><img src="/assets/icons/home.svg" alt="Home Icon"></a>
        </div>
    </header>
    <main>
        <div class="login-container">
            <form>
                <!-- Conteneur pour les champs et le texte -->
                <div class="form-content">
                    <label for="email">Email</label>
                    <input type="text" id="email" placeholder="Value">
                    
                    <label for="password">Password</label>
                    <input type="password" id="password" placeholder="Value">
                    
                    <div class="checkbox-container">
                        <input type="checkbox" id="remember-me">
                        <p>Se souvenir de moi</p>
                    </div>
                </div>
                
                <!-- Espace vide pour pousser le bouton vers le bas -->
                <div class="spacer"></div>
                
                <!-- Bouton Login -->
                <button type="submit">Login</button>
            </form>
        </div>
    </main>
    <footer>
        <a class="legal" href="/vues/MentionsLegales.php">Mentions légales</a>
        <p>© 2025 - Internity</p>
    </footer>
<<<<<<< HEAD
>>>>>>> Nono
=======
>>>>>>> Nono
</body>
</html>