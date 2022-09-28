@echo off

docker-compose stop
docker rm mysql8

docker-compose build
docker-compose up -d

pause