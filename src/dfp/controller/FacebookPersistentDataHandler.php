<?php
/**
 * File: FacebookPersistentDataHandler.php
 * User: zacharydubois
 * Date: 2016-03-20
 * Time: 19:38
 * Project: Digital-Footprint-Profile
 */

namespace dfp;

use \Facebook\PersistentData\PersistentDataInterface;


/**
 * Class FacebookPersistentDataHandler
 *
 * Impliments use of DFP session handler for Facebook PHP SDK.
 *
 * @package dfp
 */
class FacebookPersistentDataHandler implements PersistentDataInterface {
    private
        $session;

    /**
     * FacebookPersistentDataHandler constructor.
     * @param Session $session
     */
    public function __construct(Session $session) {
        $this->session = $session;
    }

    /**
     * @inheritdoc
     */
    public function get($key) {
        return $this->session->getTMP('fbsdk_' . $key);
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value) {
        $this->session->setTMP('fbsdk_' . $key, $value);
    }
}