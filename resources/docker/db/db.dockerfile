FROM mysql:5.6
COPY import /docker-entrypoint-initdb.d/
