<?php

namespace App\Helpers;


use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\PdfParser;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\FpdiPdfParser\PdfParser\CrossReference\CompressedReader;
use setasign\FpdiPdfParser\PdfParser\CrossReference\CorruptedReader;

require(base_path() . '/vendor/pdf/fpdf/fpdf.php');
require(base_path() . '/vendor/pdf/fpdi/src/autoload.php');
require(base_path() . '/vendor/pdf/fpdi-parser/src/autoload.php');

class Pdf extends Fpdi
{
    /**
     * @var string
     */
    protected $pdfParserClass = null;

    /**
     * Set the pdf reader class.
     *
     * @param string $pdfParserClass
     */
    public function setPdfParserClass($pdfParserClass)
    {
        $this->pdfParserClass = $pdfParserClass;
    }

    /**
     * Checks what kind of cross-reference parser is used.
     *
     * @return string
     */
    public function getXrefInfo()
    {
        foreach (array_keys($this->readers) as $readerId) {
            $crossReference = $this->getPdfReader($readerId)->getParser()->getCrossReference();
            $readers = $crossReference->getReaders();
            foreach ($readers as $reader) {
                if ($reader instanceof CompressedReader) {
                    return 'compressed';
                }

                if ($reader instanceof CorruptedReader) {
                    return 'corrupted';
                }
            }
        }

        return 'normal';
    }

    /**
     * Get a new pdf parser instance.
     *
     * @param StreamReader $streamReader
     * @return PdfParser
     */
    protected function getPdfParserInstance(StreamReader $streamReader)
    {
        if ($this->pdfParserClass !== null) {
            return new $this->pdfParserClass($streamReader);
        }

        return parent::getPdfParserInstance($streamReader);
    }
}