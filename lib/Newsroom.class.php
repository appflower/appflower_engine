<?php

/**
 * A support for long polling.
 * It requires the newsroom daemon to work properly.
 * http://www.immune.dk/doc_chapter/show/04_23_newsroom
 */
class Newsroom {
    const SOCKET_FILENAME = 'unix:///installed/dev/newsroom/wait.socket';

    /**
     * Waits for unseen news or until the timeout.
     * The already seen news are recognized based on the client uid.
     */
    public static function waitForNews($topic, $uid, $timeoutMillis=10000)
    {
        self::closeSessionWriteLock();
        if (!$topic || !$uid) {
            return;
        }

        $timeoutSeconds = (int)($timeoutMillis / 1000);
        $fp = fsockopen(self::SOCKET_FILENAME, -1,
            $errno, $errstr, $timeoutSeconds + 1);
        if (!$fp) {
            sleep($timeoutSeconds);
            return;
        }

        $timeoutMicros = ($timeoutMillis % 1000) * 1000;
        if(!stream_set_timeout($fp, $timeoutSeconds, $timeoutMicros)) {
            return;
        }

        $msg = sprintf("%s %s\n", $topic, $uid);
        if(!fwrite($fp, $msg)) {
            fclose($fp);
            return;
        }
        fflush($fp);

        fgets($fp, 4);
        fclose($fp);
    }

    /**
     * Waits for news if the request is OK with that.
     * Pagging and sorting requests do not allow waiting.
     */
    public static function waitIfRequested($topic)
    {
        $request = sfContext::getInstance()->getRequest();
        $wait = $request->getParameter('_wait') === '1';
        if($wait) {
            $uid = $request->getParameter('uid');
            self::waitForNews($topic, $uid);
        } else {
            self::closeSessionWriteLock();
        }
    }

    /**
     * Closes a write lock opened by a session_start().
     * The lock should not be hold while waiting.
     */
    public static function closeSessionWriteLock()
    {
        $user = sfContext::getInstance()->getUser();
        if($user) {
            $user->shutdown();
        } else {
            session_write_close();
        }
    }
}
