# Nockio
is a simple Docker-based PaaS.

### File: `.compose.nockio`
This is a regular docker-compose configuration.
It is a must in order to run an app on Nockio.

### File: `.proxy.nockio`
This is a JSON file describing the proxy settings for the app.
Is is a must in order to have HTTP and HTTPS communications on Nockio.

Example file:
```json
{
  "domain": "sub.domain.com",
  "hostname": "my-container",
  "rules": {
    "http": "pass/redirect/upgrade",
    "https": "pass/redirect/downgrade"
  }
}
```

#### Proxy: `domain`
The domain name to proxy for the app.

#### Proxy: `rules`
##### Rules: `pass`
This setting means that all communications are passed to the application.

##### Rules: `upgrade`
This setting means that HTTP communications are redirected (internally) to HTTPS on the container.

##### Rules: `downgrade`
This setting means that HTTPS communications are redirected (internally) to HTTP on the container.

##### Rules: `redirect`
This setting means that the connection will be redirected (externally) to the second protocol.


### Proxy configuration
```
 
<VirtualHost *:80>
    Redirect permanent "https://%{HTTP_HOST}%{REQUEST_URI}"
</VirtualHost>
<VirtualHost *:443>
    SSLEngine                       on
    ProxyPreserveHost               on
</VirtualHost>

<VirtualHost *:80>
    ServerName [DOMAIN]
</VirtualHost>
<VirtualHost *:443>
    ServerName [DOMAIN]
    SSLCertificateFile /var/lib/nockio/proxy/certificates/[DOMAIN]/certificate.pem
    SSLCertificateKeyFile /var/lib/nockio/proxy/certificates/[DOMAIN]/private.pem
    SSLCertificateChainFile /var/lib/nockio/proxy/certificates/[DOMAIN]/chain.pem
    ProxyPass / http://[APPLICATION]/
    ProxyPassReverse / http://[APPLICATION]/
</VirtualHost>
```

```apacheconfig
<VirtualHost *:80>
    ServerName dashboard.localhost
</VirtualHost>
<VirtualHost *:443>
    ServerName dashboard.localhost
    SSLCertificateFile /var/lib/nockio/proxy/certificates/dashboard.localhost/certificate.pem
    SSLCertificateKeyFile /var/lib/nockio/proxy/certificates/dashboard.localhost/private.pem
    SSLCertificateChainFile /var/lib/nockio/proxy/certificates/dashboard.localhost/chain.pem
    ProxyPass / http://dashboard/
    ProxyPassReverse / http://dashboard/
</VirtualHost>
```