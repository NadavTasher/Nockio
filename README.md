# Nockio
is a simple Docker-based PaaS.

## Installation
Make sure to install [Docker](https://docker.com) on your system beforehand.

Make sure your local user is in the `docker` group:

```bash
sudo usermod -aG docker $USER
```

Make sure docker is enabled on boot:

```bash
sudo systemctl enable docker
```

Download the `.deb` file from the `Releases` tab.

Run the following command:
```bash
sudo apt install ./nockio_*.deb
```

Modify the `/var/lib/nockio/.compose.nockio` file as needed

Make sure no other program is using the configured ports.

Finally, run:
```bash
nockio up
```

This process can take some time.

## Project file reference

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

## Usage
To access the dashboard, navigate to `dashboard.localhost` in your web browser.

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)