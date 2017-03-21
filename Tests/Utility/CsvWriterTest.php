<?php

namespace Chaplean\Bundle\CsvBundle\Tests\Utility;

use Chaplean\Bundle\CsvBundle\Tests\Model\TestModel;
use Chaplean\Bundle\CsvBundle\Utility\CsvReader;
use Chaplean\Bundle\CsvBundle\Utility\CsvWriter;
use Chaplean\Bundle\UnitBundle\Test\LogicalTestCase;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * CsvWriterTest.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.com)
 * @since     0.1.0
 */
class CsvWriterTest extends LogicalTestCase
{
    /**
     * @return void
     */
    public function testSetDataWithArray()
    {
        /** @var CsvWriter $csvWriter */
        $csvWriter = $this->getContainer()->get('chaplean.csv.writer');

        $csvWriter->setData(array(
            new TestModel(),
            new TestModel()
        ));

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $serializeRow->invokeArgs($csvWriter, array(new TestModel()));
    }

    /**
     * @return void
     */
    public function testSetDataWithGenerator()
    {
        /** @var CsvWriter $csvWriter */
        $csvWriter = $this->getContainer()->get('chaplean.csv.writer');

        $csvWriter->setData($this->generator());

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $serializeRow->invokeArgs($csvWriter, array(new TestModel()));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage You have to provide $dataClass if $data can be empty
     *
     * @return void
     */
    public function testSetDataWithEmptyArray()
    {
        /** @var CsvWriter $csvWriter */
        $csvWriter = $this->getContainer()->get('chaplean.csv.writer');

        $csvWriter->setData(array());

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $serializeRow->invokeArgs($csvWriter, array(new TestModel()));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage You have to provide $dataClass if $data can be empty
     *
     * @return void
     */
    public function testSetDataWithEmptyGenerator()
    {
        /** @var CsvWriter $csvWriter */
        $csvWriter = $this->getContainer()->get('chaplean.csv.writer');

        $csvWriter->setData($this->emptyGenerator());

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $serializeRow->invokeArgs($csvWriter, array(new TestModel()));
    }

    /**
     * @return void
     */
    public function testSetDataWithEmptyArrayAndDataClass()
    {
        /** @var CsvWriter $csvWriter */
        $csvWriter = $this->getContainer()->get('chaplean.csv.writer');

        $csvWriter->setData(array(), TestModel::class);

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $serializeRow->invokeArgs($csvWriter, array(new TestModel()));
    }

    /**
     * @return void
     */
    public function testSetDataWithEmptyGeneratorAndDataClass()
    {
        /** @var CsvWriter $csvWriter */
        $csvWriter = $this->getContainer()->get('chaplean.csv.writer');

        $csvWriter->setData($this->emptyGenerator(), TestModel::class);

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $serializeRow->invokeArgs($csvWriter, array(new TestModel()));
    }

    /**
     * @return void
     * @expectedException \InvalidArgumentException
     */
    public function testSetDataWithInvalidData()
    {
        /** @var CsvWriter $csvWriter */
        $csvWriter = $this->getContainer()->get('chaplean.csv.writer');

        $csvWriter->setData(null);
    }

    /**
     * @return void
     * @expectedException \InvalidArgumentException
     */
    public function testSerializeRowWithArrayChecksModelClass()
    {
        /** @var CsvWriter $csvWriter */
        $csvWriter = $this->getContainer()->get('chaplean.csv.writer');

        $csvWriter->setData(array(
            new TestModel(),
            new TestModel()
        ));

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $serializeRow->invokeArgs($csvWriter, array(new \stdClass()));
    }

    /**
     * @return void
     * @expectedException \InvalidArgumentException
     */
    public function testSerializeRowWithGeneratorChecksModelClass()
    {
        /** @var CsvWriter $csvWriter */
        $csvWriter = $this->getContainer()->get('chaplean.csv.writer');

        $csvWriter->setData($this->generator());

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $serializeRow->invokeArgs($csvWriter, array(new \stdClass()));
    }

    /**
     * @return void
     */
    public function testSerializeRowWithArray()
    {
        /** @var CsvWriter $csvWriter */
        $csvWriter = $this->getContainer()->get('chaplean.csv.writer');

        $csvWriter->setData(array(
            new TestModel(),
            new TestModel()
        ));

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $result = $serializeRow->invokeArgs($csvWriter, array(new TestModel()));

        $this->assertEquals('"test""test";"42";"3.14"' . "\n", $result);
    }

    /**
     * @return void
     */
    public function testSerializeRowWithGenerator()
    {
        /** @var CsvWriter $csvWriter */
        $csvWriter = $this->getContainer()->get('chaplean.csv.writer');

        $csvWriter->setData($this->generator());

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $result = $serializeRow->invokeArgs($csvWriter, array(new TestModel()));

        $this->assertEquals('"test""test";"42";"3.14"' . "\n", $result);
    }

    /**
     * @return void
     */
    public function testSerializeHeadersWithArray()
    {
        /** @var CsvWriter $csvWriter */
        $csvWriter = $this->getContainer()->get('chaplean.csv.writer');

        $csvWriter->setData(array(
            new TestModel(),
            new TestModel()
        ));

        $serializeHeaders = $this->getNotPublicMethod(CsvWriter::class, 'serializeHeaders');
        $result = $serializeHeaders->invokeArgs($csvWriter, array());

        $this->assertEquals('"string";"integer";"float"' . "\n", $result);
    }

    /**
     * @return void
     */
    public function testSerializeHeadersWithGenerator()
    {
        /** @var CsvWriter $csvWriter */
        $csvWriter = $this->getContainer()->get('chaplean.csv.writer');

        $csvWriter->setData($this->generator());

        $serializeHeaders = $this->getNotPublicMethod(CsvWriter::class, 'serializeHeaders');
        $result = $serializeHeaders->invokeArgs($csvWriter, array());

        $this->assertEquals('"string";"integer";"float"' . "\n", $result);
    }

    /**
     * @return void
     */
    public function testSerializeRowWithArrayWithCustomParameters()
    {
        /** @var CsvWriter $csvWriter */
        $csvWriter = $this->getContainer()->get('chaplean.csv.writer');

        $csvWriter->setData(array(
            new TestModel(),
            new TestModel()
        ));
        $csvWriter->setDelimiter(',');
        $csvWriter->setSurrounding("'");
        $csvWriter->setEndOfLine("\r\n");
        $csvWriter->setTranslationPrefix("test.csv");

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $result = $serializeRow->invokeArgs($csvWriter, array(new TestModel()));

        $this->assertEquals("'test\"test','42','3.14'\r\n", $result);
    }

    /**
     * @return void
     */
    public function testSerializeRowWithGeneratorWithCustomParameters()
    {
        /** @var CsvWriter $csvWriter */
        $csvWriter = $this->getContainer()->get('chaplean.csv.writer');

        $csvWriter->setData($this->generator());
        $csvWriter->setDelimiter(',');
        $csvWriter->setSurrounding("'");
        $csvWriter->setEndOfLine("\r\n");
        $csvWriter->setTranslationPrefix("test.csv");

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $result = $serializeRow->invokeArgs($csvWriter, array(new TestModel()));

        $this->assertEquals("'test\"test','42','3.14'\r\n", $result);
    }

    /**
     * @return void
     */
    public function testSerializeHeadersWithArrayAndCustomParameters()
    {
        /** @var CsvWriter $csvWriter */
        $csvWriter = $this->getContainer()->get('chaplean.csv.writer');

        $csvWriter->setData(array(
            new TestModel(),
            new TestModel()
        ));
        $csvWriter->setDelimiter(',');
        $csvWriter->setSurrounding("'");
        $csvWriter->setEndOfLine("\r\n");
        $csvWriter->setTranslationPrefix("test.csv");

        $serializeHeaders = $this->getNotPublicMethod(CsvWriter::class, 'serializeHeaders');
        $result = $serializeHeaders->invokeArgs($csvWriter, array());

        $this->assertEquals("'String','Integer','Float'\r\n", $result);
    }

    /**
     * @return void
     */
    public function testSerializeHeadersWithGeneratorAndCustomParameters()
    {
        /** @var CsvWriter $csvWriter */
        $csvWriter = $this->getContainer()->get('chaplean.csv.writer');

        $csvWriter->setData($this->generator());
        $csvWriter->setDelimiter(',');
        $csvWriter->setSurrounding("'");
        $csvWriter->setEndOfLine("\r\n");
        $csvWriter->setTranslationPrefix("test.csv");

        $serializeHeaders = $this->getNotPublicMethod(CsvWriter::class, 'serializeHeaders');
        $result = $serializeHeaders->invokeArgs($csvWriter, array());

        $this->assertEquals("'String','Integer','Float'\r\n", $result);
    }

    /**
     * @return void
     * @expectedException \LogicException
     */
    public function testWriteToResponseWithoutData()
    {
        /** @var CsvWriter $csvWriter */
        $csvWriter = $this->getContainer()->get('chaplean.csv.writer');

        $csvWriter->writeToResponse('test_file');
    }

    /**
     * @return void
     * @expectedException \LogicException
     */
    public function testWriteToFileWithoutData()
    {
        /** @var CsvWriter $csvWriter */
        $csvWriter = $this->getContainer()->get('chaplean.csv.writer');

        $csvWriter->writeToFile('/tmp/test_file.csv');
    }

    /**
     * @return void
     */
    public function testWriteToResponse()
    {
        /** @var CsvWriter $csvWriter */
        $csvWriter = $this->getContainer()->get('chaplean.csv.writer');

        $csvWriter->setData(array(
            new TestModel(),
            new TestModel()
        ));

        $response = $csvWriter->writeToResponse('test_file');
        $this->assertInstanceOf(StreamedResponse::class, $response);

        // Can't test generated output because expectOutputString is based on ob_flush
        // and we need to call it in streamCallback().
    }

    /**
     * @return void
     */
    public function testWriteToFile()
    {
        /** @var CsvWriter $csvWriter */
        $csvWriter = $this->getContainer()->get('chaplean.csv.writer');

        $csvWriter->setData($this->generator());

        $success = $csvWriter->writeToFile('/tmp/test_file.csv');
        $this->assertTrue($success);

        $csvReader = new CsvReader('/tmp/test_file.csv');
        $readData = $csvReader->extractData(';');
        $this->assertEquals(
            array(
                array('string', 'integer', 'float'),
                array('test""test', '42', '3.14'),
                array('test""test', '42', '3.14'),
            ),
            $readData
        );
    }

    /**
     * @return void
     */
    public function testWriteToFileFailToCreateDirectory()
    {
        /** @var CsvWriter $csvWriter */
        $csvWriter = $this->getContainer()->get('chaplean.csv.writer');

        $csvWriter->setData($this->generator());

        $success = $csvWriter->writeToFile('/sys/dir/test.csv');
        $this->assertFalse($success);
    }

    /**
     * @return \Generator
     */
    public function generator()
    {
        yield new TestModel();
        yield new TestModel();
    }

    /**
     * @return \Generator
     */
    public function emptyGenerator()
    {
        return;
        yield;
    }
}
