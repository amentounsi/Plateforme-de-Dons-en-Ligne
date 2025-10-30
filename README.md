Description

Plateforme de Dons en Ligne est une application web permettant aux associations de publier des projets caritatifs et aux donateurs de contribuer facilement à ces projets via une interface simple et intuitive.
Elle vise à digitaliser le processus de dons en facilitant la gestion, le suivi et la transparence des contributions.


Fonctionnalités principales:

  Authentification sécurisée pour les donateurs et les associations
  Gestion des associations : profil, mise à jour et suivi des projets
  Gestion des dons : consultation, ajout, historique des dons effectués
  Tableau de bord dynamique pour chaque utilisateur
  Base de données relationnelle MySQL pour la gestion centralisée des informations
  Statistiques : montant total collecté, nombre de projets actifs, total de dons par projet
  Interface responsive en HTML, CSS et JavaScript

Technologies utilisées:
 
  Front-end :	HTML5, CSS3, JavaScript
  Back-end :	PHP
  Base de données :	MySQL
  Serveur	Apache (via XAMPP/WAMP)
   
    
Structure du projet :    
Plateforme-de-Dons-en-Ligne/
│
├── bd_projet.sql                # Script de création de la base de données
├── db.php                       # Configuration de la connexion MySQL
├── login.html / login.php       # Authentification
├── register.html / register.php # Inscription
├── dashboard_association.php    # Tableau de bord association
├── dashboard_donateur.php       # Tableau de bord donateur
├── ajouter_projet.php           # Ajout d’un projet
├── modifier_projet.php          # Modification d’un projet
├── supprimer_projet.php         # Suppression d’un projet
├── donner.php                   # Page de dons
├── voir_dons_projet.php         # Historique des dons
├── stats.php                    # Statistiques globales
├── style.css                    # Feuille de style principale
├── login.js                     # Script de la page de connexion
└── index.html                   # Page d’accueil
