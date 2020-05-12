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
