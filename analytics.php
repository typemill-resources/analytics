<?php

namespace Plugins\Analytics;

use \Typemill\Plugin;

class Analytics extends Plugin
{
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
			elseif($analyticSettings['tool'] == 'plausible')
			{
				if(empty($analyticSettings['plausible_url']))
				{
					$analyticSettings['plausible_url'] = 'https://plausible.io/js/script.js';
				}
				$this->addInlineJS($twig->fetch('/plausibleanalytics.twig', $analyticSettings));
			}
			elseif($analyticSettings['tool'] == 'privatus')
			{
				$privatusId = isset($analyticSettings['privatus_id']) ? trim($analyticSettings['privatus_id']) : '';

				# only print the script for a valid site id like pa_7Q2K9XH3AB
				if(preg_match('/^pa_[A-Za-z0-9]+$/', $privatusId))
				{
					$privatusModules = array();
					foreach(array('engage', 'auto', 'vitals', 'clicks', 'ask') as $module)
					{
						if(!empty($analyticSettings['privatus_' . $module]))
						{
							$privatusModules[] = $module;
						}
					}
					$this->addInlineJS($twig->fetch('/privatusanalytics.twig', array(
						'privatus_id' 		=> $privatusId,
						'privatus_modules' 	=> implode(',', $privatusModules),
					)));
				}
			}
		}
	}

	public function onCspLoaded($csp)
	{
		$data = $csp->getData();

		$domains = array();

		$analyticSettings = $this->getPluginSettings();
		
		if(!$this->adminroute && isset($analyticSettings['tool']))
		{
			if($analyticSettings['tool'] == 'matomo')
			{
				if(!empty($analyticSettings['matomo_url']))
				{
					$domains[] = trim($analyticSettings['matomo_url']);
				}
				if(!empty($analyticSettings['matomo_cloud']))
				{
					$domains[] = 'https://cdn.matomo.cloud';
				}
			}
			elseif($analyticSettings['tool'] == 'google')
			{
				$domains[] = 'https://www.googletagmanager.com/';
			}
			elseif($analyticSettings['tool'] == 'fathom')
			{
				$domains[] = 'https://cdn.usefathom.com/';
				if(!empty($analyticSettings['fathom_url']))
				{
					$domains[] = trim($analyticSettings['fathom_url']);
				}
			}
			elseif($analyticSettings['tool'] == 'plausible')
			{
				$domains[] = 'https://plausible.io';
				if(!empty($analyticSettings['plausible_url']))
				{
					$domains[] = trim($analyticSettings['plausible_url']);
				}
			}
			elseif($analyticSettings['tool'] == 'privatus')
			{
				$domains[] = 'https://privatusstats.com';
			}
		}

		foreach($domains as $domain)
		{
			if($domain != '')
			{
				$data[] = $domain;
			}
		}

		$csp->setData($data);
	}
}