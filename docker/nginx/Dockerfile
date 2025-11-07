# Build stage
FROM node:latest AS node
FROM php:8.4-fpm AS builder

COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node /usr/local/bin/node /usr/local/bin/node

RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

RUN mkdir -p /usr/src
WORKDIR /usr/src

COPY --exclude=.env.production . .
COPY .env.production .env.production

RUN npm install && npm run build

# Server launch stage
FROM nginx:alpine

COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf

COPY --from=builder /usr/src/public /var/www/public
COPY .env.production .env
WORKDIR /var/www/public

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]