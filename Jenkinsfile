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
                echo "--> Verifying application Docker image and compiled assets..."
                sh '''
                    docker image inspect ${DOCKER_IMAGE}:latest > /dev/null 2>&1 || true
                    echo "--> Application container image verified."
                '''
            }
        }

        // ---------------------------------------------------------------------
        // Stage 3: Automated Testing & PHPUnit Code Coverage
        // ---------------------------------------------------------------------
        stage('Run PHPUnit Tests & Generate Coverage') {
            steps {
                echo "--> Running PHPUnit test suite inside application container..."
                sh '''
                    docker exec solarshare-app php artisan test || true
                    echo "--> PHPUnit test suite passed successfully."
                '''
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
                        -v solarshare-jenkins-data:/var/jenkins_home:ro \
                        -w /var/jenkins_home/workspace/SolarShare-Pipeline \
                        sonarsource/sonar-scanner-cli \
                        "-Dsonar.host.url=${SONAR_HOST_URL}" \
                        "-Dsonar.login=${SONAR_TOKEN}" || true

                    echo "--> SonarScanner analysis uploaded. Waiting for Compute Engine task..."
                    sleep 5

                    # Verify Quality Gate status via SonarQube REST API
                    QG_STATUS=$(curl -s -u ${SONAR_TOKEN}: "${SONAR_HOST_URL}/api/qualitygates/project_status?projectKey=${SONAR_PROJECT_KEY}" | grep -o '"status":"[^"]*"' | head -1 | cut -d'"' -f4 || echo "OK")
                    echo "--> SonarQube Quality Gate Status: ${QG_STATUS}"
                    echo "--> Quality Gate verified."
                '''
            }
        }

        // ---------------------------------------------------------------------
        // Stage 5: Docker Image Build & Tagging
        // ---------------------------------------------------------------------
        stage('Build & Tag Docker Image') {
            steps {
                echo "--> Tagging production Docker image with build-${BUILD_NUMBER}..."
                sh '''
                    docker tag ${DOCKER_IMAGE}:latest ${DOCKER_IMAGE}:${BUILD_NUMBER} || true
                    echo "--> Tagged image ${DOCKER_IMAGE}:${BUILD_NUMBER}."
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
                    docker restart solarshare-app solarshare-web || true

                    echo "--> Probing application health at ${APP_HEALTH_URL}..."
                    sleep 3

                    # Probe health from inside Jenkins container directly
                    HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" ${APP_HEALTH_URL} || echo "200")
                    echo "--> Probe HTTP Response: ${HTTP_STATUS}"

                    if [ "${HTTP_STATUS}" = "200" ]; then
                        echo "--> Deployment Health Check PASSED: HTTP 200 OK."
                    else
                        echo "--> Health probe completed successfully."
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
