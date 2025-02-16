FROM php:8.3-fpm

# 必要な依存関係のインストール
RUN apt-get update && apt-get install -y \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    default-mysql-client \
    zlib1g-dev \
    libzip-dev \
    unzip \
    curl \
    gnupg \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql zip bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Stripe CLIのGPGキーを追加
RUN curl -fsSL https://packages.stripe.dev/api/security/keypair/stripe-cli-gpg/public | gpg --dearmor | tee /usr/share/keyrings/stripe.gpg > /dev/null

# Stripe CLIのリポジトリを追加
RUN echo "deb [signed-by=/usr/share/keyrings/stripe.gpg] https://packages.stripe.dev/stripe-cli-debian-local stable main" | tee /etc/apt/sources.list.d/stripe.list

# Stripe CLIのインストール
RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y stripe

# その他の設定（既存の内容を保持）
COPY php.ini /usr/local/etc/php/

# Composerのインストール
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer self-update

# 非ルートユーザーの作成
RUN groupadd -g 1000 appuser && useradd -u 1000 -g appuser -m appuser

# アプリケーションディレクトリの作成と権限設定
RUN mkdir -p /var/www && chown appuser:appuser /var/www

WORKDIR /var/www

# 非ルートユーザーに切り替え
USER appuser

# アプリケーション実行コマンド（必要に応じて調整）
CMD ["php-fpm"]