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

## Évolutions

La prochaine 2.17 de Dotclear ajoutera de quoi trier sur des champs supplémentaires (liste d'utilisateurs côté administration) et donc sur le numéro de chambre ; le plugin est déjà codé pour en tenir compte.

Deux balises sont intégrées dans le plugin, balises qui seront dans la 2.17 :

### {{tpl:CommentIfEven}} et {{tpl:PingIfEven}}

Retourne "even" (ou le contenu de l'attribut return="...") si c'est un commentaire/rétrolien pair.
