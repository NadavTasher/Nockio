# Directory structure of LTS
```
/nockio/
    infrastructure/
        git/
            sources/
                // Sources per app
            ssh/
                // SSH pub-keys
        proxy/
            configurations/
            
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