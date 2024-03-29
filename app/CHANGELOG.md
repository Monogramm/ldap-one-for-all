# Changelog

<a name="1.1.0"></a>

## 1.1.0 (2021-08-19)

### Added

*   ✅ Set default LDAP role to USER \[[abb0004](https://github.com/Monogramm/ldap-all-for-one-manager/commit/abb0004e0493a52a0076f1ce3c3fa192febf66de)]
*   ✅ Test user admin enable/disable \[[94577ab](https://github.com/Monogramm/ldap-all-for-one-manager/commit/94577ab92713b9a4fc03cc487412edfb8102decb)]
*   ✨ Allow admins to enable/disable users \[[2390f63](https://github.com/Monogramm/ldap-all-for-one-manager/commit/2390f631da8d7051a3badbba9e4afbd77f044e61)]
*   ✨ Display User status in admin \[[06f9bb2](https://github.com/Monogramm/ldap-all-for-one-manager/commit/06f9bb293253559399ae31f2b49ba349db06a040)]

### Fixed

*   🐛 Edit the current user from home page \[[132afab](https://github.com/Monogramm/ldap-all-for-one-manager/commit/132afab2d6e4262e19139b7a52f2511b94fe5520)]
*   🐛 Do not display password modal if not local user \[[296b78a](https://github.com/Monogramm/ldap-all-for-one-manager/commit/296b78ab3bb000edc580697a379713eb4d56788c)]

### Miscellaneous

*   🐳 Upgrade libicu for docker debian \[[07abeac](https://github.com/Monogramm/ldap-all-for-one-manager/commit/07abeac6ab6d5950a2aa7ea3ae26f678220ab310)]
*   🚧 Disable sw cache on root \[[27f6982](https://github.com/Monogramm/ldap-all-for-one-manager/commit/27f6982db73c80324b439453b214e987d9b0a0e8)]
*   🚧 Track small improvement \[[19f91af](https://github.com/Monogramm/ldap-all-for-one-manager/commit/19f91af2f6fcd018bf587630a531095401348522)]

<a name="1.0.0"></a>

## 1.0.0 (2021-08-16)

### Added

*   ✨ Command to set Parameter ([#50](https://github.com/Monogramm/ldap-all-for-one-manager/issues/50)) \[[0ef390a](https://github.com/Monogramm/ldap-all-for-one-manager/commit/0ef390a40017812e23e4aa13aba1de4dfdd693eb)]

### Changed

*   💬 Update docs and ToS / Privacy texts \[[a7210e2](https://github.com/Monogramm/ldap-all-for-one-manager/commit/a7210e20752ad737feb870c7256da0986d41d064)]
*   🎨 Use computed i18n \[[4769e25](https://github.com/Monogramm/ldap-all-for-one-manager/commit/4769e25d6a321816c3db9989d1f35c35177fd2dd)]
*   💄 Do not display key on attribute value title \[[c83869a](https://github.com/Monogramm/ldap-all-for-one-manager/commit/c83869a5b09bdc366dcd1e9e6ae014626c4def5b)]

### Miscellaneous

*   🔨 Split tests and linters \[[9dd0076](https://github.com/Monogramm/ldap-all-for-one-manager/commit/9dd00761e5d95e6156ef5e33e59ca7cf79475a26)]
*   🌐 Add more translated attributes \[[4ed0dea](https://github.com/Monogramm/ldap-all-for-one-manager/commit/4ed0dea35d58db2c5b589fae349f3aad55b62ff5)]
*   👷 Remove default tag for CI \[[e0a86ee](https://github.com/Monogramm/ldap-all-for-one-manager/commit/e0a86ee3ff1285aa9f03e434def92550c54d0ee9)]

<a name="0.9.1"></a>

## 0.9.1 (2021-08-10)

### Added

*   ✨ Display attribute code in LdapEntry view \[[613ddc7](https://github.com/Monogramm/ldap-all-for-one-manager/commit/613ddc7cb578594d89db4eede203e0709ea2b668)]

### Changed

*   🎨 Change icons import order \[[2f98eca](https://github.com/Monogramm/ldap-all-for-one-manager/commit/2f98ecad9430fc3e1bfddae401637a82ac2aa145)]
*   💄 Add icons and local entity in admin screens \[[e0ee272](https://github.com/Monogramm/ldap-all-for-one-manager/commit/e0ee27296cebfd1ed9162720e8ff663e4f3b9cf1)]
*   🎨 Improve small quality details \[[55fdbfa](https://github.com/Monogramm/ldap-all-for-one-manager/commit/55fdbfa4aeb0e582c5272e8f60f278739d88977b)]
*   🎨 Fix Psalm issues \[[d1fd2b5](https://github.com/Monogramm/ldap-all-for-one-manager/commit/d1fd2b5c0990da6eadc08feb6e38a69feb2cdbff)]
*   💬 Change title for removing attribute values \[[f7ccf2b](https://github.com/Monogramm/ldap-all-for-one-manager/commit/f7ccf2ba5cccae069d74f9687c9d3d8c2a088ea6)]
*   💄 Display loading for LDAP user in Home \[[247c3ca](https://github.com/Monogramm/ldap-all-for-one-manager/commit/247c3ca2ddb149f31ff531dcc1a38579f22023fe)]

### Fixed

*   🐛 Fix parameter creation \[[f3c65ea](https://github.com/Monogramm/ldap-all-for-one-manager/commit/f3c65eabae56201d32adbd59dd0c0ef15ac5313b)]

### Miscellaneous

*   👷 Add docker tag for CI \[[d5c4f0d](https://github.com/Monogramm/ldap-all-for-one-manager/commit/d5c4f0df9b77620fcb97999b3871c379bbb78b87)]

<a name="0.9.0"></a>

## 0.9.0 (2021-08-09)

### Added

*   ✨ Hide LDAP only actions in Home \[[da3298d](https://github.com/Monogramm/ldap-all-for-one-manager/commit/da3298d40b6223a88d1d3dfc7d39fe025674e8f9)]
*   ✨ API and Screens to manage LDAP entries ([#25](https://github.com/Monogramm/ldap-all-for-one-manager/issues/25)) \[[21bc021](https://github.com/Monogramm/ldap-all-for-one-manager/commit/21bc02182974506b5bd9529ae19334be7e7dfc72)]
*   ✅ Fix regex for password generation \[[8acf45e](https://github.com/Monogramm/ldap-all-for-one-manager/commit/8acf45ee0a9c414eccb27458a3b6b22c46207810)]
*   ✨ Add Parameter constructor \[[e666239](https://github.com/Monogramm/ldap-all-for-one-manager/commit/e666239d1b001dd3a9d37c073e6af323316b7eef)]
*   ✅ Update tests \[[1ce9aab](https://github.com/Monogramm/ldap-all-for-one-manager/commit/1ce9aab3b7e6199dd232d373a65926894d948c44)]
*   ✨ Enable/disable registration \[[65b8b78](https://github.com/Monogramm/ldap-all-for-one-manager/commit/65b8b78765b41de2885c8dc765a1b19c75509315)]
*   ✨ Persist LDAP full DN on login \[[6e7bef2](https://github.com/Monogramm/ldap-all-for-one-manager/commit/6e7bef299e754a6d77087c4ed99c39b60f5b8ada)]
*   ✨ User Metadata with JWT payload \[[4ec5c08](https://github.com/Monogramm/ldap-all-for-one-manager/commit/4ec5c0863350a6717888b51e2616d6aff9cf5e65)]
*   ✨ Better health check \[[7d5e062](https://github.com/Monogramm/ldap-all-for-one-manager/commit/7d5e062fa4bf8ac6b4090a95b773feed9aa289e6)]
*   ✨ Manage LDAP Groups \[[fcecb62](https://github.com/Monogramm/ldap-all-for-one-manager/commit/fcecb629e768e7b489581c9dc95278c936408e0b)]
*   📈 Ignore Fixtures and Migrations coverage \[[45b21da](https://github.com/Monogramm/ldap-all-for-one-manager/commit/45b21da9d1ffb14603cb2e618bc953dde7010b93)]
*   ✅ Improve tests \[[5ed9209](https://github.com/Monogramm/ldap-all-for-one-manager/commit/5ed9209031277013de191e7279897cb7b7bfe649)]
*   ✨ Setup HTTP Basic Auth for API \[[1d00a27](https://github.com/Monogramm/ldap-all-for-one-manager/commit/1d00a2777b9a29c2c9ef519716e089f3cbce120f)]
*   ✅ Test resend of verification code \[[15608f1](https://github.com/Monogramm/ldap-all-for-one-manager/commit/15608f1a6036b4aca23261c4ab984a37deb7f73a)]
*   ✅ Test authentication token \[[b2da206](https://github.com/Monogramm/ldap-all-for-one-manager/commit/b2da2062956c663d6c15d772a3b96204483b3c85)]
*   ✨ Automatically fill admin dashboard \[[57e17c2](https://github.com/Monogramm/ldap-all-for-one-manager/commit/57e17c2110178213ec6102637aab83254f73990c)]
*   ✨ Sorting and Filtering API and tests \[[8471418](https://github.com/Monogramm/ldap-all-for-one-manager/commit/8471418503665a93fa5793eaaedb46cff7079eb0)]
*   ✨ Add hooks command in manage script \[[8a62c7b](https://github.com/Monogramm/ldap-all-for-one-manager/commit/8a62c7b785eed4e4a99d5fa71e91577c0e9fe236)]
*   ✨ Improve pagination, filtering and sort \[[e07302a](https://github.com/Monogramm/ldap-all-for-one-manager/commit/e07302a5d88ace77cac7180c2cb418adff03f2e7)]
*   ✨ Command LDAP ([#20](https://github.com/Monogramm/ldap-all-for-one-manager/issues/20)) \[[37072bc](https://github.com/Monogramm/ldap-all-for-one-manager/commit/37072bc0bfa5a958582d3dbf1088c003ef02cb09)]
*   ✨ PHPDoc configuration \[[f7a2e6d](https://github.com/Monogramm/ldap-all-for-one-manager/commit/f7a2e6d165733e86475cbe09edd37bfa8fbe4076)]
*   ✅ Add test parameters \[[764e4b1](https://github.com/Monogramm/ldap-all-for-one-manager/commit/764e4b181b6faae4fcdbc836ccad2a5e373484f4)]
*   ✨ Improve test commands \[[296107f](https://github.com/Monogramm/ldap-all-for-one-manager/commit/296107f1b26c6716993dddcbf951eac7b5ac9822)]
*   ✨ Parameters list command \[[f31ffb9](https://github.com/Monogramm/ldap-all-for-one-manager/commit/f31ffb9aa1dbd1f637d46bcbe928826f4f829bb8)]
*   ✨ Improve CI scripts \[[7c52fa8](https://github.com/Monogramm/ldap-all-for-one-manager/commit/7c52fa825d38fc8c855918d33945f6a301b32c36)]
*   ✨ Test command for docker dev env \[[8e43483](https://github.com/Monogramm/ldap-all-for-one-manager/commit/8e43483963ed97b69fff3a704d4426f4610b5d9a)]
*   ✨ Init Contact form with query param \[[d17bcee](https://github.com/Monogramm/ldap-all-for-one-manager/commit/d17bcee8d982f43e3b14ffecf440c0b2533434b7)]
*   ✅ Set fixtures times in UTC \[[a8e407a](https://github.com/Monogramm/ldap-all-for-one-manager/commit/a8e407a95a1c3cfed8a31e03209ef0e3b4b9b49d)]
*   ✅ User repository tests \[[16c1469](https://github.com/Monogramm/ldap-all-for-one-manager/commit/16c14692a6ccb3cf7cce6a7fd1731763ea0a0916)]
*   ✨ Fix param types with psalm \[[326884b](https://github.com/Monogramm/ldap-all-for-one-manager/commit/326884b71a9e89ac51a9d967e430ab5779d780e3)]
*   ✨ Add generic filters and sorting \[[648564f](https://github.com/Monogramm/ldap-all-for-one-manager/commit/648564f7a073245b3ca18a4745adf5355b026a8d)]
*   ✨ Improve Gitpod startup \[[3c44d4f](https://github.com/Monogramm/ldap-all-for-one-manager/commit/3c44d4f8d2311b02143bedc22285b7ed153bdcf5)]
*   ✨ Media management \[[7d10c7a](https://github.com/Monogramm/ldap-all-for-one-manager/commit/7d10c7ac48693250d0c2f9e5dda4e2a232a00b33)]
*   ✨ Add Env var for docker tests \[[99a880d](https://github.com/Monogramm/ldap-all-for-one-manager/commit/99a880d879813575451bd1c67e8fbc4fd228c41f)]
*   🎉 Initial commit \[[e3989bf](https://github.com/Monogramm/ldap-all-for-one-manager/commit/e3989bf27270e99f0f49689e545852d1a94bf6d8)]

### Changed

*   🚚 Renaming of project \[[f6a6ae0](https://github.com/Monogramm/ldap-all-for-one-manager/commit/f6a6ae0fa22cb0f35ce2abe722a217192eeaa1a5)]
*   ⏪ Revert update of lock file \[[dd24899](https://github.com/Monogramm/ldap-all-for-one-manager/commit/dd248994ce4ab65ff6ff66b662bd98af45eee71a)]
*   🎨 Fix code quality \[[0eda1fe](https://github.com/Monogramm/ldap-all-for-one-manager/commit/0eda1fe52fb93c7428ebf461f06f24fbf59ec9a4)]
*   🎨 Simplify Vuex states \[[a9fc90b](https://github.com/Monogramm/ldap-all-for-one-manager/commit/a9fc90b1d3a6a47e3644dabae2afe3f25d74cfc0)]
*   🍱 Update assets \[[c949ebf](https://github.com/Monogramm/ldap-all-for-one-manager/commit/c949ebfa76dbfa10226fddf586ae23e56beb5a6b)]
*   🎨 Remove empty line \[[3789377](https://github.com/Monogramm/ldap-all-for-one-manager/commit/37893779ad48ac4fdb51a7aa86cac34f5fda6ca2)]
*   ⬆️ upgrade vue-router from 3.5.1 to 3.5.2 ([#45](https://github.com/Monogramm/ldap-all-for-one-manager/issues/45)) \[[4b7c90a](https://github.com/Monogramm/ldap-all-for-one-manager/commit/4b7c90a5f610331108bb6aa537a9d92d15cd0a2f)]
*   ⚡ Only get current user data once \[[1409ef0](https://github.com/Monogramm/ldap-all-for-one-manager/commit/1409ef05f8adc85cb4feeb57e916da354b765cef)]
*   🔧 Add missing LDAP config \[[d73ffb2](https://github.com/Monogramm/ldap-all-for-one-manager/commit/d73ffb2d81272b3bf7f78168595846cfbaf2ec09)]
*   💄 Use toast for registration success \[[f6435c5](https://github.com/Monogramm/ldap-all-for-one-manager/commit/f6435c53648ffcd545a01ac92879ffcb69fd2571)]
*   ⬆️ upgrade vue-i18n from 8.24.3 to 8.24.5 ([#42](https://github.com/Monogramm/ldap-all-for-one-manager/issues/42)) \[[53f0f4a](https://github.com/Monogramm/ldap-all-for-one-manager/commit/53f0f4aa528094fab8575f3256716e73bdba1bac)]
*   🎨 Improve User constructor \[[43909a1](https://github.com/Monogramm/ldap-all-for-one-manager/commit/43909a1af479fa69663d33a13bf46c346d8567ac)]
*   🚚 Rename Metadata to EntityTrait \[[691a6c5](https://github.com/Monogramm/ldap-all-for-one-manager/commit/691a6c5b1e0184a60d70ebfad032dab29880d7a4)]
*   🔧 Fix security config \[[8c25a65](https://github.com/Monogramm/ldap-all-for-one-manager/commit/8c25a65cf56b0f78a4e009c528c3d3969bf3704b)]
*   🔧 Configure ELK Stack \[[20ce2e0](https://github.com/Monogramm/ldap-all-for-one-manager/commit/20ce2e0c599d7ab38d106c128db94248f3d770a9)]
*   🎨 Remove unused var in User Controller \[[68255c5](https://github.com/Monogramm/ldap-all-for-one-manager/commit/68255c5f1c9c653753ca8716c19b7e24ee9b41e2)]
*   🍱 Add calendar icon \[[084b41a](https://github.com/Monogramm/ldap-all-for-one-manager/commit/084b41aa88fb87ac56d566179fa628dd04b25832)]
*   🎨 Improve Messenger setup \[[fac8dd8](https://github.com/Monogramm/ldap-all-for-one-manager/commit/fac8dd8979f97fb165973fe4d10696047ceee6da)]
*   💬 Change update texts in admin \[[db84e5a](https://github.com/Monogramm/ldap-all-for-one-manager/commit/db84e5aa59729ca55c736a80e530f8a379cb3da2)]
*   🎨 Fix Parameter repository issues \[[f4d33e1](https://github.com/Monogramm/ldap-all-for-one-manager/commit/f4d33e19c224155d6fa091573d50462f46ca7746)]
*   💄 Improve dashboard buttons display \[[9cda774](https://github.com/Monogramm/ldap-all-for-one-manager/commit/9cda774fb173ec2e03d02710dee46107a5df2878)]
*   🎨 Improve docker-compose setup \[[3394bae](https://github.com/Monogramm/ldap-all-for-one-manager/commit/3394baecd9e042a3b659146a3004c861ee0b276d)]
*   ⬆️ upgrade buefy from 0.9.6 to 0.9.7 ([#28](https://github.com/Monogramm/ldap-all-for-one-manager/issues/28)) \[[a386bfb](https://github.com/Monogramm/ldap-all-for-one-manager/commit/a386bfbe99e8d6e5e05c8dc788b980dd7ebd416e)]
*   🔧 Change media upload directory \[[25dccc7](https://github.com/Monogramm/ldap-all-for-one-manager/commit/25dccc7fdd1affb1b4dc42519c391fa3e3a71926)]
*   ⬆️ upgrade buefy from 0.9.5 to 0.9.6 ([#23](https://github.com/Monogramm/ldap-all-for-one-manager/issues/23)) \[[9afbfc2](https://github.com/Monogramm/ldap-all-for-one-manager/commit/9afbfc2e1dabe72264b9fbcb5081e262ab4cf2f8)]
*   🎨 Update manage.sh \[[e6ea9b2](https://github.com/Monogramm/ldap-all-for-one-manager/commit/e6ea9b205e3778619230747bfbdb6ae4f37add3f)]
*   🎨 Improve Hooks quality \[[a6d9e00](https://github.com/Monogramm/ldap-all-for-one-manager/commit/a6d9e00d518fe5b12669be8b33597f7b36c9d07f)]
*   🎨 Format User entity getRoles \[[3da403f](https://github.com/Monogramm/ldap-all-for-one-manager/commit/3da403f9b2f688e05ef61a830b287d0512b99ac2)]
*   🎨 Use named routing \[[626c4bf](https://github.com/Monogramm/ldap-all-for-one-manager/commit/626c4bf319fbf131e7ec766ceef7fe27c493cf4c)]
*   🎨 Use Vuei18n to replace year in footer \[[541f2fd](https://github.com/Monogramm/ldap-all-for-one-manager/commit/541f2fd95fa943230e450f6c30205fc9c258d20e)]
*   🎨 Improve naming and set tests namespaces \[[2afdd57](https://github.com/Monogramm/ldap-all-for-one-manager/commit/2afdd577d9075660dfafc6615933a47ab47aad2c)]
*   🔧 Update lock files \[[8d395bd](https://github.com/Monogramm/ldap-all-for-one-manager/commit/8d395bd37d98f595116f1d6e5ad073d1d2c61f82)]
*   🔧 Improve GitPod and CI config ([#5](https://github.com/Monogramm/ldap-all-for-one-manager/issues/5)) \[[ceb6915](https://github.com/Monogramm/ldap-all-for-one-manager/commit/ceb6915c3de18f2c6ddf49a8986095e3e5dd987f)]
*   🔧 Setup Vetur plugin for GitPod \[[9fbd209](https://github.com/Monogramm/ldap-all-for-one-manager/commit/9fbd2092a2e6c4bd5a8cccf2c9e0c6d0274c574d)]
*   🔧 Improve Gitpod tasks \[[e1b5bba](https://github.com/Monogramm/ldap-all-for-one-manager/commit/e1b5bba03138a6a990aeba3a9bc47cf44a6436ca)]
*   🔧 Set default branch to main \[[4bdc07b](https://github.com/Monogramm/ldap-all-for-one-manager/commit/4bdc07bb5d52d435f1487c132d3d521c1e80ce1f)]

### Fixed

*   🐛 Add missing handler code for registration \[[6acc986](https://github.com/Monogramm/ldap-all-for-one-manager/commit/6acc986638eb42bc0f825a1764e04e1e3f68398c)]
*   🐛 Use relative imports for TS \[[542e346](https://github.com/Monogramm/ldap-all-for-one-manager/commit/542e3461825f9be596b7a544eaa36bda030a38d3)]
*   💚 Fix push for non alpine image \[[98add4a](https://github.com/Monogramm/ldap-all-for-one-manager/commit/98add4a168f51f27c32084cd7e19c17bc65cd533)]
*   💚 Add RabbitMQ config file \[[1f9401a](https://github.com/Monogramm/ldap-all-for-one-manager/commit/1f9401aae962f752cca720f3bafdd64bdc22669b)]
*   💚 Upgrade Docker version \[[9b5b433](https://github.com/Monogramm/ldap-all-for-one-manager/commit/9b5b4332a26ae2296fa207984b48886a09806a39)]
*   🐛 Reverse support address emails \[[2ef6505](https://github.com/Monogramm/ldap-all-for-one-manager/commit/2ef65051820466bf91efba66b012307c735b5801)]
*   💚 Upgrade Travis dist \[[e59dd80](https://github.com/Monogramm/ldap-all-for-one-manager/commit/e59dd80bb8f86dc3c27d2e1bfb90892d3b61364a)]
*   🐛 Fix user login role array \[[cdd120a](https://github.com/Monogramm/ldap-all-for-one-manager/commit/cdd120a9ec75d623ddf51df0d0a519bbf2b7e71c)]
*   🐛 Fix first language change \[[53cf67f](https://github.com/Monogramm/ldap-all-for-one-manager/commit/53cf67fdd1528ff9f22e73d4807adf942527a8ec)]
*   🐛 Fix Gitpod wait conditions \[[d477066](https://github.com/Monogramm/ldap-all-for-one-manager/commit/d47706663c3884af884bd5ad029a649d419439cb)]
*   🐛 Fix call to User registration API \[[d8c9041](https://github.com/Monogramm/ldap-all-for-one-manager/commit/d8c9041c4fcefdd0f07dbf0aa4943ac3ff5803bf)]
*   💚 Fix CI health check \[[aa3a415](https://github.com/Monogramm/ldap-all-for-one-manager/commit/aa3a4156776c1c37b081c1a816630aef9541b6c9)]

### Security

*   🔒 upgrade buefy from 0.9.4 to 0.9.5 ([#22](https://github.com/Monogramm/ldap-all-for-one-manager/issues/22)) \[[94ffba1](https://github.com/Monogramm/ldap-all-for-one-manager/commit/94ffba10a948632dfc0723d3976addbf581ab60b)]
*   🔒 upgrade vue-i18n from 8.24.1 to 8.24.2 ([#21](https://github.com/Monogramm/ldap-all-for-one-manager/issues/21)) \[[ddf02c7](https://github.com/Monogramm/ldap-all-for-one-manager/commit/ddf02c7edcaea2b5e92cee46640498636b0cf40e)]
*   🔒 upgrade multiple dependencies with Snyk ([#18](https://github.com/Monogramm/ldap-all-for-one-manager/issues/18)) \[[f10f8fa](https://github.com/Monogramm/ldap-all-for-one-manager/commit/f10f8fa781e9d545772769e9d558dfab04403336)]
*   🔒 upgrade vue-i18n from 8.24.0 to 8.24.1 ([#17](https://github.com/Monogramm/ldap-all-for-one-manager/issues/17)) \[[2deea78](https://github.com/Monogramm/ldap-all-for-one-manager/commit/2deea78abe687287d5335659a9e3f0cca86c7172)]

### Miscellaneous

*   📝 Add screenshots \[[2e645c2](https://github.com/Monogramm/ldap-all-for-one-manager/commit/2e645c228a94b6973de51f623bfdcd0353eea9c3)]
*   🔨 Update lock file on release \[[05ecd5d](https://github.com/Monogramm/ldap-all-for-one-manager/commit/05ecd5dfbef62e92d4c9ec017110a37c6850d143)]
*   📝 Update docs with new design \[[9e0dccd](https://github.com/Monogramm/ldap-all-for-one-manager/commit/9e0dccdf766558444526f61cb5727c33e7637e07)]
*   👷 Improve CI hooks \[[a3dc15b](https://github.com/Monogramm/ldap-all-for-one-manager/commit/a3dc15b71e9ce64d082ef3a3edc5f0091ca7b4d5)]
*   📝 Remove Docker Autobuild badge \[[0072819](https://github.com/Monogramm/ldap-all-for-one-manager/commit/00728193644c9f5948ef26f4b6eb334a3c803a68)]
*   👷 Setup GH Actions to push to DockerHub \[[4c31f5c](https://github.com/Monogramm/ldap-all-for-one-manager/commit/4c31f5c0e3cb1fc1d72516c709df50d81e19688e)]
*   🐳 Enable APCu \[[9f009c1](https://github.com/Monogramm/ldap-all-for-one-manager/commit/9f009c19e96e5dee32e0666982fd3a018ce0e3c3)]
*   🔨 Add HTML code coverage in dev \[[1910505](https://github.com/Monogramm/ldap-all-for-one-manager/commit/19105058c2d3d8f295506695dfec32f01fecc245)]
*   🔨 Update xdebug setup \[[577703c](https://github.com/Monogramm/ldap-all-for-one-manager/commit/577703cc56ee49c3157797167d5f77549df6ce5d)]
*   🐳 Add ELK Stack & Grafana / Prometheus \[[9cdb09a](https://github.com/Monogramm/ldap-all-for-one-manager/commit/9cdb09a7deb72f7d3befff6da44b38cc8cd05141)]
*   🌐 Add missing key \[[722f359](https://github.com/Monogramm/ldap-all-for-one-manager/commit/722f359a5f008563d13d1bbd1cbc01ee4a0e7809)]
*   💡 Add links to docs \[[644cb48](https://github.com/Monogramm/ldap-all-for-one-manager/commit/644cb48a7ce463a7fed53fa040d5a9d8bc060d5c)]
*   🐳 Fix update of symfony uploaded data \[[4e53ea2](https://github.com/Monogramm/ldap-all-for-one-manager/commit/4e53ea281f86eb2b0318305a55964d22137c3918)]
*   🐳 Fix ACPu setup \[[054e4e4](https://github.com/Monogramm/ldap-all-for-one-manager/commit/054e4e44e114c46e0efc4a853356d284e6db1bf3)]
*   🐳 Enable APCu \[[3993f93](https://github.com/Monogramm/ldap-all-for-one-manager/commit/3993f93e7127d93c5d72b4a1789950c9bbbe0823)]
*   🐳 Ignore GitHub config dir \[[1e812f4](https://github.com/Monogramm/ldap-all-for-one-manager/commit/1e812f41b4a39cefa07d325f7c011c43721c07d7)]
*   fix: upgrade vue-i18n from 8.24.2 to 8.24.3 ([#24](https://github.com/Monogramm/ldap-all-for-one-manager/issues/24)) \[[0c39351](https://github.com/Monogramm/ldap-all-for-one-manager/commit/0c393513ce3dea758499ad02f0b655ea9443baf7)]
*   🐳 Improve Docker XDebug config \[[cb1e7d6](https://github.com/Monogramm/ldap-all-for-one-manager/commit/cb1e7d63e44c051f26cb645f2889babd1ba9694f)]
*   👷 Add sample GitLab CI \[[7ca2f8e](https://github.com/Monogramm/ldap-all-for-one-manager/commit/7ca2f8e8a50dc8d9322301c46aacd486f938afe2)]
*   🔀 Merge pull request [#19](https://github.com/Monogramm/ldap-all-for-one-manager/issues/19) from Monogramm/Nathan-Al-manage.sh-update \[[b8ca7ca](https://github.com/Monogramm/ldap-all-for-one-manager/commit/b8ca7caa70b13ff6e2ce381438b1844d8d8540bb)]
*   Update manage.sh \[[9c3aec4](https://github.com/Monogramm/ldap-all-for-one-manager/commit/9c3aec4a5cff1cf831cf17580dc9b2e3a9f63650)]
*   📝 Update CONTRIBUTING.md ([#15](https://github.com/Monogramm/ldap-all-for-one-manager/issues/15)) \[[fda13cb](https://github.com/Monogramm/ldap-all-for-one-manager/commit/fda13cb8895da3193a2275104d375ac144840946)]
*   Merge pull request [#11](https://github.com/Monogramm/ldap-all-for-one-manager/issues/11) from Monogramm/snyk-upgrade-377d06694de76bce7f6caf138b37d638 \[[b2dd351](https://github.com/Monogramm/ldap-all-for-one-manager/commit/b2dd351f82f6d83db85bdfc7bcbbb1af9f2455a7)]
*   fix: upgrade vue-i18n from 8.23.0 to 8.24.0 \[[84ec418](https://github.com/Monogramm/ldap-all-for-one-manager/commit/84ec418710b6a26a0edd77705c137816bfe107d2)]
*   🐳 Setup xdebug in develop mode \[[b9de14f](https://github.com/Monogramm/ldap-all-for-one-manager/commit/b9de14f54e41d4c5d5151bedb28c8957f93c94a9)]
*   🐳 Restore default develop PHP config \[[d58a89d](https://github.com/Monogramm/ldap-all-for-one-manager/commit/d58a89d202bed4fbc89d79fbac1b6c16a454fd00)]
*   🐳 Fix develop docker image \[[f717c01](https://github.com/Monogramm/ldap-all-for-one-manager/commit/f717c0110acfb3719d7aa2cc363de0a63a996ad0)]
*   🐳 Upgrade composer and PHP prod config \[[d92fa2d](https://github.com/Monogramm/ldap-all-for-one-manager/commit/d92fa2dd724ac9f812cbe51ba254dc3828bdae29)]
*   📝 Update docs \[[e05645f](https://github.com/Monogramm/ldap-all-for-one-manager/commit/e05645f22b63dd7d451dff16b7f516e7fae0c712)]
*   fix: upgrade vue-i18n from 8.22.4 to 8.23.0 ([#8](https://github.com/Monogramm/ldap-all-for-one-manager/issues/8)) \[[2578980](https://github.com/Monogramm/ldap-all-for-one-manager/commit/2578980a01a078058b2eebee1f88cfa5f2d6c409)]
*   📝 Update contributors ([#7](https://github.com/Monogramm/ldap-all-for-one-manager/issues/7)) \[[5b19755](https://github.com/Monogramm/ldap-all-for-one-manager/commit/5b19755015a972b283a2305c74a713d6bdf4f08a)]
*   📝 Directory structure of the project \[[a399ee1](https://github.com/Monogramm/ldap-all-for-one-manager/commit/a399ee153d2309b10d8106a21dcffcc955f63bad)]
*   📝 Improve local dev doc \[[11feace](https://github.com/Monogramm/ldap-all-for-one-manager/commit/11feace9e753bfd49944326b2e00413708698a04)]
*   📝 Fix link for GitPod env \[[7c76734](https://github.com/Monogramm/ldap-all-for-one-manager/commit/7c767342ee26e9d5f04874d379d6fe51882c0f6b)]
*   📝 Set Codacy link \[[9661b26](https://github.com/Monogramm/ldap-all-for-one-manager/commit/9661b2669c37e34855ee4b796fe0db50937db788)]
