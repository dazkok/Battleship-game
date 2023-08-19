To set up the project, follow these steps:

1. Place the project's files into your server's main directory where you plan to host it.

2. Inside the main project folder, find the file named ".env.example" and rename it to ".env".<br/>
   Open the ".env" file and locate the database configuration section. Adjust the following rows to your empty database:

    DB_CONNECTION=mysql<br/>
    DB_HOST=127.0.0.1<br/>
    DB_PORT=3306<br/>
    DB_DATABASE=battleship<br/>
    DB_USERNAME=username<br/>
    DB_PASSWORD=password<br/>

4. Open your terminal and navigate to the project folder. Run the command "composer install".

5. Then, in the terminal, enter these two commands sequentially:

    "php artisan key:generate"<br/>
    "php artisan migrate"

Once these steps are done, you can refresh the webpage to start enjoying the game.
