<?php


namespace App\Utils;


use Symfony\Component\HttpFoundation\JsonResponse;

class JsonProblemResponse extends JsonResponse
{

    /**
     * Create a RFC7807 problem json response, see https://tools.ietf.org/rfc/rfc7807.txt
     * @param string $title A short, human-readable summary of the problem type.
     * @param null $details A human-readable explanation specific to this occurrence of the problem.
     * @param int $status HTTP status code
     * @param array $headers Additional headers
     */
    public function __construct(string $title, $details = null, int $status = 200, array $headers = [])
    {
        $data = [
            'title' => $title,
            'status' => $status,
        ];
        if ($details !== null) {
            $data['details'] = $details;
        }
        parent::__construct($data, $status, $headers);
    }
}