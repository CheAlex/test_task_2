FROM ubuntu:14.04.1

MAINTAINER Alex Chernoivan <alexsnejinsky@gmail.com>

# Create docker user
RUN mkdir -p /var/www
RUN mkdir -p /home/docker
RUN useradd -d /home/docker -s /bin/bash -M -N -G www-data,sudo,root docker
RUN echo docker:docker | chpasswd
RUN usermod -G www-data,users www-data
RUN chown -R docker:www-data /var/www
RUN chown -R docker:www-data /home/docker

# Base install
RUN apt-get update && apt-get upgrade -y
RUN apt-get install -y \
    software-properties-common \
    python-software-properties \
    git \
    vim \
    nano \
    mc \
    screen \
    curl \
    zip \
    unzip \
    wget \
    supervisor \
    htop \
    tmux \
    mcrypt
COPY configs/supervisor/cron.conf /etc/supervisor/conf.d/cron.conf

# PHP
RUN apt-get install -y language-pack-en-base
RUN LC_ALL=en_US.UTF-8 add-apt-repository -y ppa:ondrej/php
RUN apt-get update 
RUN apt-get install -y \
    php7.1 \
    php-apcu \
    php-apcu-bc \
    php-imagick \
    php-memcached \
    php-pear \
    php-redis \
    php-xdebug \
    php7.1-cgi \
    php7.1-cli \
    php7.1-common \
    php7.1-ctype \
    php7.1-curl \
    php7.1-dev \
    php7.1-fpm \
    php7.1-gd \
    php7.1-imap \
    php7.1-intl \
    php7.1-json \
    php7.1-mbstring \
    php7.1-mysql \
    php7.1-pgsql \
    php7.1-soap \
    php7.1-sqlite3 \
    php7.1-xml \
    php7.1-zip
RUN rm /etc/php/7.1/cgi/php.ini
RUN rm /etc/php/7.1/cli/php.ini
RUN rm /etc/php/7.1/fpm/php.ini
COPY configs/php/php.ini /etc/php/7.1/cgi/php.ini
COPY configs/php/php.ini /etc/php/7.1/cli/php.ini
COPY configs/php/php.ini /etc/php/7.1/fpm/php.ini
COPY configs/php/xdebug.ini /etc/php/7.1/mods-available/xdebug.ini
RUN mkdir /var/log/xdebug
RUN touch /var/log/xdebug/xdebug.log
RUN chown www-data:www-data /var/log/xdebug/xdebug.log
RUN chmod 664 /var/log/xdebug/xdebug.log
RUN sed -i -e 's/listen = \/run\/php\/php7.1-fpm.sock/listen = 0.0.0.0:9000/g' /etc/php/7.1/fpm/pool.d/www.conf \
 && sed -i -e 's/user = www-data/user = docker/g'                              /etc/php/7.1/fpm/pool.d/www.conf \
 && sed -i -e 's/;daemonize = yes/daemonize = no/g'                            /etc/php/7.1/fpm/php-fpm.conf

# Phalcon
RUN curl -s https://packagecloud.io/install/repositories/phalcon/stable/script.deb.sh | sudo bash
RUN apt-get install php7.1-phalcon=3.1.2-2+php7.1
RUN git clone https://github.com/phalcon/phalcon-devtools.git /usr/local/lib/phalcon-devtools && \
    ln -s /usr/local/lib/phalcon-devtools/phalcon.php /usr/bin/phalcon && \
    chmod +x /usr/bin/phalcon

# Configs bash start
COPY configs/autostart.sh /root/autostart.sh
RUN  chmod +x /root/autostart.sh
COPY configs/bash.bashrc /etc/bash.bashrc
COPY configs/.bashrc /root/.bashrc
COPY configs/.bashrc /home/docker/.bashrc

# Install locale
RUN locale-gen en_US en_US.UTF-8 uk_UA uk_UA.UTF-8
RUN dpkg-reconfigure locales

# Install Ant
RUN sudo apt-get install -y ant

# Install Composer
RUN cd /home
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer
RUN chmod 777 /usr/local/bin/composer

# Install code standarts
RUN composer global require "squizlabs/php_codesniffer=*"
RUN composer global require "sebastian/phpcpd=*"
RUN composer global require "phpmd/phpmd=@stable"
RUN cd /usr/bin && ln -s ~/.composer/vendor/bin/phpcpd
RUN cd /usr/bin && ln -s ~/.composer/vendor/bin/phpmd
RUN cd /usr/bin && ln -s ~/.composer/vendor/bin/phpcs

# Open ports
# 1) php-fpm handler
EXPOSE 9000

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

#USER docker

# Modify hosts (172.30.0.6 - nginx container ip)
RUN echo "172.30.0.6 content.dev" >> /etc/hosts
