<?php


namespace App\Service;


use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class CategoryService
{

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * CategoryService constructor.
     * @param RequestStack $requestStack
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(RequestStack $requestStack, CategoryRepository $categoryRepository)
    {
        $this->requestStack = $requestStack;
        $this->categoryRepository = $categoryRepository;
    }


    public function buildTreeLinkRequest(): array
    {
        if (!$this->requestStack->getCurrentRequest()->attributes->has('id_cat')) {
            return [];
        }

        $currentCat = $this->categoryRepository->find($this->requestStack->getCurrentRequest()->attributes->get('id_cat'));
        $return_arr = [$currentCat];
        while ($currentCat->getCategoryParent() !== null) {
            $return_arr[] = $currentCat->getCategoryParent();
            $currentCat = $currentCat->getCategoryParent();
        }
        return array_reverse($return_arr);
    }

}