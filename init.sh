
FOLDER=/share/kodi2sonos

mkdir -p $FOLDER

chmod -R 777 $FOLDER

php /var/www/html/start.php >> $FOLDER/kodi2sonos.log

nginx -g 'daemon off;'