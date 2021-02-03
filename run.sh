export user=$(id -u):$(id -g) && \
docker-compose up --build -d && \
docker-compose exec php composer install && \
docker-compose exec php cp .env.example .env && \
docker-compose exec php php artisan key:generate && \
docker-compose exec php php artisan migrate && \
npm install && \
npm run dev
