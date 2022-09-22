FROM postgres:13.3-alpine3.14
COPY init.sql /docker-entrypoint-initdb.d/
EXPOSE 5432
CMD ["postgres"]