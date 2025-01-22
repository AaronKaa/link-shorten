.PHONY: install up down test

install:
	bash install.sh

up:
	cd link-shortener && \
	sh vendor/bin/sail up -d

down:
	cd link-shortener && \
	sh vendor/bin/sail down

test:
	cd link-shortener && \
	sh vendor/bin/sail test