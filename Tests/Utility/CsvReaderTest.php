<?php

namespace Chaplean\Bundle\CsvBundle\Tests\Utility;

use Chaplean\Bundle\CsvBundle\Utility\CsvReader;
use Chaplean\Bundle\UnitBundle\Test\LogicalTestCase;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * CsvReaderTest.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     0.1.0
 */
class CsvReaderTest extends LogicalTestCase
{
    /**
     * @return void
     */
    public function testExtractDataWithHeader()
    {
        $csvReader = new CsvReader(__DIR__ . '/../Resources/csv/test_csv_semicolon_delimiter_and_header.csv');

        $fileExtract = $csvReader->extractData(';', 1);

        $this->assertCount(2, $fileExtract);
        $this->assertCount(3, $fileExtract[0]);
        $this->assertEquals($fileExtract[0][0], 'Dupont');
    }

    /**
     * @return void
     */
    public function testExtractDataWithSemicolonDelimiter()
    {
        $csvReader = new CsvReader(__DIR__ . '/../Resources/csv/test_csv_semicolon_delimiter_and_header.csv');

        $fileExtract = $csvReader->extractData(';', 1);

        $this->assertCount(2, $fileExtract);
        $this->assertCount(3, $fileExtract[0]);
        $this->assertEquals($fileExtract[0][0], 'Dupont');
    }

    /**
     * @return void
     */
    public function testExtractDataWithoutHeader()
    {
        $csvReader = new CsvReader(__DIR__ . '/../Resources/csv/test_csv_tab_delimiter_without_header.csv');

        $fileExtract = $csvReader->extractData("\t");

        $this->assertCount(2, $fileExtract);
        $this->assertCount(3, $fileExtract[0]);
        $this->assertEquals($fileExtract[0][0], 'Dupont');
    }

    /**
     * @return void
     */
    public function testExtractDataWithTabDelimiter()
    {
        $csvReader = new CsvReader(__DIR__ . '/../Resources/csv/test_csv_tab_delimiter_without_header.csv');

        $fileExtract = $csvReader->extractData("\t");

        $this->assertCount(2, $fileExtract);
        $this->assertCount(3, $fileExtract[0]);
        $this->assertEquals($fileExtract[0][0], 'Dupont');
    }

    /**
     * @return void
     */
    public function testExtractDataWithtHeaderAndLineEmpty()
    {
        $csvReader = new CsvReader(__DIR__ . '/../Resources/csv/test_csv_with_line_empty.csv');

        $fileExtract = $csvReader->extractData(';', 1);
        $this->assertCount(2, $fileExtract);
    }

    /**
     * @return void
     * @expectedException Exception
     */
    public function testExtractDataWithtInvalidColumn()
    {
        $csvReader = new CsvReader(__DIR__ . '/../Resources/csv/test_csv_invalid_number_column.csv');

        $csvReader->extractData(';');
    }

    /**
     * @return void
     */
    public function testExtractDataWithMultipleLineToIgnore()
    {
        $csvReader = new CsvReader(__DIR__ . '/../Resources/csv/test_csv_with_multiple_line_ignore.csv');

        $fileExtract = $csvReader->extractData(';', 2);
        $this->assertCount(2, $fileExtract);
    }

    /**
     * @return void
     * @expectedException Exception
     */
    public function testReadFileNotExists()
    {
        new CsvReader(__DIR__ . '/../Resources/csv/file_not_found.csv');
    }
}
