#  Projet AppTuteur 

Ce projet contient l'application **AppTuteur**, développée avec **Symfony**, **Docker**, et **MySQL**.  

Cette application permet à un tuteur de :  
1. Se connecter et se déconnecter  
2. Gérer les étudiants qu’il suit  
3. Planifier des visites  
4. Rédiger des comptes-rendus  
5. Visualiser l’historique des visites  

---

##  Prérequis

Avant de commencer, assurez-vous d’avoir installé :  
1. [Docker](https://www.docker.com/)  
2. [Composer](https://getcomposer.org/)  
3. [Symfony CLI](https://symfony.com/download) 

---

##  Installation du projet

### 1. Cloner le dépôt principal

```bash
git clone https://github.com/MrBrain314/Projet_Apptuteur.git
```


### 2. Accéder au dossier du projet

```bash
cd Projet_Apptuteur
```

### 3. Lancer l'environnement Docker

#### Arrêter les conteneurs existants

```bash
docker compose down --remove-orphans
```

#### Construire les conteneurs sans cache

```bash
docker compose build --no-cache app
```

#### Relancer les conteneurs si nécessaire

```bash
docker compose up -d
```

##  Installation des dépendances Symfony


### 4. Entrer dans le conteneur PHP

```bash
docker exec -it symfony_app bash
```

### 5. Installer les dépendances

```bash
cd apptuteur
composer install
```

##  Démarrer le serveur Symfony

### 6. Accéder à la base de données via l’interface graphique

http://localhost:8000/

### 7. Lancer le serveur

Depuis ta machine ou dans le conteneur :

```bash
symfony serve:start
```

##  Créer un tuteur via l'API

### 8. Créer un tuteur avec une requête POST /api/tuteurs

```json
{
    "nom": "Durand",
    "prenom": "Alice",
    "email": "alice.durand@example.com",
    "telephone": "0601020304"
}
```

##  Connexion à l’espace /login

### 9. Accéder à la page de connexion

http://localhost:8000/login

### 10. Se connecter

Utiliser l’email alice.durand@example.com et n’importe quel mot de passe (pour l’instant, mot de passe non obligatoire).


##  Captures d'écran

| DASHBOARD | LOGIN | ETUDIANTS | AJOUT ETUDIANT |
| :---------------------: | :------------------: | :------------------: | :--------------------: |
| <img src="https://github.com/MrBrain314/Apptuteur_Project/blob/main/Captures/DASHBOARD.png?raw=true" width="300"> | <img src="https://github.com/MrBrain314/Apptuteur_Project/blob/main/Captures/Login.png?raw=true" width="300"> | <img src="https://github.com/MrBrain314/Apptuteur_Project/blob/main/Captures/Etudiants.png?raw=true" width="300"> | <img src="https://github.com/MrBrain314/Apptuteur_Project/blob/main/Captures/Ajout%20Etudiant.png?raw=true" width="300"> |

| MODIFICATION ETUDIANT | VISITES | AJOUT VISITE | MODIFIER VISITE |
| :---------------------: | :-----------------: | :-----------------: | :-----------------: |
| <img src="https://github.com/MrBrain314/Apptuteur_Project/blob/main/Captures/Modifier%20Etudiant.png?raw=true" width="300"> | <img src="https://github.com/MrBrain314/Apptuteur_Project/blob/main/Captures/Les%20visites.png?raw=true" width="300"> | <img src="https://github.com/MrBrain314/Apptuteur_Project/blob/main/Captures/Ajouter%20une%20visites.png?raw=true" width="300"> | <img src="https://github.com/MrBrain314/Apptuteur_Project/blob/main/Captures/Modifier%20une%20visite.png?raw=true" width="300"> |

| COMPTE RENDU | COMPTE RENDU PDF |
| :---------------------: | :---------------------: |
| <div align="center"><img src="https://github.com/MrBrain314/Apptuteur_Project/blob/main/Captures/Compte%20rendu%20.png?raw=true" width="300"></div> | <div align="center"><img src="https://github.com/MrBrain314/Apptuteur_Project/blob/main/Captures/Compte%20rendu%20PDF.png?raw=true" width="300"></div> |





## Contributeurs

- Bastou OURO-TAGBA
- Sidiya KHABAZ









