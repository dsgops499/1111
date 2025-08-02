<?php

namespace Modules\Blog\Repositories;

use Modules\Blog\Entities\Page;
use Modules\User\Contracts\Authentication;

class PageRepository
{
    protected $model;
    private $auth;

    public function __construct(Page $model, Authentication $auth)
    {
        $this->model = $model;
        $this->auth = $auth;
    }
    
    /**
     * @param $id
     * @return post
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }
    
    /**
     * Count all records
     * @return int
     */
    public function countAll()
    {
        return $this->model->count();
    }

    /**
     * @param  mixed  $data
     * @return object
     */
    public function create($data)
    {
        $this->model->fill($data);
        $this->model->title = clean($this->model->title);
        $this->model->slug = str_slug($this->model->title);
        $this->model->user_id = $this->auth->id();
        $this->model->keywords = clean($this->model->keywords);
        $this->model->description = clean($this->model->description);

        $post = $this->model->save();

        return $post;
    }

    /**
     * @param $model
     * @param  array  $data
     * @return object
     */
    public function update($model, $data)
    {
        $model->fill($data);
        $model->title = clean($model->title);
        $model->slug = str_slug($model->title);
        $model->keywords = clean($model->keywords);
        $model->description = clean($model->description);

        $model->update();

        return $model;
    }

    public function destroy($page)
    {
        return $page->delete();
    }
}
