<?php

namespace Wispiring\CodeSpace\Exporter;

class HtmlExporter
{
    protected $repositories;
    public function __construct($repositories)
    {
        $this->repositories = $repositories;
    }

    public function export()
    {
        $html = '<!DOCTYPE html>
        <html lang="zh-CN">
            <head>
                <title>HtmlExport</title>
                <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
                <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
                <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
                <script src="http://cdn.bootcss.com/pagedown/1.0/Markdown.Converter.js"></script>
            </head>
            <body>
                <div class="container-fluid">
                    <h2>Your Program List <small>You can click the Short name to read README.md</small></h2>
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <ul class="list-inline">
                                    <li class="col-md-3"><b>Short name</b></li>
                                    <li class="col-md-3"><b>Name</b></b></b></li>
                                    <li class="col-md-3"><b>Group</b></b></li>
                                    <li class="col-md-3"><b>Path</b></li>
                                </ul>
                            </div>
                        </div>';
        foreach ($this->repositories as $i=>$r) {
            $readmeContent = '';
            $filePath = $r->getPath().'/README.md';
            if (file_exists($filePath)) {
                $readmeContent = file_get_contents($filePath);
            }
            $html .= '
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="heading'.$i.'">
                            <ul class="list-inline">
                                <li class="col-md-3">
                                   <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$i.'" aria-expanded="true" aria-controls="collapse'.$i.'">
                                    '.$r->getShortName().'
                                   </a>
                                </li>
                                <li class="col-md-3">'.$r->getName().'</li>
                                <li class="col-md-3">'.$r->getGroup().'</li>
                                <li class="col-md-3"><a href="'.$r->getPath().'">'.$r->getPath().'</a></li>
                            </ul>
                        </div>
                        <div id="collapse'.$i.'" class="panel-collapse collapse" role="tabpanel"    aria-labelledby="heading'.$i.'">
                            <div class="panel-body w-content">'.htmlspecialchars($readmeContent).'</div>
                        </div>
                    </div>
                    ';
        }
        $html.= '</div></div></body></html>';
        $html.='<script>
                    $(".w-content").each(function(){
                        var content = $(this).html();
                        var converter = new Markdown.Converter();
                        $(this).html(converter.makeHtml(content));
                    });
                </script>
                ';
        return $html;
    }

    public function exportToFile($path)
    {
        return file_put_contents($path, $this->export());
    }
}
