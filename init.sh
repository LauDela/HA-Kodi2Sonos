
FOLDER=/share/kodi2sonos

mkdir -p $FOLDER

chmod -R 777 $FOLDER

php /var/www/html/tartistsV2.php >> $FOLDER/kodi2sonos.log &

apache2-foreground