# Devenir DevOps (backend)

Ceci est un projet de formation pour devenir DevOps.

Le but de ce backend est mettre à disposition une API de type Rest et d'y exposer les ressources d'aprentissage.


Il est (actuellement) écrit pour PHP (8.1) en Symfony 6.1

N'hésitez pas à prendre part au développement.

Le but de ce projet est également pédagogique, il permettra d'étudier plusieurs mécanismes important à l'apprentissage du métier de DevOps Engineer Web. (Oauth, Dockerization, Kubernetes, Cloud, etc..).



### Authentification

C'est AWS Cognito qui gère l'authentification.
Seule une vérification de la signature de la requête (avec JWT) est effectuée sur cette API.
Une duplication de l'email est effectuée dans la base de donnée pour les fonctions communautaires et l'inscription à la futur newsletter..