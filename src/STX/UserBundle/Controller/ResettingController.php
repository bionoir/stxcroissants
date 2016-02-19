<?php

namespace STX\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\ResettingController as BaseController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResettingController extends BaseController {
    
	/**
	 * Request reset user password: show form
	 */
	public function requestAction()
	{
		return $this->container->get('templating')->renderResponse('STXUserBundle:Resetting:request.html.'.$this->getEngine());
	}
	
	
	/**
	 * Request reset user password: submit form and send email
	 */
	public function sendEmailAction()
	{
		$username = $this->container->get('request')->request->get('username');
	
		/** @var $user UserInterface */
		$user = $this->container->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);
	
		if (null === $user) {
			return $this->container->get('templating')->renderResponse('STXUserBundle:Resetting:request.html.'.$this->getEngine(), array('invalid_username' => $username));
		}
	
		if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
			return $this->container->get('templating')->renderResponse('STXUserBundle:Resetting:passwordAlreadyRequested.html.'.$this->getEngine());
		}
	
		if (null === $user->getConfirmationToken()) {
			/** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
			$tokenGenerator = $this->container->get('fos_user.util.token_generator');
			$user->setConfirmationToken($tokenGenerator->generateToken());
		}
	
		$this->container->get('session')->set(static::SESSION_EMAIL, $this->getObfuscatedEmail($user));
		$this->container->get('fos_user.mailer')->sendResettingEmailMessage($user);
		$user->setPasswordRequestedAt(new \DateTime());
		$this->container->get('fos_user.user_manager')->updateUser($user);
	
		return new RedirectResponse($this->container->get('router')->generate('fos_user_resetting_check_email'));
	}
	
	/**
	 * Tell the user to check his email provider
	 */
	public function checkEmailAction()
	{
		$session = $this->container->get('session');
		$email = $session->get(static::SESSION_EMAIL);
		$session->remove(static::SESSION_EMAIL);
	
		if (empty($email)) {
			// the user does not come from the sendEmail action
			return new RedirectResponse($this->container->get('router')->generate('fos_user_resetting_request'));
		}
	
		return $this->container->get('templating')->renderResponse('STXUserBundle:Resetting:checkEmail.html.'.$this->getEngine(), array(
				'email' => $email,
		));
	}
	
    
    /**
     * Reset user password
     */
    public function resetAction($token)
    {
        $user = $this->container->get('fos_user.user_manager')->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
        }

        if (!$user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            return new RedirectResponse($this->container->get('router')->generate('fos_user_resetting_request'));
        }

        $form = $this->container->get('fos_user.resetting.form');
        $formHandler = $this->container->get('fos_user.resetting.form.handler');
        $process = $formHandler->process($user);

        if ($process) {
            //$this->setFlash('fos_user_success', 'resetting.flash.success');
            //$response = new RedirectResponse($this->getRedirectionUrl($user));
        	$this->container->get('session')->getFlashBag()->add('success', 'Mot de passe regénéré!');
            $response = new RedirectResponse($this->container->get('router')->generate("stx_croissants_home"));
            $this->authenticateUser($user, $response);

            return $response;
        }

        return $this->container->get('templating')->renderResponse('STXUserBundle:Resetting:reset.html.'.$this->getEngine(), array(
            'token' => $token,
            'form' => $form->createView(),
        ));
    }
    
}
?>
