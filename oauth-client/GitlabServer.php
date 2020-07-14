<?php
include_once "Provider.php";

class GitlabServer extends Provider {
    protected $name = "GitlabServer";
    protected $client_id = "5c8c809e7b2334ed4aa283d7737e4786f969bba572f80ac78641f6a78c6b216d";
    protected $client_secret="79a3e1cd75a8ed046cd41895eb339466a6f6470a69d972b9a0843e3e6acb6ac7";
    protected $url = "https://gitlab.com/oauth/authorize";
    protected $scope = "api read_api";
    protected $state = "GitlabServer";
    protected $response_type = "code";

    function __construct() {
        parent::__construct();
    }

    function getInfosClient()
    {
        ['code' => $code, 'state' => $rstate] = $_GET;

        // Check state origin
        if ($this->state === $rstate) {
            $post = [
              "client_id" => $this->client_id,
              "client_secret" => $this->client_secret,
              "code" => $code,
              "grant_type" => "authorization_code",
              "redirect_uri" => $this->redirect_url
            ];

            $response = $this->request("https://gitlab.com/oauth/token", [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $post
            ]);

            $res = json_decode($response);
            $token = $res->access_token;

            // Get user data
            echo $this->request("https://gitlab.com/api/v4/projects", [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => 0,
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer ".$token,
                    "https://gitlab.com/api/v4/projects"
                ]]);
        } else {
            http_response_code(400);
            echo "Invalid state";
        }
    }
}