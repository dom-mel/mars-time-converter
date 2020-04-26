<?php


namespace App\Controller;


use App\Service\ConverterService;
use App\Utils\JsonProblemResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConverterController extends AbstractController
{
    /**
     * @Route("/v1/convert")
     */
    public function convert(Request $request, ConverterService $converter): Response
    {
        $date = $request->query->get('date');
        if ($date === null) {
            return new JsonProblemResponse('Bad Request', 'date parameter is missing', 400);
        }
        try {
            $date = new \DateTime($date);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid date format', $e->getCode(), $e);
        }
        $result = [
            'Mars Sol Date' => $converter->getMarsSolDate($date),
            'Martian Coordinated Time' => $converter->getCoordinatedMarsTime($date),
        ];
        return new JsonResponse($result, 200);
    }

}