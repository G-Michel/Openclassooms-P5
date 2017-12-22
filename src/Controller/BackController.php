<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class BackController extends Controller
{
	//personnal space
	public function personnalSpace()
	{
		return new Response("<html><head></head><body>observe</body></html>");
	}

	//edit your profile
	public function profileManagement()
	{
		return new Response("<html><head></head><body>contact</body></html>");
	}


	//a naturalist can valid an observation
	public function comfirmObs()
	{
		return new Response("<html><head></head><body>comfirmobs</body></html>");
	}

	//grand naturalist's rights to a user
	public function addNaturalist()
	{
		return new Response("<html><head></head><body>add naturalist</body></html>");
	}
}