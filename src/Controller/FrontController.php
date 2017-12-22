<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class FrontController extends Controller
{

	//Landing Page
	public function mainPage()
	{
		return new Response("<html><head></head><body>Landing</body></html>");
	}

	//Find à bird
	public function listing()
	{
		return new Response("<html><head></head><body>Listing</body></html>");
	}

	//datas about birds
	public function birdInfo()
	{
		return new Response("<html><head></head><body>Birdinfo</body></html>");
	}

	//datas about an observation
	public function obsInfo()
	{
		return new Response("<html><head></head><body>obsinfo</body></html>");
	}

	//put infos about an observartion
	public function observe()
	{
		return new Response("<html><head></head><body>observe</body></html>");
	}

		//contact
	public function contact()
	{
		return new Response("<html><head></head><body>contact</body></html>");
	}


	
	public function login()
	{
		return new Response("<html><head></head><body>login</body></html>");
	}
}