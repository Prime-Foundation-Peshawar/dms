<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Closed option lists for ACP dropdown fields.
 */
class AcpLists extends BaseConfig
{
    /** @return list<string> */
    public function userRoles(): array
    {
        return ['admin', 'editor'];
    }

    /** @return list<string> */
    public function contentStatuses(): array
    {
        return ['draft', 'published'];
    }

    /** @return list<string> */
    public function newsCategories(): array
    {
        return [
            'Admissions',
            'Achievement',
            'Conference',
            'Convocation',
            'General',
            'Research',
            'Society',
        ];
    }

    /** @return list<string> */
    public function eventCategories(): array
    {
        return $this->newsCategories();
    }

    /** @return list<string> */
    public function eventLocations(): array
    {
        return [
            'Peshawar Medical College',
            'Peshawar Medical College — Admissions',
            'Riphah International University – Peshawar Campus',
        ];
    }

    /** @return list<string> */
    public function pageSections(): array
    {
        return ['College', 'Academics', 'News'];
    }

    /** @return list<string> */
    public function pageChangefreqs(): array
    {
        return ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'];
    }

    /** @return list<string> */
    public function pageViewKeys(): array
    {
        return [
            'home',
            'about',
            'admissions',
            'vision-mission',
            'contact',
            'pmc',
            'faculty',
            'pg-medical-education',
            'curriculum',
            'examinations',
            'faculty-research',
            'student-guide',
            'vacant-seats',
            'clinical-skill-labs',
            'virtual-museum',
            'student-exchange',
            'gallery',
            'newsletter',
            'education-literature',
            'all-news',
            'single-news',
            'events',
            'event-single',
            'portal-login',
            'literary-society',
            'social-welfare',
            'sports-society',
            'sitemap-page',
            'cms_content',
            'content_wrap',
            'home_wrap',
            'pg-surgery-residency',
            'e-health',
            'student-research',
            'umr',
        ];
    }

    /** @return list<string> */
    public function departmentGroups(): array
    {
        return ['Basic', 'Clinical', 'Other'];
    }

    /** @return list<string> */
    public function facultyDesignations(): array
    {
        return [
            'Professor',
            'Associate Professor',
            'Assistant Professor',
            'Senior Lecturer',
            'Lecturer',
            'Senior Registrar',
            'Junior Registrar',
            'Demonstrator',
            'Principal',
            'Vice Principal',
            'HOD',
        ];
    }

    /** @return list<string> */
    public function vacantSeatYears(): array
    {
        return [
            '1st Year MBBS',
            '2nd Year MBBS',
            '3rd Year MBBS',
            '4th Year MBBS',
            'Final Year MBBS',
        ];
    }

    /** @return list<string> */
    public function vacantSeatSessions(): array
    {
        return [
            '2025-26',
            '2024-25',
            '2023-24',
            '2022-23',
            '2021-22',
            '2020-21',
        ];
    }

    /** @return list<string> */
    public function galleryIcons(): array
    {
        return [
            'bi-images',
            'bi-buildings',
            'bi-building',
            'bi-house-heart',
            'bi-mortarboard',
            'bi-people',
            'bi-camera',
            'bi-trophy',
            'bi-heart-pulse',
            'bi-hospital',
            'bi-book',
            'bi-calendar3',
            'bi-award-fill',
            'bi-globe2',
        ];
    }

    /** @return list<string> */
    public function departmentIcons(): array
    {
        return [
            'bi-bandaid',
            'bi-heart-pulse',
            'bi-hospital',
            'bi-capsule-pill',
            'bi-eyedropper',
            'bi-scissors',
            'bi-virus',
            'bi-moisture',
            'bi-droplet',
            'bi-body-text',
            'bi-activity',
            'bi-clipboard-pulse',
            'bi-book',
            'bi-people',
            'bi-person-badge',
            'bi-mortarboard',
            'bi-flask',
            'bi-gem',
        ];
    }
}
