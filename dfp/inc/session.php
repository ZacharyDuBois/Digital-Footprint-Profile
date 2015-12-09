<?php
/**
 * File: session.php
 * User: zacharydubois
 * Date: 2015-12-09
 * Time: 09:36
 * Project: Digital-Footprint-Profile
 */

namespace DFP;


class session {
    private
        $session,
        $dataStore,
        $sessData,
        $sessNew,
        $path,
        $cookie;

    /**
     * session constructor.
     * @param string|null $session
     */
    public function __construct($session = null) {
        // phpStorm doesn't like when you put filter_* in isset()
        $this->cookie = filter_input(INPUT_SERVER, COOKIENAME);

        switch ($session) {
            case null:
                if (isset($cookie)) {
                    $this->session = $cookie;
                } else {
                    $this->session = $this->newSession();
                }
                break;

            default:
                $this->session = $session;
                break;
        }


        $this->path = SESSIONPATH . '/' . $this->session . '.json';
        $this->dataStore = new dataStore($this->path);
        $this->sessData = $this->dataStore->read();
        $this->sessNew = $this->sessData;

    }

    /**
     * Creates new session.
     *
     * @return string
     */
    private function newSession() {
        $key = util::genSessionToken();
        $time = time();
        // 14 Days for access.
        $expire = $time + (14 * 24 * 60 * 60);
        // 15 Minutes for cookie.
        $cookieExpire = $time + 900;

        $initData = array(
            'key'    => $key,
            'time'   => $time,
            'expire' => $expire
        );

        $dataStore = new dataStore(SESSIONPATH . '/' . $this->session . '.json');
        $dataStore->write($initData);
        unset($dataStore);

        setcookie(COOKIENAME, $key, $cookieExpire, '/', DFPHOST);

        return $key;
    }

    /**
     * Ends current cookie session.
     *
     * @return bool
     */
    public function endSession() {
        setcookie(COOKIENAME, '', time() - 1, '/', DFPHOST);

        return true;
    }

    /**
     * Returns data related to the session.
     *
     * @return array
     */
    public function read() {
        return $this->sessData;

    }

    /**
     * Writes new data to temp var.
     *
     * @param array $payload
     */
    public function write(array $payload) {
        $this->sessNew = array_merge($payload, $this->sessNew);
    }

    /**
     * Saves the temp var to disk.
     *
     * @return bool
     * @throws dfpException
     */
    public function save() {
        $this->dataStore->write($this->sessNew);

        return true;
    }

    /**
     * Returns if the session exists.
     *
     * @return bool
     */
    public function isSession() {
        return file_exists($this->path);
    }

    /**
     * Returns if the session is current.
     *
     * @return bool
     */
    public function isCurrent() {
        if (isset($this->cookie) || $this->sessData['expire'] <= time()) {
            return true;
        }

        return false;
    }

    /**
     * Lists all available sessions.
     *
     * @return array
     */
    public static function listAllSessions() {
        $sessions = scandir(SESSIONPATH);
        $pattern = '^([[:xdigit:]]{40}\.json)$';
        $z = array();

        foreach ($sessions as $x) {
            if (preg_match($pattern, $x)) ;
            $z[] = $x;
        }

        return $z;
    }
}