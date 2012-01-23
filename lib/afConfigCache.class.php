<?php
/**
 * We need to reimplement some parts of this class
 * The only reason is to control permissions that are set on newly created files
 */
class afConfigCache extends sfConfigCache
{
    protected function writeCacheFile($config, $cache, $data)
    {
        if (!is_dir(dirname($cache))) {
            if (false === @mkdir(dirname($cache), 0700, true)) {
                throw new sfCacheException(sprintf('Failed to make cache directory "%s" while generating cache for configuration file "%s".', dirname($cache), $config));
            }
        }

        $tmpFile = tempnam(dirname($cache), basename($cache));

        if (!$fp = @fopen($tmpFile, 'wb')) {
            throw new sfCacheException(sprintf('Failed to write cache file "%s" generated from configuration file "%s".', $tmpFile, $config));
        }

        @fwrite($fp, $data);
        @fclose($fp);
        
        // Hack from Agavi (http://trac.agavi.org/changeset/3979)
        // With php < 5.2.6 on win32, renaming to an already existing file doesn't work, but copy does,
        // so we simply assume that when rename() fails that we are on win32 and try to use copy()
        if (!@rename($tmpFile, $cache))
        {
            if (copy($tmpFile, $cache))
            {
                unlink($tmpFile);
            }
        }
    }

}