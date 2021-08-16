# Changelog

<a name="1.0.0"></a>

## 1.0.0 (2021-08-16)

### Added

*   ✨ Command to set Parameter ([#50](https://github.com/Monogramm/ldap-all-for-one-manager/issues/50))

### Changed

*   💬 Update docs and ToS / Privacy texts
*   🎨 Use computed i18n
*   💄 Do not display key on attribute value title

### Miscellaneous

*   🔨 Split tests and linters
*   🌐 Add more translated attributes
*   👷 Remove default tag for CI

<a name="0.9.1"></a>

## 0.9.1 (2021-08-10)

### Added

*   ✨ Display attribute code in LdapEntry view

### Changed

*   🎨 Change icons import order
*   💄 Add icons and local entity in admin screens
*   🎨 Improve small quality details
*   🎨 Fix Psalm issues
*   💬 Change title for removing attribute values
*   💄 Display loading for LDAP user in Home

### Fixed

*   🐛 Fix parameter creation

### Miscellaneous

*   👷 Add docker tag for CI

<a name="0.9.0"></a>

## 0.9.0 (2021-08-09)

### Added

*   ✨ Hide LDAP only actions in Home
*   ✨ API and Screens to manage LDAP entries ([#25](https://github.com/Monogramm/ldap-all-for-one-manager/issues/25))
*   ✅ Fix regex for password generation
*   ✨ Add Parameter constructor
*   ✅ Update tests
*   ✨ Enable/disable registration
*   ✨ Persist LDAP full DN on login
*   ✨ User Metadata with JWT payload
*   ✨ Better health check
*   ✨ Manage LDAP Groups
*   📈 Ignore Fixtures and Migrations coverage
*   ✅ Improve tests
*   ✨ Setup HTTP Basic Auth for API
*   ✅ Test resend of verification code
*   ✅ Test authentication token
*   ✨ Automatically fill admin dashboard
*   ✨ Sorting and Filtering API and tests
*   ✨ Add hooks command in manage script
*   ✨ Improve pagination, filtering and sort
*   ✨ Command LDAP ([#20](https://github.com/Monogramm/ldap-all-for-one-manager/issues/20))
*   ✨ PHPDoc configuration
*   ✅ Add test parameters
*   ✨ Improve test commands
*   ✨ Parameters list command
*   ✨ Improve CI scripts
*   ✨ Test command for docker dev env
*   ✨ Init Contact form with query param
*   ✅ Set fixtures times in UTC
*   ✅ User repository tests
*   ✨ Fix param types with psalm
*   ✨ Add generic filters and sorting
*   ✨ Improve Gitpod startup
*   ✨ Media management
*   ✨ Add Env var for docker tests
*   🎉 Initial commit

### Changed

*   🚚 Renaming of project
*   ⏪ Revert update of lock file
*   🎨 Fix code quality
*   🎨 Simplify Vuex states
*   🍱 Update assets
*   🎨 Remove empty line
*   ⬆️ upgrade vue-router from 3.5.1 to 3.5.2 ([#45](https://github.com/Monogramm/ldap-all-for-one-manager/issues/45))
*   ⚡ Only get current user data once
*   🔧 Add missing LDAP config
*   💄 Use toast for registration success
*   ⬆️ upgrade vue-i18n from 8.24.3 to 8.24.5 ([#42](https://github.com/Monogramm/ldap-all-for-one-manager/issues/42))
*   🎨 Improve User constructor
*   🚚 Rename Metadata to EntityTrait
*   🔧 Fix security config
*   🔧 Configure ELK Stack
*   🎨 Remove unused var in User Controller
*   🍱 Add calendar icon
*   🎨 Improve Messenger setup
*   💬 Change update texts in admin
*   🎨 Fix Parameter repository issues
*   💄 Improve dashboard buttons display
*   🎨 Improve docker-compose setup
*   ⬆️ upgrade buefy from 0.9.6 to 0.9.7 ([#28](https://github.com/Monogramm/ldap-all-for-one-manager/issues/28))
*   🔧 Change media upload directory
*   ⬆️ upgrade buefy from 0.9.5 to 0.9.6 ([#23](https://github.com/Monogramm/ldap-all-for-one-manager/issues/23))
*   🎨 Update manage.sh
*   🎨 Improve Hooks quality
*   🎨 Format User entity getRoles
*   🎨 Use named routing
*   🎨 Use Vuei18n to replace year in footer
*   🎨 Improve naming and set tests namespaces
*   🔧 Update lock files
*   🔧 Improve GitPod and CI config ([#5](https://github.com/Monogramm/ldap-all-for-one-manager/issues/5))
*   🔧 Setup Vetur plugin for GitPod
*   🔧 Improve Gitpod tasks
*   🔧 Set default branch to main

### Fixed

*   🐛 Add missing handler code for registration
*   🐛 Use relative imports for TS
*   💚 Fix push for non alpine image
*   💚 Add RabbitMQ config file
*   💚 Upgrade Docker version
*   🐛 Reverse support address emails
*   💚 Upgrade Travis dist
*   🐛 Fix user login role array
*   🐛 Fix first language change
*   🐛 Fix Gitpod wait conditions
*   🐛 Fix call to User registration API
*   💚 Fix CI health check

### Security

*   🔒 upgrade buefy from 0.9.4 to 0.9.5 ([#22](https://github.com/Monogramm/ldap-all-for-one-manager/issues/22))
*   🔒 upgrade vue-i18n from 8.24.1 to 8.24.2 ([#21](https://github.com/Monogramm/ldap-all-for-one-manager/issues/21))
*   🔒 upgrade multiple dependencies with Snyk ([#18](https://github.com/Monogramm/ldap-all-for-one-manager/issues/18))
*   🔒 upgrade vue-i18n from 8.24.0 to 8.24.1 ([#17](https://github.com/Monogramm/ldap-all-for-one-manager/issues/17))

### Miscellaneous

*   📝 Add screenshots
*   🔨 Update lock file on release
*   📝 Update docs with new design
*   👷 Improve CI hooks
*   📝 Remove Docker Autobuild badge
*   👷 Setup GH Actions to push to DockerHub
*   🐳 Enable APCu
*   🔨 Add HTML code coverage in dev
*   🔨 Update xdebug setup
*   🐳 Add ELK Stack & Grafana / Prometheus
*   🌐 Add missing key
*   💡 Add links to docs
*   🐳 Fix update of symfony uploaded data
*   🐳 Fix ACPu setup
*   🐳 Enable APCu
*   🐳 Ignore GitHub config dir
*   fix: upgrade vue-i18n from 8.24.2 to 8.24.3 ([#24](https://github.com/Monogramm/ldap-all-for-one-manager/issues/24))
*   🐳 Improve Docker XDebug config
*   👷 Add sample GitLab CI
*   🔀 Merge pull request [#19](https://github.com/Monogramm/ldap-all-for-one-manager/issues/19) from Monogramm/Nathan-Al-manage.sh-update
*   Update manage.sh
*   📝 Update CONTRIBUTING.md ([#15](https://github.com/Monogramm/ldap-all-for-one-manager/issues/15))
*   Merge pull request [#11](https://github.com/Monogramm/ldap-all-for-one-manager/issues/11) from Monogramm/snyk-upgrade-377d06694de76bce7f6caf138b37d638
*   fix: upgrade vue-i18n from 8.23.0 to 8.24.0
*   🐳 Setup xdebug in develop mode
*   🐳 Restore default develop PHP config
*   🐳 Fix develop docker image
*   🐳 Upgrade composer and PHP prod config
*   📝 Update docs
*   fix: upgrade vue-i18n from 8.22.4 to 8.23.0 ([#8](https://github.com/Monogramm/ldap-all-for-one-manager/issues/8))
*   📝 Update contributors ([#7](https://github.com/Monogramm/ldap-all-for-one-manager/issues/7))
*   📝 Directory structure of the project
*   📝 Improve local dev doc
*   📝 Fix link for GitPod env
*   📝 Set Codacy link
