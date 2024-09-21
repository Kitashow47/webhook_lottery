# ベースイメージとして公式のPHPイメージを使用
FROM php:8.1-apache

# Apache設定のモジュールを有効化
RUN a2enmod rewrite

# 必要なPHP拡張をインストール
RUN docker-php-ext-install pdo pdo_mysql

# ドキュメントルートを設定
WORKDIR /var/www/html

# ローカルのpublicフォルダをコンテナのドキュメントルートにコピー
COPY public/ /var/www/html/

# Apacheの設定ファイルを適用
COPY ./000-default.conf /C:\Users\shoki\Desktop\lottery_pj\webhook_lottery\lottery_pj/000-default.conf
