<?php
/**
 * File: Session.php
 * User: zacharydubois
 * Date: 2016-01-01
 * Time: 23:28
 * Project: Digital-Footprint-Profile
 */

namespace dfp;

/**
 * Class Session
 *
 * Handles session generation, storage, and $_SESSION.
 * TODO: Allow more than one session to be running.
 *
 * @package dfp
 */
class Session {
    private
        $fileSession,
        $DataStore,
        $sid,
        $config;

    /**
     * Session constructor.
     *
     * Sets session settings and initializes php session. Sets variables for use.
     *
     * @param Config $config
     */
    public function __construct(Config $config) {
        // Determine if a new session or current.
        $this->sid = filter_input(INPUT_COOKIE, DFP_SESSION_NAME);

        $this->config = $config;
        $this->DataStore = new DataStore();

        if ($this->isSession($this->sid)) {
            $this->reload();
        } else {
            $this->newSession();
        }


        if (!array_key_exists('dfp', $this->fileSession)) {
            // Is new session, initialize data.
            $this->initData();
        }

        if ($this->isExpired()) {
            // Session is expired. End it.
            $this->endSession();
        }
    }

    /**
     * Validates SID
     *
     * Validates format and checks if the session exists.
     *
     * @param string $sid
     * @return bool
     */
    public static function isSession($sid) {
        if (Validate::sid($sid)) {
            return file_exists(STORAGE . $sid . '.json');
        }

        return false;
    }

    /**
     * Sets the SID
     *
     * Sets the SID for new sessions.
     *
     * @return true
     */
    private function newSession() {
        // Start the session.
        $this->sid = Utility::generateSID();

        setcookie(DFP_SESSION_NAME, $this->sid, (time() + DFP_SESSION_LIFE), Utility::buildFullLink($this->config, true, 'session'), $this->config->get('server', 'domain'), Utility::httpsBool($this->config), true);

        // Set variables.
        $this->reload();

        return true;
    }

    /**
     * Load Session
     *
     * Loads the session file that is set in $this->sid.
     *
     * @return true
     * @throws Exception
     */
    private function reload() {
        $this->DataStore->setFile(STORAGE . $this->sid . '.json');
        $this->fileSession = $this->DataStore->read();

        return true;
    }

    /**
     * Initializes Session Data
     *
     * Initializes session files with data.
     *
     * @return true
     * @throws Exception
     */
    private function initData() {
        $data = array(
            'dfp'   => array(
                'create' => time(),
                'expire' => (time() + DFP_SESSION_LIFE),
                'sid'    => $this->sid
            ),
            'data'  => array(),
            'email' => null
        );

        if (!$this->DataStore->write($data)) {
            throw new Exception("Failed to write initialization data.");
        }

        $this->fileSession = $this->DataStore->read();

        return true;
    }

    /**
     * Checks Expiration.
     *
     * Checks the expiration time in the session file.
     *
     * @return bool
     */
    private function isExpired() {
        if (time() > $this->fileSession['dfp']['expire']) {
            $this->newSession();

            return true;
        }

        return false;
    }

    /**
     * Ends and Clears Session
     *
     * Ends the PHP session, deletes the cookie, and updates the session file expire time.
     *
     * @return true
     * @throws Exception
     */
    public function endSession() {
        // Remove the cookie.
        setcookie(DFP_SESSION_NAME, $this->sid, (time() - 1), Utility::buildFullLink($this->config, true, 'session'), $this->config->get('server', 'domain'), Utility::httpsBool($this->config), true);

        // Update file expire.
        $this->fileSession['dfp']['expire'] = time();

        // Clear TMP
        $this->clearTMP();

        if (!$this->DataStore->write($this->fileSession)) {
            throw new Exception("Unable to update session expire time.");
        }

        return true;
    }

    /**
     * Get Temporary Session Information
     *
     * Get information stored in the DataStore.
     *
     * @param string $key
     * @return string|array|int|false
     */
    public function getTMP($key) {
        $payload = $this->get('tmp');

        if ($payload === false) {
            $payload = array();
        }

        if (array_key_exists($key, $payload)) {
            return $payload[$key];
        }

        return false;
    }

    /**
     * Set Temporary Session Information
     *
     * Sets temporary information to the DataStore.
     * Used for request keys, etc.
     *
     * @param string $key
     * @param string|int|array $value
     * @return true
     * @throws Exception
     */
    public function setTMP($key, $value) {
        $pre = $this->get('tmp');
        $pre[$key] = $value;

        return $this->set('tmp', $pre);
    }

    /**
     * Clear TMP Session Information
     *
     * Clears all temporary session information.
     *
     * @return true
     * @throws Exception
     */
    private function clearTMP() {
        return $this->set('tmp', '');
    }

    /**
     * Get Session Data
     *
     * Get session data under a key.
     *
     * @param string $key
     * @return string|array|false
     */
    public function get($key) {
        if (array_key_exists($key, $this->fileSession['data'])) {
            return $this->fileSession['data'][$key];
        }

        return false;
    }

    /**
     * Set Session Data
     *
     * Sets session data for the specified key.
     *
     * @param string $key
     * @param string|array $value
     * @return true
     * @throws Exception
     */
    public function set($key, $value) {
        $this->fileSession['data'][$key] = $value;

        if (!$this->DataStore->write($this->fileSession)) {
            throw new Exception("Could not update session information.");
        }

        return true;
    }

    /**
     * Add Posts
     *
     * Adds posts to the current array of posts pulled from social networks.
     *
     * @param $new
     * @return true
     * @throws Exception
     */
    public function addPosts($new) {
        $cur = $this->get('posts');

        if ($cur === false) {
            $cur = array();
        }

        $merged = array_merge($cur, $new);
        $this->set('posts', $merged);

        unset($cur);
        unset($new);
        unset($merged);

        return true;
    }
}