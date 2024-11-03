<?php
/**
 *
 * @description Import Category Processor Customizations
 *
 * @author     Enoch Data Systems <https://data.enoch.systems>
 * @author     C. M. de Picciotto <cmdepi@enochsystems.com>
 * @author     PrecisionLab Sotware Engineering  <https://precisionlab.gr>
 * @copyright  Copyright 2018 Enoch Systems LLC
 * @license    Apache License 2.0
 * @package    ProductImportCategoryId
 * @version    1.1.0
 * @link       https://github.com/arion-p/ProductImportCategoryId
 *
 */
namespace Pse\ProductImportCategoryId\Plugin\Model\Import\Product;

use Magento\Framework\Exception\AlreadyExistsException;

class CategoryProcessor
{
    /**
     *
     * Returns IDs of categories by string path creating nonexistent ones.
     *
     * @param \Magento\CatalogImportExport\Model\Import\Product\CategoryProcessor $subject
     * @param string $categoriesString
     * @param string $categoriesSeparator
     *
     * @return array
     *
     */
    public function aroundUpsertCategories(
        \Magento\CatalogImportExport\Model\Import\Product\CategoryProcessor $subject,
        callable $proceed,
        $categoriesString, $categoriesSeparator)
    {
        $categoriesIds = [];
        $categories    = explode($categoriesSeparator, $categoriesString);
        $restCategories = [];
        
        foreach ($categories as $category) {
                /**
                 *
                 * @note Validate if category is a number and exists as a category ID
                 * @note To use this feature, the category name does not have to match with categories' IDs
                 *
                 */
                if (is_numeric($category) && $subject->getCategoryById($category)) {
                    $categoriesIds[] = $category;
                }
                else {
                    $restCategories[] = $category;
                }
        }

        if(count($restCategories)) {
            $restCategoriesString = implode($categoriesSeparator, $restCategories);
            $restCategoryIds = $proceed($categoriesSeparator, $restCategoriesString);
            $categoriesIds = array_merge($categoriesIds, $restCategoryIds);
        }

        return $categoriesIds;
    }

}
