#!/bin/bash

while true; do
    socat TCP-LISTEN:1333,reuseaddr,fork SYSTEM:'python3 server.py'
done

