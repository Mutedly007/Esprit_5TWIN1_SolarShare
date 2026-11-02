import jenkins.model.Jenkins
import hudson.plugins.sonar.SonarGlobalConfiguration
import hudson.plugins.sonar.SonarInstallation
import hudson.plugins.sonar.model.TriggersConfig
import com.cloudbees.plugins.credentials.SystemCredentialsProvider
import com.cloudbees.plugins.credentials.domains.Domain
import org.jenkinsci.plugins.plaincredentials.impl.StringCredentialsImpl
import com.cloudbees.plugins.credentials.CredentialsScope
import hudson.util.Secret

def jenkins = Jenkins.get()

// 1. Add / Update SonarQube Secret Token Credential
def tokenSecret = "squ_2f87a04093c6dd49ffebc4b603ea91838afcf38f"
def credsProvider = SystemCredentialsProvider.getInstance()
def domain = Domain.global()

def existingCred = credsProvider.getCredentials(domain).find { it.id == "sonar-token" }
if (!existingCred) {
    def credential = new StringCredentialsImpl(
        CredentialsScope.GLOBAL,
        "sonar-token",
        "SonarQube Auth Token",
        Secret.fromString(tokenSecret)
    )
    credsProvider.addCredentials(domain, credential)
    println("--> SolarShare: SonarQube credential 'sonar-token' created successfully.")
} else {
    println("--> SolarShare: SonarQube credential 'sonar-token' already present.")
}

// 2. Configure SonarQube Server in Jenkins Global Configuration
try {
    def sonarDesc = jenkins.getDescriptorByType(SonarGlobalConfiguration.class)
    if (sonarDesc != null) {
        def existingInstallations = sonarDesc.getInstallations()
        def existingSonar = existingInstallations ? existingInstallations.find { it.name == "SonarQube" } : null

        if (existingSonar == null) {
            def installation = new SonarInstallation(
                "SonarQube",
                "http://sonarqube:9000",
                "sonar-token",
                "",
                "",
                new TriggersConfig(),
                ""
            )
            sonarDesc.setInstallations(installation)
            sonarDesc.save()
            println("--> SolarShare: SonarQube installation 'SonarQube' registered successfully.")
        } else {
            println("--> SolarShare: SonarQube installation 'SonarQube' already registered.")
        }
    }
} catch (Throwable t) {
    println("--> SolarShare: Notice during SonarQube config: " + t.getMessage())
}

