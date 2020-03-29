FROM php:7.3-apache 

# Set shell
SHELL ["/bin/bash", "-o", "pipefail", "-c"]

RUN \
    apk add --no-cache \
        mariadb-client=10.4.12-r0 \
        php7-gd=7.3.15-r0 \
        php7-json=7.3.15-r0 \
        php7-mbstring=7.3.15-r0 \
        php7-mysqli=7.3.15-r0 \
        php7-opcache=7.3.15-r0 


COPY 000-default.conf /etc/apache2/sites-enabled
COPY /web /var/www/html

COPY init.sh /home
RUN chmod a+x /home/init.sh

EXPOSE 7777

CMD /home/init.sh

