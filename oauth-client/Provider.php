<?php

abstract class Provider {

    protected $redirect_url = "http://localhost:7071/success";

    public function __construct() {
        $this->url= $this->url ."?client_id={$this->client_id}&state={$this->state}&redirect_uri={$this->redirect_url}";
        if (property_exists(get_called_class(), "response_type")) {
            $this->url .= "&response_type=".$this->response_type;
        }
        if (property_exists(get_called_class(), "scope")) {
            $this->url .= "&scope=".$this->scope;
        }
    }

    public function displayLink() {
		echo "<a href='".$this->url."'>Se connecter via ".$this->name."</a><br/>";
	}

	public function request($url, $opts) {
        $conn = curl_init($url);
        foreach ($opts as $opt => $value) {
            curl_setopt($conn, $opt, $value);
        }
        $res = curl_exec($conn);
        curl_close($conn);
        return $res;
    }
	
	abstract function getInfosClient();

}