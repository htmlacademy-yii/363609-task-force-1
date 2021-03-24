<?php
namespace Razot\model;
use SplFileObject;

class CreateSql {

    public array $arFiles = [];

    public function __construct(array $files)
    {
        $this->arFiles = $files;
        $this->createFile();
    }

    private function createFile()
    {
        foreach ($this->arFiles as $item) {
           $str =  $this->readFile($item);
           file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/data/' . $item . '.sql', $str);
        }
    }

    private function readFile($item) :string
    {
        $str = '';
        $file = new SplFileObject($_SERVER['DOCUMENT_ROOT'] . '/data/'.$item.'.csv');
        $file->setFlags(SplFileObject::READ_CSV);
        $file->seek($file->getSize());
        $linesTotal = $file->key();
        foreach ($file as $k => $line) {
            if(!$file->eof()) {
                if($k == 0) {
                    $str = 'INSERT INTO `' . $item . '` (';
                    foreach ($file->current() as $x => $value) {
                        $str .= '`' . $value . '`';
                        if(count($file->current()) != $x + 1)
                            $str .= ', ';
                    }
                    $str .= ') VALUES ';
                }
                else {
                    $str .= '(';
                    foreach ($file->current() as $x => $value) {
                        $str .= "'" . $value . "'";
                        if(count($file->current()) != $x + 1)
                            $str .= ', ';
                    }
                    $str .= ')';
                    if($linesTotal != $k + 1)
                        $str .= ',';
                }
            }
        }
        return $str;
    }
}
