<?php

namespace App\Controllers;

use App\Models\LetterRequestModel;

class DebugTest extends BaseController
{
    public function index()
    {
        $model = new LetterRequestModel();
        $tableInfo = $model->getTableInfo();
        
        echo "<h1>Letter Requests Table Structure</h1>";
        echo "<pre>";
        print_r($tableInfo);
        echo "</pre>";
        
        echo "<h1>Model Allowed Fields</h1>";
        echo "<pre>";
        print_r($model->allowedFields);
        echo "</pre>";
        
        // Try to get one record
        $record = $model->first();
        echo "<h1>First Record</h1>";
        echo "<pre>";
        print_r($record);
        echo "</pre>";
        
        return '';
    }
} 