<?php

namespace STX\CroissantsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class FridaySubscriptionType extends AbstractType {
	
	/* (non-PHPdoc)
	 * @see \Symfony\Component\Form\AbstractType::buildForm()
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		
		$builder
			/*->add('date',DateType::class, array('input' => 'datetime',
										'widget' => 'choice',
										'label' => 'Vendredi'))*/
			->add('date','date', array('widget' => 'single_text',
    									'format' => 'dd.MM.yyyy',
    									'attr' => array ('class' => 'form-control input-inline datepicker',
        												'data-provide' => 'datepicker',
        												'data-date-format' => 'dd.mm.yyyy')
					
    									))
			->add('user','entity', array('class' => 'STXUserBundle:User',
										'choice_label' => 'username',
										'label' => 'Ameneur principal',
										'empty_value' => 'Sélectionner un utilisateur',
										'required' => false))
			->add('backup_user','entity', array('class' => 'STXUserBundle:User',
										'choice_label' => 'username',
										'label' => 'Backup',
										'empty_value' => 'Sélectionner un utilisateur',
										'required' => false))
			->add('confirmation_user','entity', array('class' => 'STXUserBundle:User',
										'choice_label' => 'username',
										'label' => 'Confirmé',
										'empty_value' => 'Sélectionner un utilisateur',
										'required' => false))
			->add('remark','text', array('label' => 'Remarque',
										'required' => false))
			;

	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'STX\CroissantsBundle\Entity\Friday_Subscriptions',
				'cascade_validation' => TRUE
		));
	}
	
	public function getName()
	{
		return 'stx_croissantsbundle_fridaysubscriptiontype';
	}
}