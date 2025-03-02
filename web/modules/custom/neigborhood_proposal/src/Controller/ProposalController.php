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
        $data = json_decode($req->getContent(), true);
        $data['success'] = FALSE;
        if($data){
            $node = $this->entityTypeManager->getStorage('node')->create([
                'type' => 'custommer',
                'title' => $data['names'] . ' ' . $data['lastnames'] .' '.$data['document'],
                'field_document_number' => $data['document'],
                'field_names' => $data['names'],
                'field_lastname' => $data['lastnames'],
                'field_birth_date' => $data['birth_date'],
                'field_document_type' => $data['document_type'],
            ]);

            if($node->save()){
                $data['success'] = true;
            }
        }
        return new JsonResponse($data);
    }

    /**
     * Get calendar events
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *  Json with data of taxonomy
     */
    public function getDocumentType() : JsonResponse {
        $tax = $this->getListTaxonomy('document_type', 'Tipo de documento');
        return  new JsonResponse($tax);
    }

    /**
     * Get Clasification for activity id
     *
     * @param int $activity_id
     *  Id Activity
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *  Json with data of taxonomy
     */
    public function getClasificationForActivity($idActivity) : JsonResponse {
        $result = ['' => '-- Seleccionar --'];
        $companies = $this->entityTypeManager->getStorage('taxonomy_term')->loadTree('activity_clasification');
        foreach ($companies as $key => $value) {
            if(reset($value->parents) == $idActivity)
                $result[$value->tid] = $value->name;
        }
        return  new JsonResponse($result);
    }

     /**
     * Get options list taxonomy
     * @param string machine name
     * @param string empty value
     */
    public function getListTaxonomy($machine_name, $empty_value = '-- Seleccionar --') : array {
        $result = ['' => $empty_value];
        $companies = $this->entityTypeManager->getStorage('taxonomy_term')->loadTree($machine_name);
        foreach ($companies as $key => $value) {
            $result[$value->tid] = $value->name;
        }
        return $result;
    }

}