# Authentication
This documentation explains how authentication works on the site.

## Security set up
The security system is configured in app/config/security.yml and look like this :
```yaml
security:
    encoders:
        AppBundle\Entity\User: bcrypt

    providers:
        app_user_provider:
            entity:
                class: AppBundle\Entity\User
                property: email

    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false

        main:
            anonymous: ~
            pattern: ^/
            logout:
                path: logout
            guard:
                authenticators:
                    - AppBundle\Security\LoginFormAuthenticator

    access_control:
        - { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/users, roles: ROLE_ADMIN }
        - { path: ^/, roles: ROLE_USER }
```
### Firewalls
The `firewalls` key is the heart of the security configuration. The `dev` firewall isn't important, it just makes sure that Symfony's development tools - which live under URLs like `/_profiler` and `/_wdt` aren't blocked by the security.

#### The main firewall
All other URLs will be handled by the `main` firewall.
In this case, the `pattern` key in the `main` firewall is equal to `^/`, which means that it matches all the URLs.
But this does not mean that every URL requires authentication :  
the `anonymous` key with the value `~` means that you can be authenticated as anonymous. (in fact, we'll see later that all routes require authentication, except the login page.)

#### Log out
The firewall handle the log out system automatically with the `logout` key.
```yaml
# app/config/security.yml
security:
    # ...

    firewalls:
        main:
            # ...
            logout:
                path: logout

```
The `path` key with the value `logout` means that the route name for logging out is "logout".  
We can see it in `app/config/routing.yml` :
```yaml
# app/config/routing.yml
logout:
    path: /logout
```
### Custom Authentication System with Guard
The Guard component is used in this case to customize the log in system with a token to counter CSRF vulnerabilities.
```yaml
# app/config/security.yml
security:
    # ...

    firewalls:
        main:
            # ...
            guard:
                authenticators:
                    - AppBundle\Security\LoginFormAuthenticator
```
The class responsible for managing the login form is entered in the `authenticators` key : `AppBundle\Security\LoginFormAuthenticator`

In the `LoginFormAuthenticator` class we can customize the Exceptions, redirection and messages.

Examples :
```php
public function getUser($credentials, UserProviderInterface $userProvider)
{
    //...
    $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);
    if (!$user) {
        // fail authentication with a custom error
        throw new CustomUserMessageAuthenticationException('Email introuvable.');
    }
    return $user;
}
```
```php
public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
{
    if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
        return new RedirectResponse($targetPath);
    }

    // custom redirection after success log in
    // we can even add a success message
    return new RedirectResponse($this->urlGenerator->generate('homepage'));
}
```
In the Security Controller, we have access to these errors like this :
```php
public function loginAction()
{
    //...

    $authenticationUtils = $this->get('security.authentication_utils');

    $error = $authenticationUtils->getLastAuthenticationError();
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render('security/login.html.twig', array(
        'last_username' => $lastUsername,
        'error'         => $error,
    ));
}
```

### Providers
User providers are PHP classes related to Symfony Security that have two jobs: **Reload the User from the Session** & **Load the User for some Feature**

In the `providers` key we have an app_user_provider.  
This is the most common user provider for traditional web applications. Users are stored in a database and the user provider uses Doctrine to retrieve them:
```yaml
# app/config/security.yml
    # ...

    providers:
        users:
            entity:
                # the class of the entity that represents users
                class: AppBundle\Entity\User
                # the property to query by - e.g. username, email, etc
                property: email

    # ...
```

### Access control
The most basic way to secure part of the application is to secure an entire URL pattern.
```yaml
# app/config/security.yml
security:
    # ...

    firewalls:
        # ...
        main:
            # ...

    access_control:
        # no requirement for /login* because by default we are authenticated as anonymous.
        - { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        # require ROLE_ADMIN for /users*
        - { path: ^/users, roles: ROLE_ADMIN }
        # require ROLE_USER for /*
        - { path: ^/, roles: ROLE_USER }
```
You can define as many URL patterns as you need - each is a regular expression. **BUT**, only **one** will be matched. Symfony will look at each starting at the top, and stop as soon as it finds one `access_control` entry that matches the URL.

Prepending the path with `^` means that only URLs beginning with the pattern are matched. For example, a path of `/admin` (without the `^`) would match `/admin/foo` but would also match URLs like `/foo/admin`.

#### Access Control in Templates
If you want to check if the current user has a role inside a template, use the built-in is_granted() helper function:
```twig
{% if is_granted('ROLE_ADMIN') %}
    <a href="...">Delete</a>
{% endif %}
```

## The User entity
The user is represented by the **user entity** in `/src/AppBundle/Entity/User.php`.  
The user entity has several properties :
-  **id** : the primary key
-  **username** : the name of the user, not used in the login form
-  **email** : the email of the user, used in the login form and is unique
-  **password** : the user password, encrypted by the BCRYPT algorithm
-  **roles** : the user roles, used for the access control
