#!/bin/sh
set -e

###########################################################
# Functions


log() {
    echo "[$0] [$(date +%Y-%m-%dT%H:%M:%S)] $*"
}

init_file() {
    FILE=${1}
    if [ -z "${FILE}" ]; then
        log "Missing step id for docker init file!"
        exit 1
    fi

    CONTENT=${2:-$(date -u +%Y-%m-%dT%H:%M:%SZ)}

    mkdir -p "/var/www/html/.docker"
    echo "$CONTENT" \
        > "/var/www/html/.docker/init-${FILE}"
}

rm_init_file() {
    FILE=${1}
    if [ -z "${FILE}" ]; then
        log "Missing step for docker init file!"
        exit 1
    fi

    rm -f "/var/www/html/.docker/init-${FILE}"
}

init_file_exists() {
    test -e "/var/www/html/.docker/init-${1}"
}

wait_for_file() {
    FILE=${1}
    if [ -z "${FILE}" ]; then
        log "Missing file to wait for!"
        exit 1
    fi
    WAIT_TIME=0
    WAIT_STEP=${2:-30}
    WAIT_TIMEOUT=${3:--1}

    while ! init_file_exists "${FILE}" ; do
        if [ "${WAIT_TIMEOUT}" -gt 0 ] && [ "${WAIT_TIME}" -gt "${WAIT_TIMEOUT}" ]; then
            log "Init file '.docker/init-${STEP}' was not created on time!"
            exit 1
        fi

        log "Waiting create of init file '.docker/init-${FILE}'..."
        sleep "${WAIT_STEP}"
        WAIT_TIME=$(( WAIT_TIME + WAIT_STEP ))
    done
    log "Init file '.docker/init-${FILE}' found."
}

wait_init_files() {
    WAIT_FILES=${1}
    if [ -z "${WAIT_FILES}" ]; then
        log "Missing files to wait for!"
        exit 1
    fi

    for F in $(echo "$WAIT_FILES" | tr ',' ' '); do
        wait_for_file "${F}" "${WAIT_STEP}" "${WAIT_TIMEOUT}"
    done
}

wait_for_host() {
    WAIT_FOR_ADDR=${1}
    if [ -z "${WAIT_FOR_ADDR}" ]; then
        log "Missing host's address to wait for!"
        exit 1
    fi

    WAIT_FOR_PORT=${2}
    if [ -z "${WAIT_FOR_PORT}" ]; then
        log "Missing host's port to wait for!"
        exit 1
    fi

    WAIT_TIME=0
    WAIT_STEP=${3:-10}
    WAIT_TIMEOUT=${4:--1}

    while ! nc -z "${WAIT_FOR_ADDR}" "${WAIT_FOR_PORT}" ; do
        if [ "${WAIT_TIMEOUT}" -gt 0 ] && [ "${WAIT_TIME}" -gt "${WAIT_TIMEOUT}" ]; then
            log "Host '${WAIT_FOR_ADDR}:${WAIT_FOR_PORT}' was not available on time!"
            exit 1
        fi

        log "Waiting host '${WAIT_FOR_ADDR}:${WAIT_FOR_PORT}'..."
        sleep "${WAIT_STEP}"
        WAIT_TIME=$(( WAIT_TIME + WAIT_STEP ))
    done
    log "Host '${WAIT_FOR_ADDR}:${WAIT_FOR_PORT}' available."
}

wait_for_it() {
    WAIT_FOR_HOSTS=${1}
    if [ -z "${WAIT_FOR_HOSTS}" ]; then
        log "Missing hosts to wait for!"
        exit 1
    fi

    for H in $(echo "$WAIT_FOR_HOSTS" | tr ',' ' '); do
        WAIT_FOR_ADDR=$(echo "${H}" | cut -d: -f1)
        WAIT_FOR_PORT=$(echo "${H}" | cut -d: -f2)

        wait_for_host "${WAIT_FOR_ADDR}" "${WAIT_FOR_PORT}" "${WAIT_STEP}" "${WAIT_TIMEOUT}"
    done

}

# date_greater A B returns whether A > B
date_greater() {
    [ $(date -u -d "$1" -D "%Y-%m-%dT%H:%M:%SZ" +%s) -gt $(date -u -d "$2" -D "%Y-%m-%dT%H:%M:%SZ" +%s) ];
}

###########################################################
# Wait for it...

if [ -n "${WAIT_FILE}" ]; then
    wait_init_files "${WAIT_FILE}" "${WAIT_STEP}" "${WAIT_TIMEOUT}"
fi

if [ -n "${WAIT_FOR}" ]; then
    wait_for_it "${WAIT_FOR}" "${WAIT_STEP}" "${WAIT_TIMEOUT}"
fi

###########################################################
# Runtime

if [ "$(id -u)" = 0 ]; then
    log "Update user and group permissions..."
    usermod -u "${WWW_USER_ID}" www-data
    groupmod -g "${WWW_GROUP_ID}" www-data

    log "Update web app permissions..."
    chown -R www-data:www-data /var/www

    if [ ! -f /usr/local/etc/php/php.ini ]; then
        log "PHP configuration initialization..."

        cat <<EOF > /usr/local/etc/php/php.ini
date.timezone = ${PHP_INI_DATE_TIMEZONE}
memory_limit = ${PHP_MEMORY_LIMIT}
file_uploads = On
upload_max_filesize = ${PHP_MAX_UPLOAD}
max_execution_time = ${PHP_MAX_EXECUTION_TIME}
sendmail_path = /usr/sbin/sendmail -t -i
short_open_tag = off
EOF

        init_file 'php'
        log "PHP configuration initialized"
    fi

fi

installed_build_date="1970-01-01T00:00:00Z"
if [ -f '/var/www/html/.docker/build-date' ]; then
    installed_build_date=$(cat /var/www/html/.docker/build-date)
fi

image_build_date="1970-01-01T00:00:00Z"
if [ -f '/usr/src/symfony/.docker/build-date' ]; then
    image_build_date=$(cat /usr/src/symfony/.docker/build-date)
fi

if [ ! -d '/usr/src/symfony' ] && [ -d '/var/www/html' ]; then

    log "Application running from sources. Installing dependencies..."

    composer \
        --prefer-dist \
        --no-interaction \
        --no-ansi \
        --optimize-autoloader --apcu-autoloader \
        install

    npm install \
        --non-interactive

    init_file 'app'
    log "Application initialization complete (running from sources)"

elif ! init_file_exists 'app' || date_greater "$image_build_date" "$installed_build_date"; then
    log "Symfony initialization..."
    rm_init_file 'app'

    # Set a temporary maintenance mode
    mkdir -p /var/www/html/public/build
    rm -rf /var/www/html/public/build/*
    echo '<?php echo "Maintenance in progress... try again in a few minutes.";' > /var/www/html/public/index.php

    if [ "$(id -u)" = 0 ]; then
        rsync_options="-rlDog --chown www-data:root"
    else
        rsync_options="-rlD"
    fi

    if [ "${SF_PROD}" = "true" ]; then
        # Ignore development mode & configuration checkup during copy
        rsync_options="${rsync_options} --exclude /web/config.php --exclude /web/app_dev.php"
    fi

    log "Updating Symfony app..."
    rsync $rsync_options \
        --exclude /var \
        --exclude /public/uploads \
        --exclude /web \
        --delete --ignore-times \
        /usr/src/symfony/ \
        /var/www/html/

    # Force copy web directory
    if [ -d /usr/src/symfony/web ]; then
        log "Enabling Symfony 3 and older app..."
        rm -rf /var/www/html/public/*
        cp -r /usr/src/symfony/web /var/www/html/public

        if [ "${SF_PROD}" = "true" ]; then
            # Remove dev files
            rm -f \
                /var/www/html/public/app_dev.php \
                /var/www/html/public/config.php
        fi
    fi

    init_file 'app'
    log "Application initialization complete"
else
    log "Application already initialized"
fi


# Update default parameters
if [ -f /var/www/html/app/parameters.yml ]; then
    log "Updating Symfony application parameters..."

    sed -i \
        -e "s|database_host:.*|database_host: '${SF_DB_HOST}'|g" \
        -e "s|database_port:.*|database_port: '${SF_DB_PORT}'|g" \
        -e "s|database_name:.*|database_name: '${SF_DB_NAME}'|g" \
        -e "s|database_user:.*|database_user: '${SF_DB_USER}'|g" \
        -e "s|database_password:.*|database_password: '${SF_DB_PASSWORD}'|g" \
        -e "s|mailer_transport:.*|mailer_transport: '${MAILER_TRANSPORT}'|g" \
        -e "s|mailer_host:.*|mailer_host: '${MAILER_HOST}'|g" \
        -e "s|mailer_user:.*|mailer_user: '${MAILER_USER}'|g" \
        -e "s|mailer_password:.*|mailer_password: '${MAILER_PASSWORD}'|g" \
        /var/www/html/app/parameters.yml

    log "Symfony application parameters updated"
fi

if [ -z "${DATABASE_URL}" ]; then
    log "Initializing Symfony database URL..."

    if [ "${SF_DB_TYPE}" = "sqlite" ]; then
        export DATABASE_URL="sqlite://%kernel.project_dir%/var/${SF_DB_NAME:-app_db}"
    elif [ -z "${SF_DB_HOST}" ] || [ -z "${SF_DB_PORT}" ] || [ -z "${SF_DB_VERSION}" ]; then
        log "Cannot generate Symfony database URL without SF_DB_HOST, SF_DB_PORT and SF_DB_VERSION"
    elif [ -n "${SF_DB_USER}" ] && [ -n "${SF_DB_PASSWORD}" ]; then
        export DATABASE_URL="${SF_DB_TYPE}://${SF_DB_USER}:${SF_DB_PASSWORD}@${SF_DB_HOST}:${SF_DB_PORT}/${SF_DB_NAME:-app_db}?serverVersion=${SF_DB_VERSION}${SF_DB_OPTIONS}"
    else
        export DATABASE_URL="${SF_DB_TYPE}://${SF_DB_HOST}:${SF_DB_PORT}/${SF_DB_NAME:-app_db}?serverVersion=${SF_DB_VERSION}${SF_DB_OPTIONS}"
    fi

    log "Symfony database URL initialized"
fi

if [ -z "${MAILER_DSN}" ]; then
    # https://symfony.com/doc/current/mailer.html
    log "Initializing Symfony mailer DSN..."

    if [ -n "${MAILER_USER}" ] && [ -n "${MAILER_PASSWORD}" ]; then
        export MAILER_DSN="${MAILER_TRANSPORT:-smtp}://${MAILER_USER}:${MAILER_PASSWORD}@${MAILER_HOST:-mailer}:${MAILER_PORT:-465}"
    else
        export MAILER_DSN="${MAILER_TRANSPORT:-smtp}://${MAILER_HOST:-mailer}:${MAILER_PORT:-465}"
    fi

    log "Symfony mailer DSN initialized"
fi

if [ -z "${MESSENGER_TRANSPORT_DSN}" ]; then
    if [ "${MESSENGER_TRANSPORT}" = "amqp" ]; then
        # https://symfony.com/doc/current/messenger.html#amqp-transport
        log "Initializing Symfony Messenger AMQP transport..."
        export MESSENGER_TRANSPORT_DSN="amqp://${SF_RABBITMQ_USER:-guest}:${SF_RABBITMQ_PASSWORD:-guest}@${SF_RABBITMQ_HOST:-rabbitmq}:${SF_RABBITMQ_PORT:-5672}/%2f/messages"
    elif [ "${MESSENGER_TRANSPORT}" = "redis" ]; then
        # https://symfony.com/doc/current/messenger.html#redis-transport
        log "Initializing Symfony Messenger Redis transport..."
        export MESSENGER_TRANSPORT_DSN="redis://${SF_REDIS_HOST:-redis}:${SF_REDIS_PORT:-6379}/messages"
    else
        # https://symfony.com/doc/current/messenger.html#doctrine-transport
        log "Initializing Symfony Messenger Doctrine transport..."
        export MESSENGER_TRANSPORT_DSN=doctrine://default
    fi
    # XXX Call console messenger:setup-transports?

    log "Symfony messenger transport DSN initialized"
fi

# Generate SSH keys for JWT authentication
if [ ! -e "config/jwt/public.pem" ] && [ -n "${JWT_PASSPHRASE}" ]; then
    log "Generating keys for JWT authentication..."

    mkdir -p config/jwt
    rm -f config/jwt/*.pem

    export JWT_PASSPHRASE=${JWT_PASSPHRASE}
    openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096 -pass "pass:${JWT_PASSPHRASE}"
    openssl pkey -in config/jwt/private.pem -passin "pass:${JWT_PASSPHRASE}" -out config/jwt/public.pem -pubout

    export JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
    export JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem

    init_file 'jwt'
    log "Keys for JWT authentication generated"
fi

if [ -n "${SF_CLEAR_CACHE}" ]; then
    rm_init_file 'cache'

    log "Clearing application cache..."
    if [ "${SF_PROD}" = "true" ]; then
        php bin/console cache:clear --env=prod
        php bin/console cache:warmup --env=prod
    else
        php bin/console cache:clear
        php bin/console cache:warmup
    fi

    init_file 'cache'
    log "Application cache cleared"
else
    log "Wait application cache to be cleared..."
    wait_init_files 'cache'
fi

if [ -n "${DATABASE_URL}" ]; then
    log "Checking application's database status..."
    php bin/console doctrine:migrations:status

    if [ -n "${SF_INIT_DB}" ]; then
        if ! php bin/console doctrine:migrations:up-to-date; then
            log "Executing application's database migration..."
            php bin/console doctrine:migrations:migrate --no-interaction
            init_file 'db-migrations'
            log "Application's database migrations applied."
        elif ! init_file_exists 'db-migrations'; then
            init_file 'db-migrations'
        fi
    else
        wait_init_files 'db-migrations'
    fi

    # Generate default admin account if never done before
    if ! init_file_exists 'admin' && [ -n "${SF_ADMIN_PASSWD}" ]; then
        log "Generating default admin account..."

        php bin/console 'app:users:create' \
            --role=USER \
            --role=ADMIN \
            --role=SUPER_ADMIN \
            --verified \
            "${SF_ADMIN_LOGIN}" \
            "${SF_ADMIN_EMAIL}" \
            "${SF_ADMIN_PASSWD}"

        init_file 'admin'
        unset SF_ADMIN_PASSWD

        log "Default admin account generated..."
    fi
fi

log "Executing command:" "$@"
exec "$@"
