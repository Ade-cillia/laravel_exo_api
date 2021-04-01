# Laravel DE CILLIA Aurélien

## Récupérer ce projet
Se mettre dans le dossier souhaité, puis utiliser cette commande :
```bash
git clone https://github.com/Ade-cillia/laravel_exo_api.git .
```
Faire une copie du ```.env.example``` et la nommer ```.env```, puis :
```bash
composer install
php artisan migrate:fresh --seed
```

## Récupérer la library postman:
[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/5d4a39a9eb142f2726b3)

## Element query:
- Pagination+search+sort disponible sur chaque entité.
- Filter disponible sur BookController.

## 🤑🤑🤑🤑 Bonus 🤑🤑🤑🤑:
- Swagger disponible : http://localhost/api/documentation
- Autentification sur le swagger fonctionnelle (authentification via le token d'un user)
- Validation des données pour chaque post/patch (store/update)


#Bref ya tout 😋 