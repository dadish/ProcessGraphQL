# syntax=docker/dockerfile:1
FROM alpine:3.18.0

RUN apk update && apk upgrade

RUN apk add \
  git \
  openssh \
  tar \
  gzip \
  ca-certificates \
  nodejs \
  npm \
  php \
  php-gd \
  php-tokenizer \
  php-dom \
  php-mysqli \
  php-xmlwriter \
  php-xml \
  php-ctype \
  composer

ENTRYPOINT ["echo", "Started!"]
