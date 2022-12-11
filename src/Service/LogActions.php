<?php

namespace App\Service;

use LimitIterator;
use SplFileObject;
use Symfony\Component\Process\Exception\RuntimeException;

class LogActions{

    private $log = '';
    private $page = 1;
    private $offset = 0;

    public function setPage($page){
        $this->page = $page;
    }

    public function setOffset($offset){
        $this->offset = $offset;
    }

    public function getLogs($path):array
    {
        try{
            $file = $this->isFileValid($path);

            $file->setFlags(SplFileObject::READ_AHEAD);
            $fileIterator = new \LimitIterator($file, $this->offset, 10);

            foreach ($fileIterator as $line) {
                $response[] = ['line' => $fileIterator->key(),'path' => $line];
            }        
            $response['status'] = 200;
            $response['page'] = $this->page;
            return $response;
        }
        catch (RuntimeException $e){
            return [ 'status' => $e->getCode(), 'message' => $e->getMessage()];
        }  
    }

    public function next($path):array
    {
        try{
            $file = $this->isFileValid($path);
            $file->seek(PHP_INT_MAX); 
            $total_lines = $file->key();
            $this->page = ($total_lines > ($this->page + 1) * 10) ? $this->page + 1 : $this->page;

            $this->offset = $this->page * 10;
            $fileIterator = new \LimitIterator($file, $this->offset, 10);
            foreach ($fileIterator as $line) {
                $response[] = ['line' => $fileIterator->key(),'path' => $line];
            }        
            $response['status'] = 200;
            $response['page'] = $this->page;
            return $response;
        }
        catch (RuntimeException $e){
            return [ 'status' => $e->getCode(), 'message' => $e->getMessage()];
        }  
    }

    public function previous($path):array
    {
        try{
            $file = $this->isFileValid($path);
            $this->page = (($this->page - 1) * 10 < 10) ? 0 : $this->page - 1;

            $this->offset = $this->page * 10;

            $fileIterator = new \LimitIterator($file, $this->offset, 10);
            foreach ($fileIterator as $line) {
                $response[] = ['line' => $fileIterator->key(),'path' => $line];
            }        
            $response['status'] = 200;
            $response['page'] = $this->page;
            return $response;
        }
        catch (RuntimeException $e){
            return [ 'status' => $e->getCode(), 'message' => $e->getMessage()];
        }  
    }

    private function isFileValid($path):SplFileObject
    {
        if(!file_exists($path)){
            throw new RuntimeException("File Not exist",'301');
        }

        $file = new SplFileObject($path);

        if (!$file->getSize()){
            throw new RuntimeException("File Empty","302");
        }         
        
        if(!$file->valid()){
            throw new RuntimeException("End of file","303");
        }

        return $file;
    }
}