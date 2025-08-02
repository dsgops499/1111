<?php

use Modules\Base\Http\Controllers\FileUploadController;
use Illuminate\Support\Facades\Auth;

// Version of the plugin
$currentpluginver = "4.1.3";

// show/hide file extension
if(!isset($_COOKIE["file_extens"])){
    $file_extens = "no";
} else {
    $file_extens = $_COOKIE["file_extens"];
}

// file_style
if(!isset($_COOKIE["file_style"])){
    $file_style = "block";
}

$useruploadfolder = FileUploadController::getPostRelativePath(Sentinel::check()->id);
$useruploadpath = "$useruploadfolder/";
