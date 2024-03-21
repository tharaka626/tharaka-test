## How to use

- Clone the repository with __git clone__

__Back-end__

- Go to the sub-folder `cd reservation-app`
- Copy __.env.example__ file to __.env__ and edit database credentials there
- Run __composer install__
- Run __php artisan key:generate__
- Run __php artisan migrate --seed__ (it has some seeded data for your testing)
- Run __php artisan passport:install__


__Front-end__

- Go to the sub-folder `cd reservation-frontend`
- setup backend url in src/main.js 49 line and composables records
- Run __npm install__ 
- Run __npm run serve__ 
- That's it: launch the URL that appears in the terminal - could be `http://localhost:8080`

