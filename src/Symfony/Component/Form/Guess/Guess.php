<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Guess;

/**
 * Base class for guesses made by TypeGuesserInterface implementation
 *
 * Each instance contains a confidence value about the correctness of the guess.
 * Thus an instance with confidence HIGH_CONFIDENCE is more likely to be
 * correct than an instance with confidence LOW_CONFIDENCE.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
abstract class Guess
{
    /**
     * Marks an instance with a value that is extremely likely to be correct
     * @var integer
     */
    const VERY_HIGH_CONFIDENCE = 3;

    /**
     * Marks an instance with a value that is very likely to be correct
     * @var integer
     */
    const HIGH_CONFIDENCE = 2;

    /**
     * Marks an instance with a value that is likely to be correct
     * @var integer
     */
    const MEDIUM_CONFIDENCE = 1;

    /**
     * Marks an instance with a value that may be correct
     * @var integer
     */
    const LOW_CONFIDENCE = 0;

    /**
     * The list of allowed confidence values
     * @var array
     */
    private static $confidences = array(
        self::VERY_HIGH_CONFIDENCE,
        self::HIGH_CONFIDENCE,
        self::MEDIUM_CONFIDENCE,
        self::LOW_CONFIDENCE,
    );

    /**
     * The confidence about the correctness of the value
     *
     * One of VERY_HIGH_CONFIDENCE, HIGH_CONFIDENCE, MEDIUM_CONFIDENCE
     * and LOW_CONFIDENCE.
     *
     * @var integer
     */
    private $confidence;

    /**
     * Returns the guess most likely to be correct from a list of guesses
     *
     * If there are multiple guesses with the same, highest confidence, the
     * returned guess is any of them.
     *
     * @param array $guesses A list of guesses
     *
     * @return Guess The guess with the highest confidence
     */
    public static function getBestGuess(array $guesses)
    {
        $result = null;
        $maxConfidence = -1;

        foreach ($guesses as $guess) {
            if ($maxConfidence < $confidence = $guess->getConfidence()) {
                $maxConfidence = $confidence;
                $result = $guess;
            }
        }

        return $result;
    }

    /**
     * Constructor
     *
     * @param integer $confidence The confidence
     *
     * @throws \UnexpectedValueException if the given value of confidence is unknown
     */
    public function __construct($confidence)
    {
        if (!in_array($confidence, self::$confidences)) {
            throw new \UnexpectedValueException(sprintf('The confidence should be one of "%s"', implode('", "', self::$confidences)));
        }

        $this->confidence = $confidence;
    }

    /**
     * Returns the confidence that the guessed value is correct
     *
     * @return integer One of the constants VERY_HIGH_CONFIDENCE,
     *                 HIGH_CONFIDENCE, MEDIUM_CONFIDENCE and LOW_CONFIDENCE
     */
    public function getConfidence()
    {
        return $this->confidence;
    }
}
