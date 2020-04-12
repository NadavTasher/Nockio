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
http {
    server {
        server_name [domain];

        location / {
            proxy_redirect [to]://[hostname] [from]://[domain];
        }

        ssl_certificate /var/lib/nockio/proxy/certificates/[domain]/chain.crt;
        ssl_certificate_key /var/lib/nockio/proxy/certificates/[domain]/private.key;
    }
}