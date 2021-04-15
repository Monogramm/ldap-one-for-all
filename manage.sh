#!/bin/bash
set -e

###########################################################
# Functions

log() {
    echo "[$0] [$(date +%Y-%m-%dT%H:%M:%S)] $*"
}

function ask_field() {
    local FIELD=$1
    local MESSAGE=$2
    local DEFAULT_VALUE=$3

    local TEMP=
    echo "$MESSAGE (or leave empty for default value '$DEFAULT_VALUE'):"
    read -r -e TEMP
    echo ' '
    export "$FIELD"="${TEMP:-$DEFAULT_VALUE}"
}

lc-check() {
    symfony check:requirements --dir=app
    symfony check:security --dir=app
    symfony server:ca:install
}

lc-jwt-keys() {
    JWT_PATH=${1:-app/config/jwt}

    if [ ! -e "${JWT_PATH}/public.pem" ]; then
        log "Generating keys for JWT authentication at ${JWT_PATH}..."
        export JWT_PASSPHRASE
        JWT_PASSPHRASE=${2:-P@ssw0rd}

        mkdir -p "${JWT_PATH}"
        rm -f "${JWT_PATH}"/*.pem

        openssl genpkey -out "${JWT_PATH}/private.pem" -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096 -pass "pass:${JWT_PASSPHRASE}"
        openssl pkey -in "${JWT_PATH}/private.pem" -passin "pass:${JWT_PASSPHRASE}" -out "${JWT_PATH}/public.pem" -pubout

        chmod 644 \
            "${JWT_PATH}/private.pem" \
            "${JWT_PATH}/public.pem"

        log "Keys for JWT authentication generated at ${JWT_PATH}"
    fi
}

lc-build() {
    # Backend install
    log "Backend install..."
    composer install --working-dir=app

    if [ ! -f 'app/.env.local' ]; then
        log "Init local environment..."
        cat <<EOF > 'app/.env.local'
###> app/service/encryptor ###
# Custom encryptor configuration
# Generate one with </dev/urandom tr -dc 'A-Za-z0-9+\-*_' | head -c 32 ; echo
ENCRYPTOR_KEY=$(</dev/urandom tr -dc 'A-Za-z0-9+\-*_' | head -c 32 ; echo)
###< app/service/encryptor ###

###> lexik/jwt-authentication-bundle ###
JWT_PASSPHRASE=P@ssw0rd
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
###< lexik/jwt-authentication-bundle ###

###> symfony/mailer ###
MAILER_DSN=smtp://localhost:1025
###< symfony/mailer ###

###> symfony/messenger ###
#MESSENGER_TRANSPORT_DSN=amqp://guest:guest@localhost:5672/%2f/messages
###< symfony/messenger ###

###> symfony/ldap ###
#LDAP_AUTH_HOST=localhost
#LDAP_AUTH_PORT=10389
#LDAP_AUTH_ENCRYPTION=none
#LDAP_AUTH_USERNAME_ATTRIBUTE=uid
#LDAP_AUTH_EMAIL_ATTRIBUTE=mail
#LDAP_AUTH_BASE_DN=ou=people,dc=planetexpress,dc=com
#LDAP_AUTH_IS_AD=0
#LDAP_AUTH_AD_DOMAIN=planetexpress.com
#LDAP_AUTH_USER_QUERY=(objectClass=inetOrgPerson)
#LDAP_BIND_DN=cn=admin,dc=planetexpress,dc=com
#LDAP_BIND_SECRET=GoodNewsEveryone
#LDAP_AUTH_ENABLED=0
###< symfony/ldap ###

# Paypal configuration
PAYPAL_CLIENT_ID=client_id
PAYPAL_CLIENT_SECRET=client_secret
EOF
    fi

    lc-jwt-keys 'app/config/jwt' 'P@ssw0rd'

    export JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
    export JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem

    lc-jwt-keys 'app/config/jwt/test' 'P@ssw0rd'

    log "Checking application's database status..."
    php app/bin/console doctrine:migrations:status

    if ! php app/bin/console doctrine:migrations:up-to-date; then
        log "Executing application's database migration..."
        php app/bin/console doctrine:migrations:migrate --no-interaction
        log "Application's database migrations applied."
    fi

    # Frontend install
    log "Frontend install..."
    yarn install --cwd=app
    cd app && npm install && cd ..

}

lc-start() {
    lc-start-back -d
    lc-start-front
    #lc-start-story
}

lc-start-front() {
    yarn run --cwd=app encore dev --watch "$@"
}

lc-start-story() {
    yarn run --cwd=app storybook "$@"
}

lc-start-back() {
    symfony server:start --dir=app --port=8000 "$@"
}

lc-stop-back() {
    symfony server:stop --dir=app "$@"
}

lc-test() {
    lc-test-front
    lc-test-back
}

lc-test-front() {
    cd app
    log "Stylelint on SCSS..."
    npx stylelint 'assets/styles/*.scss' --fix

    log "ESLint on TypeScript..."
    npx eslint 'assets/vue/**/*.ts' --fix

    log "Stylelint on Vue.js..."
    npx stylelint 'assets/vue/**/*.vue' --fix
    log "ESLint on Vue.js..."
    npx eslint 'assets/vue/**/*.vue' --fix
    cd ..
}

lc-test-back() {
    cd app
    log "Init test database..."
    php ./bin/console doctrine:migrations:migrate --no-interaction --env=test
    php ./bin/console doctrine:fixtures:load --no-interaction --env=test
    log "PHPUnit bug fixer..."
    php ./bin/phpunit --coverage-text "$@"
    #log "PHPStan..."
    #vendor/bin/phpstan analyse src tests
    log "PHP_CodeSniffer bug fixer..."
    vendor/bin/phpcbf src tests
    log "Psalm (with auto-fixes)..."
    vendor/bin/psalm --alter --issues=MissingParamType,MissingReturnType,InvalidReturnType,InvalidNullableReturnType
    log "Psalm..."
    vendor/bin/psalm
    log "PHP Copy/Paste detector..."
    vendor/bin/phpcpd src
    log "PHP_CodeSniffer..."
    vendor/bin/phpcs src
    log "PHPMD..."
    vendor/bin/phpmd src text cleancode,controversial,codesize,naming,design,unusedcode
    #vendor/bin/phpmd src xml phpmd.xml
    cd ..
}

lc-log-back() {
    symfony server:log --dir=app "$@"
}

lc-log-ps() {
    symfony server:list "$@"
}

lc-console() {
    #symfony console --dir=app "$@"
    php app/bin/console "$@"
}

lc-prepare-release() {
    NEW_VERSION=${1}
    if [ -z "${NEW_VERSION}" ] ; then
        log 'Missing release version!'
        return 1;
    fi
    #NEW_VERSION=$(grep '"version"' .gitmoji-changelogrc | cut -d'"' -f4)

    log 'Updating gitmoji-changelog version...'
    sed -i \
        -e "s|\"version\": \".*\"|\"version\": \"${NEW_VERSION}\"|g" \
        app/package.json app/composer.json .gitmoji-changelogrc
    sed -i \
        -e "s|-v.*';|-v${NEW_VERSION}';|g" \
        app/public/sw.js
    sed -i \
        -e "s| VERSION=.*| VERSION=${NEW_VERSION}|g" \
        .travis.yml Dockerfile.alpine Dockerfile.debian

    # Generate changelog for current version
    log "Installing dev dependencies for Changelog..."
    yarn --cwd ./app install

    log "Generating Changelog for version '${NEW_VERSION}'..."
    yarn --cwd ./app run gitmoji-changelog
    sed '/\*   Merge branch/d;s/ \\\[.*//' app/CHANGELOG.md > CHANGELOG.md
}

lc-after-release() {
    CURRENT_VERSION=$(grep '"version"' .gitmoji-changelogrc | cut -d'"' -f4)
    NEXT_VERSION=${1}

    # Update next version in existing files
    if [ -z "${NEXT_VERSION}" ]; then
        BASE_VERSION=$(echo "$CURRENT_VERSION" | cut -d. -f1-2)
        FIX_VERSION=$(echo "$CURRENT_VERSION" | cut -d. -f3)
        NEXT_VERSION="${BASE_VERSION}.$(( FIX_VERSION + 1 ))"
    fi
    sed -i \
        -e "s|\"version\": \".*\"|\"version\": \"${NEXT_VERSION}\"|g" \
        .gitmoji-changelogrc
}

lc-release() {
    VERSION=${1}

    git branch -a | grep -q " develop$" || {
      git checkout -b develop origin/develop
    }
    git checkout develop && git pull || exit 2
    lc-prepare-release "${VERSION}"
    git add CHANGELOG.md .gitmoji-changelogrc .travis.yml Dockerfile* app/*json
    git commit -m":bookmark: Release ${VERSION}"
    echo "Version ${VERSION} is now HEAD of develop."
    git push
    git checkout main && git pull || exit 2
    git merge develop && git tag "${VERSION}"
    echo "Version ${VERSION} is now HEAD of main and tagged if all went well."
    echo "Please double check and amend last commit if needed."
    echo "Finally, push the release to remote main branch:"
    echo "  $ git push"
    echo "  $ git push origin ${VERSION}"
}

lc-prepare-docker() {
    CURRENT_VERSION=$(grep '"version"' package.json | cut -d'"' -f4)

    # Update Dockerfile build args default values
    local VCS_REF
    VCS_REF=$(git rev-parse --short HEAD)
    local BUILD_DATE
    BUILD_DATE=$(date -u +"%Y-%m-%dT%H:%M:%SZ")

    sed -i \
        -e "s|ARG VERSION=.*|ARG VERSION=${CURRENT_VERSION}|g" \
        -e "s|ARG VCS_REF=.*|ARG VCS_REF=${VCS_REF}|g" \
        -e "s|ARG BUILD_DATE=.*|ARG BUILD_DATE=${BUILD_DATE}|g" \
        Dockerfile.alpine Dockerfile.debian
}

init_compose() {
    if [ ! -f '.env' ]; then
        log 'Init docker compose environment variables...'
        cp .env_template .env.tmp

        mv .env.tmp .env
    fi
    export VARIANT=alpine
    export BASE=fpm

    export DOCKER_REPO=monogramm/ldap-all-for-one-manager
    export DOCKERFILE_PATH=Dockerfile.${VARIANT}
    export DOCKER_TAG=${VARIANT}
    export IMAGE_NAME=${DOCKER_REPO}:${DOCKER_TAG}
}

dc() {
    init_compose

    docker-compose -f "$@"
}

dc-build() {
    CURRENT_VERSION=$(grep '"version"' app/package.json | cut -d'"' -f4)

    log 'Building container(s)...'
    dc "${1}" build \
        --build-arg STORIES=true \
        --build-arg VERSION="${CURRENT_VERSION}" \
        --build-arg VCS_REF=$(git rev-parse --short HEAD) \
        --build-arg BUILD_DATE=$(date -u +"%Y-%m-%dT%H:%M:%SZ") \
        "${@:2}"
}

dc-start() {
    log 'Starting container(s)...'
    dc "${1}" up -d "${@:2}"
}

dc-stop() {
    log 'Stopping container(s)...'
    dc "${1}" stop "${@:2}"
}

dc-restart() {
    log 'Restarting container(s)...'
    dc "${1}" restart "${@:2}"
}

dc-logs() {
    log 'Following container(s) logs (Ctrl + C to stop)...'
    dc "${1}" logs -f "${@:2}"
}

dc-exec() {
    log "Executing container(s) command: '${*:2}'"
    dc "${1}" exec "${@:2}"
}

dc-test() {
    log 'Executing container(s) test...'
    dc-test-front "${1}" "${2}"
    dc-test-back "${1}" "${3}"
}

dc-test-front() {
    log "Stylelint on SCSS..."
    dc-exec "${1}" "${2}" npx stylelint 'assets/styles/*.scss' --fix

    log "ESLint on TypeScript..."
    dc-exec "${1}" "${2}" npx eslint 'assets/vue/**/*.ts' --fix

    log "Stylelint on Vue.js..."
    dc-exec "${1}" "${2}" npx stylelint 'assets/vue/**/*.vue' --fix
    log "ESLint on Vue.js..."
    dc-exec "${1}" "${2}" npx eslint 'assets/vue/**/*.vue' --fix
}

dc-test-back() {
    log "Init test database..."
    dc-exec "${1}" "${2}" php ./bin/console doctrine:migrations:migrate --no-interaction --env=test
    dc-exec "${1}" "${2}" php ./bin/console doctrine:fixtures:load --no-interaction --env=test
    log "PHPUnit bug fixer..."
    dc-exec "${1}" "${2}" php ./bin/phpunit --coverage-text
    #log "PHPStan..."
    #vendor/bin/phpstan analyse src tests
    log "PHP_CodeSniffer bug fixer..."
    dc-exec "${1}" "${2}" vendor/bin/phpcbf src tests
    log "Psalm (with auto-fixes)..."
    dc-exec "${1}" "${2}" vendor/bin/psalm --alter --issues=MissingParamType,MissingReturnType,InvalidReturnType,InvalidNullableReturnType
    log "Psalm..."
    dc-exec "${1}" "${2}" vendor/bin/psalm
    log "PHP Copy/Paste detector..."
    dc-exec "${1}" "${2}" vendor/bin/phpcpd src
    log "PHP_CodeSniffer..."
    dc-exec "${1}" "${2}" vendor/bin/phpcs src
    log "PHPMD..."
    dc-exec "${1}" "${2}" vendor/bin/phpmd src text cleancode,controversial,codesize,naming,design,unusedcode
    #vendor/bin/phpmd src xml phpmd.xml
}

dc-ps() {
    log 'Checking container(s)...'
    dc "${1}" ps "${@:2}"
}

dc-down() {
    log 'Stopping and removing container(s)...'
    dc "${1}" down "${@:2}"
}

dc-console() {
    dc-exec "${1}" "${2}" php bin/console "${@:3}"
}

usage() {
    echo "usage: ./manage.sh COMMAND [ARGUMENTS]

    Commands:
      local
        local:check, check-local                Check Local env requirements and security
        local:build, build-local                Build Local env
        local:start, start-local                Start Local env (backend in background)
        local:start-front, start-local-front    Start Local env frontend
        local:start-back, start-local-back      Start Local env backend
        local:start-story, start-local-story    Start Local Storybook
        local:restart, restart-local            Retart Local env
        local:stop-back, stop-local-back        Stop Local env
        local:test, test-local                  Execute test of Local env
        local:test-front, test-front-local      Execute test of Frontend Local env
        local:test-back, test-back-local        Execute test of Backend Local env
        local:logs, logs-local                  Follow logs of Local env
        local:ps, ps-local                      List Local env servers
        local:console, console                  Send command to Local env bin/console
        local:prepare-release, prepare-release  Prepare release
        local:after-release, after-release      Update version after release
        local:prepare-docker, prepare-docker    Prepare docker build args

      dev
        dev:build, build-dev                    Build Docker Dev env
        dev:start, start-dev                    Start Docker Dev env
        dev:restart, restart-dev                Retart Docker Dev env
        dev:stop, stop-dev                      Stop Docker Dev env
        dev:test, test-dev                      Execute test of Docker Dev env
        dev:test-front, test-front-dev          Execute test of Frontend Docker Dev env
        dev:test-back, test-back-dev            Execute test of Backend Docker Dev env
        dev:logs, logs-dev                      Follow logs of Docker Dev env
        dev:exec, exec-dev                      Execute command in Docker Dev env
        dev:down, down-dev                      Stop and remove Docker Dev env
        dev:reset, reset-dev                    Stop and remove Docker Dev env, and remove all data
        dev:ps, ps-dev                          List Docker Prod env containers
        dev:console, console-dev                Send command to Docker Dev env bin/console

      prod
        prod:build, build-prod, build           Build Docker Prod env
        prod:start, start-prod, start           Start Docker Prod env
        prod:restart, restart-prod, restart     Retart Docker Prod env
        prod:stop, stop-prod, stop              Stop Docker Prod env
        prod:logs, logs-prod, logs              Follow logs of Docker Prod env
        prod:exec, exec-prod, exec              Execute command in Docker Prod env
        prod:down, down-prod, down              Stop and remove Docker Prod env
        prod:reset, reset-prod, reset           Stop and remove Docker Prod env, and remove all data
        prod:ps, ps-prod, ps                    List Docker Prod env containers
        prod:console, console-prod, console     Send command to Docker Prod env bin/console

    "
}

###########################################################
# Runtime

case "${1}" in
    # Local env
    local:check|check-local) lc-check;;
    local:build|build-local) lc-build;;
    local:start|start-local) lc-start;;
    local:start-front|start-local-front) lc-start-front "${@:2}";;
    local:start-back|start-local-back) lc-start-back "${@:2}";;
    local:start-story|start-local-story) lc-start-story "${@:2}";;
    local:stop-back|stop-local-back) lc-stop-back "${@:2}";;
    local:test|test-local) lc-test "${@:2}";;
    local:test-front|test-front-local) lc-test-front "${@:2}";;
    local:test-back|test-back-local) lc-test-back "${@:2}";;
    local:logs|logs-local) lc-log-back "${@:2}";;
    local:ps|ps-local) lc-log-ps "${@:2}";;
    local:console|console-local) lc-console "${@:2}";;
    local:prepare-release|prepare-release) lc-prepare-release "${@:2}";;
    local:after-release|after-release) lc-after-release "${@:2}";;
    local:release|release) lc-release "${@:2}";;
    local:prepare-docker|prepare-docker) lc-prepare-docker "${@:2}";;

    # DEV env
    dev:build|build-dev) dc-build 'docker-compose.yml' "${@:2}";;
    dev:start|start-dev) dc-start 'docker-compose.yml' "${@:2}";;
    dev:restart|restart-dev) dc-restart 'docker-compose.yml' "${@:2}";;
    dev:stop|stop-dev) dc-stop 'docker-compose.yml' "${@:2}";;
    dev:test|test-dev) dc-test 'docker-compose.yml' 'app_dev_encore' 'app_dev_symfony';;
    dev:test-front|test-front-dev) dc-test-front 'docker-compose.yml' 'app_dev_encore';;
    dev:test-back|test-back-dev) dc-test-back 'docker-compose.yml' 'app_dev_symfony';;
    dev:logs|logs-dev) dc-logs 'docker-compose.yml' "${@:2}";;
    dev:exec|exec-dev) dc-exec 'docker-compose.yml' "${@:2}";;
    dev:down|down-dev) dc-down 'docker-compose.yml' "${@:2}";;
    dev:reset|reset-dev) dc-down 'docker-compose.yml' "${@:2}";
    . .env;
    sudo rm -rf "${APP_HOME:-/srv/app}_dev"
    ;;
    dev:ps|ps-dev) dc-ps 'docker-compose.yml' "${@:2}";;
    dev:console|console-dev)
    dc-console 'docker-compose.yml' app_dev_symfony "${@:2}";;

    # PROD env
    prod:build|build-prod|build) dc-build "docker-compose.${BASE:-fpm}.test.yml" "${@:2}";;
    prod:start|start-prod|start) dc-start "docker-compose.${BASE:-fpm}.test.yml" "${@:2}";;
    prod:restart|restart-prod|restart) dc-restart "docker-compose.${BASE:-fpm}.test.yml" "${@:2}";;
    prod:stop|stop-prod|stop) dc-stop "docker-compose.${BASE:-fpm}.test.yml" "${@:2}";;
    prod:logs|logs-prod|logs) dc-logs "docker-compose.${BASE:-fpm}.test.yml" "${@:2}";;
    prod:exec|exec-prod|exec) dc-exec "docker-compose.${BASE:-fpm}.test.yml" "${@:2}";;
    prod:down|down-prod|down) dc-down "docker-compose.${BASE:-fpm}.test.yml" "${@:2}";;
    prod:reset|reset-prod|reset) dc-down "docker-compose.${BASE:-fpm}.test.yml" "${@:2}";
    . .env;
    sudo rm -rf "${APP_HOME:-/srv/app}"
    ;;
    prod:ps|ps-prod|ps) dc-ps "docker-compose.${BASE:-fpm}.test.yml" "${@:2}";;
    prod:console|console-prod|console)
    dc-console "docker-compose.${BASE:-fpm}.test.yml" app_backend "${@:2}";;

    # Help
    *) usage;;
esac

exit 0
