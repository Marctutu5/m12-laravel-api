# 2daw-m07-geomir-backend-laravel10
Backend del projecte GeoMir de 2DAW. Curs 2023-2024.

Laravel 10 amb Debugbar, Breeze i Tailwind. Backoffice amb [Filament](https://filamentphp.com/).

## Notes de la versió 0.1

Hi ha uns fitxers extra per desplegar al servidor extern amb entorn de producció:

* index.php: Redirigeix a laravel/public/index.php
* laravel/.htaccess: Redirigeix TOTES les peticions al directori public

## Notes de la versió 0.2

### Filament

Hi ha dues formes de tenir recursos relacionats amb Filament:

 * L'administració de Posts utilitza [Relation managers](https://filamentphp.com/docs/2.x/admin/resources/relation-managers)
 * L'administració de Places utilitza [Relations](https://filamentphp.com/docs/2.x/admin/resources/getting-started#relations)

L'administració de Posts no permet la creació de recursos i recursos relacionats alhora. En canvi, l'administració de Places sí que ho permet gràcies a la combinació de [Relations](https://filamentphp.com/docs/2.x/admin/resources/getting-started#relations) i el mètode `mutateFormDataBeforeCreate` (veure [Customizing data before saving](https://filamentphp.com/docs/2.x/admin/resources/creating-records#customizing-data-before-saving)).

### Policy

Hi ha diverses formes de restringir l'accés a un recurs amb [`Policy`](https://laravel.com/docs/10.x/authorization#creating-policies). En aquesta versió, veiem les següents:

 * Posts i Places utilitzen el helper `authorizeResource` pels mètodes CRUD (index, create, store, show, edit, update i destroy)
 * Posts utilitza el helper `authorize` pels mètodes like i unlike
 * Places utilitza el middleware `can` pels mètodes favorite i unfavorite
 * Totes les vistes utilitzen les directives `@can` o `@cannot`

## Notes de la versió 1.0

### Resources

Les respostes JSON de l'API es generen a través de [Eloquent API Resources](https://laravel.com/docs/10.x/eloquent-resources), controlant l'accés als recursos amb [`Policy`](https://laravel.com/docs/10.x/authorization#creating-policies) i el helper `authorize`.

### Postman

El directori `postman` inclou els fitxers d'importació de [Postman](https://www.postman.com/): 

 * la col·lecció de peticions de l'API amb tests inclosos (Collection)
 * les variables d'entorn per realitzar les peticions (Environment)

### Vercel

Aquesta versió també inclou els arxius necessari per desplegar a [Vercel](https://vercel.com/):

 * directori `api`
 * fitxer `.vercelignore`
 * fitxer `vercel.json` (veure [PHP Runtime for Vercel](https://github.com/vercel-community/php))