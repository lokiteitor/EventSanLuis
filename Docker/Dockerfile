FROM php:apache

EXPOSE 80:80

RUN apt-get update && apt-get -y install curl 
RUN apt-get -y install php5-mcrypt php5-mysql
RUN docker-php-ext-install -j$(nproc) pdo_mysql json mysqli
RUN php5enmod mcrypt
RUN a2enmod rewrite
RUN chown www-data:www-data -R /var/www/html

RUN apt-get -y autoremove && apt-get clean && rm -rf /var/lib/apt/lists/*

#ENTRYPOINT ["/bin/bash"]


CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
