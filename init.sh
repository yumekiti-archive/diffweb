export user=$(id -u):$(id -g) && \
docker-compose up --build -d && \
docker-compose exec php composer install && \
docker-compose exec php cp .env.example .env && \
docker-compose exec php php artisan cache:clear && \
docker-compose exec php php artisan config:clear && \
docker-compose exec php php artisan route:clear && \
docker-compose exec php php artisan view:clear && \
docker-compose exec php php artisan key:generate && \
docker-compose exec php php artisan migrate
