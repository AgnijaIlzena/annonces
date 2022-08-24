# PROJET: annonces  / Click & Fit

composer install

## data base
symfony console doctrine:database:create

## migration
symfony console make:migration
symfony console doctrine:migration:migrate
symfony console d:s:u --force

## fixtures
composer require orm-fixtures --dev
composer require fzaninotto/faker
symfony console doctrine:fixtures:load
