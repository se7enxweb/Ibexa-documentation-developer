<?php declare(strict_types=1);

namespace App\Controller;

use Ibexa\Bundle\Core\Controller;
use Ibexa\Contracts\Core\Repository\LocationService;
use Ibexa\Contracts\Core\Repository\SearchService;
use Ibexa\Contracts\Core\Repository\Values\Content\LocationQuery;
use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion;
use Symfony\Component\HttpFoundation\Response;

class BreadcrumbController extends Controller
{
    private LocationService $locationService;

    private SearchService $searchService;

    public function __construct(LocationService $locationService, SearchService $searchService)
    {
        $this->locationService = $locationService;
        $this->searchService = $searchService;
    }

    public function showBreadcrumbsAction($locationId): Response
    {
        $query = new LocationQuery();
        $query->query = new Criterion\Ancestor([$this->locationService->loadLocation($locationId)->pathString]);

        $results = $this->searchService->findLocations($query);
        $breadcrumbs = [];
        foreach ($results->searchHits as $searchHit) {
            $breadcrumbs[] = $searchHit;
        }

        return $this->render(
            '@ibexadesign/parts/breadcrumbs.html.twig',
            [
                'breadcrumbs' => $breadcrumbs,
            ]
        );
    }
}
