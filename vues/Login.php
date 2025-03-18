<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="/assets/styles.css">
    <link rel="stylesheet" href="/assets/login.css">
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

</body>
</html>