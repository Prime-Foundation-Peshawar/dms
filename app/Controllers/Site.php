<?php

namespace App\Controllers;

class Site extends PublicController
{
    public function robots()
    {
        ob_start();
        include ROOTPATH . 'legacy/robots.php';
        $body = (string) ob_get_clean();

        return $this->response
            ->setHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->setHeader('X-Robots-Tag', 'noindex')
            ->setBody($body);
    }

    public function sitemap()
    {
        ob_start();
        include ROOTPATH . 'legacy/sitemap.php';
        $body = (string) ob_get_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->setBody($body);
    }
}
