FROM php:8.2-apache

# Instala extensões do PHP necessárias para MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilita mod_rewrite (útil se o projeto usa URLs amigáveis)
RUN a2enmod rewrite

# Railway injeta a porta via variável de ambiente $PORT.
# Este script corrige o conflito de MPM (bug conhecido da imagem php-apache)
# e ajusta o Apache para escutar na porta correta antes de iniciar.
RUN echo '#!/bin/bash\n\
set -eux\n\
a2dismod mpm_event mpm_worker mpm_prefork 2>/dev/null || true\n\
rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.* /etc/apache2/mods-enabled/mpm_prefork.*\n\
a2enmod mpm_prefork\n\
sed -i "s/80/${PORT:-80}/g" /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf\n\
apache2ctl -t\n\
exec apache2-foreground' > /usr/local/bin/start-apache.sh \
    && chmod +x /usr/local/bin/start-apache.sh

# Copia o código do projeto para dentro do container
COPY . /var/www/html/

# Ajusta permissões
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["/usr/local/bin/start-apache.sh"]
