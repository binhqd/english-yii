<?php

/**
 * JLu_SiteTest
 */
class JLu_SiteTest extends JLu_WebTestCase
{
	/**
         * testIndex
         */  
	public function testIndex()
	{
		$this->open('');
		$this->assertTextPresent('Welcome');
	}

	/**
         * testContact
         */  
	public function testContact()
	{
		$this->open('?r=site/contact');
		$this->assertTextPresent('Contact Us');
		$this->assertElementPresent('name=ContactForm[name]');

		$this->type('name=ContactForm[name]','tester');
		$this->type('name=ContactForm[email]','tester@example.com');
		$this->type('name=ContactForm[subject]','test subject');
		$this->clickAndWait("//input[@value='Submit']");
		$this->assertTextPresent('Body cannot be blank.');
	}

	/**
         * testLoginLogout
         */  
	public function testLoginLogout()
	{
		$this->open('');
		// ensure the user is logged out
		if($this->isTextPresent('Logout'))
			$this->clickAndWait('link=Logout');

		// test login process, including validation
		$this->clickAndWait('link=Login');
		$this->assertElementPresent('name=JLa_LoginForm[username]');
		$this->type('name=JLa_LoginForm[username]','demo');
		$this->clickAndWait("//input[@value='Login']");
		$this->assertTextPresent('Password cannot be blank.');
		$this->type('name=JLa_LoginForm[password]','demo');
		$this->clickAndWait("//input[@value='Login']");
		$this->assertTextNotPresent('Password cannot be blank.');
		$this->assertTextPresent('Logout');

		// test logout process
		$this->assertTextNotPresent('Login');
		$this->clickAndWait('link=Logout (demo)');
		$this->assertTextPresent('Login');
	}
}
