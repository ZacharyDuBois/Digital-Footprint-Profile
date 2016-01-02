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
        if(!is_string($file)) {
            throw new Exception("setFile did not receive string.");
        }

        if (!file_exists($file) && !$this->canWrite()) {
            throw new Exception("Could not create " . $file . " for writing.");
        }

        $this->file = $file;

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
        if (is_writeable(dirname($this->file))) {
            if (is_writeable($this->file)) {
                return true;
            }
        }

        return false;
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

            return $data['payload'];
        } elseif($this->canRead() && !file_exists($this->file)) {
            // Quick Fix. If file doesn't exist and a read is attempted, just return a blank array.
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