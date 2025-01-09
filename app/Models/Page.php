<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;

/**
 * Page Model
 * columns: id, app_id, name, description, seo_title, seo_description, route_path, data_source, data_source_id, created_at, updated_at, deleted_at
 */
class Page extends BaseModel
{
    use Translatable;

    protected $table = 'pages';

    public array $translatedAttributes = ['name', 'description', 'seo_title', 'seo_description'];
    protected $translationForeignKey = 'page_id';

    public function app()
    {
        return $this->belongsTo(App::class, 'app_id', 'id');
    }

    // public function components()
    // {
    //     return $this->belongsToMany(); //TODO: complete
    // }
}
