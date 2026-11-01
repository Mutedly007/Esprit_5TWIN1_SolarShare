FROM jenkins/jenkins:lts-jdk17

USER root

# Install prerequisites and Docker CLI binary for Docker-outside-of-Docker
RUN apt-get update && \
    apt-get install -y --no-install-recommends curl tar ca-certificates && \
    curl -fsSL https://download.docker.com/linux/static/stable/x86_64/docker-26.0.0.tgz | tar -xz -C /tmp && \
    mv /tmp/docker/docker /usr/local/bin/docker && \
    rm -rf /tmp/docker && \
    groupadd -f -g 999 docker && \
    usermod -aG docker jenkins && \
    rm -rf /var/lib/apt/lists/*

# Pre-install essential pipeline plugins
RUN jenkins-plugin-cli --plugins "workflow-aggregator git pipeline-stage-view"

# Copy automated Groovy initialization scripts
COPY init.groovy.d/ /usr/share/jenkins/ref/init.groovy.d/

USER jenkins
