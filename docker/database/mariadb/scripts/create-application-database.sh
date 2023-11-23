#!/usr/bin/env bash

# Testing DB
mysql --user=root --password="" <<-EOSQL
    CREATE DATABASE IF NOT EXISTS testing;
    GRANT ALL PRIVILEGES ON \`testing%\`.* TO '$MYSQL_USER'@'%';
EOSQL

# Master DB
mysql --user=root --password="" <<-EOSQL
    CREATE DATABASE IF NOT EXISTS $MYSQL_DATABASE;
    GRANT ALL PRIVILEGES ON \`$MYSQL_DATABASE%\`.* TO '$MYSQL_USER'@'%';
EOSQL
