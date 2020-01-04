<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Core\Repositories\SliderRepository;

/**
 * Home page controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
 class HomeController extends Controller
{
    protected $_config;
    protected $sliderRepository;
    protected $current_channel;

    public function __construct(SliderRepository $sliderRepository)
    {
        $this->_config = request('_config');

        $this->sliderRepository = $sliderRepository;
    }

    /**
     * loads the home page for the storefront
     */
    public function index()
    {
        $currentChannel = core()->getCurrentChannel()->id;
        $sliderData = $this->sliderRepository->findByField('channel_id', $currentChannel)->toArray();

        // todo rewrite outside core

        // $activeSlugs = [];
        $categories = [];

        foreach (app('Webkul\Category\Repositories\CategoryRepository')->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id) as $cat) {
            if ($cat->slug)
                array_push($categories, $cat);
        }

        return view($this->_config['view'], compact('sliderData', 'categories'));
    }

    /**
     * loads the home page for the storefront
     */
    public function notFound()
    {
        abort(404);
    }
}