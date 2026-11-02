# 🛠️ CI/CD & SonarQube Troubleshooting Guide

This guide details host and container optimizations required for running the Jenkins CI/CD pipeline and SonarQube on WSL2/Docker.

---

## 1. Host WSL2 Configuration (`.wslconfig`)

When running Jenkins, SonarQube (with embedded Elasticsearch), MySQL, and Laravel containers concurrently on Windows/WSL2, memory constraints can cause JVM processes to stall or crash (OOM 137).

Create or update `C:\Users\<Your-Username>\.wslconfig` (for example, on an 8GB RAM host):

```ini
[wsl2]
# On an 8GB total RAM host, reserve 5GB for WSL2 / Docker and leave 3GB for Windows
memory=5GB
# Swap provides virtual memory buffer to prevent SonarQube/Elasticsearch OOM crashes
swap=6GB
processors=4
# Automatically sets vm.max_map_count for Elasticsearch permanently on every WSL2 boot
kernelCommandLine = sysctl.vm.max_map_count=524288
```

Apply changes by restarting WSL2 from PowerShell:
```powershell
wsl --shutdown
```

---

## 2. Elasticsearch Memory Map Limit (`vm.max_map_count`)

Elasticsearch requires adequate virtual memory mmap counts to index active rules without throwing `OutOfMemoryError` or failing socket requests.

* **Via PowerShell (already set for Docker Desktop):**
  ```powershell
  wsl -u root sysctl -w vm.max_map_count=524288
  ```
* **Via Linux / WSL2 bash:**
  ```bash
  sudo sysctl -w vm.max_map_count=524288
  echo "vm.max_map_count=524288" | sudo tee -a /etc/sysctl.conf
  ```
```

---

## 3. Diagnostic Commands

If SonarQube or the scanner encounters timeouts, inspect the container state using:

* **Inspect SonarQube container logs:**
  ```bash
  docker logs solarshare-sonarqube --tail 200 -f
  ```

* **Inspect embedded Elasticsearch logs directly:**
  ```bash
  docker exec solarshare-sonarqube tail -n 100 /opt/sonarqube/logs/es.log
  ```

* **Check live container CPU and Memory usage:**
  ```bash
  docker stats --no-stream
  ```

* **Verify SonarQube operational readiness:**
  ```bash
  curl -s http://localhost:9000/api/system/status
  # Expected when healthy: {"id":"...","version":"9.9.8...","status":"UP"}
  ```

---

## 4. Security Reminders & Secret Revocation

1. **SonarQube Token (`sonar-token`):**
   * The previously exposed static token (`squ_2f87a04093c6dd49ffebc4b603ea91838afcf38f`) should be revoked in SonarQube:
     * Navigate to: **Administration > Security > Users > Tokens**.
     * Revoke the old token and generate a new User/Project analysis token for `SolarShare`.
     * Update the credential `sonar-token` in Jenkins under **Manage Jenkins > Credentials**.
2. **Application Key (`APP_KEY`):**
   * The exposed development key in `.env` or history should be rotated for production:
     ```bash
     php artisan key:generate
     ```
   * CI test stages now automatically generate a secure, ephemeral throwaway key at runtime (`openssl rand -base64 32`) rather than storing static secrets in the pipeline script.

