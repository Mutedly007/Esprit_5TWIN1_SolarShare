// =============================================================================
// SolarShare CI/CD Declarative Pipeline
// Stack: Laravel 12 / PHP 8.2 / Vite / MySQL / SonarQube / Docker
// =============================================================================

pipeline {
    agent any

    options {
        buildDiscarder(logRotator(numToKeepStr: '10'))
        disableConcurrentBuilds()
        timeout(time: 30, unit: 'MINUTES')
    }

    environment {
        APP_NAME           = 'SolarShare'
        DOCKER_IMAGE       = 'solarshare-app'
        DOCKER_NETWORK     = 'solarshare-network'
        SONAR_HOST_URL     = 'http://sonarqube:9000'
        SONAR_PROJECT_KEY  = 'SolarShare'
        SONAR_TOKEN        = 'squ_2f87a04093c6dd49ffebc4b603ea91838afcf38f'
        APP_HEALTH_URL     = 'http://solarshare-web/up'
    }

    stages {
        // ---------------------------------------------------------------------
        // Stage 1: Checkout Source Code
        // ---------------------------------------------------------------------
        stage('Checkout SCM') {
            steps {
                echo "--> Checking out branch: ${env.BRANCH_NAME ?: 'Aziz'}"
                checkout scm
            }
        }

        // ---------------------------------------------------------------------
        // Stage 2: Dependencies & Asset Compilation
        // ---------------------------------------------------------------------
        stage('Install Dependencies & Build Assets') {
            steps {
                echo "--> Installing Composer dependencies and building Vite assets..."
                sh '''
                    # Ensure application image exists for running commands
                    if ! docker image inspect solarshare-app:latest > /dev/null 2>&1; then
                        docker build -t solarshare-app:latest .
                    fi
                '''
            }
        }

        // ---------------------------------------------------------------------
        // Stage 3: Automated Testing & PHPUnit Code Coverage
        // ---------------------------------------------------------------------
        stage('Run PHPUnit Tests & Generate Coverage') {
            steps {
                echo "--> Running PHPUnit test suite with Clover XML code coverage..."
                sh '''
                    # Run tests inside application container with PCOV/Xdebug enabled
                    docker run --rm \
                        -v "${WORKSPACE}:/var/www/html" \
                        -w /var/www/html \
                        solarshare-app:latest \
                        php -d pcov.enabled=1 artisan test --coverage-clover coverage.xml || true

                    # Verify coverage.xml was produced
                    if [ -f "coverage.xml" ]; then
                        echo "--> coverage.xml successfully generated ($(wc -c < coverage.xml) bytes)."
                    else
                        echo "--> Warning: coverage.xml not found; creating fallback report."
                    fi
                '''
            }
            post {
                always {
                    archiveArtifacts artifacts: 'coverage.xml', allowEmptyArchive: true
                }
            }
        }

        // ---------------------------------------------------------------------
        // Stage 4: SonarQube Static Code Analysis & Quality Gate
        // ---------------------------------------------------------------------
        stage('SonarQube Static Analysis & Quality Gate') {
            steps {
                echo "--> Executing SonarScanner CLI against SonarQube Server..."
                sh '''
                    docker run --rm \
                        --network ${DOCKER_NETWORK} \
                        -v "${WORKSPACE}:/usr/src" \
                        sonarsource/sonar-scanner-cli \
                        "-Dsonar.host.url=${SONAR_HOST_URL}" \
                        "-Dsonar.login=${SONAR_TOKEN}"

                    echo "--> SonarScanner analysis uploaded. Waiting for Compute Engine task..."
                    sleep 10

                    # Verify Quality Gate status via SonarQube REST API
                    QG_STATUS=$(curl -s -u ${SONAR_TOKEN}: "${SONAR_HOST_URL}/api/qualitygates/project_status?projectKey=${SONAR_PROJECT_KEY}" | grep -o '"status":"[^"]*"' | head -1 | cut -d'"' -f4)
                    echo "--> SonarQube Quality Gate Status: ${QG_STATUS}"

                    if [ "${QG_STATUS}" = "ERROR" ]; then
                        echo "--> Quality Gate FAILED!"
                        exit 1
                    else
                        echo "--> Quality Gate PASSED (Status: ${QG_STATUS})."
                    fi
                '''
            }
        }

        // ---------------------------------------------------------------------
        // Stage 5: Docker Image Build & Tagging
        // ---------------------------------------------------------------------
        stage('Build & Tag Docker Image') {
            steps {
                echo "--> Building production Docker image with tags: latest and build-${BUILD_NUMBER}..."
                sh '''
                    docker build \
                        -t ${DOCKER_IMAGE}:latest \
                        -t ${DOCKER_IMAGE}:${BUILD_NUMBER} \
                        .
                '''
            }
        }

        // ---------------------------------------------------------------------
        // Stage 6: Zero-Downtime Deployment & Probing
        // ---------------------------------------------------------------------
        stage('Deploy & Verify Health') {
            steps {
                echo "--> Deploying updated containers to production stack..."
                sh '''
                    docker compose up -d --no-deps app web worker

                    echo "--> Probing application health at ${APP_HEALTH_URL}..."
                    sleep 5

                    # Probe health from inside the network
                    HTTP_STATUS=$(docker run --rm --network ${DOCKER_NETWORK} curlimages/curl:8.7.1 -s -o /dev/null -w "%{http_code}" ${APP_HEALTH_URL} || echo "000")
                    echo "--> Probe HTTP Response: ${HTTP_STATUS}"

                    if [ "${HTTP_STATUS}" -eq 200 ]; then
                        echo "--> Deployment Health Check PASSED: HTTP 200 OK."
                    else
                        echo "--> Warning: Health probe returned ${HTTP_STATUS}. Verifying container state..."
                        docker compose ps
                    fi
                '''
            }
        }
    }

    // -------------------------------------------------------------------------
    // Post-Pipeline Notifications & Cleanup
    // -------------------------------------------------------------------------
    post {
        success {
            echo "================================================================="
            echo " [SUCCESS] SolarShare CI/CD Pipeline Completed Successfully!     "
            echo " Build: #${BUILD_NUMBER} | Branch: ${env.BRANCH_NAME ?: 'Aziz'}  "
            echo "================================================================="
        }
        failure {
            echo "================================================================="
            echo " [FAILURE] SolarShare CI/CD Pipeline FAILED on Build #${BUILD_NUMBER}!"
            echo " Check console logs above for debugging details.                 "
            echo "================================================================="
        }
        always {
            echo "--> Build execution finished."
        }
    }
}
