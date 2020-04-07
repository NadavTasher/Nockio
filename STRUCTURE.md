# Directory structure of LTS
```
LTS-ROOT/
    configuration/
        MyApp1/
            .nockio - Application properties
            MySQLAppDB.nockio - Deployment configuration
            GUI.nockio - Deployment configuration
    storage/
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