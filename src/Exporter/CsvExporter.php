<?php

namespace Wispiring\CodeSpace\Exporter;

class CsvExporter
{
    protected $repositories;

    public function __construct($repositories)
    {
        $this->repositories = $repositories;
    }

    public function export()
    {
        $del = ',';
        $ln = "\r\n";
        $csv = 'Short name'.$del.'Name'.$del.'Group'.$del.'Path'.$ln;
        foreach ($this->repositories as $r) {
            $csv .= $r->getShortName().$del.$r->getName().$del.$r->getGroup().$del.$r->getPath().$ln;
        }

        return $csv;
    }

    public function exportToFile($path)
    {
        return file_put_contents($path, $this->export());
    }
}
