#!/bin/sh

if [ "$1" = "travis" ]; then
    psql -U postgres -c "CREATE DATABASE t2recb_test;"
    psql -U postgres -c "CREATE USER t2recb PASSWORD 't2recb' SUPERUSER;"
else
    [ "$1" = "test" ] || sudo -u postgres dropdb --if-exists t2recb
    sudo -u postgres dropdb --if-exists t2recb_test
    [ "$1" = "test" ] || sudo -u postgres dropuser --if-exists t2recb
    [ "$1" = "test" ] || sudo -u postgres psql -c "CREATE USER t2recb PASSWORD 't2recb' SUPERUSER;"
    [ "$1" = "test" ] || sudo -u postgres createdb -O t2recb t2recb
    [ "$1" = "test" ] || sudo -u postgres psql -d t2recb -c "CREATE EXTENSION pgcrypto;" 2>/dev/null
    sudo -u postgres createdb -O t2recb t2recb_test
    sudo -u postgres psql -d t2recb_test -c "CREATE EXTENSION pgcrypto;" 2>/dev/null
    [ "$1" = "test" ] && exit
    LINE="localhost:5432:*:t2recb:t2recb"
    FILE=~/.pgpass
    if [ ! -f $FILE ]; then
        touch $FILE
        chmod 600 $FILE
    fi
    if ! grep -qsF "$LINE" $FILE; then
        echo "$LINE" >> $FILE
    fi
fi
