<?php

namespace Chaplean\Bundle\CsvBundle\Utility;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Translation\TranslatorInterface;

/***
 * Class CsvWriter.
 *
 * @package   Chaplean\Bundle\CsvBundle\Utility
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.coop)
 * @since     0.1.0
 */
class CsvWriter
{
    /** @var TranslatorInterface $translator */
    protected $translator;

    /** @var array|\Iterator $data */
    protected $data;

    /** @var mixed|null $data */
    protected $firstItem;

    /** @var string $translationPrefix */
    protected $translationPrefix;

    /** @var string $dataClass */
    protected $dataClass;

    /** @var string $endOfLine */
    private $endOfLine;

    /** @var string $delimiter */
    private $delimiter;

    /** @var string $surrounding */
    private $surrounding;

    /**
     * Construct
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;

        $this->data = null;
        $this->firstItem = null;
        $this->translationPrefix = '';
        $this->dataClass = null;

        $this->setDelimiter();
        $this->setSurrounding();
        $this->setEndOfLine();
    }

    /**
     * Sets the delimiter used to separate columns
     *
     * @param string $delimiter
     *
     * @return void
     */
    public function setDelimiter($delimiter = CsvReader::DEFAULT_DELIMITER)
    {
        $this->delimiter = $delimiter;
    }

    /**
     * Sets the character used to surround columns content
     *
     * @param string $surrounding
     *
     * @return void
     */
    public function setSurrounding($surrounding = CsvReader::DEFAULT_SURROUNDING)
    {
        $this->surrounding = $surrounding;
    }

    /**
     * Sets the end of line character(s)
     *
     * @param string $endOfLine
     *
     * @return void
     */
    public function setEndOfLine($endOfLine = CsvReader::DEFAULT_END_OF_LINE)
    {
        $this->endOfLine = $endOfLine;
    }

    /**
     * Fills the csv with the given $data
     *
     * $data can be anything you can iterate on e.g. an array or even better
     * a generator (if you want to be able to stream writes)
     *
     * $data must always contain instances of the same class
     *
     * $dataClass is the class name (as per get_class()). It's used as a substitute
     * in case $data is empty.
     *
     * @param array|\Iterator $data
     * @param string          $dataClass
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function setData($data, $dataClass = null)
    {
        if (!(is_array($data) || $data instanceof \Iterator)) {
            throw new \InvalidArgumentException('$data must be an array or a \Iterator');
        }

        $this->data = $data;

        $this->firstItem = is_array($data)
            ? $data[0] ?? null
            : $data->current();

        if ($this->firstItem !== null) {
            $this->dataClass = get_class($this->firstItem);
        } else {
            if ($dataClass !== null) {
                $this->dataClass = $dataClass;
            } else {
                throw new \InvalidArgumentException('You have to provide $dataClass if $data can be empty');
            }
        }
    }

    /**
     * Sets the prefix use for the translation of the headers
     * (without the final '.')
     *
     * @param string $prefix
     *
     * @return void
     */
    public function setTranslationPrefix($prefix)
    {
        $this->translationPrefix = $prefix;
    }

    /**
     * Returns the csv wrapped in a StreamResponse with the proper HTTP headers
     *
     * @param string $name Name of the downloaded file (without the .csv extension)
     *
     * @return StreamedResponse
     * @throws \LogicException
     */
    public function writeToResponse($name)
    {
        if ($this->data === null) {
            throw new \LogicException('writeToResponse called before any data was set');
        }

        $callback = function () {
            echo $this->serializeHeaders();

            if ($this->firstItem !== null) {
                foreach ($this->data as $row) {
                    echo $this->serializeRow($row);

                    ob_flush();
                    flush();
                }
            }
        };

        return new StreamedResponse(
            $callback,
            Response::HTTP_OK,
            array(
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $name . '.csv"'
            )
        );
    }

    /**
     * Writes the csv to the given $file path
     *
     * @param string $path Path to write to
     *
     * @return boolean Wether the write succeeded or not
     * @throws \LogicException
     */
    public function writeToFile($path)
    {
        if ($this->data === null) {
            throw new \LogicException('writeToFile called before any data was set');
        }

        try {
            $this->checkParentDirectory($path);

            $file = fopen($path, 'w');

            if (fwrite($file, $this->serializeHeaders()) === false) {
                throw new \RuntimeException('Failed to write to the file: ' . $path);
            }

            if ($this->firstItem !== null) {
                foreach ($this->data as $row) {
                    if (fwrite($file, $this->serializeRow($row)) === false) {
                        throw new \RuntimeException('Failed to write to the file: ' . $path);
                    }
                }
            }

            fclose($file);
        } catch (\RuntimeException $e) {
            if (file_exists($path)) {
                unlink($path);
            }

            return false;
        }

        return true;
    }

    /**
     * Generates a string representing a row of the csv from the given $row
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function serializeHeaders()
    {
        $headers = array_keys(get_class_vars($this->dataClass));
        $translatedHeaders = array();

        if ($this->translationPrefix !== '') {
            foreach ($headers as $header) {
                $translatedHeaders[] = $this->translator->trans($this->translationPrefix . '.' . $header);
            }
        } else {
            $translatedHeaders = $headers;
        }

        return $this->serialize($translatedHeaders);
    }

    /**
     * Generates a string representing a row of the csv from the given $row
     *
     * @param $row
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function serializeRow($row)
    {
        $rowClass = get_class($row);

        if ($this->dataClass !== $rowClass) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The content given to setData must always be of the same class: expected: %s, found: %s',
                    $this->dataClass,
                    $rowClass
                )
            );
        }

        return $this->serialize(get_object_vars($row));
    }

    /**
     * @param array $data
     *
     * @return string
     */
    protected function serialize(array $data)
    {
        $serializedRow = '';
        $values = array_values($data);

        foreach ($values as $index => $value) {
            $serializedRow .= ($index !== 0 ? $this->delimiter : '')
                              . $this->surrounding
                              . $this->escapeDelimiter((string) $value)
                              . $this->surrounding;
        }

        $serializedRow .= $this->endOfLine;

        return $serializedRow;
    }

    /**
     * Escapes the surrounding character in the given $text
     *
     * @param string $text
     *
     * @return string
     */
    protected function escapeDelimiter($text)
    {
        return str_replace($this->surrounding, $this->surrounding . $this->surrounding, $text);
    }

    /**
     * Create the parent directory of the given $path if it doesn't exist
     *
     * @param $path
     *
     * @return void
     * @throws \RuntimeException If the creation of the directory failed
     */
    protected function checkParentDirectory($path)
    {
        $dir = dirname($path);

        if (!@mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new \RuntimeException("Can't create containing directory");
        }
    }
}
