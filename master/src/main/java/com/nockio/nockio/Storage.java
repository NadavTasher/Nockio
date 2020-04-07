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

    // Initialize sub-root directories
    private static final File INFRASTRUCTURE_STORAGE = new File(ROOT_DATA, Constants.DIRECTORY_NOCKIO);

    private static final File APPLICATION_STORAGE = new File(ROOT_DATA, Constants.DIRECTORY_APPLICATIONS);
    private static final File APPLICATION_CONFIGURATION = new File(ROOT_CONFIGURATION, Constants.DIRECTORY_APPLICATIONS);

    public static File getSourcesDirectory(){

    }

}
