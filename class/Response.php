<?php

class Response
{
    public $data = null;
    public $correctOperation = false;
    public $message = '';

    public function setCorrectOperation($correctOperation, $m = '')
    {
        $this->correctOperation = $correctOperation;
        $this->message = $m;

        if (!$correctOperation && $m = '') {
            $this->message = 'Hi ha hagut un error inesperat';
        }
    }

    public function setData($data)
    {
        $this->data = $data;
    }
}
