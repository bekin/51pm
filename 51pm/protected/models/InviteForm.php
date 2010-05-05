<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class InviteForm extends CFormModel
{
	public $emails;
	public $subject;
	public $message;
	public $privilege;
	
	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('emails,message', 'required'),
			// email has to be a valid email address
			//array('emails','email'),
		);
	}
}