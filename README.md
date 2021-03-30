# Laravel

## Récupérer ce projet
Se mettre dans le dossier souhaité, puis utiliser cette commande :
```bash
git clone https://github.com/jperaudon/laravel_dwm14.git .
```
Faire une copie du ```.env.example``` et la nommer ```.env```, puis :
```bash
composer install
php artisan migrate --seed
```


## Exercices

### Exo 1
Créer un Controller : NavController


### Exo 2
- Arriver à ajouter une information dans une vue.
  
### Exo 3
- Ajouter une nouvele page avec un formulaire. Ce formulaire demande votre age. => Formulaire Blade
- Quand on valide le formulaire, on arrive de nouveau sur une nouvelle page, qui affiche 'Tu as [age] ans !'

### Exo 4 : séparer les auteurs de la tables "books".
Vous devrez créer une nouvelle table "author" qui contiendra principalement une colonne "name".
Il faudra ensuite trouver comment relier les tables books et authors.
Sachant que chaque livre n'a qu'un seul auteur, mais que les auteurs peuvent avoir écrit plusieurs livres.
Mettre en place le CRUD des auteurs (update étant optionnel)
Et princialement : faire en sorte que l'affichage actuel redevienne valide ! (les choix des auteurs se feront via des inputs select)
