<?php namespace App\Pagination;

use Illuminate\Pagination\BootstrapThreePresenter;

class ClubPresenter extends BootstrapThreePresenter
{

    /**
     * Convert the URL window into Bootstrap HTML.
     *
     * @return string
     */
    public function render()
    {
        if ($this->hasPages()) {
            return sprintf(
                '<ul class="pagination pull-right">%s %s %s</ul>',
                $this->getPreviousButton(),
                $this->getLinks(),
                $this->getNextButton()
            );
        }

        return '';
    }

    /**
     * Get HTML wrapper for a page link.
     *
     * @param  string $url
     * @param  int    $page
     * @param null    $rel
     *
     * @return string
     */
    public function getAvailablePageWrapper($url, $page, $rel = null)
    {
        $rel = is_null($rel) ? '' : ' rel="' . $rel . '"';

        return '<li><a href="' . $url . '"' . $rel . '>' . $page . '</a></li>';
    }

    /**
     * Get HTML wrapper for disabled text.
     *
     * @param  string $text
     *
     * @return string
     */
    public function getDisabledTextWrapper($text)
    {
        return '<li class="disabled"><a href="#">' . $text . '</a></li>';
    }

    /**
     * Get HTML wrapper for active text.
     *
     * @param  string $text
     *
     * @return string
     */
    public function getActivePageWrapper($text)
    {
        return '<li class="active"><a href="#">' . $text . '</a></li>';
    }

    /**
     * Get HTML wrapper for a previous page link.
     *
     * @param  string $url
     * @param  string $class
     *
     * @return string
     */
    public function getPrevPageLinkWrapper($url, $class)
    {
        return '<li class="' . $class . '"><a href="' . $url . '"><i class="fa fa-arrow-left"></i></a></li>';
    }

    /**
     * Get HTML wrapper for a next page link.
     *
     * @param  string $url
     * @param  string $class
     *
     * @return string
     */
    public function getNextPageLinkWrapper($url, $class)
    {
        return '<li class="' . $class . '"><a href="' . $url . '"><i class="fa fa-arrow-right"></i></a></li>';
    }

    /**
     * Get HTML wrapper for previous disabled text.
     *
     * @param  string $class
     *
     * @return string
     */
    public function getPrevDisabledTextWrapper($class)
    {
        return '<li class="disabled ' . $class . '"><a href="#"><i class="fa fa-arrow-left"></i></a></li>';
    }

    /**
     * Get HTML wrapper for next disabled text.
     *
     * @param  string $class
     *
     * @return string
     */
    public function getNextDisabledTextWrapper($class)
    {
        return '<li class="disabled ' . $class . '"><a href="#"><i class="fa fa-arrow-right"></i></a></li>';
    }

    /**
     * Get the previous page pagination element.
     *
     * @param  string $text
     *
     * @return string
     */
    public function getPrevious($text = '&laquo;')
    {
        // If the current page is less than or equal to one, it means we can't go any
        // further back in the pages, so we will render a disabled previous button
        // when that is the case. Otherwise, we will give it an active "status".
        if ($this->currentPage <= 1) {
            return $this->getPrevDisabledTextWrapper('prev');
        } else {
            $url = $this->paginator->getUrl($this->currentPage - 1);

            return $this->getPrevPageLinkWrapper($url, 'prev');
        }
    }

    /**
     * Get the next page pagination element.
     *
     * @param  string $text
     *
     * @return string
     */
    public function getNext($text = '&raquo;')
    {
        // If the current page is greater than or equal to the last page, it means we
        // can't go any further into the pages, as we're already on this last page
        // that is available, so we will make it the "next" link style disabled.
        if ($this->currentPage >= $this->lastPage) {
            return $this->getNextDisabledTextWrapper('next');
        } else {
            $url = $this->paginator->getUrl($this->currentPage + 1);

            return $this->getNextPageLinkWrapper($url, 'next');
        }
    }

}
