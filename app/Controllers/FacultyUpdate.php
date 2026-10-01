<?php

namespace App\Controllers;

class FacultyUpdate extends PublicController
{
    public function index(): string
    {
        require_once ROOTPATH . 'legacy/includes/faculty-lib.php';

        $page_title = 'Faculty Profile Form | Department of Medical Sciences';
        $page_description = 'Add or update your PMC faculty profile for the college website.';
        $robots = 'noindex, nofollow';
        $thanks = $this->request->getGet('thanks') !== null;
        $errorMsg = trim((string) ($this->request->getGet('error') ?? ''));

        return $this->renderPage('pages/faculty-update', compact(
            'page_title',
            'page_description',
            'robots',
            'thanks',
            'errorMsg'
        ));
    }

    public function submit()
    {
        // Delegate to existing legacy submit (keeps validation/upload logic).
        ob_start();
        include ROOTPATH . 'legacy/faculty-update-submit.php';
        $body = (string) ob_get_clean();
        // Legacy script redirects via header(); if it returned, pass body through.
        if ($body !== '') {
            return $this->response->setBody($body);
        }

        return $this->response;
    }
}
