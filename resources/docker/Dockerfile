FROM alpine:3.7
MAINTAINER Thornton Phillis (Thornton@xedi.com)

# ENV

ENV NGINX_VERSION 1.12.2
ENV PHP_VERSION 7.1.16-r1
ENV SUPERVISOR_LOG_ROOT /var/log/supervisor
ENV NGINX_DOCUMENT_ROOT /web/html
ENV APP_URL localhost

# Install Dependencies

RUN apk add --no-cache --virtual .build-deps \
	gcc \
	g++ \
	make

RUN apk add --no-cache \
	tzdata \
	curl \
	bash \
	libc-dev \
	openssl-dev \
	pcre-dev \
	zlib-dev \
	linux-headers \
	gnupg \
	libxslt-dev \
	gd-dev \
	imagemagick \
	geoip-dev

RUN apk add --no-cache supervisor \
	&& mkdir -p $SUPERVISOR_LOG_ROOT

# Install Nginx

RUN GPG_KEYS=B0F4253373F8F6F510D42178520A9993A1C052F8 \
	&& CONFIG="\
		--prefix=/etc/nginx \
		--sbin-path=/usr/sbin/nginx \
		--modules-path=/usr/lib/nginx/modules \
		--conf-path=/etc/nginx/nginx.conf \
		--error-log-path=/var/log/nginx/error.log \
		--http-log-path=/var/log/nginx/access.log \
		--pid-path=/var/run/nginx.pid \
		--lock-path=/var/run/nginx.lock \
		--http-client-body-temp-path=/var/cache/nginx/client_temp \
		--http-proxy-temp-path=/var/cache/nginx/proxy_temp \
		--http-fastcgi-temp-path=/var/cache/nginx/fastcgi_temp \
		--http-uwsgi-temp-path=/var/cache/nginx/uwsgi_temp \
		--http-scgi-temp-path=/var/cache/nginx/scgi_temp \
		--user=nginx \
		--group=nginx \
		--with-http_ssl_module \
		--with-http_realip_module \
		--with-http_addition_module \
		--with-http_sub_module \
		--with-http_dav_module \
		--with-http_flv_module \
		--with-http_mp4_module \
		--with-http_gunzip_module \
		--with-http_gzip_static_module \
		--with-http_random_index_module \
		--with-http_secure_link_module \
		--with-http_stub_status_module \
		--with-http_auth_request_module \
		--with-http_xslt_module=dynamic \
		--with-http_image_filter_module=dynamic \
		--with-http_geoip_module=dynamic \
		--with-threads \
		--with-stream \
		--with-stream_ssl_module \
		--with-stream_ssl_preread_module \
		--with-stream_realip_module \
		--with-stream_geoip_module=dynamic \
		--with-http_slice_module \
		--with-mail \
		--with-mail_ssl_module \
		--with-compat \
		--with-file-aio \
		--with-http_v2_module \
	" \
	&& addgroup -S nginx \
	&& adduser -D -S -h /var/cache/nginx -s /sbin/nologin -G nginx nginx \
	&& curl -fSL https://nginx.org/download/nginx-$NGINX_VERSION.tar.gz -o nginx.tar.gz \
	&& curl -fSL https://nginx.org/download/nginx-$NGINX_VERSION.tar.gz.asc  -o nginx.tar.gz.asc \
	&& export GNUPGHOME="$(mktemp -d)" \
	&& found=''; \
	for server in \
		ha.pool.sks-keyservers.net \
		hkp://keyserver.ubuntu.com:80 \
		hkp://p80.pool.sks-keyservers.net:80 \
		pgp.mit.edu \
	; do \
		echo "Fetching GPG key $GPG_KEYS from $server"; \
		gpg --keyserver "$server" --keyserver-options timeout=10 --recv-keys "$GPG_KEYS" && found=yes && break; \
	done; \
	test -z "$found" && echo >&2 "error: failed to fetch GPG key $GPG_KEYS" && exit 1; \
	gpg --batch --verify nginx.tar.gz.asc nginx.tar.gz \
	&& rm -rf "$GNUPGHOME" nginx.tar.gz.asc \
	&& mkdir -p /usr/src \
	&& tar -zxC /usr/src -f nginx.tar.gz \
	&& rm nginx.tar.gz \
	&& cd /usr/src/nginx-$NGINX_VERSION \
	&& ./configure $CONFIG --with-debug \
	&& make -j$(getconf _NPROCESSORS_ONLN) \
	&& mv objs/nginx objs/nginx-debug \
	&& mv objs/ngx_http_xslt_filter_module.so objs/ngx_http_xslt_filter_module-debug.so \
	&& mv objs/ngx_http_image_filter_module.so objs/ngx_http_image_filter_module-debug.so \
	&& mv objs/ngx_http_geoip_module.so objs/ngx_http_geoip_module-debug.so \
	&& mv objs/ngx_stream_geoip_module.so objs/ngx_stream_geoip_module-debug.so \
	&& ./configure $CONFIG \
	&& make -j$(getconf _NPROCESSORS_ONLN) \
	&& make install \
	&& rm -rf /etc/nginx/html/ \
	&& mkdir /etc/nginx/conf.d/ \
	&& mkdir -p /usr/share/nginx/html/ \
	&& install -m644 html/index.html /usr/share/nginx/html/ \
	&& install -m644 html/50x.html /usr/share/nginx/html/ \
	&& install -m755 objs/nginx-debug /usr/sbin/nginx-debug \
	&& install -m755 objs/ngx_http_xslt_filter_module-debug.so /usr/lib/nginx/modules/ngx_http_xslt_filter_module-debug.so \
	&& install -m755 objs/ngx_http_image_filter_module-debug.so /usr/lib/nginx/modules/ngx_http_image_filter_module-debug.so \
	&& install -m755 objs/ngx_http_geoip_module-debug.so /usr/lib/nginx/modules/ngx_http_geoip_module-debug.so \
	&& install -m755 objs/ngx_stream_geoip_module-debug.so /usr/lib/nginx/modules/ngx_stream_geoip_module-debug.so \
	&& ln -s ../../usr/lib/nginx/modules /etc/nginx/modules \
	&& strip /usr/sbin/nginx* \
	&& strip /usr/lib/nginx/modules/*.so \
	&& rm -rf /usr/src/nginx-$NGINX_VERSION \
	\
	# Bring in gettext so we can get `envsubst`, then throw
	# the rest away. To do this, we need to install `gettext`
	# then move `envsubst` out of the way so `gettext` can
	# be deleted completely, then move `envsubst` back.
	&& apk add --no-cache --virtual .gettext gettext \
	&& mv /usr/bin/envsubst /tmp/ \
	\
	&& runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' /usr/sbin/nginx /usr/lib/nginx/modules/*.so /tmp/envsubst \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)" \
	&& apk add --no-cache --virtual .nginx-rundeps $runDeps \
	&& apk del .gettext \
	&& mv /tmp/envsubst /usr/local/bin/ \
	# forward request and error logs to docker log collector
	&& ln -sf /dev/stdout /var/log/nginx/access.log \
	&& ln -sf /dev/stderr /var/log/nginx/error.log

# Install PHP

RUN apk add --update --no-cache \
        php7-session>=${PHP_VERSION} \
        php7-mcrypt>=${PHP_VERSION} \
        php7-openssl>=${PHP_VERSION} \
        php7-json>=${PHP_VERSION} \
        php7-dom>=${PHP_VERSION} \
        php7-zip>=${PHP_VERSION} \
        php7-bcmath>=${PHP_VERSION} \
        php7-gd>=${PHP_VERSION} \
        php7-odbc>=${PHP_VERSION} \
        php7-gettext>=${PHP_VERSION} \
        php7-xmlreader>=${PHP_VERSION} \
        php7-xmlwriter>=${PHP_VERSION} \
        php7-xmlrpc>=${PHP_VERSION} \
        php7-xml>=${PHP_VERSION} \
        php7-simplexml>=${PHP_VERSION} \
        php7-bz2>=${PHP_VERSION} \
        php7-iconv>=${PHP_VERSION} \
        php7-curl>=${PHP_VERSION} \
        php7-ctype>=${PHP_VERSION} \
        php7-pcntl>=${PHP_VERSION} \
        php7-posix>=${PHP_VERSION} \
        php7-phar>=${PHP_VERSION} \
        php7-opcache>=${PHP_VERSION} \
        php7-mbstring>=${PHP_VERSION} \
        php7-fileinfo>=${PHP_VERSION} \
        php7-tokenizer>=${PHP_VERSION} \
        php7-opcache>=${PHP_VERSION} \
        php7-pdo>=${PHP_VERSION} \
        php7-mysqli>=${PHP_VERSION} \
        php7-pdo_mysql>=${PHP_VERSION} \
        php7-pear>=${PHP_VERSION} \
        php7-fpm>=${PHP_VERSION} \
        php7-mbstring>=${PHP_VERSION} \
        php7-imagick>=${PHP_VERSION} \
    	php7-dev>=${PHP_VERSION}

RUN rm -f /var/cache/apk/* \
    && mkdir -p /opt/utils

RUN echo 'date.timezone = "Europe/London"' >> /etc/php7/php.ini

RUN sed -i 's/;clear_env/clear_env/' /etc/php7/php-fpm.d/www.conf
RUN sed -i 's/memory_limit = 128M/memory_limit = 512M/' /etc/php7/php.ini
RUN sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 512M/' /etc/php7/php.ini
RUN sed -i 's/post_max_size = 8M/post_max_size = 512M/' /etc/php7/php.ini

# Files

COPY resources/docker/root /
WORKDIR $NGINX_DOCUMENT_ROOT
COPY src/ $NGINX_DOCUMENT_ROOT

RUN sed -i "s/REPLACEMENT_SERVER_NAME/$APP_URL/g" /etc/nginx/conf.d/default.conf

# Clean Up

RUN apk del .build-deps

# Default Command

ENTRYPOINT ["/run/start.sh"]

