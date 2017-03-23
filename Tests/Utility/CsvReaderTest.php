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
     * @group v6.0
     *
     * @return void
     */
    public function testExtractDataWithHeader()
    {
        $csvReader = new CsvReader(__DIR__ . '/../Resources/csv/test_csv_semicolon_delimiter_and_header.csv');

        $fileExtract = $csvReader->get();

        $this->assertCount(2, $fileExtract);
        $this->assertCount(3, $fileExtract[0]);
        $this->assertEquals($fileExtract[0][0], 'Dupont');
    }

    /**
     * @group v6.0
     *
     * @return void
     */
    public function testExtractDataWithoutHeader()
    {
        $csvReader = new CsvReader(__DIR__ . '/../Resources/csv/test_csv_tab_delimiter_without_header.csv', "\t", false);

        $fileExtract = $csvReader->get();

        $this->assertCount(2, $fileExtract);
        $this->assertCount(3, $fileExtract[0]);
        $this->assertEquals($fileExtract[0][0], 'Dupont');
    }

    /**
     * @group v6.0
     *
     * @return void
     */
    public function testExtractDataWithtHeaderAndLineEmpty()
    {
        $csvReader = new CsvReader(__DIR__ . '/../Resources/csv/test_csv_with_line_empty.csv');

        $fileExtract = $csvReader->get();

        $this->assertCount(2, $fileExtract);
    }

    /**
     * @group v6.0
     *
     * @return void
     * @expectedException Exception
     */
    public function testExtractDataWithtInvalidColumn()
    {
        $csvReader = new CsvReader(__DIR__ . '/../Resources/csv/test_csv_invalid_number_column.csv');

        $csvReader->get();
    }

    /**
     * @group v6.0
     *
     * @return void
     */
    public function testExtractDataWithSemicolonInColumn()
    {
        $csvReader = new CsvReader(__DIR__ . '/../Resources/csv/test_csv_valid_with_semicolon_in_column.csv');

        $fileExtract = $csvReader->get();

        $this->assertCount(2, $fileExtract);
        $this->assertEquals('Strong;Slow', $fileExtract[1][2]);
    }

    /**
     * @group v6.0
     *
     * @return void
     */
    public function testExtractDataWithQuotation()
    {
        $csvReader = new CsvReader(__DIR__ . '/../Resources/csv/test_csv_valid_with_quote_in_column.csv');

        $fileExtract = $csvReader->get();

        $this->assertCount(1, $fileExtract);
        $this->assertEquals('"', $fileExtract[0][2]);
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
