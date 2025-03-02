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
use Dompdf\Dompdf;
use Symfony\Component\HttpFoundation\Response;

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
        /*$coverage = $this->entityTypeManager->getStorage('node')->load(57);
        $coverage->set('field_sum',8000000);
        $coverage->save();*/
        $tax = $this->getListTaxonomy('document_type', 'Tipo de documento');
        return  new JsonResponse($tax);
    }

    /**
     * Calculate prize total proposal
     *
     * @param Request $req
     *  Request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *  Json with data
     */
    public function calculateProposal(Request $req) : JsonResponse {
        $content = json_decode($req->getContent(), TRUE);
        $coverage = $this->entityTypeManager->getStorage('node')->load($this->extractId($content['coverage']));
        $prize = 0;
        $prizeTotal = 0;
        if(!empty($coverage))
            $prize = $coverage->field_monthly_value->value;
        foreach ($content['lines'] as $key => $line) {
            $custommer = $this->entityTypeManager->getStorage('node')->load($this->extractId($line['custommer']));
            $forEge =  $this->ageCalculate($custommer->field_birth_date->value) > 60 ? ($prize * 2) : $prize;
            $prizeTotal += $forEge * $content['months'];
        }
        return  new JsonResponse(['prize' => $prize, 'prizeTotal' => $prizeTotal]);
    }

    /**
     * Return age
     *
     * @param [type] $birthDate
     * @return void
     */
    public function ageCalculate($birthDate) {
        $birthDate = new \DateTime($birthDate);
        $today = new \DateTime();
        $age = $birthDate->diff($today)->y;
        return $age;
    }

    /**
     * Return number id
     *
     * @param string $text
     *
     * @return int
     */
    public function extractId($text) : int {
        if (preg_match('/\((\d+)\)/', $text, $matches)) {
            return $matches[1];
        }
        return -1;
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

    /**
     * Download PDF proposal
     *
     * @param $nid
     *   id node
     * @return Response
     *  Return PDF proposal
     */
    public function downloadPDFProposal($nid) : Response {
        $proposal = $this->entityTypeManager->getStorage('node')->load($nid);
        $base_path = \Drupal::service('extension.path.resolver')->getPath('module', 'neigborhood_proposal');
        $tmp = \Drupal::service('file_system')->getTempDirectory();
        $dompdf = new Dompdf([
        'isRemoteEnabled' => TRUE,
        'fontDir' => $tmp,
        'fontCache' => $tmp,
        'tempDir' => $tmp,
        'chroot' => $tmp,
        ]);
        ob_start();
        require_once $base_path . '/templates/DownloadProposal.php';
        $html = ob_get_clean();
        $dompdf->loadHtml($html);
        $dompdf->render();
        $pdfOutput = $dompdf->output();
        $filename = "Proposal_$nid.pdf";
        return new Response(
            $pdfOutput,
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]
        );

      }

}