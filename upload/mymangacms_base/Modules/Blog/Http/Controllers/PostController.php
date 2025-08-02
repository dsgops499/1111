<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Modules\Base\Http\Controllers\FileUploadController;

use Modules\Manga\Entities\Manga;
use Modules\Blog\Entities\Post;
use Modules\Blog\DataTables\PostDataTable;
use Modules\Blog\Repositories\PostRepository;
use Modules\Blog\Http\Requests\PostRequest;
use Modules\User\Contracts\Authentication;

/**
 * Post Controller Class
 * 
 * PHP version 5.4
 *
 * @category PHP
 * @package  Controller
 * @author   cyberziko <cyberziko@gmail.com>
 * @license  commercial http://getcyberworks.com/
 * @link     http://getcyberworks.com/
 */
class PostController extends Controller
{
    private $post;
    private $auth;
    
    /**
     * Constructor
     * 
     * @param Post $post current post
     */
    public function __construct(PostRepository $post, Authentication $auth)
    {
        $this->post = $post;
        $this->auth = $auth;
        $this->middleware('permission:blog.manage_posts');
        $this->middleware('permission:blog.manage_pages|blog.manage_posts', ['only' => ['uploadImage','browseImage','deletePostImage']]);
    }
    
    public function index(PostDataTable $dataTable)
    {
        return $dataTable->render('blog::admin.posts.index');
    }

    public function create()
    {
    	$categories = array(0 => 'General') + Manga::pluck('name', 'id')->all();
        
        return view('blog::admin.posts.create', ['categories' => $categories]);
    }

    /**
     * Create post
     * 
     * @return view
     */
    public function store(PostRequest $request)
    {
        $this->post->create($request->all());

        return redirect()->route('admin.posts.index')
            ->withSuccess(trans('blog::messages.admin.posts.create-success'));
    }

    public function edit(Post $post)
    {
        $categories = array(0 => 'General') + Manga::pluck('name', 'id')->all();
		
        return view('blog::admin.posts.edit', ['post' => $post, 'categories' => $categories]);
    }
    
    public function update(Post $post, PostRequest $request)
    {
        $this->post->update($post, $request->all());

        return redirect()->route('admin.posts.index')
            ->withSuccess(trans('blog::messages.admin.posts.update-success'));
    }
    
    /**
     * Delete post
     * 
     * @param $post
     * 
     * @return view
     */
    public function destroy(Post $post)
    {
        $this->post->destroy($post);

        return redirect()->route('admin.posts.index')
            ->withSuccess(trans('blog::messages.admin.posts.delete-success'));
    }

	
    /**
     * CKeditor upload image
     */
    public function uploadImage()
    {
        $file = Input::file('upload');
        if(!str_contains($file->getMimeType(),"image")) {
            return response('Unauthorized.', 401);
        }
        
        $filename = preg_replace('/\s+/', '', $file->getClientOriginalName());
        $file->move(FileUploadController::getPostPath($this->auth->user()->id), $filename);
        
        $CKEditorFuncNum = Input::get('CKEditorFuncNum');
        return redirect()->route('admin.posts.browseImage', ['CKEditorFuncNum'=>$CKEditorFuncNum]);
    }
	
	/**
     * CKeditor upload image
     */
    public function browseImage()
    {
        $CKEditorFuncNum = filter_input(INPUT_GET, 'CKEditorFuncNum');
		
        $UploadTeamPath = FileUploadController::getPostPath($this->auth->user()->id);

        return view('blog::admin.posts.imageuploader.imgbrowser',[
                'UploadTeamPath' => $UploadTeamPath,
                'CKEditorFuncNum' => $CKEditorFuncNum
        ]);
    }
    
    /**
     * CKeditor delete uploaded image
     */
    public function deletePostImage()
    {
        $imgSrc = Input::get('imgSrc');
		
        if (File::exists($imgSrc)) {
            File::delete($imgSrc);
        }
		
        return Response::json(
            [
                'status' => 'ok'
            ]
        );
    }
}

