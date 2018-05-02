#!/bin/sh

# Define values
eonconfpath=$(readlink -f "$0")
eonconfdir=$(dirname "$eonconfpath")
eondir="/srv/eyesofnetwork"
datadir="$eondir/eorweb"
eorwebdb="eorweb"
nagiosbpdb="nagiosbp"
snmpdir="/etc/snmp"
backupdir="/etc"

# change right acces for this files
chmod 775 ${datadir}/cache
chmod 644 ${backupdir}/backup-manager.conf

# change own user for eorweb directory
chown -R root:eyesofnetwork ${datadir}*

# create the eorweb database
mysqladmin -u root --password=root66 create ${eorwebdb}
mysqladmin -u root --password=root66 create ${nagiosbpdb}

# create the database content
mysql -u root --password=root66 ${eorwebdb} < ${eonconfdir}/eorweb.sql
mysql -u root --password=root66 ${nagiosbpdb} < ${eonconfdir}/nagiosbp.sql

# Change DocumentRoot for apache
sed -i 's/^DocumentRoot.*/DocumentRoot\ \"\/srv\/eyesofnetwork\/eorweb\"/g' /etc/httpd/conf/httpd.conf

# crons for eon
cp -rf ${eonconfdir}/eonbackup /etc/cron.d/
cp -rf ${eonconfdir}/eorwebpurge /etc/cron.d/

# start the services
/etc/init.d/httpd restart   > /dev/null 2>&1

