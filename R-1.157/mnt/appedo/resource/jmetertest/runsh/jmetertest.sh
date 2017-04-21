#!/bin/bash
echo  $1 
echo  $2
cd /var/apache-jmeter-2.11/bin
./jmeter -n -t $1 -l $2

