<?php

namespace Plugins\Analytics;

use \Typemill\Plugin;

class Analytics extends Plugin
{
	protected $settings;
	
    public static function getSubscribedEvents()
    {
		return array(
			'onTwigLoaded' 			=> 'onTwigLoaded',
			'onCspLoaded'			=> 'onCspLoaded',
		);
    }

	public function onTwigLoaded()
	{
		if($this->adminroute)
		{
			return;
		}

		# get Twig Instance and add the cookieconsent template-folder to the path
		$twig 	= $this->getTwig();					
		$loader = $twig->getLoader();
		$loader->addPath(__DIR__ . '/templates');

		$analyticSettings = $this->getPluginSettings();
		
		if(isset($analyticSettings['tool']))
		{
			# fetch the template, render it with twig and add javascript with settings
			if($analyticSettings['tool'] == 'matomo')
			{
				$this->addInlineJS($twig->fetch('/matomoanalytics.twig', $analyticSettings));
			}
			elseif($analyticSettings['tool'] == 'google')
			{
				$this->addJS('https://www.googletagmanager.com/gtag/js?id=' . $analyticSettings['google_id']);
				$this->addInlineJS($twig->fetch('/googleanalytics.twig', $analyticSettings));
			}
			elseif($analyticSettings['tool'] == 'fathom')
			{
				if(empty($analyticSettings['fathom_url']))
				{
					$analyticSettings['fathom_url'] = 'https://cdn.usefathom.com/script.js';
				}
				$this->addInlineJS($twig->fetch('/fathomanalytics.twig', $analyticSettings));
			}
		}
	}

	public function onCspLoaded($csp)
	{
		$data = $csp->getData();

		$domain = '';

		$analyticSettings = $this->getPluginSettings();
		
		if(!$this->adminroute && isset($analyticSettings['tool']))
		{
			if($analyticSettings['tool'] == 'matomo')
			{
				if(!empty($analyticSettings['matomo_url']))
				{
					$domain = trim($analyticSettings['matomo_url']);
				}
			}
			elseif($analyticSettings['tool'] == 'google')
			{
				$domain = 'https://www.googletagmanager.com/';
			}
			elseif($analyticSettings['tool'] == 'fathom')
			{
				$domain = 'https://cdn.usefathom.com/';
				if(!empty($analyticSettings['fathom_url']))
				{
					$domain = trim($analyticSettings['fathom_url']);
				}
			}
		}

		$data[] = $domain;

		$csp->setData($data);
	}
}