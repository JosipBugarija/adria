## Adria Electronic Evaluation Task

Back-end is Built with Laravel, Front-end with Vue & Vuetify and assets are bundled with Webpack.
Calendarific and Openaq APIs are on the same endpoint and cached for 60 minutes (ApiController.php).

## Installation

1. Create a database and inform .env file
2. Insert Calendarific API key in .env file
### Terminal
4. >_ composer install
5. >_ php artisan key:generate
6. >_ php artisan migrate