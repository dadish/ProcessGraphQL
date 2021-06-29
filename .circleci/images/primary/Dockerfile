# syntax=docker/dockerfile:1
FROM alpine:3.14

RUN apk update && apk upgrade

RUN apk add \
  git \
  openssh \
  tar \
  gzip \
  ca-certificates \
  nodejs \
  npm \
  php7 \
  php7-gd \
  php7-tokenizer \
  php7-dom \
  php7-mysqli \
  php7-xmlwriter \
  php7-xml \
  composer

ENTRYPOINT ["echo", "Started!"]
