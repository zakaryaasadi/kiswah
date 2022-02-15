<?php

namespace App\Services;

use App\Models\Region;
use Illuminate\Http\JsonResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Arr;
use App\Models\Task;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Exception\JsonException;

class Tookan
{

    private $base_url;
    private $api_key;
    private $client;

    public function __construct()
    {
        $this->base_url = config('tookan.base_url');
        $this->api_key = config('tookan.key');
        $this->access_token = config('tookan.access');
        $this->user = config('tookan.user');
        $this->fleet_id = config('tookan.fleet_id');
        $this->client = new Client(
            [
                'base_uri' => $this->base_url,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]
        );
    }
    /**
     * CUSTOMER SERVICES
     */

    /**
     * Lists Customers already on Tookan
     *
     * @param array $data
     * @return object
     * @throws \JsonException
     */
    public function listCustomers(array $data = []): object
    {
        return (object)$this->makeRequest('get_all_customers', 'POST', $data);
    }

    /**
     * Add Customer to Tookan API
     *
     * @param array $data
     * @return object
     * @throws \JsonException
     */
    public function addCustomer(array $data): object
    {
        $url = 'customer/add';
        $data['user_type'] = 0;
        return (object)$this->makeRequest($url, 'POST', $data);
    }

    /**
     * Edit customer on Tookan API
     *
     * @param array $data
     * @return object
     * @throws \JsonException
     */
    public function editCustomer(array $data): object
    {
        $url = 'customer/edit';
        $data['user_type'] = 0;
        return (object)$this->makeRequest($url, 'POST', $data);
    }

    /**
     * Delete customer on Tookan API
     *
     * @param int $customerId
     * @return object
     * @throws \JsonException
     */
    public function deleteCustomer(int $customerId): object
    {
        $url = 'delete_customer';
        return (object)$this->makeRequest($url, 'POST', ['customer_id' => $customerId]);
    }

    /**
     * Find Customer by phone on Tookan API
     *
     * @param String $phone
     * @return object
     * @throws \JsonException
     */
    public function findCustomerByPhone(string $phone): object
    {
        $url = 'find_customer_with_phone';
        return (object)$this->makeRequest($url, 'POST', ['customer_phone' => $phone]);
    }

    /**
     * Find Customer by name on Tookan API
     *
     * @param String $name
     * @return object
     * @throws \JsonException
     */
    public function findCustomerByName(string $name): object
    {
        $url = 'find_customer_with_name';
        return (object)$this->makeRequest($url, 'POST', ['customer_name' => $name]);
    }

    /**
     * Find Customer by ID on Tookan API
     *
     * @param int $id
     * @return object
     * @throws \JsonException
     */
    public function customerProfile(int $id): object
    {
        $url = 'find_customer_with_name';
        return (object)$this->makeRequest($url, 'POST', ['customer_id' => $id]);
    }

    /** USER SERVICES ***/

    /**
     * Edit User Phone on Tookan API
     *
     * @param String $phone
     * @return object
     * @throws \JsonException
     */
    public function updateUserPhone(string $phone): object
    {
        $url = 'edit_phone';
        return (object)$this->makeRequest($url, 'POST', ['phone' => $phone]);
    }


    /**
     * Edit customer on Tookan API
     *
     * @param array $data
     * @return object
     * @throws \JsonException
     */
    public function updateUserPassword(array $data): object
    {
        $url = 'users_change_password';
        return (object)$this->makeRequest($url, 'POST', $data);
    }


    /**
     * Check User email availability on Tookan API
     *
     * @param array $data
     * @return object
     * @throws \JsonException
     */
    public function checkUserEmail(array $data): object
    {
        $url = 'check_email_exists';
        return (object)$this->makeRequest($url, 'POST', $data);
    }


    /**
     * Show User data on Tookan API
     *
     * @return object
     * @throws \JsonException
     */
    public function getUser(): object
    {
        $url = 'get_user_details';
        return (object)$this->makeRequest($url, 'POST', []);
    }

    /** MANAGER SERVICES **
     * @param $data
     * @return object
     * @throws \JsonException
     */

    public function createManager($data): object
    {
        $url = 'add_manager';
        return (object)$this->makeRequest($url, 'POST', $data);
    }

    /**
     * @param $dispatcher_id
     * @return object
     * @throws \JsonException
     */
    public function deleteManager($dispatcher_id): object
    {
        $url = 'delete_manager';
        return (object)$this->makeRequest($url, 'POST', compact('dispatcher_id'));
    }


    /** TASK SERVICES ***/

    /**
     * @param Task $task
     * @return object
     * @throws \JsonException
     */
    public function createTask(Task $task): object
    {
        $user = auth()->user();
        $start = Carbon::parse($task->job_delivery_datetime);
        $start_time = $start->format('Y-m-d') . ' ' . now()->format('H:i:s');
        $end = Carbon::parse($start_time)->addHours(18);
        $end_time = $end->format('Y-m-d H:i:s ') ;
        $data = [
            "is_dashboard" => 1,
            "fleet_id" => "",
            "timezone" => -180,
            "has_pickup" => 0,
            "has_delivery" => 0,
            "start_time" => $start_time,
            "end_time" => $end_time,
            "pickup_delivery_relationship" => 0,
            'job_pickup_datetime' => $start_time,
            'job_delivery_datetime' => $end_time,
            'job_description' =>  'KSA App ' .$task->job_description,
            "layout_type" => 1,
            "team_id" => "",
            "geofence" => 0,
            "auto_assignment" => 1,
            "tags" => "",
            "no_of_agents" => 1,
            "deliveries" => [],
            "pickups" => [],
            'meta_data' => $task->meta_data,
            "appointments" => [
                [
                    "address" => $task->location->address,
                    "name" => $user->name ?? '',
                    'latitude' => $task->location->latitude,
                    'longitude' => $task->location->longitude,
                    "start_time" => $start_time,
                    "end_time" => $end_time,
                    "phone" => $user->phone ?? '',
                    "template_data" => [],
                    "ref_images" => $task->ref_images,
                    "email" => $user->email ?? '',
                    "order_id" => $task->order_id ?? '',
                    'job_pickup_datetime' => $start_time,
                    'job_delivery_datetime' => $end_time,
                    'job_description' => 'KSA App ' . $task->job_description,
                ]
            ],
        ];
        $url = 'create_multiple_tasks';
        return (object)$this->makeRequest($url, 'POST', $data);
    }


    public function createWebTask( $request): object
    {

        $start = Carbon::parse($request->job_delivery_datetime);
        $start_time = $start->format('Y-m-d') . ' ' . now()->format('H:i:s');
        $end = Carbon::parse($start_time)->addHours(18);
        $end_time = $end->format('Y-m-d H:i:s ') ;
        $data = [
            "is_dashboard" => 1,
            "fleet_id" => "",
            "timezone" => -180,
            "has_pickup" => 0,
            "has_delivery" => 0,
            "start_time" => $start_time,
            "end_time" => $end_time,
            "pickup_delivery_relationship" => 0,
            'job_pickup_datetime' => $start_time,
            'job_delivery_datetime' => $end_time,
            'job_description' =>  'KSA Web ' .$request->job_description,
            "layout_type" => 1,
            "team_id" => "",
            "geofence" => 0,
            "auto_assignment" => 1,
            "tags" => "",
            "no_of_agents" => 1,
            "deliveries" => [],
            "pickups" => [],
            'meta_data' => [],
            "appointments" => [
                [
                    "address" => $request->address,
                    "name" => $request->name ?? '',
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    "start_time" => $start_time,
                    "end_time" => $end_time,
                    "phone" => $request->phone ?? '',
                    "template_data" => [],
                    "ref_images" => [],
                    "email" => $request->email ?? '',
                    "order_id" => rand(72222222222222,99999999999999),
                    'job_pickup_datetime' => $start_time,
                    'job_delivery_datetime' => $end_time,
                    'job_description' => 'KSA Web ' . $request->job_description,
                ]
            ],
        ];
        $url = 'create_multiple_tasks';
        return (object)$this->makeRequest($url, 'POST', $data);
    }

    /**
     * @param array $_data
     * @return object
     * @throws \JsonException
     */
    public function createTaskForFormUser(array $_data): object
    {
        $data = array_merge([
            'team_id' => '',
            'auto_assignment' => '0',
            'has_pickup' => '1',
            'has_delivery' => '0',
            'layout_type' => '0',
            'tracking_link' => 1,
            'timezone' => '300',
            'fleet_id' => '',
            'notify' => 1,
            'tags' => '',
            'geofence' => 0,
        ], $_data);
        $url = 'create_task_for_form_use';
        return (object)$this->makeRequest($url, 'POST', $data);
    }

    /**
     * @param array $job_ids
     * @param int $include_task_history
     * @return object
     * @throws \JsonException
     */
    public function getTaskDetails($job_ids = [], $include_task_history = 1): object
    {

        $url = 'get_job_details';
        return (object)$this->makeRequest($url, 'POST', compact('include_task_history', 'job_ids'));
    }

    /**
     * @param array $order_ids
     * @param int $include_task_history
     * @return object
     * @throws \JsonException
     */
    public function getTaskDetailsFromOrders($order_ids = [], $include_task_history = 1): object
    {

        $url = 'get_job_details_by_order_id';
        return (object)$this->makeRequest($url, 'POST', compact('include_task_history', 'order_ids'));
    }

    /**
     * @param String $job_id
     * @param array $data
     * @return object
     * @throws \JsonException
     */
    public function editTask($job_id = '', $data = []): object
    {
        $data['job_id'] = $job_id;
        $url = 'get_job_details_by_order_id';
        return (object)$this->makeRequest($url, 'POST', $data);
    }



    /** REGIONS SERVICES ***/

    /**
     * @param Region $region
     * @return object
     * @throws \JsonException
     */
    public function addRegion(Region $region): object
    {
        $data = [
            "region_name" => $region->name,
            "region_description" => $region->description,
            "region_data" => $region->data,
            "selected_team_id" => [],
            "fleet_id" => $this->fleet_id,
            "is_force" => 0,
            "user_id" => $this->user
        ];
        $url = 'add_region';
        return (object)$this->makeRequest($url, 'POST', $data, true);
    }

    /**
     * @param Region $region
     * @return object
     * @throws \JsonException
     */
    public function deleteRegion(Region $region): object
    {
        $data = [
            "region_id" => $region->tookan_id,
            "user_id" => $this->user,
        ];
        $url = 'remove_region';
        return (object)$this->makeRequest($url, 'POST', $data);
    }

    /**
     * @param Region $region
     * @return object
     * @throws \JsonException
     */
    public function deleteRegionFromAgent(Region $region, $fleet_id): object
    {
        $data = [
            "region_id" => $region->tookan_id,
            "user_id" => 988782,
            'fleet_id' => $fleet_id
        ];
        $url = 'remove_region_for_agent';
        return (object)$this->makeRequest($url, 'POST', $data);
    }


    /**
     * @return object
     * @throws \JsonException
     */
    public function findRegion($lat, $lng): object
    {
        $data['points'] = [['latitude' => (float)$lat, 'longitude' => (float)$lng]];
        $url = 'find_region_from_points';
        return (object)$this->makeRequest($url, 'POST', $data);
    }


    /**
     * @param Region $region
     * @return object
     * @throws \JsonException
     */
    public function editRegion(Region $region): object
    {
        $data = [
            "region_name" => $region->name,
            "region_description" => $region->description,
            "region_data" => $region->data,
            "selected_team_id" => [986651],
            "region_id" => $region->tookan_id,
            "fleet_id" => "1029444",
            "is_force" => 1,
        ];
        $url = 'edit_region';
        return (object)$this->makeRequest($url, 'POST', $data, true);
    }

    /**
     * @param string $id
     * @return object
     * @throws \JsonException
     */
    public function viewRegions($id = ''): object
    {
        $data = ["user_id" => 988782];
        if ($id) {
            $data['region_id'] = $id;
        }
        $url = 'view_regions';
        return (object)$this->makeRequest($url, 'POST', $data);
    }


    /** AGENTS SERVICES ***/
    //TODO Agent services


    /** TEAM SERVICES ***/
    //TODO Team Services


    /**
     * NETWORK REQUESTER
     * @param String $url
     * @param String $method
     * @param Object $data
     */
    public function makeRequest(string $url, string $method, $data = [], $use_access = false)
    {
        if ($use_access) {
            $request_url = 'https://api.tookanapp.com/v2/' . $url;
            $data['access_token'] = $this->access_token;
            $data['user_id'] = $this->user;
        } else {
            $request_url = $this->base_url . $url;
            $data['api_key'] = $this->api_key;
        }
        try {
            $response = $this->client->{strtolower($method)}($request_url, ['body' => json_encode($data, JSON_THROW_ON_ERROR)]);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        }
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }
}
