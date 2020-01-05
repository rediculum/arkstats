FROM alpine:latest
LABEL Description="ARK Survival statistics webserver"

RUN apk --update add curl nginx php7-fpm && \
    mkdir -p /run/nginx

ADD www /www
ADD nginx_arkstats.conf /etc/nginx/conf.d/arkstats.conf
ADD run.sh /run.sh

EXPOSE 80
HEALTHCHECK CMD curl -sf http://localhost/health || exit 1
CMD /run.sh
