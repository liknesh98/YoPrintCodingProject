This project is built using docker, therefore please have docker installed before running the project. 

Once the project is cloned from the repository, run these four commands to initialize and build your docker containers. 

1) Start Service 
docker-compose up -d --build

2) Install Laravel dependencies inside the app container 
docker exec -it yoPrintApp composer install

3) Generate the Laravel Application key 
docker exec -it yoPrintApp php artisan key:generate

4) Run database migrations 
docker exec -it yoPrintApp php artisan migrate

Note: Run Composer Install for the first time or when dependencies change
