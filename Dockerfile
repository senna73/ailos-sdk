FROM php:8.5-cli

# Instala dependências do sistema necessárias para extensões comuns e para o Composer
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libzip-dev \
    && docker-php-ext-install zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala o Composer (copiando o binário oficial da imagem do composer)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copia primeiro só os arquivos de dependência para aproveitar cache do Docker
COPY composer.json composer.lock* ./

RUN composer install --no-interaction --no-progress --prefer-dist

# Agora copia o restante do código
COPY . .

CMD ["php", "-a"]
