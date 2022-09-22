#!/bin/bash
set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" <<-EOSQL
    CREATE DATABASE parthenon_test;
    GRANT ALL PRIVILEGES ON DATABASE parthenon_test TO appuser;
EOSQL
