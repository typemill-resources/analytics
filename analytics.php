<?php

namespace Plugins\Analytics;

use \Typemill\Plugin;

class Analytics extends Plugin
{
	protected $settings;
	
    public static function getSubscribedEvents()
    {
		return array(
			'onSettingsLoaded'		=> 'onSettingsLoaded',
			'onTwigLoaded' 			=> 'onTwigLoaded'
		);
    }
	
	public function onSettingsLoaded($settings)
	{
		$this->settings = $settings->getData();
	}
	
	public function onTwigLoaded()
	{
		/* get Twig Instance and add the cookieconsent template-folder to the path */
		$twig 	= $this->getTwig();					
		$loader = $twig->getLoader();
		$loader->addPath(__DIR__ . '/templates');

		$analyticSettings = $this->settings['settings']['plugins']['analytics'];

		if(isset($analyticSettings['tool']))
		{
			/* fetch the template, render it with twig and add javascript with settings */
			if($analyticSettings['tool'] == 'matomo')
			{
				$this->addInlineJS($twig->fetch('/matomoanalytics.twig', $this->settings));
			}
			elseif($analyticSettings['tool'] == 'google')
			{
				$this->addJS('https://www.googletagmanager.com/gtag/js?id=' . $analyticSettings['google_id']);
				$this->addInlineJS($twig->fetch('/googleanalytics.twig', $analyticSettings));
			}elseif($analyticSettings['tool'] == 'fathom'){
				if(empty($analyticSettings['fathom_url'])){
					$analyticSettings['fathom_url'] = 'https://cdn.usefathom.com/script.js';
				}
				$this->addInlineJS($twig->fetch('/fathomanalytics.twig', $analyticSettings));
			}
		}
	}
}