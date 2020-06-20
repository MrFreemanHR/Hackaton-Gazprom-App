<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\Response;

class APIResponse{
	
	private $data;
	private $code;
	private $message;

	public function __construct($data, $code = 0, $message = "")
	{
		$this->data = $data;
		$this->code = $code;
		$this->message = $message;
	}

	public function __invoke()
	{
		$responseSymfony = new Response();
        $responseSymfony->headers->set("Access-Control-Allow-Origin", "*");
		$response = [];
		if (($this->data) and (!$this->code))
			$response['data'] = $this->data;
		elseif ((!$this->data) and ($this->code != 0) and ($this->message != ""))
			$response['error'] = ['code' => $this->code, 'message' => $this->message];
		$responseSymfony->setContent(json_encode($response, JSON_UNESCAPED_UNICODE));
		return [$responseSymfony];
	}

}

?>