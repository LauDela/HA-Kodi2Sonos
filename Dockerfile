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
        mariadb-client=1:10.3.22-0+deb10u1 \
        php7.3-curl=7.3.11-1~deb10u1 \
        php7.3-cli=7.3.11-1~deb10u1 \
        php7.3-common=7.3.11-1~deb10u1 \
        php7.3-gd=7.3.11-1~deb10u1 \
        php7.3-json=7.3.11-1~deb10u1 \
        php7.3-mbstring=7.3.11-1~deb10u1 \
        php7.3-mysql=7.3.11-1~deb10u1 \
        php7.3-xml=7.3.11-1~deb10u1 \
        php7.3=7.3.11-1~deb10u1 


COPY 000-default.conf /etc/apache2/sites-enabled
COPY /web /var/www/html

COPY init.sh /home
RUN chmod a+x /home/init.sh

EXPOSE 7777

CMD /home/init.sh

