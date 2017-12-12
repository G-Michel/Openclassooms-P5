<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class MainController extends Controller
{
	public function helloWorld()
	{
		return new Response("<html><head></head><body>hello world</body></html>");
	}
}