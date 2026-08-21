FROM php:8.2-apache

# Instala extensões do PHP necessárias para MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilita mod_rewrite (útil se o projeto usa URLs amigáveis)
RUN a2enmod rewrite

# Copia o código do projeto para dentro do container
COPY . /var/www/html/

# Ajusta permissões
RUN chown -R www-data:www-data /var/www/html

# Railway injeta a porta via variável de ambiente $PORT.
# Este script ajusta o Apache para escutar nessa porta antes de iniciar.
RUN echo '#!/bin/bash\n\
sed -i "s/80/${PORT:-80}/g" /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf\n\
apache2-foreground' > /usr/local/bin/start-apache.sh \
    && chmod +x /usr/local/bin/start-apache.sh

EXPOSE 80

CMD ["/usr/local/bin/start-apache.sh"]
