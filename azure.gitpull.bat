@echo off
set SSH_SERVER=74.234.18.118
set SSH_USER=groupe3
set SSH_PASSWORD=HeticGroupe3!

echo Connecting to SSH server %SSH_SERVER%...
ssh -p %SSH_PASSWORD% ssh %SSH_USER%@%SSH_SERVER% "cd /var/www/html/serveur-nginx && git pull"
echo Git pull command completed.
