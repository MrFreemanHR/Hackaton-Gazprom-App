#!/bin/bash
sudo docker-compose up -d
sudo docker exec gazprom-webserver bash -c "cd back-end && composer update"
sudo docker exec gazprom-webserver bash -c "cd back-end && symfony server:start -d"
