export user=$(id -u):$(id -g) && \
docker-compose up --build -d && \
docker exec -it diffweb_php bash
