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

    public function testWriteWithDelimiterInContent()
    {
        $csvTool = new CsvWriter(';');

        $content = array(
            array('1', 'Jean', 'Dupon;t', '23'),
            array('2', 'Sophie', 'Dupuit', '22'),
        );

        foreach ($content as $line) {
            $csvTool->write($line);
        }

        $this->assertEquals("1;Jean;Dupon;t;23\n2;Sophie;Dupuit;22\n", $csvTool->getContentCsv());
    }

    public function testSaveWithTabEndOfLine()
    {
        $csvTool = new CsvWriter(';', "\t");

        $content = array(
            array('1', 'Jean', 'Dupont', '23'),
            array('2', 'Sophie', 'Dupuit', '22'),
        );

        foreach ($content as $line) {
            $csvTool->write($line);
        }

        $this->assertEquals("1;Jean;Dupont;23\t2;Sophie;Dupuit;22\t", $csvTool->getContentCsv());
    }

    public function testSaveInTwoFile()
    {
        $csv = new CsvWriter(';');

        $content = array(
            array('1', 'Jean', 'Dupont', '23'),
            array('2', 'Sophie', 'Dupuit', '22'),
        );

        foreach ($content as $line) {
            $csv->write($line);
        }

        $this->assertEquals("1;Jean;Dupont;23\n2;Sophie;Dupuit;22\n", $csv->getContentCsv());

        $file1 = '/tmp/test1.csv';
        $file2 = '/tmp/test2.csv';

        $csv->saveCsv($file1);
        $csv->saveCsv($file2);

        $this->assertTrue(file_exists($file1));
        $this->assertTrue(file_exists($file2));
    }

    /**
     * @expectedException Exception
     */
    public function testSaveWithoutFilename()
    {
        $csvTool = new CsvWriter(';');

        $csvTool->saveCsv(null);
    }

    public function testSaveWithFilename()
    {
        $fileName = '/tmp/test';
        $csvTool = new CsvWriter(';');

        $csvTool->saveCsv($fileName);

        $this->assertTrue(file_exists($fileName));
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage fopen(/folder/test): failed to open stream: No such file or directory
     */
    public function testSaveWithFilenamePathNotExsit()
    {
        $fileName = '/folder/test';
        $csvTool = new CsvWriter(';');

        $csvTool->saveCsv($fileName);

        $this->assertFalse(file_exists($fileName));
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage fopen(/var/log/app.log): failed to open stream: Permission denied
     */
    public function testSaveWithFileInRootDirectory()
    {
        $fileName = '/var/log/app.log';

        $csvTool = new CsvWriter(';');

        $csvTool->saveCsv($fileName);

        $this->assertFalse(file_exists($fileName));
    }
}