# Audit initial

## 1. Comportement observable

L'application réalise un paiement de 143,82 via Stripe.
Ce paiement est ensuite rajouté dans la base de données avec le numéro de réservation 1001 et le status qui est passé en Confirmed ainsi que le montant total qui est assigné à la colonne total.
Ensuite un mail est envoyé à lea@example.com qui indique que la réservation 1001 est confirmé.
Et l'application indique ensuite le total final.

## 2. Problèmes identifiés

| #   | Problème                                                                                                                                             | Catégorie                 | Impact                                                                                                                                   |
| --- | ---------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- |
| 1   | **BookingService.php** -> La méthode `confirm()` gère beaucoup trop de logiques différentes.                                                         | Responsabilité            | La classe est difficile à maintenir et viole le principe de responsabilité unique                                                        |
| 2   | **BookingService.php** -> La classe instancie directement ses dépendances (`$emailService = new EmailService();`, `$stripe = new StripeClient();`).  | Couplage                  | Difficulté à changer le moyen de paiement, possibilité de tout casser car il est situé dans la classe principale de réservation          |
| 3   | **BookingService** -> Les paiements sont gérés avec des `if` et `elseif`. Ajouter un nouveau moyen de paiement oblige à venir modifier cette classe. | Couplage / lisibilité     | Le code deviendra vite illisible avec l'ajout de nouveaux moyens de paiements.                                                           |
| 4   | Absence d'interfaces, il n'y a donc aucune abstraction (ex: pour la passerelle de paiement).                                                         | Couplage                  | Le code métier est fortement dépendant d'implémentations techniques. pas besoin dans booking service d'avoir le fonctionnement de stripe |
| 5   | Absence de garde-fou. Ex : `if ($booking->passType === '3days') { $total -= 10.0; }`. On ne vérifie pas si le prix devient négatif.                  | Règles métier             | Facturer des tarifs négatifs si le prix est mal configuré. Aucune vérification des valeurs                                               |
| 6   | Utilisation nécessaire du design pattern Observer pour la confirmation de commande, car les besoins de moyens de confirmation se multiplient         | Architecture / Lisibilité | Impacte la lisbilité du code en polluant la partie confirmation de commande                                                              |

## 3. Nos trois priorités

1. 5 : **Absence de garde fou** : Possibilité de faire passer des données un peu partout dans l'application et de faire planter lors de l'exécution
2. 1 : **Pas de SRP dans BookingService.php** : La méthode `confirm()` est trop complexe, séparer les responsabilité pour plus de lisibilité et une meilleure séparation et identification des erreurs
3. 4: **Absence d'interfaces** : Mettre en place des interfaces pour pouvoir faire des abstractions et séparer la partie métier et la partie technique

## 4. Risques avant refactoring

Risque de casser la méthode `confirm()` car la plupart des informations sont gérées dedans. Possibilité de plantage d'une seule étape dans `confirm()` et dans ce cas là tout plante. Empèche l'évolution du code avec pas d'interface et d'abstraction
