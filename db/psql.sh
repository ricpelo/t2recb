#!/bin/sh

[ "$1" = "test" ] && BD="_test"
psql -h localhost -U t2recb -d t2recb$BD
