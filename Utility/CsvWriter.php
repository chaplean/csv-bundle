<?php
/**
 * CsvWriter.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     0.1.0
 */

namespace Chaplean\Bundle\CsvBundle\Utility;

use Symfony\Component\Config\Definition\Exception\Exception;

class CsvWriter
{
    private $fileName = null;

    private $output = '';

    private $endOfLine;

    private $delimiter;

    /**
     * Construct
     *
     * @param string $delimiter Delimiter to use separate datas
     * @param null   $fileName  Name of file to save
     * @param string $endOfLine End of line
     */
    public function __construct($delimiter, $fileName = null, $endOfLine = CsvReader::DEFAULT_END_OF_LINE)
    {
        $this->fileName  = $fileName;
        $this->delimiter = $delimiter;
        $this->endOfLine = $endOfLine;
    }

    /**
     * Write the names of columns
     *
     * @param array $headers
     *
     * @return void
     */
    public function writeHeader($headers)
    {
        $this->write($headers);
    }

    /**
     * Write the content of csv
     *
     * @param array $columns Content of columns
     *
     * @return void
     */
    public function write($columns)
    {
        $line = "";
        foreach ($columns as $key => $header) {
            $line .= ($key ? $this->delimiter : '') . $header;
        }
        $line .= $this->endOfLine;

        $this->output .= $line;
    }

    /**
     * Return a csv content if any file is set
     *
     * @return string
     */
    public function getContentCsv()
    {
        return $this->output;
    }

    /**
     * Save output in a file if filename is set
     *
     * @return void
     * @throws Exception
     */
    public function saveCsv()
    {
        if ($this->fileName != null) {
            $file = fopen($this->fileName, 'w');
            fwrite($file, $this->output);
        } else {
            throw new Exception('No file specified');
        }
    }
}