<?php

namespace Chaplean\Bundle\CsvBundle\Tests\Model;

/**
 * TestModel.php.
 *
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.coop)
 * @since     5.0.0
 */
class TestModel
{
    /** @var string $string */
    public $string;

    /** @var integer $integer */
    public $integer;

    /** @var float $float */
    public $float;

    /**
     * TestModel constructor.
     */
    public function __construct()
    {
        $this->string = 'test"test';
        $this->integer = 42;
        $this->float = 3.14;
    }
}
