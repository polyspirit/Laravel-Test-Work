<?php
namespace App\Traits;

Trait Relatable
{
    protected function cutIdsfromAttributes(array &$attributes, string $attributeName): array
    {
        $ids = [];
        if (isset($attributes[$attributeName])) {
            $ids = json_decode($attributes[$attributeName], true);
            unset($attributes[$attributeName]);
        }

        return $ids;
    }

    protected function syncIds(\Illuminate\Database\Eloquent\Model &$model, array $ids)
    {
        if ($ids) {
            $model->cities()->sync($ids);
        }
    }
}
