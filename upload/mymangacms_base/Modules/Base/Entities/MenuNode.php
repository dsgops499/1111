<?php 

namespace Modules\Base\Entities;

use Illuminate\Database\Eloquent\Model;

class MenuNode extends Model
{
    protected $table = 'menu_nodes';

    protected $fillable = [
        'menu_id', 'parent_id', 'related_id', 'type', 'url', 'title', 'icon_font', 'css_class', 'target', 'sort_order',
    ];

    protected $relatedModelInfo = [];
    protected $allRelatedNodes;
    
    public function getMenuNodes($menuId, $parentId = null)
    {
        if (!$this->allRelatedNodes) {
            $this->allRelatedNodes = $this->where('menu_id', $menuId)
                ->select(['id', 'menu_id', 'parent_id', 'related_id', 'type', 'url', 'title', 'icon_font', 'css_class', 'target'])
                ->orderBy('sort_order', 'ASC')
                ->get();
        }

        $nodes = $this->allRelatedNodes->where('parent_id', $parentId);

        $result = [];

        foreach ($nodes as $node) {
            $node->model_title = $node->title;
            $node->children = $this->getMenuNodes($menuId, $node->id);
            $result[] = $node;
            /**
             * Reset related nodes when done
             */
            if ($node->id == $nodes->last()->id && $parentId === null) {
                $this->allRelatedNodes = null;
            }
        }

        return collect($result);
    }
    
    /**
     * @param $value
     * @return mixed|string
     */
    public function getTitleAttribute($value)
    {
        if ($value) {
            return $value;
        }
        if (!$this->resolveRelatedModel()) {
            return '';
        }

        return array_get($this->relatedModelInfo, 'title');
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getUrlAttribute($value)
    {
        if (!$this->resolveRelatedModel()) {
            return $value;
        }

        return array_get($this->relatedModelInfo, 'slug');
    }

    protected function resolveRelatedModel()
    {
        if ($this->type === 'custom-link' || $this->type === 'route') {
            return null;
        }
        $this->relatedModelInfo = \Modules\Blog\Entities\Page::find($this->related_id);

        return $this->relatedModelInfo;
    }
}
