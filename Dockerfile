FROM alpine:3.4

RUN apk --update add nginx php7-fpm && \
    mkdir -p /run/nginx

ADD www /www
ADD nginx_arkstats.conf /etc/nginx/conf.d/arkstats.conf
ADD run.sh /run.sh

EXPOSE 80
CMD /run.sh
