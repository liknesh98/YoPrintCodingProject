This project is built using docker, therefore please have docker installed before running the project. 

Once the project is cloned from the repository, run these four commands to initialize and build your docker containers. 

0) Create .env file and copy the contents from env.example

1) Start Service 
docker-compose up -d --build

2) Install Laravel dependencies inside the app container (not necessary as build the containers will run the composer install)
docker exec -it yoPrintApp composer install

Note: Make sure to check logs (docker logs yoPrintApp) to make sure composer install is finished and server is running before launching the localhost

3) Generate the Laravel Application key 
docker exec -it yoPrintApp php artisan key:generate

4) Create db 
touch database/yoPrintDB.sqlite

5) Run database migrations 
docker exec -it yoPrintApp php artisan migrate