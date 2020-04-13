# Nockio
is a simple Docker-based PaaS.

### File: `.compose.nockio`
This is a regular docker-compose configuration.
It is a must in order to deploy an app on Nockio.

### File: `.proxy.nockio`
This file describes the proxy configuration.
The proxy is an Apache2 server, with sub-configurations for each application.

Example configuration:
```apacheconfig
<VirtualHost *:80>
    # Set the server name
    ServerName dashboard.localhost
    # Redirect to HTTPS
    Redirect / https://dashboard.localhost/
</VirtualHost>
<VirtualHost *:443>
    # Set the server name
    ServerName dashboard.localhost
    # Enable SSL/TLS
    SSLEngine On
    # Set the certificate files
    SSLCertificateFile /var/lib/nockio/certificates/dashboard.localhost/certificate.crt
    SSLCertificateKeyFile /var/lib/nockio/certificates/dashboard.localhost/private.key
    # Proxy
    ProxyPass / http://dashboard/
    ProxyPassReverse / http://dashboard/
</VirtualHost>
```

### File: `.application.nockio`
This file describes the application, and is used to show information on the dashboard.

Example configuration:
```json
{
  "description": "A simple PaaS based on Docker",
  "services": [
    "PHP"
  ]
}
```

### File: `.log.compose.nockio`
This file is a log file of the application's deployment.
It is generated once you push a commit on `master`.