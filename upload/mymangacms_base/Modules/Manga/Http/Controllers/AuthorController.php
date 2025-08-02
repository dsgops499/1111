<?php

namespace Modules\Manga\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Manga\DataTables\AuthorDataTable;

use Modules\Manga\Entities\Author;
use Modules\Manga\Entities\Manga;

/**
 * Author Controller Class
 * 
 * PHP version 5.4
 *
 * @category PHP
 * @package  Controller
 * @author   cyberziko <cyberziko@gmail.com>
 * @license  commercial http://getcyberworks.com/
 * @link     http://getcyberworks.com/
 */
class AuthorController extends Controller
{
    /**
     * Constructor
     * 
     */
    public function __construct()
    {
        $this->middleware('permission:taxonomies.manage_authors');
    }
    
    /**
     * Author page
     * 
     * @return type
     */
    public function index(AuthorDataTable $dataTable)
    {
        return $dataTable->render('manga::admin.taxo.author.index');
    }

    /**
     * Save author
     * 
     * @return type
     */
    public function store()
    {
        $input = clean(request()->all());
        $author = new Author();
        
        if (!$author->fill($input)->isValid()) {
            return redirect()->back()
                ->withInput()->withErrors($author->errors);
        }
        
        $author->save();
        return redirect()->back()
            ->withSuccess(trans('messages.admin.item.create-success'));
    }

    /**
     * Edit page
     * 
     * @param type $id category id
     * 
     * @return type
     */
    public function edit(AuthorDataTable $dataTable, $id)
    {
        return $dataTable->render('manga::admin.taxo.author.edit',['author' => Author::find($id)]);
    }

    /**
     * Update author
     * 
     * @param type $id tag id
     * 
     * @return type
     */
    public function update($id)
    {
        $input = clean(request()->all());
        $author = Author::find($id);

        if (!$author->fill($input)->isValid()) {
            return redirect()->back()
                ->withInput()->withErrors($author->errors);
        }

        $author->save();
        return redirect()->route('admin.author.index')
            ->withSuccess(trans('messages.admin.item.update-success'));
    }

    /**
     * Delete item
     * 
     * @param type $id item id
     * 
     * @return type
     */
    public function destroy($id)
    {
        $author = Author::find($id);

        $author->delete();

        return redirect()->route('admin.author.index')
            ->withSuccess(trans('messages.admin.item.delete-success'));
    }

    public function migrateAuthors()
    {
        $manga_array = array();
        $author_array = Author::pluck('id','name')->all();

        $mangas = Manga::select(['id', 'author', 'artist'])
                ->whereNotNull('author')
                ->orWhereNotNull('artist')
                ->get();

        foreach($mangas as $manga) {
            $manga_array[$manga->id]=[$manga, 'artist'=>[], 'author'=>[]];
            $artists = explode(',', $manga->artist);
            $authors = explode(',', $manga->author);
            foreach($artists as $artist) {
                if(!is_null($artist) && strlen(trim($artist))>0) {
                    if(array_key_exists(trim($artist), $author_array)) {
                        array_push($manga_array[$manga->id]['artist'], $author_array[trim($artist)]);
                    } else {
                        $art = new Author();
                        $art->name = trim($artist);
                        $art->save();
                        $author_array[trim($artist)] = $art->id;
                        array_push($manga_array[$manga->id]['artist'], $art->id);
                    }
                }
            }
            foreach($authors as $author) {
                if(!is_null($author) && strlen(trim($author))>0) {
                    if(array_key_exists(trim($author), $author_array)) {
                        array_push($manga_array[$manga->id]['author'], $author_array[trim($author)]);
                    } else {
                        $aut = new Author();
                        $aut->name = trim($author);
                        $aut->save();
                        $author_array[trim($author)] = $aut->id;
                        array_push($manga_array[$manga->id]['author'], $aut->id);
                    }
                }
            }
            
            $manga->authors()->attach($manga_array[$manga->id]['author'], ['type' => 1]);
            $manga->artists()->attach($manga_array[$manga->id]['artist'], ['type' => 2]);
        }
    }
}
