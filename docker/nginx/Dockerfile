FROM nginx:1.27.3 AS base

WORKDIR /var/www/html

ENV TZ=Asia/Tokyo

COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/

RUN chown -R nginx:root /var/cache/nginx \
    && chmod -R g+w /var/cache/nginx

USER nginx

FROM base AS local
