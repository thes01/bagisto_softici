<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Shop\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Product\Repositories\ProductRepository as Product;

/**
 * Category controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CategoryController extends Controller
{

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * CategoryRepository object
     *
     * @var array
     */
    protected $category;

    /**
     * ProductRepository object
     *
     * @var array
     */
    protected $product;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Category\Repositories\CategoryRepository $category
     * @param  \Webkul\Product\Repositories\ProductRepository $product
     * @return void
     */
    public function __construct(Category $category, Product $product)
    {
        $this->product = $product;

        $this->category = $category;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        $category = $this->category->findBySlugOrFail($slug);
        $breadcrumbs = $this->getBreadcrumbs($category);

        $categories = [];

        foreach (app('Webkul\Category\Repositories\CategoryRepository')->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id) as $cat) {
            if ($cat->slug)
                array_push($categories, $cat);
        }

        return view($this->_config['view'], compact('category', 'breadcrumbs', 'categories'));
    }

    private function getBreadcrumbs($category) {
        $breadcrumbs = collect([
            ['name' => 'E-shop', 'slug' => 'eshop']
        ]);

        // todo: extend Controller instead of writing in the Core
        // todo: czech translations
        $breadcrumbsCategories = $category->ancestors()->where('parent_id', '!=', null)->get()->merge([$category]);

        $breadcrumbs = $breadcrumbs->merge($breadcrumbsCategories->map(function ($c) {
            return [
                'name' => $c->name,
                // todo: translatable route
                'slug' => 'categories/'.$c->slug
            ];
        }));

        return $breadcrumbs;

        // $activeSlugs = $breadcrumbsCategories->map(function ($c) {
        //     return $c->slug;
        // });
    }
}
