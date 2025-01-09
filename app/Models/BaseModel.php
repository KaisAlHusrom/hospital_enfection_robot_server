<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class BaseModel extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Get the casts for the model's attributes.
     *
     * @return array
     */
    public function getCasts(): array
    {
        $casts = parent::getCasts(); // Get existing casts

        // Get all attributes defined in the database
        $attributes = $this->getAttributes();

        // Iterate over the attributes and check for columns starting with "is" or "has"
        foreach ($attributes as $attribute => $value) {
            if (str_starts_with($attribute, 'is') || str_starts_with($attribute, 'has') || str_starts_with($attribute, 'enable')) {
                $casts[$attribute] = 'boolean'; // Set the cast to boolean
            }
        }

        return $casts; // Return the modified casts
    }


    public static function getIdType(): string
    {
        return 'unsignedBigInteger';
    }

    /**
     * Get column names for the current model's table.
     *
     * @return array
     */
    public static function getColumnNames(): array
    {
        // `new static` allows accessing the table of the child model
        return Schema::getColumnListing((new static)->getTable());
    }
}
