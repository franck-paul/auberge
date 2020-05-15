# Plugin vis et boulons pour l'Auberge des blogueurs

## Data

Ajout d'un champ room_id (integer) dans la table user

Par convention:

* 0 = utilisateur non affecté
* 1 à 999 = numéro de la chambre (résident) à laquelle est affectée l'utilisateur
* 1000+ = numéro du membre du personnel correspondant à l'utilisateur

## Balises template

Exemple :

```
<h3 class="{{tpl:AuthorRoomClass}}">{{tpl:AuthorRoom}}</h3>
```

### {{tpl:AuthorRoom}}

Fournit l'intitulé en fonction du numéro de chambre affecté à l'auteur du billet :

* "" si pas de numéro de chambre affectée
* "Chambre nnn" si c'est un résident
* "Membre du personnel" si c'est un membre du staff

### {{tpl:AuthorRoomClass}}

Fournit les classes associées :

* "" si pas de numéro de chambre affectée
* "room room_nnn" si c'est un résident (chambres 1 à 999)
* "staff staff_nnn" si c'est un membre du staff (nnn étant égal au numéro de chambre - 999)

### {{tpl:CommentIfEven}} et {{tpl:PingIfEven}}

Retourne "even" (ou le contenu de l'attribut return="...") si c'est un commentaire/rétrolien pair.

### {{tpl:CommentIfMe}}

Le plugin limite la vérification à l'email, le site est ignoré.

## Divers

* Le module Entrée rapide n'est pas affiché pour les utilisateurs standards, hors admins et superadmin.
* Un URL handler a été mis en place pour rediriger les archives mensuelles vers la page des archives globales, avec un accès direct au mois et année demandés.
* Un module de tableau de bord est affiché en permanence avec :
  * Le numéro de chambre affectée (résidents ou staff)
  * Le pseudo utilisé (signature publique des textes)
  * L'email utilisé (masqué publiquement)
  * L'identité réelle (connue à l'inscription)

## Évolutions

La prochaine 2.17 de Dotclear ajoutera de quoi trier sur des champs supplémentaires (liste d'utilisateurs côté administration) et donc sur le numéro de chambre ; le plugin est déjà codé pour en tenir compte.
