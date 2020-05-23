# Plugin vis et boulons pour l'Auberge des blogueurs

## Data

### Ajout d'un champ room_id (integer) dans la table user

Par convention :

* 0 = utilisateur non affecté
* 1 à 999 = numéro de la chambre (résident) à laquelle est affectée l'utilisateur
* 1000+ = numéro du membre du personnel correspondant à l'utilisateur

### Ajout d'un champ staff_role (chaîne de caractère) dans la table user

Par convention :

Seuls les membres du personnel (numéro de chambre >= 1000) peuvent avoir un rôle défini qui sera affiché en lieu et place du numéro de chambre qui leur sera attribué.

## Balises template

Exemple :

```
<h3 class="{{tpl:AuthorRoomClass}}">{{tpl:AuthorRoom}}</h3>
```

### {{tpl:AuthorRoom [role="1"]}}

Fournit l'intitulé en fonction du numéro de chambre affecté à l'auteur du billet :

* "" si pas de numéro de chambre affectée
* "Chambre nnn" si c'est un résident
* "Membre du personnel" si c'est un membre du staff

Si l'attribut ```role="1"``` est ajouté alors le rôle de l'auteur, si celui-ci est connu, sera affiché en lieu et place de la mention "Membre du personnel".

### {{tpl:AuthorRoomClass}}

Fournit les classes associées :

* "" si pas de numéro de chambre affectée
* "room room_nnn" si c'est un résident (chambres 1 à 999)
* "staff staff_nnn" si c'est un membre du staff (nnn étant égal au numéro de chambre - 999)

### {{tpl:CommentIfEven}} et {{tpl:PingIfEven}}

Retourne "even" (ou le contenu de l'attribut return="...") si c'est un commentaire/rétrolien pair.

### {{tpl:CommentIfMe}}

Le plugin limite la vérification à l'email, le site est ignoré.

### {{tpl:BlogShortname}}

Retourne la valeur de la constance DC_BLOG_SHORTNAME définie par exemple dans le fichier inc/condig.php, ou l'id du blog si elle ne l'est pas.

Exemple d'utilisation :

```html
<link rel="stylesheet" type="text/css" href="{{tpl:BlogThemeURL}}/overwrite-{{tpl:BlogShortname}}.css" media="screen" />
```

## Divers

* Le module Entrée rapide n'est pas affiché pour les utilisateurs standards, hors admins et superadmin.
* Le favicon std de l'admin est remplacé par l'affichage d'un favicon spécifique si option cochée (pref user / superadmin)
* Un URL handler a été mis en place pour rediriger les archives mensuelles vers la page des archives globales, avec un accès direct au mois et année demandés.
* Un module de tableau de bord est affiché en permanence avec :
  * Le pseudo utilisé (signature publique des textes) en titre du module
  * Le numéro de chambre affectée (résidents ou staff)
  * Les dates d'arrivées et de départs si elles sont connues
  * L'email utilisé (masqué publiquement)

## Évolutions

La prochaine 2.17 de Dotclear ajoutera de quoi trier sur des champs supplémentaires (liste d'utilisateurs côté administration) et donc sur le numéro de chambre ; le plugin est déjà codé pour en tenir compte.
