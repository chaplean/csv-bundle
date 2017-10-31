<?php

namespace Chaplean\Bundle\CsvBundle\Tests\Utility;

use Chaplean\Bundle\CsvBundle\Tests\Model\TestModel;
use Chaplean\Bundle\CsvBundle\Tests\Resources\LogicalTestCase;
use Chaplean\Bundle\CsvBundle\Utility\CsvReader;
use Chaplean\Bundle\CsvBundle\Utility\CsvWriter;
use Mockery;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * CsvWriterTest.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.coop)
 * @since     0.1.0
 */
class CsvWriterTest extends LogicalTestCase
{
    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::setData()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::serializeRow()
     *
     * @doesNotPerformAssertions
     * @return void
     */
    public function testSetDataWithArray()
    {
        $translator = Mockery::mock(TranslatorInterface::class);
        $session = Mockery::mock(SessionInterface::class);
        $csvWriter = new CsvWriter($translator, $session);

        $csvWriter->setData(
            array(
                new TestModel(),
                new TestModel()
            )
        );

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $serializeRow->invokeArgs($csvWriter, array(new TestModel()));
    }

    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::setData()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::serializeRow()
     *
     * @doesNotPerformAssertions
     * @return void
     */
    public function testSetDataWithGenerator()
    {
        $translator = Mockery::mock(TranslatorInterface::class);
        $session = Mockery::mock(SessionInterface::class);
        $csvWriter = new CsvWriter($translator, $session);

        $csvWriter->setData($this->generator());

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $serializeRow->invokeArgs($csvWriter, array(new TestModel()));
    }

    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::setData()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::serializeRow()
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage You have to provide $dataClass if $data can be empty
     *
     * @return void
     */
    public function testSetDataWithEmptyArray()
    {
        $translator = Mockery::mock(TranslatorInterface::class);
        $session = Mockery::mock(SessionInterface::class);
        $csvWriter = new CsvWriter($translator, $session);

        $csvWriter->setData(array());

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $serializeRow->invokeArgs($csvWriter, array(new TestModel()));
    }

    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::setData()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::serializeRow()
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage You have to provide $dataClass if $data can be empty
     *
     * @return void
     */
    public function testSetDataWithEmptyGenerator()
    {
        $translator = Mockery::mock(TranslatorInterface::class);
        $session = Mockery::mock(SessionInterface::class);
        $csvWriter = new CsvWriter($translator, $session);

        $csvWriter->setData($this->emptyGenerator());

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $serializeRow->invokeArgs($csvWriter, array(new TestModel()));
    }

    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::setData()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::serializeRow()
     *
     * @doesNotPerformAssertions
     * @return void
     */
    public function testSetDataWithEmptyArrayAndDataClass()
    {
        $translator = Mockery::mock(TranslatorInterface::class);
        $session = Mockery::mock(SessionInterface::class);
        $csvWriter = new CsvWriter($translator, $session);

        $csvWriter->setData(array(), TestModel::class);

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $serializeRow->invokeArgs($csvWriter, array(new TestModel()));
    }

    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::setData()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::serializeRow()
     *
     * @doesNotPerformAssertions
     * @return void
     */
    public function testSetDataWithEmptyGeneratorAndDataClass()
    {
        $translator = Mockery::mock(TranslatorInterface::class);
        $session = Mockery::mock(SessionInterface::class);
        $csvWriter = new CsvWriter($translator, $session);

        $csvWriter->setData($this->emptyGenerator(), TestModel::class);

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $serializeRow->invokeArgs($csvWriter, array(new TestModel()));
    }

    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::setData()
     *
     * @return void
     * @expectedException \InvalidArgumentException
     */
    public function testSetDataWithInvalidData()
    {
        $translator = Mockery::mock(TranslatorInterface::class);
        $session = Mockery::mock(SessionInterface::class);
        $csvWriter = new CsvWriter($translator, $session);

        $csvWriter->setData(null);
    }

    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::setData()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::serializeRow()
     *
     * @return void
     * @expectedException \InvalidArgumentException
     */
    public function testSerializeRowWithArrayChecksModelClass()
    {
        $translator = Mockery::mock(TranslatorInterface::class);
        $session = Mockery::mock(SessionInterface::class);
        $csvWriter = new CsvWriter($translator, $session);

        $csvWriter->setData(
            array(
                new TestModel(),
                new TestModel()
            )
        );

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $serializeRow->invokeArgs($csvWriter, array(new \stdClass()));
    }

    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::setData()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::serializeRow()
     *
     * @return void
     * @expectedException \InvalidArgumentException
     */
    public function testSerializeRowWithGeneratorChecksModelClass()
    {
        $translator = Mockery::mock(TranslatorInterface::class);
        $session = Mockery::mock(SessionInterface::class);
        $csvWriter = new CsvWriter($translator, $session);

        $csvWriter->setData($this->generator());

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $serializeRow->invokeArgs($csvWriter, array(new \stdClass()));
    }

    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::setData()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::serializeRow()
     *
     * @return void
     */
    public function testSerializeRowWithArray()
    {
        $translator = Mockery::mock(TranslatorInterface::class);
        $session = Mockery::mock(SessionInterface::class);
        $csvWriter = new CsvWriter($translator, $session);

        $csvWriter->setData(
            array(
                new TestModel(),
                new TestModel()
            )
        );

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $result = $serializeRow->invokeArgs($csvWriter, array(new TestModel()));

        $this->assertEquals('"test""test";"42";"3.14"' . "\n", $result);
    }

    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::setData()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::serializeRow()
     *
     * @return void
     */
    public function testSerializeRowWithGenerator()
    {
        $translator = Mockery::mock(TranslatorInterface::class);
        $session = Mockery::mock(SessionInterface::class);
        $csvWriter = new CsvWriter($translator, $session);

        $csvWriter->setData($this->generator());

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $result = $serializeRow->invokeArgs($csvWriter, array(new TestModel()));

        $this->assertEquals('"test""test";"42";"3.14"' . "\n", $result);
    }

    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::setData()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::serializeHeaders()
     *
     * @return void
     */
    public function testSerializeHeadersWithArray()
    {
        $translator = Mockery::mock(TranslatorInterface::class);
        $session = Mockery::mock(SessionInterface::class);
        $csvWriter = new CsvWriter($translator, $session);

        $csvWriter->setData(
            array(
                new TestModel(),
                new TestModel()
            )
        );

        $serializeHeaders = $this->getNotPublicMethod(CsvWriter::class, 'serializeHeaders');
        $result = $serializeHeaders->invokeArgs($csvWriter, array());

        $this->assertEquals('"string";"integer";"float"' . "\n", $result);
    }

    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::setData()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::serializeHeaders()
     *
     * @return void
     */
    public function testSerializeHeadersWithGenerator()
    {
        $translator = Mockery::mock(TranslatorInterface::class);
        $session = Mockery::mock(SessionInterface::class);
        $csvWriter = new CsvWriter($translator, $session);

        $csvWriter->setData($this->generator());

        $serializeHeaders = $this->getNotPublicMethod(CsvWriter::class, 'serializeHeaders');
        $result = $serializeHeaders->invokeArgs($csvWriter, array());

        $this->assertEquals('"string";"integer";"float"' . "\n", $result);
    }

    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::setData()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::setSurrounding()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::setSurrounding()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::setTranslationPrefix()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::serializeRow()
     *
     * @return void
     */
    public function testSerializeRowWithArrayWithCustomParameters()
    {
        $translator = Mockery::mock(TranslatorInterface::class);
        $session = Mockery::mock(SessionInterface::class);
        $csvWriter = new CsvWriter($translator, $session);

        $csvWriter->setData(
            array(
                new TestModel(),
                new TestModel()
            )
        );
        $csvWriter->setDelimiter(',');
        $csvWriter->setSurrounding("'");
        $csvWriter->setEndOfLine("\r\n");
        $csvWriter->setTranslationPrefix("test.csv");

        $serializeRow = $this->getNotPublicMethod(CsvWriter::class, 'serializeRow');
        $result = $serializeRow->invokeArgs($csvWriter, array(new TestModel()));

        $this->assertEquals("'test\"test','42','3.14'\r\n", $result);
    }

    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::setData()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::serializeRow()
     *
     * @return void
     */
    public function testSerializeRowWithGeneratorWithCustomParameters()
    {
        $translator = Mockery::mock(TranslatorInterface::class);
        $session = Mockery::mock(SessionInterface::class);
        $csvWriter = new CsvWriter($translator, $session);

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
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::serializeHeaders()
     *
     * @return void
     */
    public function testSerializeHeadersWithArrayAndCustomParameters()
    {
        $translator = Mockery::mock(TranslatorInterface::class);
        $translator->shouldReceive('trans')
            ->andReturn('String', 'Integer', 'Float');

        $session = Mockery::mock(SessionInterface::class);
        $csvWriter = new CsvWriter($translator, $session);

        $csvWriter->setData(
            [
                new TestModel(),
                new TestModel()
            ]
        );
        $csvWriter->setDelimiter(',');
        $csvWriter->setSurrounding("'");
        $csvWriter->setEndOfLine("\r\n");
        $csvWriter->setTranslationPrefix("test.csv");

        $serializeHeaders = $this->getNotPublicMethod(CsvWriter::class, 'serializeHeaders');
        $result = $serializeHeaders->invokeArgs($csvWriter, array());

        $this->assertEquals("'String','Integer','Float'\r\n", $result);
    }

    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::serializeHeaders()
     *
     * @return void
     */
    public function testSerializeHeadersWithGeneratorAndCustomParameters()
    {
        $translator = Mockery::mock(TranslatorInterface::class);
        $translator->shouldReceive('trans')
            ->andReturn('String', 'Integer', 'Float');

        $session = Mockery::mock(SessionInterface::class);
        $csvWriter = new CsvWriter($translator, $session);

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
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::writeToResponse()
     *
     * @return void
     * @expectedException \LogicException
     */
    public function testWriteToResponseWithoutData()
    {
        $translator = Mockery::mock(TranslatorInterface::class);
        $session = Mockery::mock(SessionInterface::class);
        $csvWriter = new CsvWriter($translator, $session);

        $csvWriter->writeToResponse('test_file');
    }

    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::writeToFile()
     *
     * @return void
     * @expectedException \LogicException
     */
    public function testWriteToFileWithoutData()
    {
        $translator = Mockery::mock(TranslatorInterface::class);
        $session = Mockery::mock(SessionInterface::class);
        $csvWriter = new CsvWriter($translator, $session);

        $csvWriter->writeToFile('/tmp/test_file.csv');
    }

    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::writeToResponse()
     *
     * @return void
     */
    public function testWriteToResponse()
    {
        $translator = Mockery::mock(TranslatorInterface::class);
        $session = Mockery::mock(SessionInterface::class);
        $csvWriter = new CsvWriter($translator, $session);

        $csvWriter->setData(
            array(
                new TestModel(),
                new TestModel()
            )
        );

        $response = $csvWriter->writeToResponse('test_file');
        $this->assertInstanceOf(StreamedResponse::class, $response);

        // Can't test generated output because expectOutputString is based on ob_flush
        // and we need to call it in streamCallback().
    }

    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::writeToFile()
     *
     * @return void
     */
    public function testWriteToFile()
    {
        $translator = Mockery::mock(TranslatorInterface::class);
        $session = Mockery::mock(SessionInterface::class);
        $csvWriter = new CsvWriter($translator, $session);

        $csvWriter->setData($this->generator());

        $success = $csvWriter->writeToFile('/tmp/test_file.csv');
        $this->assertTrue($success);

        $csvReader = new CsvReader('/tmp/test_file.csv', CsvReader::DEFAULT_DELIMITER, false);
        $readData = $csvReader->get();
        $this->assertEquals(
            array(
                array('string', 'integer', 'float'),
                array('test"test', '42', '3.14'),
                array('test"test', '42', '3.14'),
            ),
            $readData
        );
    }

    /**
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::__construct()
     * @covers \Chaplean\Bundle\CsvBundle\Utility\CsvWriter::writeToFile()
     *
     * @return void
     */
    public function testWriteToFileFailToCreateDirectory()
    {
        $translator = Mockery::mock(TranslatorInterface::class);
        $session = Mockery::mock(SessionInterface::class);
        $csvWriter = new CsvWriter($translator, $session);

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
