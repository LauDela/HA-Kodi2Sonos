ARG BUILD_FROM=hassioaddons/debian-base:3.0.1
# hadolint ignore=DL3006
FROM ${BUILD_FROM}

#FROM php:7.3-apache 


# Set shell
SHELL ["/bin/bash", "-o", "pipefail", "-c"]

# Setup base
# hadolint ignore=DL3003
RUN \
    apt-get update \
    \
    && apt-get install -y --no-install-recommends \
        composer=1.8.4-1 \
        git=1:2.20.1-2+deb10u1 \
        locales=2.28-10 \
        mariadb-client=1:10.3.22-0+deb10u1 \
        nginx=1.14.2-2+deb10u1 \
        php7.3-bcmath=7.3.11-1~deb10u1 \
        php7.3-curl=7.3.11-1~deb10u1 \
        php7.3-cli=7.3.11-1~deb10u1 \
        php7.3-common=7.3.11-1~deb10u1 \
        php7.3-fpm=7.3.11-1~deb10u1 \
        php7.3-gd=7.3.11-1~deb10u1 \
        php7.3-intl=7.3.11-1~deb10u1 \
        php7.3-json=7.3.11-1~deb10u1 \
        php7.3-ldap=7.3.11-1~deb10u1 \
        php7.3-mbstring=7.3.11-1~deb10u1 \
        php7.3-mysql=7.3.11-1~deb10u1 \
        php7.3-opcache=7.3.11-1~deb10u1 \
        php7.3-readline=7.3.11-1~deb10u1 \
        php7.3-sqlite3=7.3.11-1~deb10u1 \
        php7.3-xml=7.3.11-1~deb10u1 \
        php7.3-zip=7.3.11-1~deb10u1 \
        php7.3=7.3.11-1~deb10u1 


COPY 000-default.conf /etc/apache2/sites-enabled
COPY /web /var/www/html

COPY init.sh /home
RUN chmod a+x /home/init.sh

EXPOSE 7777

CMD /home/init.sh

