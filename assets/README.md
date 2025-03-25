# 📌 Installation et Configuration de MySQL sur WSL avec Connexion via Python

Ce guide détaille les étapes pour installer MySQL sur WSL (Debian ou Ubuntu),
configurer un utilisateur, et connecter la base de données avec Python.

---

## Étape 1 : Mettre à Jour les Paquets du Système
Avant d'installer MySQL, assurez-vous que votre système est à jour :
```bash
sudo apt update && sudo apt upgrade -y
```

---

## Étape 2 : Installer MySQL Server
### 🔹 Option 1 : Installation via `apt` (Méthode Classique)
```bash
sudo apt install mysql-server -y
```

### 🔹 Option 2 : Installation via `wget` (Alternative)
Téléchargez le package MySQL APT :
```bash
wget https://dev.mysql.com/get/mysql-apt-config_0.8.29-1_all.deb
```
Installez le package téléchargé :
```bash
sudo dpkg -i mysql-apt-config_0.8.29-1_all.deb
```
Mettez à jour les paquets après l'ajout du dépôt MySQL :
```bash
sudo apt update
```
Installez MySQL Server :
```bash
sudo apt install mysql-server -y
```

---

## Étape 3 : Démarrer MySQL et Activer son Lancement au Démarrage
```bash
sudo service mysql start
sudo systemctl enable mysql
```

---

## Étape 4 : Sécuriser l'Installation MySQL
MySQL propose un assistant pour renforcer la sécurité :
```bash
sudo mysql_secure_installation
```
Suivez les instructions à l’écran.

---

## Étape 5 : Créer un Utilisateur MySQL et lui Attribuer des Droits
Accédez à MySQL avec `root` :
```bash
sudo mysql -u root -p
```
Dans MySQL, exécutez les commandes suivantes :
```sql
CREATE DATABASE Internity;
CREATE USER 'Internity'@'%' IDENTIFIED BY 'MotDePasseSécurisé!';
GRANT ALL PRIVILEGES ON *.* TO 'Internity'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
EXIT;
```

---

## Étape 6 : Modifier la Configuration de MySQL pour Accepter les Connexions Externes
Ouvrez le fichier de configuration :
```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```
Modifiez la ligne suivante :
```
bind-address = 127.0.0.1
```
en
```
bind-address = 0.0.0.0
```
Sauvegardez (`CTRL+X`, `Y`, `Entrée`) puis redémarrez MySQL :
```bash
sudo systemctl restart mysql
```

---

## Étape 7 : Trouver l'Adresse IP de WSL
Pour connecter MySQL depuis Windows, trouvez l'IP de WSL :
```bash
ip a
```
Notez l’adresse `inet` (exemple : `172.18.x.x`).

---

## Étape 8 : Installer le Connecteur MySQL pour Python
Dans WSL, installez le module MySQL Connector :
```bash
pip3 install mysql-connector-python
```
ou si vous êtes des fous :
```bash
sudo apt install python3-mysql-connector-python
```

---

## Étape 9 : Remplir la Base de Données
Dans WSL, lancez la commande :
```bash
python3 /assets/Database.py
```

---
🎉 Félicitations ! Votre MySQL est installé, configuré et prêt à être utilisé avec Python 🚀
