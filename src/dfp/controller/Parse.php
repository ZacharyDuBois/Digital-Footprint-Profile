<?php
/**
 * File: Parse.php
 * User: zacharydubois
 * Date: 2016-01-05
 * Time: 20:20
 * Project: Digital-Footprint-Profile
 */

namespace dfp;

use PHPInsight\Sentiment;

/**
 * Class Parse
 *
 * Used to parse post contents for a score.
 *
 * @package dfp
 */
class Parse {
    private
        $insight,
        $DataStore,
        $keywords,
        $content,
        $insightScore,
        $keywordScore;

    /**
     * Rules constructor.
     *
     * Creates objects for use and loads keywords.
     */
    public function __construct() {
        $this->insight = new Sentiment();
        $this->DataStore = new DataStore();
        $this->DataStore->setFile(KEYWORDS);
        $this->keywords = $this->DataStore->read();
    }

    /**
     * Parse Content
     *
     * Parses content into filters to generate a score.
     *
     * @param string $content
     */
    public function parse($content) {
        $this->content = strtolower(filter_var($content));
        $this->insightScore = $this->sentiment();
        $this->keywordScore = $this->keywords();
    }

    /**
     * Create Score
     *
     * Creates score out of 10 points.
     *
     * @return int
     */
    public function score() {
        $score = $this->keywordScore + $this->insightScore;

        if ($score > 10) {
            $score = 10;
        }

        return $score;
    }

    /**
     * Get Tags
     *
     * Retrieves post tags.
     *
     * @return string
     */
    public function tags() {
        $tags = '';

        if ($this->keywordScore > 0) {
            $tags .= '| Keywords found. Points: ' . $this->keywordScore . ' ';
        }

        if ($this->insightScore > 0) {
            $tags .= '| Post appears negative. Points: ' . $this->insightScore;
        }

        return $tags;
    }

    /**
     * Keyword Check
     *
     * Check to see if the content contains keywords.
     *
     * @return int
     */
    private function keywords() {
        $score = 0;

        $breakdown = array_count_values(str_word_count($this->content, 1));

        foreach ($this->keywords as $keyword => $weight) {
            if (array_key_exists($keyword, $breakdown)) {
                $score = $score + ($breakdown[$keyword] * $weight);
            }
        }

        return $score;
    }

    /**
     * Sentiment Analysis
     *
     * Analyze the string using pos, neg and neu terms.
     *
     * @return int
     */
    private function sentiment() {
        $score = $this->insight->score($this->content)['neg'];

        if ($score >= .75) {
            return 3;
        } elseif ($score < .5 && $score >= .4) {
            return 2;
        } elseif ($score < .4 && $score > .2) {
            return 1;
        } else {
            return 0;
        }
    }
}