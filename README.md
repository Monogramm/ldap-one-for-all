[![License: AGPL v3][uri_license_image]][uri_license]
[![Docs](https://img.shields.io/badge/Docs-Github%20Pages-blue)](https://monogramm.github.io/ldap-all-for-one-manager/)
[![gitmoji-changelog](https://img.shields.io/badge/Changelog-gitmoji-blue.svg)](https://github.com/frinyvonnick/gitmoji-changelog)
[![Managed with Taiga.io](https://img.shields.io/badge/Managed%20with-TAIGA.io-709f14.svg)](https://tree.taiga.io/project/monogrammbot-monogrammldap-all-for-one-manager/ "Managed with Taiga.io")
[![GitHub stars](https://img.shields.io/github/stars/Monogramm/ldap-all-for-one-manager?style=social)](https://github.com/Monogramm/ldap-all-for-one-manager)

[![Gitpod ready-to-code](https://img.shields.io/badge/Gitpod-ready--to--code-blue?logo=gitpod)](https://gitpod.io/#https://github.com/Monogramm/ldap-all-for-one-manager)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/c0736494f8314577b7164bfcbd752780)](https://www.codacy.com/gh/Monogramm/ldap-all-for-one-manager/dashboard?utm_source=github.com&utm_medium=referral&utm_content=Monogramm/ldap-all-for-one-manager&utm_campaign=Badge_Grade)
[![Codacy Badge](https://app.codacy.com/project/badge/Coverage/c0736494f8314577b7164bfcbd752780)](https://www.codacy.com/gh/Monogramm/ldap-all-for-one-manager/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Monogramm/ldap-all-for-one-manager&amp;utm_campaign=Badge_Coverage)
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/Monogramm/ldap-all-for-one-manager/Docker%20Image%20CI)](https://github.com/Monogramm/ldap-all-for-one-manager/actions)

[![Docker Automated buid](https://img.shields.io/docker/cloud/build/monogramm/ldap-all-for-one-manager.svg)](https://hub.docker.com/r/monogramm/ldap-all-for-one-manager/)
[![Docker Pulls](https://img.shields.io/docker/pulls/monogramm/ldap-all-for-one-manager.svg)](https://hub.docker.com/r/monogramm/ldap-all-for-one-manager/)
[![Docker Image Size (tag)](https://img.shields.io/docker/image-size/monogramm/ldap-all-for-one-manager/latest)](https://hub.docker.com/r/monogramm/ldap-all-for-one-manager/)

# **LDAP All-For-One Manager**

> :alembic: :elephant: A web based LDAP Manager for everyone.

:construction: **This project is still in development!**

This project was initialized from [Monogramm/vue-symfony-starter](https://github.com/Monogramm/vue-symfony-starter).

## :blue_book: Docs

See GitHub Pages at [monogramm.github.io/ldap-all-for-one-manager](https://monogramm.github.io/ldap-all-for-one-manager/).

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

[Dockerhub monogramm/ldap-all-for-one-manager](https://hub.docker.com/r/monogramm/ldap-all-for-one-manager/)

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

You can also [![open in Gitpod](https://gitpod.io/button/open-in-gitpod.svg)](https://gitpod.io/#https://github.com/Monogramm/ldap-all-for-one-manager) the local development environment.
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

## :bust_in_silhouette: Authors

**Monogramm**

-   Website: <https://www.monogramm.io>
-   Github: [@Monogramm](https://github.com/Monogramm)

**Mathieu BRUNOT**

-   GitHub: [@madmath03](https://github.com/madmath03)

**Artur Khachaturyan**

-   Github: [@Arky9782](https://github.com/orgs/Monogramm/people/Arky9782)

## :handshake: Contributing

Contributions, issues and feature requests are welcome!<br />Feel free to check [issues page](https://github.com/Monogramm/ldap-all-for-one-manager/issues).
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
