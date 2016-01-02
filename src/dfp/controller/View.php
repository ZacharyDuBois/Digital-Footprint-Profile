<?php
/**
 * File: View.php
 * User: zacharydubois
 * Date: 2015-12-30
 * Time: 16:05
 * Project: Digital-Footprint-Profile
 */

namespace dfp;

/**
 * Class View
 * @package dfp
 */
class View {
    private
        $mustache,
        $tpl,
        $content;

    /**
     * view constructor.
     *
     * Create new \Mustache_Engine object.
     */
    public function __construct() {
        $this->mustache = new \Mustache_Engine(array(
            'loader'          => new \Mustache_Loader_FilesystemLoader(THEME),
            'partials_loader' => new \Mustache_Loader_FilesystemLoader(THEME . 'partials' . DS)
        ));
    }

    /**
     * Set Template
     *
     * Sets the template file for mustache to use.
     *
     * @param string $tpl
     * @return bool
     * @throws Exception
     */
    public function tpl($tpl) {
        if (!is_string($tpl)) {
            throw new Exception("Template is not string.");
        }
        if (!file_exists(THEME . $tpl . '.mustache')) {
            throw new Exception("Template does not exist.");
        }

        $this->tpl = $tpl;

        return true;
    }

    /**
     * Set Content
     *
     * Sets the content to be used for generating the template.
     *
     * @param array $payload
     * @return bool
     */
    public function content(array $payload) {
        $this->content = $payload;

        return true;
    }

    /**
     * Render
     *
     * Renders and returns the rendered template.
     *
     * @return string
     */
    public function render() {
        return $this->mustache->render($this->tpl, $this->content);
    }
}