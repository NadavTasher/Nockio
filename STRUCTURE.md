# Directory structure of LTS
```
/var/lib/nockio/
    git/
        sources/
            MyApp1/
                MyDB/
                    .git/
                    // Git repos
        ssh/
            authorized_keys
    proxy/
        configurations/
            MyApp1.conf
        certificates/
            MyApp1/
                certificate.pem
                private.pem
                chain.pem
            
    applications/
        MyApp1/
            .git/
            .compose.nockio
            .proxy.nockio
            .application.nockio
```

### Dashboard bind mounts
```
/nockio/infrastructure/git -> /nockio/infrastructure/git
/nockio/infrastructure/proxy -> /nockio/infrastructure/proxy
```