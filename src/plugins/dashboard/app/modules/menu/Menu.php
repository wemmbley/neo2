<?php

declare(strict_types=1);

namespace Neo\Plugins\Dashboard\App\Modules\Menu;

use Neo\Core\App\Neo;

class Menu
{
    private Neo $neo;

    private array $menuItems = [];
    private string $menuItemsHtml = '';

    public function __construct(Neo $neo)
    {
        $this->neo = $neo;
    }

    public function setMenuHtml()
    {
        foreach ($this->menuItems as $item) {
            // separator
            if (empty($item)) {
                $this->menuItemsHtml .= '<li class="list-divider"></li>';
                continue;
            }

            // without children
            if (!isset($item['items'])) {
                $this->menuItemsHtml .= '<li class="sidebar-item"> 
                                            <a class="sidebar-link sidebar-link" href="'. $item['href'] .'" aria-expanded="false">
                                                <i class="'. $item['icon'] .'"></i>
                                                <span class="hide-menu">'. $item['name'] .'</span>
                                            </a>
                                         </li>';
                continue;
            }

            // has children
            if (isset($item['items'])) {
                $childrenHtml = '<li class="sidebar-item"> 
                                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                        <i class="'. $item['icon'] .'"></i>
                                        <span class="hide-menu">'. $item['name'] .'</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse first-level base-level-line">';

                foreach ($item['items'] as $children) {
                    // separator
                    if (empty($children)) {
                        $this->menuItemsHtml .= '<li class="list-divider"></li>';
                        continue;
                    }

                    $childrenHtml .= '<li class="sidebar-item"><a href="'. $children['href'] .'" class="sidebar-link"><span
                                            class="hide-menu">'. $children['name'] .'</span></a>
                                      </li>';
                }

                $childrenHtml .= '</ul></li>';

                $this->menuItemsHtml .= $childrenHtml;
            }
        }
    }

    public function getMenuHtml()
    {
        return $this->menuItemsHtml;
    }

    public function addItem(array $params)
    {
        //$this->validateParams($params);

        $this->menuItems[] = $params;
        // insert into db menu item
        // register new route
    }

    public function addSeparator()
    {

    }

    private function validateParams(array $params)
    {
        $requiredKeys = ['name', 'href', 'icon'];

        if (!is_array_has_keys($requiredKeys, $params)) {
            throw new \Exception('Plugin Menu, method addItem() wrong $params. Expected: ' . implode(',', $requiredKeys));
        }

        if (isset($params['items']) && !empty($params['items']) && is_array($params['items'])) {
            $requiredKeys = ['name', 'href'];

            // submenu validator
        }
    }
}