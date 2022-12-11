<?php

namespace App\Service;

use LimitIterator;
use SplFileObject;
use Symfony\Component\Process\Exception\RuntimeException;

class LogActions{

    private $log = '';
    private $page = 1;
    private $offset = 0;
    private $limit = 10;

    public function setPage($page){
        $this->page = $page;
    }

    /**
     * Load logs from file 
     * @param $path
     * @return array
    */
    public function getLogs($path):array
    {
        try{
            $file = $this->isFileValid($path);

            $file->setFlags(SplFileObject::READ_AHEAD);
            $fileIterator = new \LimitIterator($file, $this->offset, $this->limit);

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

    /**
     * rewind to the first logs from file 
     * @param $path
     * @return array
    */
    public function rewind($path):array
    {
        try{
            $file = $this->isFileValid($path);
            $this->page = 0;

            $this->offset = $this->page * $this->limit;

            $fileIterator = new \LimitIterator($file, $this->offset, $this->limit);
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

    /**
     * next limit logs from file 
     * @param $path
     * @return array
    */    
    public function next($path):array
    {
        try{
            $file = $this->isFileValid($path);
            $file->seek(PHP_INT_MAX); 
            $total_lines = $file->key();
            $this->page = ($total_lines > ($this->page + 1) * $this->limit) ? $this->page + 1 : $this->page;

            $this->offset = $this->page * $this->limit;
            $fileIterator = new \LimitIterator($file, $this->offset, $this->limit);
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

    /**
     * previous limit logs from file 
     * @param $path
     * @return array
    */        
    public function previous($path):array
    {
        try{
            $file = $this->isFileValid($path);
            $this->page = (($this->page - 1) * $this->limit < $this->limit) ? 0 : $this->page - 1;

            $this->offset = $this->page * $this->limit;

            $fileIterator = new \LimitIterator($file, $this->offset, $this->limit);
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

    /**
     * end limit logs from file 
     * @param $path
     * @return array
    */        
    public function end($path):array
    {
        try{
            $file = $this->isFileValid($path);

            $file->seek(PHP_INT_MAX); 
            $total_lines = $file->key();

            $this->page = ceil($total_lines / $this->limit) - 1;

            $this->offset = $this->page * $this->limit;

            $fileIterator = new \LimitIterator($file, $this->offset, $this->limit);
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
    
    /**
     * check if file valid
     * @param $path
     * @return SplFileObject
    */    
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