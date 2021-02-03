export user=$(id -u):$(id -g) && \
docker-compose exec php php artisan db:seed
