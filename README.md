# 🛒 SymfoPop - Mercat de Segona Mà

Aplicació web de mercat de segona mà desenvolupada amb Symfony 7.
Els usuaris poden registrar-se, publicar productes, editar-los i esborrar-los.

## 🛠 Tecnologies

- **Symfony 7** - Framework PHP
- **Doctrine ORM** - Gestió de base de dades
- **Twig** - Motor de plantilles
- **Bootstrap 5** - Framework CSS
- **MySQL/MariaDB** - Base de dades

## 📋 Requisits

- PHP 8.2 o superior
- Composer
- MySQL 8.0 o MariaDB 10.4+
- Symfony CLI (recomanat)

## 🚀 Instal·lació (macOS)

### 1. Prerequisits

```bash
# Instal·lar Homebrew (si no el tens)
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Instal·lar PHP
brew install php

# Instal·lar Composer
brew install composer

# Instal·lar MySQL
brew install mysql
brew services start mysql

# Instal·lar Symfony CLI
brew install symfony-cli/tap/symfony-cli
```

### 2. Crear el projecte

```bash
# Crear projecte Symfony amb webapp (inclou Twig, Doctrine, etc.)
symfony new symfopop --webapp
cd symfopop
```


### 4. Configurar la base de dades

```bash
# Edita el fitxer .env i modifica la línia DATABASE_URL
# Exemple per MySQL local sense contrasenya:
# DATABASE_URL="mysql://root:@127.0.0.1:3306/symfopop?serverVersion=8.0.32&charset=utf8mb4"
#
# Exemple per MySQL local amb contrasenya:
# DATABASE_URL="mysql://root:laTevaContrasenya@127.0.0.1:3306/symfopop?serverVersion=8.0.32&charset=utf8mb4"
```

### 5. Crear la base de dades i executar migracions

```bash
# Crear la base de dades
php bin/console doctrine:database:create

# Executar les migracions (crea les taules)
php bin/console doctrine:migrations:migrate
```

### 6. Instal·lar dependències addicionals i carregar fixtures

```bash
# Instal·lar DoctrineFixturesBundle
composer require --dev doctrine/doctrine-fixtures-bundle

# Instal·lar Faker per generar dades realistes
composer require --dev fakerphp/faker

# Carregar les dades de prova (5 usuaris i 20 productes)
php bin/console doctrine:fixtures:load
```

### 7. Iniciar el servidor

```bash
symfony serve
```

L'aplicació estarà disponible a: **https://127.0.0.1:8000**

## 👤 Usuaris de Prova

| Email | Contrasenya |
|-------|-------------|
| user1@symfopop.com | password123 |
| user2@symfopop.com | password123 |
| user3@symfopop.com | password123 |
| user4@symfopop.com | password123 |
| user5@symfopop.com | password123 |

## 🏗 Estructura del Projecte

```
symfopop/
├── config/packages/
│   ├── security.yaml      # Configuració de seguretat
│   └── twig.yaml           # Configuració de Twig
├── src/
│   ├── Controller/
│   │   ├── HomeController.php          # Pàgina principal
│   │   ├── ProductController.php       # CRUD de productes
│   │   ├── RegistrationController.php  # Registre d'usuaris
│   │   └── SecurityController.php      # Login/Logout
│   ├── DataFixtures/
│   │   └── AppFixtures.php             # Dades de prova
│   ├── Entity/
│   │   ├── Product.php                 # Entitat producte
│   │   └── User.php                    # Entitat usuari
│   ├── Form/
│   │   ├── ProductType.php             # Formulari producte
│   │   └── RegistrationFormType.php    # Formulari registre
│   └── Repository/
│       ├── ProductRepository.php
│       └── UserRepository.php
├── templates/
│   ├── base.html.twig                  # Layout base
│   ├── product/
│   │   ├── index.html.twig             # Llistat productes
│   │   ├── show.html.twig              # Detall producte
│   │   ├── new.html.twig               # Crear producte
│   │   └── edit.html.twig              # Editar producte
│   ├── registration/
│   │   └── register.html.twig          # Formulari registre
│   └── security/
│       └── login.html.twig             # Formulari login
└── migrations/
```

## 🔒 Seguretat

- Contrasenyes hashejades amb bcrypt/argon2
- Protecció CSRF en formularis d'esborrat
- Validació de permisos (només el propietari pot editar/esborrar)
- Protecció de rutes amb `#[IsGranted('ROLE_USER')]`
- Escapament automàtic a Twig (protecció XSS)

## 📝 Funcionalitats

- ✅ Registre d'usuaris amb validacions
- ✅ Login/Logout amb "Recorda'm"
- ✅ Llistat públic de productes
- ✅ Detall de producte
- ✅ Crear producte (només usuaris autenticats)
- ✅ Editar producte (només propietari)
- ✅ Esborrar producte (només propietari)
- ✅ "Els meus productes" (llistat personal)
- ✅ Missatges flash de confirmació
- ✅ Disseny responsive amb Bootstrap 5
