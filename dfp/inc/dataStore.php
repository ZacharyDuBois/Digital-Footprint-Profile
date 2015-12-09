<?php
/**
 * File: dataStore.php
 * User: zacharydubois
 * Date: 2015-12-06
 * Time: 20:58
 * Project: Digital-Footprint-Profile
 */

namespace DFP;

/**
 * Class dataStore
 *
 * Used for simple json storage.
 *
 * @package DFP
 */
class dataStore {
    private
        $path;

    /**
     * dataStore constructor.
     * @param string $path
     * @throws dfpException
     */
    public function __construct($path) {
        $this->path = $path;

        if (!file_exists($this->path) && !$this->canWrite()) {
            throw new dfpException("Could not create " . $path . " for writing.");
        }
    }

    /**
     * File write check.
     *
     * @return bool
     */
    private function canWrite() {
        if (is_writeable(dirname($this->path))) {
            if (is_writeable($this->path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * File read check.
     *
     * @return bool
     */
    private function canRead() {
        if (is_readable(dirname($this->path))) {
            if (is_readable($this->path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Reads dataStore file.
     *
     * @return array
     * @throws dfpException
     */
    public function read() {
        if ($this->canRead()) {
            $data = json_decode(file_get_contents($this->path), true);

            return $data['payload'];
        }

        throw new dfpException('Cannot read ' . $this->path);
    }

    /**
     * Get the appended file metadata.
     *
     * @return array|bool
     */
    public function readMeta() {
        if ($this->canRead()) {
            $data = json_encode(file_get_contents($this->path), true);

            return $data['meta'];
        }

        return false;
    }

    /**
     * Set the file meta.
     *
     * @return array
     */
    private function setMeta() {
        $meta = array(
            'version'   => VERSION,
            'lastWrite' => time()
        );

        return $meta;
    }

    /**
     * Writes dataStore file.
     *
     * @param array $payload
     * @return bool
     * @throws dfpException
     */
    public function write(array $payload) {
        if ($this->canRead() && $this->canWrite()) {
            $data = array(
                'meta'    => $this->setMeta(),
                'payload' => $payload
            );

            if (file_put_contents($this->path, json_encode($data, JSON_PRETTY_PRINT))) {
                return true;
            } else {
                throw new dfpException('Failed to write to ' . $this->path);
            }
        }

        return false;
    }
}