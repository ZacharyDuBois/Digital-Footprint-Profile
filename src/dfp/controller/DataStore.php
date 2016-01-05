<?php
/**
 * File: DataStore.php
 * User: zacharydubois
 * Date: 2015-12-30
 * Time: 20:35
 * Project: Digital-Footprint-Profile
 */

namespace dfp;


/**
 * Class DataStore
 *
 * Used for simple array storage and retrieval.
 *
 * @package dfp
 */
class DataStore {
    private
        $file;

    /**
     * Set File
     *
     * Sets the the filename for editing.
     *
     * @return true
     * @param string $file
     * @throws Exception
     */
    public function setFile($file) {
        if (!is_string($file)) {
            throw new Exception("setFile did not receive string.");
        }

        $this->file = $file;

        if (!file_exists($file) && !$this->canWrite()) {
            $c = $this->canWrite();
            if ($c) {
                $c = 'true';
            } else {
                $c = 'false';
            }
            throw new Exception($c . "Could not create " . $file . " for writing.");
        }

        return true;
    }

    /**
     * Write Status
     *
     * Checks a file to see if it can be written to.
     *
     * @return bool
     */
    private function canWrite() {
        if (file_exists($this->file)) {
            return is_writable($this->file);
        } else {
            return is_writable(dirname($this->file));
        }
    }

    /**
     * Read Status
     *
     * Checks a file to see if it can be read.
     *
     * @return bool
     */
    private function canRead() {
        if (is_readable(dirname($this->file))) {
            if (is_readable($this->file)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Read File
     *
     * Reads and decodes file.
     *
     * @return array
     * @throws Exception
     */
    public function read() {
        if ($this->canRead() && file_exists($this->file)) {
            $data = json_decode(file_get_contents($this->file), true);
        }

        if (isset($data) && $data !== false) {
            return $data['payload'];
        } elseif (!isset($data) || $data === false) {
            return array();
        }

        throw new Exception('Cannot read ' . $this->file);
    }

    /**
     * Read File Meta
     *
     * Reads the prepended meta information on the file.
     *
     * @return array|false
     */
    public function readMeta() {
        if ($this->canRead()) {
            $data = json_decode(file_get_contents($this->file), true);

            return $data['meta'];
        }

        return false;
    }

    /**
     * Sets File Meta
     *
     * Creates the file meta array that is prepended to files.
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
     * Write
     *
     * Write payload array to the file. Prepends the file meta with the payload.
     *
     * @param array $payload
     * @return bool
     * @throws Exception
     */
    public function write(array $payload) {
        if ($this->canWrite()) {
            $data = array(
                'meta'    => $this->setMeta(),
                'payload' => $payload
            );

            if (file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT))) {
                return true;
            } else {
                throw new Exception('Failed to write to ' . $this->file);
            }
        }

        return false;
    }
}