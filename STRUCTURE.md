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
            GUI/
                // LTS per app
```

### Dashboard bind mounts
```
/nockio/infrastructure/git -> /nockio/infrastructure/git
/nockio/infrastructure/proxy -> /nockio/infrastructure/proxy
```