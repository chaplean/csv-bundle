<?php
/**
 * CsvReader.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     0.1.0
 */

namespace Chaplean\Bundle\CsvBundle\Utility;

use Symfony\Component\Config\Definition\Exception\Exception;

class CsvReader
{
    const DEFAULT_END_OF_LINE = "\n";

    /**
     * @var string
     */
    private $file;

    /**
     * @var integer
     */
    private $numColumn = -1;

    /**
     * @param string $file path of file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Return a array
     *
     * @param string  $delimiter        Delimiter used in the file
     * @param integer $numberLineIgnore Number of line to ignore
     * @param string  $endOfLine        Character used on end of lines
     *
     * @return array
     */
    public function extractData($delimiter, $numberLineIgnore = 0, $endOfLine = self::DEFAULT_END_OF_LINE)
    {
        $myFile = fopen($this->file, 'r');
        $data = array();

        $file = explode($endOfLine, fread($myFile, filesize($this->file)));

        for ($i = 0; $i < $numberLineIgnore; $i++) {
            array_shift($file);
        }

        foreach ($file as $line) {
            if (!empty($line)) {
                $data[] = $this->parseLine($line, $delimiter);
            }
        }

        return $data;
    }

    /**
     * Parse line without column name
     *
     * @param string $line      Line of file
     * @param string $delimiter Delimiter of columns
     *
     * @return array
     * @throws Exception
     */
    private function parseLine($line, $delimiter)
    {
        $values = explode($delimiter, $line);
        $dataLine = array();

        if ($this->numColumn != count($values) && $this->numColumn != -1) {
            throw new Exception('Bad line !');
        }

        foreach ($values as $key => $value) {
            $dataLine[] = $value;
        }

        $this->numColumn = count($values);
        return $dataLine;
    }
}
