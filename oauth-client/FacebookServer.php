<?php
include_once "Provider.php";

class FacebookServer extends Provider {
    protected $name = "FacebookServer";
    protected $client_id = "589445128659258";
    protected $client_secret="6f4465276cf23bbfbc625d067c92c0ee";
    protected $url = "https://www.facebook.com/v7.0/dialog/oauth";
    protected $state = "FacebookServer";

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

            $response = $this->request("https://graph.facebook.com/oauth/access_token", [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $post
            ]);

            $res = json_decode($response);
            $token = $res->access_token;

            // Get user data
            echo $this->request("https://graph.facebook.com/".$this->client_id."/?access_token=".$token, [
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