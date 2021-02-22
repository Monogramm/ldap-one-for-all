#!/usr/bin/env groovy

pipeline {
    agent any
    options {
        buildDiscarder(logRotator(numToKeepStr: '5'))
    }
    /*
     * Expected Parameters to create the Pipeline in Jenkins
     *  - gitlabSourceRepoURL:
     *      - https://scm.example.com/owner/project.git
     *      - The GitLab Source repository URL.
     *        If you use login / password credentials, this is expected to be a HTTPS URL, as in https://scm.example.com/path/to/project.git
     *        If you use SSH key credentials, this is expected to be a SSH URL, as in ssh://scm.example.com/path/to/project.git
     *        This is expected to be a HTTPS URL, as in https://scm.example.com/path/to/project.git, if you use login / password credentials.
     *  - sourceBranch:
     *      - heads/develop
     *      - The GitLab Source branch.
     *        Will be overriden by $gitlabSourceBranch in case of GitLab hook, pulling refs/heads/${gitlabSourceBranch}.
     *        
     *        The repository will be pulled from refs/${sourceBranch}. You can use the following formats:
     *        .  heads/<branchName>
     *            * Tracks/checks out the specified branch. E.g. heads/master, heads/feature1/something
     *        
     *        .  tags/<tagName>
     *            * Tracks/checks out the specified tag. E.g. tags/git-2.3.0
     */
    parameters {
        string(name: 'DOCKER_REPO', defaultValue: 'monogramm/ldap-all-for-one-manager', description: 'Docker Image name.')

        string(name: 'DOCKER_TAG', defaultValue: 'latest', description: 'Docker Image tag.')

        choice(name: 'VARIANT', choices: ['alpine', 'debian'], description: 'Docker Image variant.')

        string(name: 'DOCKER_REGISTRY', defaultValue: 'registry.hub.docker.com', description: 'Docker Registry to publish the result image.')

        credentials(name: 'DOCKER_CREDENTIALS', credentialType: 'Username with password', required: true, defaultValue: 'dh-reg-ci', description: 'Docker credentials to push on the Docker registry.')

        choice(name: 'APP_PUBLIC_URL', choices: ['https://app.example.com'], description: 'Application target domain name.')

        choice(name: 'WEBSITE_PUBLIC_URL', choices: ['https://www.example.com'], description: 'Website target domain name.')

        choice(name: 'STORIES', choices: ['true', 'false'], description: 'Build Storybook in build/storybook?')

        choice(name: 'EXPORT_TESTS_RESULTS', choices: ['true', 'false'], description: 'Export tests results for future analysis?')
    }
    triggers {
        cron('H 6 * * 1-5')
    }
    stages {
        stage('pending') {
            steps {
                updateGitlabCommitStatus name: 'jenkins', state: 'pending'
            }
        }

        stage('check docker') {
            steps {
                sh "docker --version"
                sh "docker-compose --version"
            }
        }

        stage('build') {
            steps {
                updateGitlabCommitStatus name: 'jenkins', state: 'running'

                script {
                    docker.withRegistry("https://${DOCKER_REGISTRY}", "${DOCKER_CREDENTIALS}") {
                        def customImage = docker.build(
                            "${DOCKER_REGISTRY}/${DOCKER_REPO}:${DOCKER_TAG}",
                            "--build-arg TAG=${DOCKER_TAG} --build-arg STORIES=${STORIES} --build-arg EXPORT_TESTS_RESULTS=${EXPORT_TESTS_RESULTS} --build-arg APP_PUBLIC_URL=${APP_PUBLIC_URL} --build-arg WEBSITE_PUBLIC_URL=${WEBSITE_PUBLIC_URL} --build-arg VCS_REF=$(git rev-parse --short HEAD) --build-arg BUILD_DATE=$(date -u +'%Y-%m-%dT%H:%M:%SZ') -f Dockerfile.${VARIANT} ."
                        )

                        customImage.push()
                        customImage.push("${VARIANT}")
                    }
                }
            }
        }
    }
    post {
        always {
            // Always cleanup after the build.
            sh 'docker image prune -f --filter until=$(date -d "yesterday" +%Y-%m-%d)'
        }
        success {
            updateGitlabCommitStatus name: 'jenkins', state: 'success'
        }
        failure {
            updateGitlabCommitStatus name: 'jenkins', state: 'failed'
        }
    }
}
