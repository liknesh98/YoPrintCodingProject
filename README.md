This project is built using docker, therefore please have docker installed before running the project. 

Once the project is cloned from the repository, run these four commands to initialize and build your docker containers. 

0) Create .env file and copy the contents from env.example

1) Create db 
touch database/yoPrintDB.sqlite

2) Start Service 
docker-compose up -d --build

3) Install Laravel dependencies inside the app container (not necessary as build the containers will run the composer install)
docker exec -it yoPrintApp composer install

Note: Make sure to check logs (docker logs yoPrintApp) to make sure composer install is finished and server is running before launching the localhost

4) Generate the Laravel Application key 
docker exec -it yoPrintApp php artisan key:generate
(after this sometimes you have to execute docker-compose down -v and docker-compose up -d --build again)
this is done because when the container is built it didnt build with the APP_KEY thus, it has to be built again with the app key

5) Run database migrations 
docker exec -it yoPrintApp php artisan migrate

6) Run horizon 
docker exec -it yoPrintApp php artisan horizon:install 

after running the above command, all the config for horizon would have been created (the above command is only required to run for the first time)

run this command below to activate horizon 

docker exec -it yoPrintApp phpp artisan horizon