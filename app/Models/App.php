<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

/**
 * App Model
 * columns: id, name, description, logo, version, is_translation_enabled, main_lang, created_at, updated_at, deleted_at
 */
class App extends BaseModel
{
    protected $table = 'apps';


    public function pages()
    {
        return $this->hasMany(Page::class);
    }
}
