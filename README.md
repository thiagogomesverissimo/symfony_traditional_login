# Symfony 4 Traditional Login Form with users from database (migrations available to mysql)

This project is an basic implementation of traditional login form 
with users from database on symfony, as described in the
https://symfony.com/doc/current/security/form_login_setup.html and
some codes from [symfony demo](https://github.com/symfony/demo)

[![Symfony 4 Traditional Login Form with users from database (migrations available to mysql)](https://img.youtube.com/vi/PxCFsOSG1zg/0.jpg)](https://www.youtube.com/watch?v=PxCFsOSG1zg)

Included:

 - login and logout routes configured
 - web crud and commands to manage users in database
 - passwords encoded with bcrypt

Not Included:

 - Registration to anonymous
 - Area to user to change the own password

The main reason for this project is to be a start point to
another projects thet depends of local users to work.

# Deploy

Download lastest release:

    composer create-project thiagogomesverissimo/symfony_traditional_login 
    
Download branch master:

    composer create-project thiagogomesverissimo/symfony_traditional_login -s dev

Configure .env variables and run migrations (only for mysql users):

    php bin/console doctrine:migrations:migrate

Up server:

    php bin/console server:run

## Three suggestions to create users on database:

### 1. To use data fixtures that create two users: *admin* and *user*, same for passwords:

     php bin/console doctrine:fixtures:load

### 2. To use command:

    php bin/console app:add-user user user123
    php bin/console app:add-user admin admin123 --admin
    php bin/console app:list-users
    php bin/console app:delete-user admin
    php bin/console app:delete-user user

### 3. For learning purposes, you can use psysh:

    bin/console psysh
    $em = $container->get('doctrine')->getManager()
    $admin = new App\Entity\User
    $admin->setUsername('admin')
    $password = $container->get('security.password_encoder')->encodePassword($admin, 'admin')
    $admin->setPassword($password)
    $em->persist($admin)
    $em->flush()

