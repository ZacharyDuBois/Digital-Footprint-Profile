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
 *
 * @package dfp
 */
class Session {
    private
        $fileSession,
        $DataStore,
        $sid;

    /**
     * Session constructor.
     *
     * Sets session settings and initializes php session. Sets variables for use.
     */
    public function __construct() {
        // Determine if a new session or current.
        $sid = filter_input(INPUT_COOKIE, DFP_SESSION_NAME);

        if ((!isset($sid) || $sid === false) && defined('DFP_SESSION_SID')) {
            $sid = DFP_SESSION_SID;
        }

        // Start the session.
        if (!$this->isSession($sid)) {
            $this->sid = $this->newSession();
            define('DFP_SESSION_SID', $this->sid);

            $config = new Config();

            setcookie(DFP_SESSION_NAME, $this->sid, (time() + DFP_SESSION_LIFE), Utility::buildFullLink($config, true, 'session'), $config->get('server', 'domain'), Utility::httpsBool($config), true);

            unset($config);

        }

        // Set variables.
        $this->DataStore = new DataStore();
        $this->DataStore->setFile(STORAGE . $this->sid . '.json');
        $this->fileSession = $this->DataStore->read();

        if (!isset($this->fileSession['dfp']['expire'])) {
            // Is new session, initialize data.
            $this->initData();
        } elseif ($this->isExpired()) {
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
     * @return string
     */
    private function newSession() {
        $sid = Utility::generateSID();

        return $sid;
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
        $time = time();

        $data = array(
            'dfp'   => array(
                'create' => $time,
                'expire' => (DFP_SESSION_LIFE + $time),
                'sid'    => $this->sid
            ),
            'data'  => array(),
            'email' => null
        );

        $this->fileSession = $data;

        if (!$this->DataStore->write($data)) {
            throw new Exception("Failed to write initialization data.");
        }

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
        if ($this->fileSession['dfp']['expire'] <= time()) {
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
        $config = new Config();
        setcookie(DFP_SESSION_NAME, $this->sid, (time() - 1), Utility::buildFullLink($config, true, 'session'), $config->get('server', 'domain'), Utility::httpsBool($config), true);

        // Update file expire.
        $this->fileSession['dfp']['expire'] = time();

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
        if (isset($this->get('tmp')[$key])) {
            return $this->get('tmp')[$key];
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
    public function clearTMP() {
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
        if (isset($this->fileSession['data'][$key])) {
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
}