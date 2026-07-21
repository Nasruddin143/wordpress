<?php

namespace KT\Features;

if (!defined('ABSPATH')) exit;

class SEO
{
    public function __construct()
    {
        add_filter('rank_math/json_ld', [$this, 'opening_hours'], 99, 2);
    }

    /**
     * Add Opening Hours Schema (Improved Version)
     */
    public function opening_hours($data, $jsonld)
    {
        if (empty($data) || !is_array($data)) {
            return $data;
        }

        foreach ($data as $key => $entity) {

            if (!isset($entity['@type'])) continue;

            $types = (array) $entity['@type'];

            // Target your business type
            if (in_array('MensClothingStore', $types, true) || in_array('LocalBusiness', $types, true)) {

                // Remove old format
                if (isset($data[$key]['openingHours'])) {
                    unset($data[$key]['openingHours']);
                }

                // Add structured format
                $data[$key]['openingHoursSpecification'] = $this->get_hours();

                // Optional: Add timezone (GOOD SEO SIGNAL)
                $data[$key]['timeZone'] = 'Asia/Kolkata';
            }
        }

        return $data;
    }

    /**
     * Define Opening Hours
     */
    private function get_hours()
    {
        return [
            [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => [
                    'https://schema.org/Monday',
                    'https://schema.org/Tuesday',
                    'https://schema.org/Thursday',
                    'https://schema.org/Friday',
                    'https://schema.org/Saturday',
                    'https://schema.org/Sunday',
                ],
                'opens'  => '09:00',
                'closes' => '21:00',
            ]
        ];
    }
}