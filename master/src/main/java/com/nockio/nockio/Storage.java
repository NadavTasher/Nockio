package com.nockio.nockio;

import java.io.File;

/**
 * This class represents Long-term storage.
 */
public abstract class Storage {

    // Initialize root directories
    private static final File ROOT = new File(Constants.STORAGE_DIRECTORY);
    private static final File ROOT_DATA = new File(ROOT, Constants.DIRECTORY_DATA);
    private static final File ROOT_CONFIGURATION = new File(ROOT, Constants.DIRECTORY_CONFIGURATIONS);

    private static final File INFRASTRUCTURE_STORAGE = new File(ROOT_DATA, Constants.DIRECTORY_NOCKIO);

    // Git module
    private static final File INFRASTRUCTURE_GIT = new File(INFRASTRUCTURE_STORAGE, "git");
    private static final File INFRASTRUCTURE_SOURCES = new File(INFRASTRUCTURE_GIT, "sources");

    private static final File APPLICATION_STORAGE = new File(ROOT_DATA, Constants.DIRECTORY_APPLICATIONS);
    private static final File APPLICATION_CONFIGURATION = new File(ROOT_CONFIGURATION, Constants.DIRECTORY_APPLICATIONS);

    public static File getSourcesDirectory(String application, String deployment) {

    }

}
