Contacts directory
========================

1) Installing
-------------

```
git clone git@github.com:misak113/contactsDirectory.git
cd contactsDirectory
composer update
php app/console doctrine:schema:update --force
php app/console server:run
```

- and then go to broser & type http://localhost:8000/contacts-directory
