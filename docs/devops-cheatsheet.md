# SolarShare DevOps Quick Reference Cheatsheet

Everything you need to run, access, and manage the SolarShare DevOps stack.

---

## 1. Web Service URLs & Credentials

| Service | URL | Default Username | Default Password / Token | Description |
| :--- | :--- | :--- | :--- | :--- |
| **SolarShare App** | [http://localhost:8088](http://localhost:8088) | — | — | Main Laravel Web Application |
| **App Health Check** | [http://localhost:8088/up](http://localhost:8088/up) | — | — | HTTP 200 health probe |
| **Jenkins CI/CD** | [http://localhost:8082](http://localhost:8082) | `admin` | `admin` | Pipeline Build & Deployment UI |
| **Jenkins Pipeline Job** | [http://localhost:8082/job/SolarShare-Pipeline/](http://localhost:8082/job/SolarShare-Pipeline/) | `admin` | `admin` | Direct link to Build Now |
| **SonarQube** | [http://localhost:9000](http://localhost:9000) | `admin` | `SolarShareDevOps2026!` | Code Quality & Security Dashboard |
| **SonarQube Project** | [http://localhost:9000/dashboard?id=SolarShare](http://localhost:9000/dashboard?id=SolarShare) | `admin` | `SolarShareDevOps2026!` | SolarShare Project Quality Gate |
| **Grafana** | [http://localhost:3000](http://localhost:3000) | `admin` | `admin` | Monitoring & Metrics Dashboards |
| **Grafana Dashboard** | [http://localhost:3000/d/solarshare-overview/solarshare-devops-observability](http://localhost:3000/d/solarshare-overview/solarshare-devops-observability) | `admin` | `admin` | Pre-configured Observability Board |
| **Prometheus Targets** | [http://localhost:9090/targets](http://localhost:9090/targets) | *(No auth)* | *(No auth)* | Scrape status of all 4 targets |
| **Prometheus Query** | [http://localhost:9090/query](http://localhost:9090/query) | *(No auth)* | *(No auth)* | PromQL expression browser |
| **cAdvisor Metrics** | [http://localhost:8085](http://localhost:8085) | *(No auth)* | *(No auth)* | Container hardware stats |
| **Blackbox Exporter** | [http://localhost:9115](http://localhost:9115) | *(No auth)* | *(No auth)* | Endpoint prober interface |
| **MySQL Database** | `127.0.0.1:3308` | `solarshare` | `secretpassword` | App DB (Root Pass: `rootsecretpassword`) |

> **Jenkins SonarQube Token (Credential ID: `sonar-token`):**  
> `squ_2f87a04093c6dd49ffebc4b603ea91838afcf38f`

---

## 2. Daily Docker Commands

Run these from your PowerShell terminal inside the `SolarShare` project directory:

### Start the Entire Stack
```powershell
docker compose up -d
```

### Check Running Containers & Status
```powershell
docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"
```

### Stop the Entire Stack (Preserves Data Volumes)
```powershell
docker compose down
```

### Restart a Specific Container
```powershell
docker restart solarshare-grafana
docker restart solarshare-sonarqube
docker restart solarshare-jenkins
docker restart solarshare-app
```

### View Live Container Logs
```powershell
# Follow Jenkins logs
docker logs -f solarshare-jenkins

# Follow SonarQube logs
docker logs -f solarshare-sonarqube

# Follow Laravel Application logs
docker logs -f solarshare-app
```

---

## 3. Git Workflow (Development & CI Trigger)

Always work on branch `Aziz`. When you are ready to build in Jenkins, merge into `main` and push:

```powershell
# 1. Commit and push your work on Aziz
git checkout Aziz
git add .
git commit -m "feat: your descriptive message"
git push origin Aziz

# 2. Merge into main so Jenkins picks it up
git checkout main
git merge Aziz
git push origin main

# 3. Switch back to your working branch
git checkout Aziz
```

---

## 4. Troubleshooting Quick Fixes

### If SonarQube fails to start:
Verify that WSL2 kernel memory mapping is active:
```powershell
wsl -d docker-desktop -e sysctl vm.max_map_count
```
*Expected output: `vm.max_map_count = 524288`*

If it ever resets on a fresh reboot, re-run:
```powershell
wsl -d docker-desktop -u root sysctl -w vm.max_map_count=524288
```

### If Jenkins pipeline is triggered:
Go to **[http://localhost:8082/job/SolarShare-Pipeline/](http://localhost:8082/job/SolarShare-Pipeline/)** and click **Build Now**.

