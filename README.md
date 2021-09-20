# This is my code for a marketplace written in php

The main idea is to create a local geocaching based offgrid marketplace, where people can exchange goods.
It works but is not finished. 
I stopped development because the project openbazaar was already everything i wanted this project to become. 
But if you want to create your own centraliced marketplace, this is a good base.

Feel free to contact me if you have questions or need help setting everything up.

## Setting up the marketplace:

### Install dependencies

        sudo apt install default-mysql-server nginx php-fpm php-mysql php-gd qrencode php-gnupg
        <!---
        php-gd:         for the image captchas
        qrencode:       the php qrlibrary doesnt support the '?' sign
        --->

### Download this project and place it to host

        rm -i /var/www/html/*
        wget .... && unzip ... && rm ...zip && mv .../* /var/www/html/*

### activate fast-cgi for nginx

	cd /var/www/html/
	sudo sed -i 's/index.htm /index.htm index.php /; s/#location \~ \\.php$ {/location \~ \\.php$ {/; s/#location \~ \\.php$ {/location \~ \\.php$ {/; s/#\tinclude snippets/\tinclude snippets/; s/#\tfastcgi_pass unix:/\tfastcgi_pass unix:/; s/php7.3-fpm.sock;/php7.3-fpm.sock;\n\t}/' /etc/nginx/sites-enabled/default
        sudo systemctl restart nginx.service

### Create SQL database
        
        sudo mysql_secure_installation
        sudo mysql < seed_files/mysql.seed

### Done

visit [http://localhost](localhost)

# Licence:

for using my code you have to:
- send me a link to your project
teicheld@webl.de




Enjoy the free world
