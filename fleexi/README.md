# Fleexi — Projet de petites annonces (type Leboncoin)

Application web de petites annonces développée en **PHP procédural**, **HTML**, **CSS** et **MySQL**.
Un utilisateur peut s'inscrire, se connecter, déposer/modifier/supprimer des annonces, consulter les annonces des autres, les filtrer, les mettre en favoris et contacter les vendeurs par messagerie.

---

## 1. Membres de l'équipe et rôles

| Membre   | Module développé                              |
|----------|-----------------------------------------------|
| Billal   | Module 1 — Authentification (inscription, connexion, déconnexion) |
| Anis     | Module 2 — Gestion des annonces (créer, modifier, supprimer, mes annonces) |
| Fouad    | Module 3 — Affichage public (accueil, détail, design CSS, header) |
| Mohand   | Module 4 — Filtrage & Favoris                 |
| Yanis    | Module 5 — Messagerie                         |

> Adaptez ce tableau aux prénoms réels de votre groupe.

---

## 2. Technologies utilisées

- **PHP procédural** (pas de POO, pas de framework)
- **MySQLi** procédural pour la base de données (avec requêtes préparées)
- **MySQL** pour la base de données
- **HTML / CSS** pour l'interface
- **MAMP** comme serveur local

---

## 3. Installation étape par étape

### Étape 1 — Copier le projet
Placez le dossier `fleexi/` dans le répertoire web de MAMP :
- **Windows** : `C:\MAMP\htdocs\fleexi`
- **Mac** : `/Applications/MAMP/htdocs/fleexi`

### Étape 2 — Démarrer MAMP
Lancez MAMP et cliquez sur **Start Servers** (Apache + MySQL doivent être verts).

### Étape 3 — Créer la base de données
1. Ouvrez **phpMyAdmin** (bouton dans MAMP, ou `http://localhost/phpMyAdmin`).
2. Onglet **Importer**.
3. Choisissez le fichier `database.sql` fourni.
4. Cliquez sur **Exécuter**.
La base `fleexi` et ses 4 tables sont créées, avec des données de test.

### Étape 4 — Vérifier la configuration
Ouvrez `config.php`. Les identifiants par défaut de MAMP sont :
```php
$conn = mysqli_connect("localhost", "root", "root", "fleexi");
```
Si votre MySQL utilise un autre mot de passe, modifiez-le ici.

### Étape 5 — Lancer le site
Dans le navigateur :
```
http://localhost:8888/fleexi/index.php
```
(le port `8888` est celui de MAMP par défaut sur Mac ; sur Windows c'est souvent `8080` ou rien).

### Comptes de test
| Email           | Mot de passe   |
|-----------------|----------------|
| alice@mail.com  | motdepasse123  |
| bob@mail.com    | motdepasse123  |

> Si la connexion avec ces comptes ne fonctionne pas, créez simplement un nouveau compte via la page Inscription.

---

## 4. Structure des dossiers et fichiers

```
fleexi/
├── index.php            -> Page d'accueil : liste des annonces + formulaire de filtrage
├── config.php           -> Connexion à la base de données (incluse partout)
├── style.css            -> Feuille de style
├── database.sql         -> Script de création de la base + données de test
├── README.md            -> Ce fichier
│
├── auth/                -> MODULE 1 : Authentification
│   ├── inscription.php  -> Créer un compte
│   ├── connexion.php    -> Se connecter
│   └── deconnexion.php  -> Se déconnecter
│
├── annonces/            -> MODULE 2 + 3 : Annonces
│   ├── creer.php        -> Créer une annonce (avec upload photo)
│   ├── modifier.php     -> Modifier une annonce (formulaire pré-rempli)
│   ├── supprimer.php    -> Supprimer une annonce (avec confirmation)
│   ├── mes_annonces.php -> Liste de mes annonces
│   └── detail.php       -> Page de détail d'une annonce
│
├── favoris/             -> MODULE 4 : Favoris
│   ├── ajouter.php      -> Ajouter / retirer un favori (toggle)
│   └── mes_favoris.php  -> Liste de mes favoris
│
├── messages/            -> MODULE 5 : Messagerie
│   ├── envoyer.php      -> Envoyer un message à un vendeur
│   └── mes_messages.php -> Liste de mes messages
│
└── uploads/             -> Photos des annonces (les photos déposées arrivent ici)
```

---

## 5. Guide d'utilisation rapide

1. **S'inscrire** : menu → Inscription → email + mot de passe (10 caractères min).
2. **Se connecter** : menu → Connexion.
3. **Déposer une annonce** : menu → Deposer une annonce → remplir titre, prix, description, catégorie, photo.
4. **Voir / modifier / supprimer ses annonces** : menu → Mes annonces.
5. **Filtrer** : sur l'accueil, formulaire prix min / prix max / catégorie.
6. **Favoris** : sur la page détail d'une annonce → bouton "Ajouter / Retirer des favoris". Consultables dans "Mes favoris".
7. **Contacter un vendeur** : page détail d'une annonce → écrire un message. Les échanges sont visibles dans "Mes messages".

---

## 6. Conseils pour la soutenance

**Plan imposé (10 min de présentation) :**
1. Introduction : présenter le projet Fleexi et le but.
2. Répartition des tâches : montrer le tableau des membres ci-dessus.
3. Architecture : projet PHP procédural simple, un fichier = une page, `config.php` partagé pour la connexion, dossier par module.
4. Fonctionnalités développées : faire la démo en suivant le guide d'utilisation.
5. Difficultés rencontrées (idées) :
   - L'upload de photos (`move_uploaded_file` + `enctype="multipart/form-data"`).
   - La suppression en cascade des messages/favoris quand on supprime une annonce.
   - Le hachage et la vérification du mot de passe (`password_hash` / `password_verify`).
6. Bilan : compétences acquises (PHP, SQL, sessions, sécurité de base) et points d'amélioration (passer en PDO, architecture MVC, AJAX...).

**Points techniques à savoir expliquer :**
- `session_start()` et `$_SESSION` : comment on retient qu'un utilisateur est connecté.
- `password_hash` / `password_verify` : le mot de passe n'est jamais stocké en clair.
- Les **requêtes préparées** (`mysqli_prepare` + `bind_param`) : protection contre l'injection SQL.
- La méthode `GET` pour le filtrage (visible dans l'URL) vs `POST` pour les formulaires sensibles.
- Le toggle des favoris : on vérifie d'abord si le favori existe, puis on ajoute OU on supprime.

---

## 7. Tableau récapitulatif des fonctionnalités

| Module | Fonctionnalité                          | Points | Statut       |
|--------|-----------------------------------------|--------|--------------|
| 1      | Créer un compte (sécurisé)              | 2      | Fait / Testé |
| 1      | Se connecter (sécurisé)                 | 2      | Fait / Testé |
| 2      | Créer une annonce                       | 2      | Fait / Testé |
| 2      | Modifier une annonce                    | 2      | Fait / Testé |
| 2      | Supprimer une annonce (confirmation)    | 2      | Fait / Testé |
| 3      | Voir la liste des annonces (cartes)     | 2      | Fait / Testé |
| 3      | Voir le détail d'une annonce            | 1      | Fait / Testé |
| 4      | Filtrer les annonces                    | 2      | Fait / Testé |
| 4      | Mettre des annonces en favoris          | 2      | Fait / Testé |
| 5      | Envoyer un message                      | 2      | Fait / Testé |
| 5      | Consulter ses messages                  | 2      | Fait / Testé |

**Total fonctionnalités de base : 21 points (noté sur 20).**

> Pensez à tester chaque fonctionnalité une dernière fois avant la soutenance et à passer le statut en "Testé".
