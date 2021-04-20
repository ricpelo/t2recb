#!/bin/sh

BASE_DIR=$(dirname "$(readlink -f "$0")")
if [ "$1" != "test" ]; then
    psql -h localhost -U t2recb -d t2recb < $BASE_DIR/t2recb.sql
    if [ -f "$BASE_DIR/t2recb_test.sql" ]; then
        psql -h localhost -U t2recb -d t2recb < $BASE_DIR/t2recb_test.sql
    fi
    echo "DROP TABLE IF EXISTS migration CASCADE;" | psql -h localhost -U t2recb -d t2recb
fi
psql -h localhost -U t2recb -d t2recb_test < $BASE_DIR/t2recb.sql
echo "DROP TABLE IF EXISTS migration CASCADE;" | psql -h localhost -U t2recb -d t2recb_test
