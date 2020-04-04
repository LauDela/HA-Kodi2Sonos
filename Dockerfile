ARG BUILD_FROM=hassioaddons/base:7.0.5
# hadolint ignore=DL3006
FROM ${BUILD_FROM}

# Set shell
SHELL ["/bin/bash", "-o", "pipefail", "-c"]

# Setup base
# hadolint ignore=DL3003
RUN \
    apk add --no-cache \
        nginx=1.16.1-r6 \
        php7-curl=7.3.16-r0 \
        php7-fpm=7.3.16-r0 \
        php7-gd=7.3.16-r0 \
        php7-json=7.3.16-r0 \
        php7-mbstring=7.3.16-r0 \
        php7-mysqli=7.3.16-r0 \
        php7-opcache=7.3.16-r0 \
        php7-session=7.3.16-r0 \
        php7-zip=7.3.16-r0 \
        php7=7.3.16-r0 \
    \
    && mkdir /var/www/kodi2sonos 
    

# Copy root filesystem
COPY rootfs /
COPY kodi2sonos /var/www/kodi2sonos
# Build arguments
ARG BUILD_ARCH
ARG BUILD_DATE
ARG BUILD_REF
ARG BUILD_VERSION

# Labels
LABEL \
    io.hass.name="kodi2sonos" \
    io.hass.description="An addon for playing kodi music library on sonos via HA interface" \
    io.hass.arch="${BUILD_ARCH}" \
    io.hass.type="addon" \
    io.hass.version=${BUILD_VERSION} \
    maintainer="LauDela <laudela@gmail.com>" \
    org.label-schema.description="An addon for playing kodi music library on sonos via HA interface" \
    org.label-schema.build-date=${BUILD_DATE} \
    org.label-schema.name="kodi2sonos" \
    org.label-schema.schema-version="1.0" \
    org.label-schema.url="https://github.com/LauDela/HA-Kodi2Sonos" \
    org.label-schema.usage="https://github.com/LauDela/HA-Kodi2Sonos" \
    org.label-schema.vcs-ref=${BUILD_REF} \
    org.label-schema.vcs-url="https://github.com/LauDela/HA-Kodi2Sonos" \
    org.label-schema.vendor="Home Assistant Add-ons"
