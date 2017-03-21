<?php

namespace frontend\controllers;

use common\models\Category;
use common\models\Product;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use pjhl\multilanguage\LangHelper;
//use yii\i18n\PhpMessageSource;

class CatalogController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            Url::remember();
            return true;
        } else {
            return false;
        }
    }

    public function actionList($id = null)
    {
        /** @var Category $category */
		//print_r(LangHelper::getLanguage());
        $category = null;
        $categories = Category::find()->indexBy('id')->orderBy('id')->all();
        $productsQuery = Product::find()->where(['lang_id' => LangHelper::getLanguage('id')]);
        if ($id !== null && isset($categories[$id])) {
			$category = $categories[$id];
            $productsQuery->where(['category_id' => $this->getCategoryIds($categories, $id)])->andwhere(['lang_id' => LangHelper::getLanguage('id')]);
        }
        //print_r($productsQuery);
        $productsDataProvider = new ActiveDataProvider([
            'query' => $productsQuery,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
//echo '<pre>'.print_r($productsDataProvider,true).'</pre>';
        return $this->render('list', [
            'category' => $category,
            'menuItems' => $this->getMenuItems($categories, isset($category->id) ? $category->id : null),
            'productsDataProvider' => $productsDataProvider,
        ]);
    }

    public function actionView()
    {
		//echo LangHelper::getLanguage('id');
		$name = Yii::$app->request->get('id');
		$items = Product::find()->where(['product_id'=>$name])->andwhere(['lang_id'=>LangHelper::getLanguage('id')])->one();
        return $this->render('view',compact('items'));
    }

    /**
     * @param Category[] $categories
     * @param int $activeId
     * @param int $parent
     * @return array
     */
    private function getMenuItems($categories, $activeId = null, $parent = null)
    {
        $menuItems = [];
        foreach ($categories as $category) {
            if ($category->parent_id === $parent) {
                $menuItems[$category->id] = [
                    'active' => $activeId === $category->id,
                    'label' => $category->title,
                    'url' => ['catalog/list', 'id' => $category->id,'name' => $category->slug],
                    'items' => $this->getMenuItems($categories, $activeId, $category->id),
                ];
            }
        }
        return $menuItems;
    }


    /**
     * Returns IDs of category and all its sub-categories
     *
     * @param Category[] $categories all categories
     * @param int $categoryId id of category to start search with
     * @param array $categoryIds
     * @return array $categoryIds
     */
    private function getCategoryIds($categories, $categoryId, &$categoryIds = [])
    {
        foreach ($categories as $category) {
            if ($category->id == $categoryId) {
                $categoryIds[] = $category->id;
            }
            elseif ($category->parent_id == $categoryId){
                $this->getCategoryIds($categories, $category->id, $categoryIds);
            }
        }
        return $categoryIds;
    }
}
