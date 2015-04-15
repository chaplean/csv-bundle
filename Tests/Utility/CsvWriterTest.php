<?php
/**
 * CsvWriterTest.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     0.1.0
 */

namespace Chaplean\Bundle\CsvBundle\Tests\Utility;

use Chaplean\Bundle\CsvBundle\Utility\CsvWriter;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\Config\Definition\Exception\Exception;

class CsvWriterTest extends WebTestCase
{
    public function testConstructCsvWriter()
    {
        $csvTool = new CsvWriter(';');

        $this->assertEmpty($csvTool->getContentCsv());
    }

    public function testWriteHeader()
    {
        $csvTool = new CsvWriter(';');

        $headers = array(
            'ID', 'Firstname', 'Lastname', 'Age'
        );

        $csvTool->writeHeader($headers);

        $this->assertEquals("ID;Firstname;Lastname;Age\n", $csvTool->getContentCsv());
    }

    public function testWriteContent()
    {
        $csvTool = new CsvWriter(';');

        $content = array(
            array('1', 'Jean', 'Dupont', '23'),
            array('2', 'Sophie', 'Dupuit', '22'),
        );

        foreach ($content as $line) {
            $csvTool->write($line);
        }

        $this->assertEquals("1;Jean;Dupont;23\n2;Sophie;Dupuit;22\n", $csvTool->getContentCsv());
    }

    /**
     * @expectedException Exception
     */
    public function testSaveWithoutFilename()
    {
        $csvTool = new CsvWriter(';');

        $csvTool->saveCsv();
    }

    public function testSaveWithFilename()
    {
        $fileName = '/tmp/test';
        $csvTool = new CsvWriter(';', $fileName);

        $csvTool->saveCsv();

        $this->assertTrue(file_exists($fileName));
    }
}