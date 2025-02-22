<?php

namespace Drupal\neigborhood_proposal\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class controller events
 */
class ProposalController extends ControllerBase
{

    /**
     * The current user service.
     *
     * @var Session\AccountProxyInterface
     */
    protected $currentUser;

    /**
     * Cache
     * @var Cache\CacheBackendInterface
     */
    protected $cache;

    /**
     * Entity type manager
     *
     * @var Entity\EntityTypeManagerInterface
     */
    protected $entityTypeManager;
    /**
     * the render service
     *
     * @var \Drupal\Core\Render\RendererInterface
     */
    protected $renderer;

    /**
     * Constructs a StatementController object.
     *
     * @param Session\AccountProxyInterface $current_user
     *   The current user service.
     * @param Cache\CacheBackendInterface $cache
     *   Cache backend interface.
     * @param Entity\EntityTypeManagerInterface $entity_type_manager
     *   Entity type manager service.
     * @param \Drupal\Core\Render\RendererInterface $renderer
     *   the renderer service
     */
    public function __construct(
        AccountProxyInterface $currentUser,
        CacheBackendInterface $cache,
        EntityTypeManagerInterface $entityTypeManager,
        RendererInterface $renderer
    ) {
        $this->currentUser = $currentUser;
        $this->cache = $cache;
        $this->entityTypeManager = $entityTypeManager;
        $this->renderer = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('current_user'),
            $container->get('cache.default'),
            $container->get('entity_type.manager'),
            $container->get('renderer')
        );
    }

    /**
     * Create client
     * 
     * @param Request $req
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *  Json with data of ultmod
     */
    public function createClient(Request $req) : JsonResponse {
        $param = $req->query->all();
        var_dump($param);
        exit;
        return  new JsonResponse($param);
    }

    /**
     * Get calendar events
     *
     * @param string $type
     *  Type calendar event
     * @param string $field_year
     *  Field year is all or noyear, the difference is that the
     *  field_year_only field shows whether the event is annual or monthly and weekly
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *  Json with data of taxonomy
     */
    public function getDocumentType() : JsonResponse {
        $tax = $this->getListTaxonomy('document_type', 'Tipo de documento');
        return  new JsonResponse($tax);
    }

     /**
     * Get options list taxonomy
     * @param string machine name
     * @param string empty value
     */
    public function getListTaxonomy($machine_name, $empty_value = '-- Seleccionar --') : array {
        $result = ['' => $empty_value];
        $companies = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($machine_name);
        foreach ($companies as $key => $value) {
            $result[$value->tid] = $value->name;
        }
        return $result;
    }
    
}