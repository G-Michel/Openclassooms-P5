<?php

namespace App\Controller;

use App\Entity\Taxref;
use App\Repository\TaxrefRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/taxref")
 */
class TaxrefController extends Controller
{
    /**
     * @Route("/", name="taxref_index")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
    public function index(Request $request, TaxrefRepository $taxref): Response
    {
        if (!$request->isXmlHttpRequest()) {
            $posts = $taxref->findByFrType();
            return $this->render('taxref/index.html.twig', compact('posts'));
        }

        $rawQuery = $request->query->get('q', '');
        $query = $this->sanitizeSearchQuery($rawQuery);
        $searchTerms = $this->extractSearchTerms($query);
        $termsLighting = $this->lightingSearchTerms($searchTerms);
        $limit = $request->query->get('l', 30);
        $foundPosts = $taxref->findBySearchQuery($searchTerms, $limit);

        $results = [];
        foreach ($foundPosts as $post) {

            $results[] = [
                'nomVernType' => str_ireplace($searchTerms,$termsLighting,htmlspecialchars($post->getNomVernType())),
                'phylumType' => htmlspecialchars($post->getPhylumType()),
                'classType' => htmlspecialchars($post->getClassType()),
                'url' => $post->getPicture() ? htmlspecialchars($post->getPicture()->getUrl()):'',
                'alt' => $post->getPicture() ? htmlspecialchars($post->getPicture()->getAlt()):''
            ];
        }

        return $this->json($results);
    }

    /**
     * @Route("/{slug}",name="taxref_show")
     * @Method("GET")
     */
    public function show(Taxref $taxref): Response
    {
        return $this->render('taxref/show.html.twig', compact('taxref'));
    }


    /**
     * Removes all non-alphanumeric characters except whitespaces.
     */
    private function sanitizeSearchQuery(string $query): string
    {
        return preg_replace('/[^[:alnum:] ]/', '', trim(preg_replace('/[[:space:]]+/', ' ', $query)));
    }

    /**
     * Splits the search query into terms and removes the ones which are irrelevant.
     */
    private function extractSearchTerms(string $searchQuery): array
    {
        $terms = array_unique(explode(' ', mb_strtolower($searchQuery)));

        return array_filter($terms, function ($term) {
            return 2 <= mb_strlen($term);
        });
    }

    /**
     * Splits the search query into terms and removes the ones which are irrelevant.
     */
    private function lightingSearchTerms(array $searchTerms): array
    {
        $bgs = ['bg-primary','bg-secondary','bg-success','bg-danger','bg-info'];
        return array_map(function($term, $bg) {
            $bg = $bg ?: 'bg-primary';
            return '<span class="'.$bg.' text-white">' . $term . '</span>';

        }, $searchTerms,$bgs);

    }
}
