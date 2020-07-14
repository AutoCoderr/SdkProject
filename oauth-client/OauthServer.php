<?php
include_once "Provider.php";

class OauthServer extends Provider {
    protected $name = "OauthServer";
    protected $client_id = "client_5edfd43b0db573.88203718";
    protected $client_secret="e0a6a1f5c55fafd48cbcce2b7279d4029fad76f4";
    protected $url = "http://localhost:7070/auth";
    protected $scope = "email";
    protected $state = "OauthServer";
    protected $redirect_url = "http://localhost:7071/success";
    protected $response_type = "code";

    function __construct() {
        parent::__construct();
    }

    function getInfosClient()
    {
        ['code' => $code, 'state' => $rstate] = $_GET;

        // Check state origin
        if ($this->state === $rstate) {
            // Get access token
            $link = "http://oauth-server/token?grant_type=authorization_code&code={$code}&client_id={$this->client_id}&client_secret={$this->client_secret}";
            ['token' => $token] = json_decode(file_get_contents($link), true);

            // Get user data
            $link = "http://oauth-server/me";
            echo $this->request($link, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => 0,
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer {$token}"
                ]
            ]);
        } else {
            http_response_code(400);
            echo "Invalid state";
        }
    }
}