[![License: AGPL v3][uri_license_image]][uri_license]
[![Docs](https://img.shields.io/badge/Docs-Github%20Pages-blue)](https://monogramm.github.io/ldap-one-for-all/)
[![gitmoji-changelog](https://img.shields.io/badge/Changelog-gitmoji-blue.svg)](https://github.com/frinyvonnick/gitmoji-changelog)
[![Managed with Taiga.io](https://img.shields.io/badge/Managed%20with-TAIGA.io-709f14.svg)](https://tree.taiga.io/project/monogrammbot-monogrammldap-one-for-all/ "Managed with Taiga.io")
[![GitHub stars](https://img.shields.io/github/stars/Monogramm/ldap-one-for-all?style=social)](https://github.com/Monogramm/ldap-one-for-all)

[![Gitpod ready-to-code](https://img.shields.io/badge/Gitpod-ready--to--code-blue?logo=gitpod)](https://gitpod.io/#https://github.com/Monogramm/ldap-one-for-all)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/c0736494f8314577b7164bfcbd752780)](https://www.codacy.com/gh/Monogramm/ldap-one-for-all/dashboard?utm_source=github.com&utm_medium=referral&utm_content=Monogramm/ldap-one-for-all&utm_campaign=Badge_Grade)
[![Codacy Badge](https://app.codacy.com/project/badge/Coverage/c0736494f8314577b7164bfcbd752780)](https://www.codacy.com/gh/Monogramm/ldap-one-for-all/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Monogramm/ldap-one-for-all&amp;utm_campaign=Badge_Coverage)
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/Monogramm/ldap-one-for-all/Docker%20Image%20CI)](https://github.com/Monogramm/ldap-one-for-all/actions)

[![Docker Pulls](https://img.shields.io/docker/pulls/monogramm/ldap-one-for-all.svg)](https://hub.docker.com/r/monogramm/ldap-one-for-all/)
[![Docker Image Size (tag)](https://img.shields.io/docker/image-size/monogramm/ldap-one-for-all/latest)](https://hub.docker.com/r/monogramm/ldap-one-for-all/)

# ![icon](docs/assets/icon.png) **LDAP One-For-All**

> :alembic: :elephant: A web based LDAP Manager for everyone.

:construction: **This project is still in development!**

This project was initialized from [Monogramm/vue-symfony-starter](https://github.com/Monogramm/vue-symfony-starter).

## :blue_book: Docs

See GitHub Pages at [monogramm.github.io/ldap-one-for-all](https://monogramm.github.io/ldap-one-for-all/).

You can generate the PHP documentation using PHPDocumentor:

```bash
docker run --rm -v "${PWD}:/data" phpdoc/phpdoc:3 run
```

## :chart_with_upwards_trend: Changes

All notable changes to this project will be documented in [CHANGELOG](./CHANGELOG.md) file.

This CHANGELOG is generated with :heart: by [gitmoji-changelog](https://github.com/frinyvonnick/gitmoji-changelog).

<!--
To generate new changelog:
* update `.gitmoji-changelogrc`
* execute `gitmoji-changelog --preset generic`

-->

This project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## :bookmark: Roadmap

See [Taiga.io](https://tree.taiga.io/project/monogrammbot-monogrammldap-all-for-one-manager/ "Taiga.io monogrammbot-monogrammldap-all-for-one-manager")

## :whale: Supported Docker tags

[Dockerhub monogramm/ldap-one-for-all](https://hub.docker.com/r/monogramm/ldap-one-for-all/)

-   `latest`
-   `debian`
-   `develop`
-   `gitpod`

## Docker Development environment

You can build and run a development environment using Docker (recommended).

### :construction: Docker Development Install

```bash
./manage.sh dev:build
```

### :rocket: Docker Development Usage

```bash
./manage.sh dev:start
```

Now go to <http://localhost:8000> to access development environment using docker.
Go to <http://localhost:6006> to access the storybook.

## Docker Production environment

You can build and run a "_production-like_" environment using Docker.

### :construction: Docker Production Install

```bash
./manage.sh prod:build
```

### :rocket: Docker Production Usage

```bash
./manage.sh prod:start
```

Now go to <http://localhost:8080> to access development environment using docker.

## Local Development environment

You can run the development environment locally.

You can also use [GitPod](https://gitpod.io/) to run the local development environment: [![open in Gitpod](https://gitpod.io/button/open-in-gitpod.svg)](https://gitpod.io/#https://github.com/Monogramm/ldap-one-for-all)

If you wish to sign your commits using GitPod, follow the instructions from [gitpod-io/gitpod#666](https://github.com/gitpod-io/gitpod/issues/666#issuecomment-534347856):

-   Convert your source `.gnugpg` directory contents to base64 data: `tar -czvf - ./.gnupg | base64 -w 0`
-   Place this data into a gitpod environment variable called `GNUGPG`
-   Get your source signing key: `gpg --list-secret-keys --keyid-format LONG`
-   Place this data into a gitpod environment variable called `GNUGPG_KEY`

### :construction: Local Development Install

Requires PHP 7.4 or higher, [Composer 2](https://getcomposer.org/), [Symfony 4.4](https://symfony.com/) and [yarn](https://yarnpkg.com/) installed.
You can check your local requirements with the following command:

```bash
./manage.sh local:check
```

To install locally:

```bash
./manage.sh local:build
```

### :rocket: Local Development Usage

To start frontend and backend:

```sh
./manage.sh local:start
```

Now go to <http://localhost:8000> to access development environment using your local host.
Make sure the create an admin using the command line for full access to the application:
```bash
./manage.sh local:console app:users:create --role=ADMIN --verified admin admin@example.com password
```

To start local storybook:

```sh
./manage.sh local:start-back
```

Now go to <http://localhost:6006> to access locally the storybook.

## :white_check_mark: Run tests

All tests are executed automatically when building the Docker production images, but it is possible to execute tests locally.

Code coverage results will be stored in `./coverage` directory at the end of CI builds.

### Backend tests and code quality analysis

```bash
./manage.sh local:test-back
```

### Frontend tests and code quality analysis

```bash
./manage.sh local:test-front
```

## :construction_worker: Continuous Integration

This project support Continuous Integration with the following platforms:

-   DockerHub [Advanced Automated Build](https://docs.docker.com/docker-hub/builds/advanced/) hooks
-   [GitHub Actions](https://docs.github.com/en/actions) using DockerHub Advanced Automated Build hooks
-   [Travis-CI](https://travis-ci.com/) using DockerHub Advanced Automated Build hooks
-   [Codacy](https://www.codacy.com/) code quality and code coverage review
-   [Snyk](https://snyk.io/) security review

## :bust_in_silhouette: Authors

**Monogramm**

-   Website: <https://www.monogramm.io>
-   Github: [@Monogramm](https://github.com/Monogramm)

**Mathieu BRUNOT**

-   GitHub: [@madmath03](https://github.com/madmath03)

**Artur KHACHATURYAN**

-   Github: [@Arky9782](https://github.com/orgs/Monogramm/people/Arky9782)

**Allan PRUDENTOS**

-   Github: [@Nathan-Al](https://github.com/Nathan-Al)

## :handshake: Contributing

Contributions, issues and feature requests are welcome!<br />Feel free to check [issues page](https://github.com/Monogramm/ldap-one-for-all/issues).
[Check the contributing guide](./CONTRIBUTING.md).<br />

## :thumbsup: Show your support

Give a :star: if this project helped you!

## :page_facing_up: License

Copyright © 2021 [Monogramm](https://github.com/Monogramm).<br />
This project is [AGPL v3](uri_license) licensed.

* * *

_This README was generated with :heart: by [readme-md-generator](https://github.com/kefranabg/readme-md-generator)_

[uri_license]: http://www.gnu.org/licenses/agpl.html

[uri_license_image]: https://img.shields.io/badge/License-AGPL%20v3-blue.svg
