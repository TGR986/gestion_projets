# Gestion de projets — README de reprise

## Présentation

Application web interne de gestion de projets développée avec Laravel, en environnement Docker, avec :

- Laravel / PHP 8.3
- MariaDB
- Nginx
- Blade
- Tailwind CSS
- composants UI personnalisés

L'application couvre actuellement :

- les projets
- les étapes / sous-étapes
- les documents liés aux sous-étapes
- les versions de documents
- les validations de versions
- les commentaires
- les utilisateurs / rôles / permissions

---

## État actuel validé

### 1. Base utilisateurs / rôles

Architecture en place :

- `users`
- `roles`
- `utilisateur_roles`
- `role_type_document_permissions`
- `role_validation_permissions`

Point important déjà corrigé :

- la FK `utilisateur_roles.utilisateur_id` est réalignée sur `users.id`

### 2. Modèle `User`

Méthodes métier confirmées :

- `estAdmin()`
- `isAdmin()`
- `hasRole()`
- `hasValidationPermission(string $validationType)`

Relation confirmée pour les rôles :

- `roles(): BelongsToMany`
- table pivot : `utilisateur_roles`
- clé pivot utilisateur : `utilisateur_id`
- clé pivot rôle : `role_id`

### 3. Gestion des types de documents

La logique d’accès aux types de documents est centralisée dans `TypeDocument` avec :

- `allowedForUpload($user)`
- `allowedForView($user)`
- `allowedForDownload($user)`
- `userCanView($user, $typeDocumentId)`
- `userCanDownload($user, $typeDocumentId)`

### 4. Sécurité documents

Le bloc sécurité des documents est validé :

- `DocumentPolicy` homogénéisée
- `DocumentController::create()` sécurisé
- `DocumentController::store()` sécurisé
- `DocumentController::download()` sécurisé

Tests réalisés et validés :

- admin : accès formulaire OK
- admin : upload OK
- admin : téléchargement OK
- non-admin sans droit upload : 403 OK
- non-admin avec droit upload : accès OK
- non-admin avec droit upload : upload OK

### 5. Validations de versions

Types de validation utilisés :

- `validation_technique`
- `validation_administrative`
- `validation_financiere`
- `refus`

Les rôles de validation ont été harmonisés :

- `validation_technique`
- `validation_financiere`
- `validation_administrative`

Le rôle `validation_administrative` a été ajouté.

La table `role_validation_permissions` a été alimentée pour relier chaque rôle de validation à son `validation_type`.

Le filtrage du sélecteur de validation côté interface est validé.

### 6. Administration utilisateurs

Fonctionnalités admin déjà en place :

#### Dashboard

Dashboard avec cartes cliquables :

- `Projets`
- `Créer un utilisateur`
- `Utilisateurs`

#### Création utilisateur

Un admin peut créer un utilisateur avec :

- nom
- prénom
- fonction
- structure
- email
- mot de passe
- rôle

Champ retenu pour l’organisation : `structure`

Valeurs actuelles proposées :

- ADSUP
- DFIP
- CSPI
- SECAL
- Entreprises

#### Liste utilisateurs

Page admin de liste des utilisateurs en place avec affichage :

- nom
- prénom
- email
- fonction
- structure
- rôle
- statut admin

#### Modification du rôle

La route et la page d’édition du rôle existent.
Le bouton `Modifier` est présent dans la liste des utilisateurs.
La route `admin.users.edit` est branchée et fonctionnelle.

---

## État des routes

Routes générales déjà confirmées :

- dashboard : `/dashboard`
- admin : `/admin`
- projets : `/projets`
- étapes / sous-étapes
- documents
- versions
- validations

Routes admin utilisateurs désormais attendues :

- `admin.users.index`
- `admin.users.create`
- `admin.users.store`
- `admin.users.edit`
- `admin.users.update`

---

## Points techniques importants

### Table `roles`

Le modèle `Role` doit avoir :

```php
public $timestamps = false;
```

car la table `roles` ne contient pas `created_at` / `updated_at`.

### Table `role_validation_permissions`

Le modèle `RoleValidationPermission` est branché sur :

- table : `role_validation_permissions`
- champs : `role_id`, `validation_type`

### Profil utilisateur

Colonnes ajoutées dans `users` :

- `prenom`
- `fonction`
- `structure`

---

## Prochaines étapes prévues

### 1. Modifier les informations d’un utilisateur

Objectif :

- permettre à l’admin de modifier :
  - nom
  - prénom
  - fonction
  - structure
  - email
  - éventuellement le statut admin selon décision métier

### 2. Supprimer / désactiver un utilisateur

Décision à cadrer :

- suppression réelle
ou
- désactivation logique recommandée

À traiter avec prudence pour éviter :
- la perte de traçabilité
- la casse des relations
- les problèmes sur les validations et documents déjà déposés

### 3. Multi-rôles

Faire évoluer la gestion actuelle pour permettre :

- plusieurs rôles par utilisateur
- affichage clair des rôles
- formulaire admin adapté
- synchronisation propre dans `utilisateur_roles`

---

## Recommandations pour la reprise

Ordre conseillé pour la prochaine discussion :

1. modifier les informations utilisateur
2. traiter la suppression / désactivation
3. faire évoluer vers les multi-rôles

Conserver la méthode de travail habituelle :

- une modification à la fois
- ne pas supposer les colonnes si non vérifiées
- rester cohérent avec l’existant
- fournir du code prêt à copier-coller
- privilégier Laravel propre

---

## Rappel important pour la prochaine reprise

Le projet est déjà avancé. Il ne faut pas revenir sur :

- le bloc sécurité documents déjà validé
- la logique TypeDocument déjà centralisée
- le système de rôles déjà branché
- la page admin utilisateurs déjà créée

La prochaine discussion doit repartir directement sur :

- modification des informations utilisateur
- suppression / désactivation
- multi-rôles
