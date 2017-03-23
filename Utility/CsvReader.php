<?php

namespace Chaplean\Bundle\CsvBundle\Utility;

/**
 * Class CsvReader.
 *
 * @package   Chaplean\Bundle\CsvBundle\Utility
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     0.1.0
 */
class CsvReader
{
    const DEFAULT_DELIMITER = ';';
    const DEFAULT_SURROUNDING = '"';
    const DEFAULT_END_OF_LINE = "\n";
    const KEY_INDEX = 0;
    const KEY_HEADER = 1;

    /**
     * @var string
     */
    private $delimiter;

    /**
     * @var resource
     */
    private $fp;

    /**
     * @var boolean
     */
    private $hasHeader;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var integer|null
     */
    private $length;

    /**
     * @var integer
     */
    private $keyNaming;

    /**
     * @var integer
     */
    private $index;

    /**
     * @var integer
     */
    private $nbColumnsInFirstRow;

    /**
     * CsvReader constructor.
     *
     * @param string       $file
     * @param string       $delimiter
     * @param boolean      $hasHeader
     * @param integer      $keyNaming
     * @param null|integer $length
     *
     * @throws \Exception
     */
    public function __construct($file, $delimiter = self::DEFAULT_DELIMITER, $hasHeader = true, $keyNaming = self::KEY_INDEX, $length = null)
    {
        if (!$hasHeader && $keyNaming === self::KEY_HEADER) {
            throw new \Exception('Arguments incompatible ! ("KEY_HEADER" and "hasHeader" false is not possible)');
        }

        $this->fp = fopen($file, 'r');
        $this->keyNaming = $keyNaming;
        $this->delimiter = $delimiter;
        $this->hasHeader = $hasHeader;
        $this->length = $length;
        $this->index = 0;

        if ($this->keyNaming == self::KEY_HEADER || $this->hasHeader) {
            $this->headers = fgetcsv($this->fp, $this->length, $this->delimiter);
        }
    }

    /**
     *
     */
    public function __destruct()
    {
        if ($this->fp) {
            fclose($this->fp);
        }
    }

    /**
     * @return array
     */
    public function get()
    {
        $data = array();

        while (($row = fgetcsv($this->fp, $this->length, $this->delimiter)) !== false) {
            if ($this->index === 0) {
                $this->nbColumnsInFirstRow = count($row);
            }

            $this->index++;
            if (!$this->isValidRow($row)) {
                continue;
            }

            switch ($this->keyNaming) {
                case self::KEY_HEADER:
                    $rowNew = array();

                    foreach ($this->headers as $i => $header) {
                        $rowNew[$header] = $row[$i];
                    }

                    $data[] = $rowNew;
                    break;
                case self::KEY_INDEX:
                default:
                    $data[] = $row;
            }
        }

        return $data;
    }

    /**
     * @param array $row
     *
     * @return boolean
     * @throws \Exception
     */
    public function isValidRow(array $row)
    {
        if ($row[0] === null) {
            return false;
        }

        if ($this->nbColumnsInFirstRow != count($row)) {
            throw new \Exception('Bad line !');
        }

        return true;
    }
}
