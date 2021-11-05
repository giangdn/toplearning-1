<?php

namespace Modules\AppNotification\Helpers;

class FirebaseMessage
{
	protected $Title = '';
	protected $Body = '';
	protected $Route = '';
	protected $Image = '';

	public function __construct($Title, $Body, $Route, $Image)
	{
		$this->Title = $Title;
		$this->Body = $Body;
		$this->Route = $Route;
		$this->Image = $Image;
	}

	public function to($DeviceToken)
	{
		return json_encode([
			'registration_ids' => $DeviceToken,
			'notification' => [
				'title' => $this->Title,
				'body' => $this->Body,
				'image' => $this->Image,
				'priority' => 10
			],
			'data' => [
				'Route' => $this->Route,
			]
		]);
	}
}
