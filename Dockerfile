FROM php:8.1-cli

WORKDIR /app
COPY . .

ENV PORT=10000
EXPOSE 10000

CMD php -S 0.0.0.0:$PORT index.php
