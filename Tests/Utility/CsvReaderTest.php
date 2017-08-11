<?php

namespace Chaplean\Bundle\CsvBundle\Tests\Utility;

use Chaplean\Bundle\CsvBundle\Utility\CsvReader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * CsvReaderTest.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     0.1.0
 */
class CsvReaderTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvReader::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvReader::get()
     *
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
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvReader::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvReader::get()
     *
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
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvReader::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvReader::get()
     *
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
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvReader::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvReader::get()
     *
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
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvReader::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvReader::get()
     *
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
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvReader::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvReader::get()
     *
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
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvReader::__construct()
     *
     * @return void
     * @expectedException Exception
     */
    public function testReadFileNotExists()
    {
        new CsvReader(__DIR__ . '/../Resources/csv/file_not_found.csv');
    }

    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvReader::__construct()
     *
     * @group v6.0
     *
     * @return void
     * @expectedException Exception
     */
    public function testIncompatibleArgument()
    {
        new CsvReader(__DIR__ . '/../Resources/csv/file_not_found.csv', CsvReader::DEFAULT_DELIMITER, false, CsvReader::KEY_HEADER);
    }

    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvReader::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvReader::get()
     *
     * @group v6.0
     *
     * @return void
     */
    public function testExtractDateWithHeaderKey()
    {
        $csvReader = new CsvReader(__DIR__ . '/../Resources/csv/test_csv_semicolon_delimiter_and_header.csv', CsvReader::DEFAULT_DELIMITER, true, CsvReader::KEY_HEADER);

        $fileExtract = $csvReader->get();

        $this->assertCount(2, $fileExtract);
        $this->assertCount(3, $fileExtract[0]);
        $this->assertArrayHasKey('Nom', $fileExtract[0]);
        $this->assertArrayHasKey('Prenom', $fileExtract[0]);
        $this->assertArrayHasKey('Age', $fileExtract[0]);
        $this->assertEquals($fileExtract[0]['Nom'], 'Dupont');
    }
}
