FROM yiipowered:latest as yiipowered

FROM nginx:1.19-alpine

# nginx user must be in www-data group
# to have access to PHP socket
RUN adduser nginx www-data \
    && echo "server_tokens off;" >> /etc/nginx/conf.d/privacy.conf

ADD ./local /etc/nginx/conf.d

COPY --from=yiipowered /var/www/web /var/www/web

WORKDIR /var/www

