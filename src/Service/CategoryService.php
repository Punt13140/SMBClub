<?php


namespace App\Service;


use App\Entity\Category;

class CategoryService
{

    public function buildTreeLink(Category $category): array
    {
        $currentCat = $category;
        $return_arr = [$category];
        while ($currentCat->getCategoryParent() !== null) {
            $return_arr[] = $currentCat->getCategoryParent();
            $currentCat = $currentCat->getCategoryParent();
        }
        return array_reverse($return_arr);
    }

}