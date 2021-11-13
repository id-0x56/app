#!/bin/bash

set -e
set -u

psql -v ON_ERROR_STOP=1 --username 'sail' --dbname 'postgres' << EOF
    CREATE DATABASE app_test;
    GRANT ALL PRIVILEGES ON DATABASE app_test TO sail;
EOF
