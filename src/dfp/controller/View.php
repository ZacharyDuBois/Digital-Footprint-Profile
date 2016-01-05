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
 *
 * Handles template rendering.
 *
 * @package dfp
 */
class View {
    private
        $mustache,
        $tpl,
        $content,
        $nav,
        $config;

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
        $this->config = new Config();
    }

    /**
     * Set Template
     *
     * Sets the template file for mustache to use.
     *
     * @param string $tpl
     * @return true
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
     * @return true
     */
    public function content(array $payload) {
        $this->content = $payload;

        return true;
    }

    /**
     * Generate Assets Array
     *
     * Creates an array of assets to be used.
     *
     * @return array
     */
    private function assetsArray() {
        $this->config = new Config();
        $assets = array(
            'css' => array(),
            'js'  => array()
        );
        $css = glob(PATH . 'public' . DS . 'css' . DS . '*.min.css');
        $js = glob(PATH . 'public' . DS . 'js' . DS . '*.min.js');

        foreach ($css as $file) {
            $assets['css'][] = array('url' => Utility::buildFullLink($this->config, true, PUBLIC_URI . 'css/' . basename($file)));
        }
        foreach ($js as $file) {
            $assets['js'][] = array('url' => Utility::buildFullLink($this->config, true, PUBLIC_URI . 'js/' . basename($file)));
        }

        return $assets;
    }

    /**
     * Sets Nav Array
     *
     * Sets the nav array to be used when rendering the template.
     *
     * @param array $navArray
     * @return true
     */
    public function navArray(array $navArray) {
        $this->nav = $navArray;

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
        if ($this->tpl !== 'email' && isset($this->content) && isset($this->tpl) && isset($this->nav)) {
            $base = array(
                'home'      => Utility::buildFullLink($this->config, true),
                'version'   => VERSION,
                'copyright' => $this->config->get('custom', 'copyright')
            );

            $content = array_merge($base, array_merge($this->nav, array_merge($this->assetsArray(), $this->content)));
        } elseif ($this->tpl === 'email') {
            $base = array(
                'home'      => Utility::buildFullLink($this->config),
                'version'   => VERSION,
                'copyright' => $this->config->get('custom', 'copyright')
            );

            $content = array_merge($base, $this->content);
        } else {
            throw new Exception("View cannot render as required content is not set.");
        }


        return $this->mustache->render($this->tpl, $content);
    }
}