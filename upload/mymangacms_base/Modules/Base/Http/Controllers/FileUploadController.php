<?php

namespace Modules\Base\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Base\Supports\HelperController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

/**
 * File upload Controller Class
 * 
 * PHP version 5.4
 *
 * @category PHP
 * @package  Controller
 * @author   cyberziko <cyberziko@gmail.com>
 * @license  commercial http://getcyberworks.com/
 * @link     http://getcyberworks.com/
 */
class FileUploadController extends Controller
{

    static $TMP_COVER_DIR = 'tmp/mangacover/';
    static $TMP_CHAPTER_DIR = 'tmp/mangachapter/';
    static $TMP_DOWNLOADS_DIR = 'tmp/downloads/';
    static $TMP_AVATAR_DIR = 'tmp/avatar/';
    static $MANGA_DIR = 'manga/';
    static $POST_DIR = 'posts/';
    static $USERS_DIR = 'users/';
    
    /**
     * Upload cover
     * 
     * @return type
     */
    public function uploadMangaCover()
    {
        if(!Storage::exists(static::$TMP_COVER_DIR)) {
            Storage::makeDirectory(static::$TMP_COVER_DIR);
        }

        $file = Input::file('file');
        if ($file) {
            $cover_250x350 = 'cover_250x350_' . time() . '.jpg';

            // GD API
            $image = HelperController::openImage($file);

            if ($image === false) {
                return Response::json(
                    ['error' =>
                        [
                            'type' => 'Unable to open image',
                            'message' => 'extension not supported'
                        ]
                    ]
                    , 400
                );
            }

            HelperController::makeThumb($image, static::getCoverTmpPath() . $cover_250x350, 250, 350);

            return Response::json(
                ['result' => Storage::url(static::$TMP_COVER_DIR . $cover_250x350)]
            );
        } else {
            return Response::json('error', 400);
        }
    }

    /**
     * Delete cover
     * 
     * @return type
     */
    public function deleteCover()
    {
        $filename = filter_input(INPUT_POST, 'filename');
        $exists = Storage::exists(static::$TMP_COVER_DIR . $filename);

        if ($exists == true) {
            $stat = Storage::delete(static::$TMP_COVER_DIR . $filename);
            return Response::json(['result' => $stat]);
        } else {
            return Response::json('error', 400);
        }
    }

    /**
     * Upload avatar
     * 
     * @return type
     */
    public function uploadAvatar()
    {
        if(!Storage::exists(static::$TMP_AVATAR_DIR)) {
            Storage::makeDirectory(static::$TMP_AVATAR_DIR);
        }
        
        $file = Input::file('file');
        if ($file) {
            $avatar = 'avatar' . time() . '.jpg';

            // GD API
            $image = HelperController::openImage($file);

            if ($image === false) {
                return Response::json(
                    ['error' =>
                        [
                            'type' => 'Unable to open image',
                            'message' => 'extension not supported'
                        ]
                    ]
                    , 400
                );
            }

            HelperController::makeThumb($image, static::getAvatarTmpPath() . $avatar, 200, 200);

            return Response::json(
                ['result' => Storage::url(static::$TMP_AVATAR_DIR . $avatar)]
            );
        } else {
            return Response::json('error', 400);
        }
    }
    
    /**
     * Delete avatar
     * 
     * @return type
     */
    public function deleteAvatar()
    {
        $filename = filter_input(INPUT_POST, 'filename');

        if (Storage::exists(static::$TMP_AVATAR_DIR)) {
            $stat = Storage::delete(static::$TMP_AVATAR_DIR . $filename);
            return Response::json(['result' => $stat]);
        } else {
            return Response::json('error', 400);
        }
    }
    
    public static function createAvatar($cover, $id)
    {
        if(!Storage::exists(static::$USERS_DIR . $id)) {
            Storage::makeDirectory(static::$USERS_DIR . $id);
        }
        
        if(Storage::exists(static::$USERS_DIR . $id . '/avatar.jpg')) {
            Storage::delete(static::$USERS_DIR . $id . '/avatar.jpg');
        }
       
        $cover_name = substr(strrchr($cover, "/"), count($cover));
        $coverCreated = Storage::move(static::$TMP_AVATAR_DIR . $cover_name, static::$USERS_DIR . $id . '/avatar.jpg');

        return $coverCreated;
    }
    
    public function uploadLogo()
    {
        $file = Input::file('file');
        if ($file) {
            $image = HelperController::openImage($file);
            if ($image === false) {
                return Response::json(
                    ['error' =>
                        [
                            'type' => 'Unable to open image',
                            'message' => 'extension not supported'
                        ]
                    ]
                    , 400
                );
            }

            $fileExtension = strrchr($file->getClientOriginalName(), '.');
            $logo = 'logo' . $fileExtension;
            $file->move(Storage::getAdapter()->getPathPrefix(), $logo);      

            return Response::json(
                ['result' => Storage::url($logo)]
            );
        } else {
            return Response::json('error', 400);
        }
    }
    
    public function uploadIcon()
    {
        $file = Input::file('file');
        if ($file) {
            $image = HelperController::openImage($file);

            if ($image === false) {
                return Response::json(
                    ['error' =>
                        [
                            'type' => 'Unable to open image',
                            'message' => 'extension not supported'
                        ]
                    ]
                    , 400
                );
            }

            $fileExtension = strrchr($file->getClientOriginalName(), '.');
            $favicon = 'favicon' . $fileExtension;
            $file->move(Storage::getAdapter()->getPathPrefix(), $favicon);      
            
            return Response::json(
                ['result' => Storage::url($favicon)]
            );
        } else {
            return Response::json('error', 400);
        }
    }
        
    public function deleteImg()
    {
        $filename = filter_input(INPUT_POST, 'filename');
        $stat = Storage::delete($filename);

        if ($stat) {
            return Response::json(['result' => $stat]);
        } else {
            return Response::json('error', 400);
        }
    }
    
    /**
     * Extract Zip file
     * 
     * @param type $file        zip file
     * @param type $extractPath path
     * 
     * @return array
     */
    public static function zipExtract($file, $extractPath)
    {
        $zip = new \ZipArchive();
        $files = array();

        $res = $zip->open($file);
        if ($res === true) {
            if ($zip->extractTo($extractPath)) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $stat = $zip->statIndex($i);
                    array_push($files, basename($stat['name']));
                }
            }

            $zip->close();
        }

        sort($files);
        return $files;
    }
    
    /**
     * download zip file
     */
    public static function downloadChapter($mangaSlug, $chapterSlug) {
        $sourcePath = static::getChapterPath($mangaSlug, $chapterSlug);

        if (!File::isDirectory($sourcePath)) {
            return Redirect::back()->with('downloadError', 'no pages');
        }
        
        if(!Storage::exists(static::$TMP_DOWNLOADS_DIR)) {
            Storage::makeDirectory(static::$TMP_DOWNLOADS_DIR);
        }
        
        // Choose a name for the archive.
        $zipFileName = $mangaSlug.'-c'.$chapterSlug.'.zip';	
        $downloadFile = Storage::getAdapter()->getPathPrefix() . static::$TMP_DOWNLOADS_DIR. $zipFileName;

        // Create "MyCoolName.zip" file in public directory of project.
        $zip = new \ZipArchive;
        if ($zip->open($downloadFile, \ZipArchive::CREATE) === TRUE) {

            // Copy all the files from the folder and place them in the archive.
            foreach (glob($sourcePath . '/*') as $fileName) {
                $file = basename($fileName);                
                $zip->addFile($fileName, $file);
            }
			                   
            $zip->close();

            $headers = array(
                'Content-Type' => 'application/octet-stream',
            );
	
            // Download .zip file.	    
            return Response::download($downloadFile, $zipFileName, $headers);            
        }
    }
    
    public static function deleteDownloadsDir()
    {
        if(Storage::exists(static::$TMP_DOWNLOADS_DIR)) {
            return Storage::deleteDirectory(static::$TMP_DOWNLOADS_DIR);
        }
        return false;
    }
    
    /**
     * Create cover
     * 
     * @param type $cover the image
     * @param type $slug  manga slug
     * 
     * @return type
     */
    public static function createCover($cover, $slug) {
        $coverNewRelativePath = static::$MANGA_DIR . $slug . '/cover/';
        $coverNewPath = static::getCoverPath($slug);

        static::cleanCoverDir($slug);
        $cover_name = substr(strrchr($cover, "/"), count($cover));
        $coverCreated = Storage::move(static::$TMP_COVER_DIR . $cover_name, $coverNewRelativePath . 'cover_250x350.jpg');

        // GD API
        $image = HelperController::openImage($coverNewPath . 'cover_250x350.jpg');
        HelperController::makeThumb($image, $coverNewPath . 'cover_thumb.jpg', 100, 100);

        return $coverCreated;
    }

    public static function cleanCoverDir($slug) {
        Storage::deleteDirectory(static::$MANGA_DIR . $slug . '/cover/');
    }
    
    public static function getCoverPath($slug) {
        return Storage::getAdapter()->getPathPrefix() . static::$MANGA_DIR . $slug . '/cover/';
    }

    public static function getCoverTmpPath() {
        return Storage::getAdapter()->getPathPrefix() . static::$TMP_COVER_DIR;
    }

    public static function getAvatarTmpPath() {
        return Storage::getAdapter()->getPathPrefix() . static::$TMP_AVATAR_DIR;
    }
    
    public static function getChapterTmpPath() {
        return Storage::getAdapter()->getPathPrefix() . static::$TMP_CHAPTER_DIR;
    }
    
    public static function createChapterDirectory($mangaSlug, $chapterSlug) {
        if (!Storage::exists(static::$MANGA_DIR . $mangaSlug . '/chapters/' . $chapterSlug)) {
            return Storage::makeDirectory(static::$MANGA_DIR . $mangaSlug . '/chapters/' . $chapterSlug);
        }
        return false;
    }

    public static function cleanMangaDirectory($slug) {
        Storage::deleteDirectory(static::$MANGA_DIR . $slug);
    }
    
    public static function cleanChapterDirectory($mangaSlug, $chapterSlug) {
        Storage::deleteDirectory(static::$MANGA_DIR . $mangaSlug . '/chapters/' . $chapterSlug);
    }

    public static function moveMangaDirectory($old, $new) {
        return Storage::move(static::$MANGA_DIR . $old, static::$MANGA_DIR . $new);
    }
    
    public static function moveChapterDirectory($mangaSlug, $old, $new) {
        return Storage::move(static::$MANGA_DIR . $mangaSlug . '/chapters/' . $old, static::$MANGA_DIR . $mangaSlug . '/chapters/' . $new);
    }
    
    public static function avatarUrl($user) {
        if(is_null($user)) {
            return Storage::url(static::$USERS_DIR);
        }
        return Storage::url(static::$USERS_DIR . $user . '/avatar.jpg');
    }
    
    public static function coverUrl($path) {
        return Storage::url(static::$MANGA_DIR . $path);
    }
    
    public static function pageImageUrl($mangaSlug, $chapterSlug, $pageImage) {
        if(is_null($pageImage)) {
            return Storage::url(static::$MANGA_DIR . $mangaSlug . '/chapters/' . $chapterSlug . '/');
        }
        return Storage::url(static::$MANGA_DIR . $mangaSlug . '/chapters/' . $chapterSlug . '/' . $pageImage);
    }
    
    public static function getChapterPath($mangaSlug, $chapterSlug) {
        return Storage::getAdapter()->getPathPrefix() . static::$MANGA_DIR . $mangaSlug . '/chapters/' . $chapterSlug . '/';
    }

    public static function getPostPath($user) {
        if (!Storage::exists(static::$POST_DIR . $user)) {
            Storage::makeDirectory(static::$POST_DIR . $user);
        }
        return Storage::getAdapter()->getPathPrefix() . static::$POST_DIR . $user;
    }

    public static function getPostRelativePath($user) {
        $path = Storage::getAdapter()->getPathPrefix();
        return substr($path, strrpos($path, '/')+1) .'/'. static::$POST_DIR . $user;
    }
    
    public static function cleanAvatarDirectory($user) {
        Storage::deleteDirectory(static::$USERS_DIR . $user);
    }
}
