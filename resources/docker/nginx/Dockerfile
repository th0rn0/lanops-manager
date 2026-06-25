FROM node:20-alpine AS node-builder

WORKDIR /app

COPY src/package.json src/package-lock.json ./
RUN npm ci

COPY src/ ./
RUN npm run build

FROM nginx:latest
LABEL maintainer="Thornton Phillis (dev@th0rn0.co.uk)"

WORKDIR /var/www/html

COPY src/public /var/www/html/public
COPY --from=node-builder /app/public/build /var/www/html/public/build

COPY resources/docker/nginx/default.conf /etc/nginx/conf.d/default.conf

RUN usermod -u 33 -o nginx && groupmod -g 33 -o nginx