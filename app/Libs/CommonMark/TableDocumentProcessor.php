<?php declare(strict_types=1);

namespace App\Libs\CommonMark;

use League\CommonMark\Block\Element\Document;
use League\CommonMark\DocumentProcessorInterface;
use Webuni\CommonMark\TableExtension\Table;

final class TableDocumentProcessor implements DocumentProcessorInterface
{
    public function processDocument(Document $document): void
    {
        $walker = $document->walker();
        while ($event = $walker->next()) {
            $node = $event->getNode();

            if (!$event->isEntering() || !($node instanceof Table)) {
                continue;
            }

            $node->data['attributes']['class'] = 'table table-bordered table-striped table-hover';
        }
    }
}
