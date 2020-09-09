<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Domain;

class LogItem
{

    public static function convert(array $raw): array
    {
        $raw = $raw['data'];
        $data = [
            'riddle_id' => $raw['riddle']['id'] ?? 0,
            'riddle_title' => $raw['riddle']['title'] ?? '',
            'riddle_type' => $raw['riddle']['type'] ?? '',
            'embed_location' => $raw['embed']['parentLocation'] ?? '',
            'time_taken' => $raw['timeTaken']['milliseconds'] ?? 0,
            'result_score_number' => $raw['resultData']['scoreNumber'] ?? 0,
            'result_result_index' => $raw['resultData']['resultIndex'] ?? 0,
            'result_score_percentage' => $raw['resultData']['scorePercentage'] ?? 0,
            'result_score_text' => $raw['resultData']['scoreText'] ?? '',
            'result_title' => $raw['resultData']['title'] ?? '',
            'result_description' => $raw['resultData']['description'] ?? '',
            'result_id' => $raw['resultData']['resultId'] ?? '',
            'lead_name' => $raw['lead2']['Name']['value'] ?? '',
            'lead_email' => $raw['lead2']['Email']['value'] ?? '',
            'full_json' => json_encode($raw, JSON_THROW_ON_ERROR)
        ];

        return $data;
    }
}
