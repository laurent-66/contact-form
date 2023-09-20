# Contact Form

Exercice technique / fonctionnel utilisé par Artmajeur pour ses recrutements.


## Contexte

Vous êtes développeur chez Artmajeur. Vous recevez une demande de la direction pour la mise en place d'une nouvelle fonctionnalité sur son site Internet.


> Nous souhaiterions mettre en place un formulaire de contact sur notre site.
> Le formulaire de contact doit être simple : il doit nous permettre de connaitre les coordonnées de l'internaute, et sa question.
> Il nous faut au moins son nom, son email, et sa question pour que nous traitions sa demande.

> Il nous faudrait aussi un petit back-office avec accès sécurisé pour permettre au webmaster de consulter la liste des demandes, et de pouvoir cocher les messages que nous avons traité

Les règles de gestion suivantes sont à mettre en place :

> Un utilisateur qui dépose plusieurs demande de contact avec le même email, doit voir ses demandes regroupées et se cumulées pour ce contact

> Toute demande de contact doit déclencher la création d'un fichier JSON unique dans un répertoire spécifique non exposé par le serveur web, qui contient l'ensemble du contenu de la demande : informations du contact et contenu de la demande. A terme d'autres notifications seront déclenchées.

Il vous est demandé de mettre en place la solution sur la base du Framework Symfony.



## Installation et informations pour tester le projet en local sur sa machine


### Cloner le dépôt git distant en local
Dans votre terminal, positionnez vous dans le bon répertoire est cloner le dépot git en local 

```bash
git clone https://github.com/laurent-66/contact-form.git
```

### Installer les dépendances
Installer les dépendances avec composer à partir du fichier composer.lock

```bash
composer install
```

### Paramétrer les variables d'environnement pour tester sur votre machine

* Dupliquer le fichier .env et renomé le .env.local
* Dans l'aborescence du projet rendez vous dans le fichier .env.local
* les réglages qui vont y être fait seront pour une configuration en local:
* Utilisation de xampp comme serveur pour la base de donnée en SQL avec utilisation de phpmyadmin
* L'adresse de l'application sera http://127.0.0.1:8000
* l'adresse du serveur pour la base de données http://127.0.0.1:3306

Dans le fichier .env.local penser à commenté la ligne concernant le postgresql et décommenté la ligne mysql au dessus

Sur la ligne MySQL rentrer les informations de la manière suivante

DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"

* db_user : entrée un identifiant pour l'accés à la base de donnée
* db_password : entrée mot de passe
* db_name : entrée le nom de la base de donnée par exemple snowTricksProject

Enregistrez le fichier .env.local

### création de la base de donnée
1-installer xampp ( pour cet exemple, mais vous pouvez installer une image sur container docker)
1-Lancer l'application Xampp démarrer les modules Apach et MySQL
2-sur xampp ouvrer la page de phpmyadmin en cliquant sur admin

Dans votre terminal
```bash
symfony console doctrine:database:create
```
Cette commande va créer la base de donnée en récupérant le nom que nous avons donnés dans le fichier .env
Rafraîchir la page de phpmyadmin [nomdevotreprojet] doit apparaître dans l'aborescence

### Jouer les migrations pour alimenter la base de données

Tapez la commande dans votre terminal
```bash
symfony console doctrine:migrations:migrate
```
A la question "Êtes-vous sûr de vouloir continuer d'éxecuté la migration dans la base de données "snowtricksproject" ? répondre oui

Rafraichir la page de phpmyadmin, la liste des tables doit apparaître dans la base de donnée.

### Charger les fixtures

Important, il est impératif de jouer les fixtures pour afficher des exemples de contacts à afficher dans le back office et surtout générer les identifiants de l'administrateur ( id: laurent.lesage51@gmail.com  password: artmajeur)

Dans votre terminal

```bash
symfony console doctrine:fixtures:load
```
Cela aura pour effet de créer un jeu de fausses données.
A la question répondre oui.
Rafraichir la page de phpmyadmin, les fausses données doivent apparaître.


### Les fichiers json générés par contact

Par défaut, les fixtures ne charge pas de fichier json. Pour cela vous devez créer un nouveau contact via le formulaire de contact et y laisser un commentaire. A la validation du formulaire, un fichier sera généré dans un dossier nommé datasJson à la racine du projet. le titre du fichier et composé comme ceci [email]-id-[uniqId].json

### Chargement de l'application

1-Lancer le serveur

Dans votre terminal

```bash
symfony server:start
```
2- tapez dans la barre d'url de votre navigateur

http://127.0.0.1:8000 ou localhost:8000

3- Pour arrêter le serveur

```bash
symfony server:stop
```
### Rappel

Avant le lancement de l'application n'oublié pas au préalable de lancer les modules de xampp.