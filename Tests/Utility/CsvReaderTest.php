<?php
/**
 * CsvReaderTest.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     0.1.0
 */

namespace Chaplean\Bundle\CsvBundle\Tests\Utility;

use Chaplean\Bundle\CsvBundle\Utility\CsvReader;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\Config\Definition\Exception\Exception;

class CsvReaderTest extends WebTestCase
{

    public function testExtractDataWithHeader()
    {
        $csvReader = new CsvReader('src/Chaplean/Bundle/CsvBundle/Resources/doc/test_csv_semicolon_delimiter_and_header.csv');

        $fileExtract = $csvReader->extractData(';', 1);

        $this->assertCount(2, $fileExtract);
        $this->assertCount(3, $fileExtract[0]);
        $this->assertEquals($fileExtract[0][0], 'Dupont');
    }

    public function testExtractDataWithSemicolonDelimiter()
    {
        $csvReader = new CsvReader('src/Chaplean/Bundle/CsvBundle/Resources/doc/test_csv_semicolon_delimiter_and_header.csv');

        $fileExtract = $csvReader->extractData(';', 1);

        $this->assertCount(2, $fileExtract);
        $this->assertCount(3, $fileExtract[0]);
        $this->assertEquals($fileExtract[0][0], 'Dupont');
    }


    public function testExtractDataWithoutHeader()
    {
        $csvReader = new CsvReader('src/Chaplean/Bundle/CsvBundle/Resources/doc/test_csv_tab_delimiter_without_header.csv');

        $fileExtract = $csvReader->extractData("\t");

        $this->assertCount(2, $fileExtract);
        $this->assertCount(3, $fileExtract[0]);
        $this->assertEquals($fileExtract[0][0], 'Dupont');
    }

    public function testExtractDataWithTabDelimiter()
    {
        $csvReader = new CsvReader('src/Chaplean/Bundle/CsvBundle/Resources/doc/test_csv_tab_delimiter_without_header.csv');

        $fileExtract = $csvReader->extractData("\t");

        $this->assertCount(2, $fileExtract);
        $this->assertCount(3, $fileExtract[0]);
        $this->assertEquals($fileExtract[0][0], 'Dupont');
    }

    public function testExtractDataWithtHeaderAndLineEmpty()
    {
        $csvReader = new CsvReader('src/Chaplean/Bundle/CsvBundle/Resources/doc/test_csv_with_line_empty.csv');

        $fileExtract = $csvReader->extractData(';', 1);
        $this->assertCount(2, $fileExtract);
    }

    /**
     * @expectedException Exception
     */
    public function testExtractDataWithtInvalidColumn()
    {
        $csvReader = new CsvReader('src/Chaplean/Bundle/CsvBundle/Resources/doc/test_csv_invalid_number_column.csv');

        $csvReader->extractData(';');
    }

    public function testExtractDataWithMultipleLineToIgnore()
    {
        $csvReader = new CsvReader('src/Chaplean/Bundle/CsvBundle/Resources/doc/test_csv_with_multiple_line_ignore.csv');

        $fileExtract = $csvReader->extractData(';', 2);
        $this->assertCount(2, $fileExtract);
    }

    /**
     * @expectedException Exception
     */
    public function testReadFileNotExists()
    {
        new CsvReader('Resources/doc/file_not_found.csv');
    }
}