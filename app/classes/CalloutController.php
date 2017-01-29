<?php
use Interop\Container\ContainerInterface;
use \Psr\Http\Message\ResponseInterface as Response;

class CalloutController
{
    protected $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * get all callouts from db, return as json
     * @param $request
     * @param Response $response
     * @return mixed
     */
    public function getCallouts($request, Response $response){
        try {
            $callouts = [];
            foreach ($this->container->db->query('SELECT * FROM callouts ORDER BY id') as $row) {
                $callouts[] = $row;
            }
            return json_encode($callouts);
        } catch (PDOException $e) {
            return $response->withStatus(400);
        }
    }

    /**
     * add new callout to db, return insert id
     * @param $request
     * @param Response $response
     * @return mixed
     */
    public function addCallout($request, Response $response){
        //create new callout array from request
        $data = $request->getParsedBody();
        $callout = [];
        $callout['title'] = filter_var($data['title'], FILTER_SANITIZE_STRING);
        $callout['type'] = filter_var($data['type'], FILTER_SANITIZE_STRING);
        $callout['message'] = filter_var($data['message'], FILTER_SANITIZE_STRING);
        //send callout to db
        try {
            $query = "INSERT INTO callouts (title, type, message) VALUES ('" . $callout['title'] . "', '" . $callout['type'] . "', '" . $callout['message'] . "')";
            $this->container->db->query($query);
            //return insert id
            return $this->container->db->lastInsertId();
        } catch (PDOException $e) {
            return $response->withStatus(400);
        }
    }

    /**
     * update callout
     * @param $request
     * @param Response $response
     * @return mixed
     */
    public function updateCallout($request, Response $response){
        try {
            //create new callout array from request
            $data = $request->getParsedBody();
            $callout = [];
            $callout['title'] = filter_var($data['title'], FILTER_SANITIZE_STRING);
            $callout['type'] = filter_var($data['type'], FILTER_SANITIZE_STRING);
            $callout['message'] = filter_var($data['message'], FILTER_SANITIZE_STRING);
            $callout['id'] = filter_var($data['id'], FILTER_SANITIZE_STRING);
            //send callout to db
            $query = "UPDATE callouts SET title='" . $callout['title'] . "', type='" . $callout['type'] . "', message='" . $callout['message'] . "' WHERE id=" . $callout['id'];
            $this->container->db->query($query);
            return true;
        } catch (PDOException $e) {
            return $response->withStatus(400);
        }
    }

    /**
     * delete callout
     * @param $request
     * @param Response $response
     * @param $args
     * @return mixed
     */
    public function deleteCallout($request, Response $response, $args){
        try {
            $this->container->db->query('DELETE FROM callouts WHERE id=' . $args["calloutId"]);
            return true;
        } catch (PDOException $e) {
            return $response->withStatus(400);
        }
    }
}