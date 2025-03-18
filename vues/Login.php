<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
</body>
</html>