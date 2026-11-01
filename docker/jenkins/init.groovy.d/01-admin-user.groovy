import jenkins.model.*
import hudson.security.*

def instance = Jenkins.getInstance()

def currentRealm = instance.getSecurityRealm()
if (!(currentRealm instanceof HudsonPrivateSecurityRealm)) {
    def hudsonRealm = new HudsonPrivateSecurityRealm(false)
    hudsonRealm.createAccount("admin", "SolarShareDevOps2026!")
    instance.setSecurityRealm(hudsonRealm)
} else {
    def user = currentRealm.getUser("admin")
    if (user == null) {
        currentRealm.createAccount("admin", "SolarShareDevOps2026!")
    }
}

def strategy = new FullControlOnceLoggedInAuthorizationStrategy()
strategy.setAllowAnonymousRead(false)
instance.setAuthorizationStrategy(strategy)

instance.save()
println("--> SolarShare: Jenkins admin user and authorization strategy configured successfully.")
