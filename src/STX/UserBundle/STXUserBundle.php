<?php

namespace STX\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class STXUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}