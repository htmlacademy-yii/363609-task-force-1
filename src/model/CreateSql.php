<?php
namespace Razot\model;
use SplFileObject;

class CreateSql {

    private array $arFiles = [];
    private string $dir;

    public function __construct(array $files, string $dir)
    {
        $this->arFiles = $files;
        $this->dir = $dir;
        $this->createFile();
    }

    private function createFile()
    {
        foreach ($this->arFiles as $item) {
           $arReturn =  $this->parse($item);
           file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/'. $this->dir .'/' . $arReturn['tableName'] . '.sql', $arReturn['str']);
        }
    }

    private function parse($item) :array
    {
        $file = new SplFileObject($item);
        $file->setFlags(SplFileObject::READ_CSV);
        $columns = [];
        $values = [];
        $i = 0;
        while ($file->valid()) {
            if ($i == 0) {
                $columns = $file->fgetcsv(',');
            } else {
                $ar = $file->fgetcsv(',');
                if(!empty($ar[0]))
                    $values[] = '(' . implode(', ' , array_map(function ($item) {
                                return "'".$item."'";
                            }, $ar)) . ')';
            }
            $i++;
        }

        $tableName = str_replace('.csv', '', $file->getBasename());

        $str = 'INSERT INTO `' . $tableName . '` (' . implode(', ' , array_map(function ($item) {
            return "`".$item."`";
            }, $columns)) . ') VALUES ' . implode(', ' , $values);

        return compact('str', 'tableName');
    }
}
