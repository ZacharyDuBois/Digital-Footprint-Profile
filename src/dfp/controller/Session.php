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
        $sid,
        $phpSession;

    /**
     * Session constructor.
     *
     * Sets session settings and initializes php session. Sets variables for use.
     */
    public function __construct() {
        // Setup PHP session handling.
        $config = new Config();
        session_name(DFP_SESSION_NAME);
        ini_set("session.gc_maxlifetime", DFP_SESSION_LIFE);
        session_set_cookie_params(DFP_SESSION_LIFE, $config->get('server', 'base') . '/session', $config->get('server', 'name'), $config->get('server', 'https'), true);
        unset($config);

        // Determine if a new session or current.
        $sid = filter_input(INPUT_COOKIE, DFP_SESSION_NAME);

        if (isset($sid) || !$this->isSession($sid)) {
            $this->sid = $this->newSession();
        } else {
            $this->sid = $sid;
        }

        // Start the session.
        session_start();

        // Set variables.
        $this->DataStore = new DataStore();
        $this->DataStore->setFile(STORAGE . $this->sid . '.json');
        $this->fileSession = $this->DataStore->read();
        $this->phpSession = filter_input_array(INPUT_SESSION);

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
        session_id($sid);

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
                'expire' => $time + DFP_SESSION_LIFE,
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
        // Clear session data.
        $_SESSION = array();

        // Remove the cookie.
        $cookieParams = session_get_cookie_params();
        setcookie(DFP_SESSION_NAME, '', time() - 1, $cookieParams['path'], $cookieParams['domain'], $cookieParams['secure'], true);

        // End the PHP sessions.
        session_unset();
        session_destroy();

        // Update file expire.
        $this->fileSession['dfp']['expire'] = time();

        if (!$this->DataStore->write($this->fileSession)) {
            throw new Exception("Unable to update session expire time.");
        }

        define('DFP_SESSION_ENDED', true);
        
        return true;
    }

    /**
     * Get Temporary Session Information
     *
     * Get information stored in the PHP session.
     *
     * @param string $key
     * @return string|array|false
     */
    public function getTMP($key) {
        $value = $this->phpSession[$key];
        if (isset($value)) {
            return $value;
        }

        return false;
    }

    /**
     * Set Temporary Session Information
     *
     * Sets temporary information to the PHP session.
     * Used for request keys, etc.
     *
     * @param string $key
     * @param string|array $value
     * @return true
     */
    public function setTMP($key, $value) {
        $_SESSION[$key] = $value;

        return true;
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
        $value = $this->fileSession['data'][$key];
        if (isset($value)) {
            return $value;
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