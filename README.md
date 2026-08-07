# Analytics Plugin

Add Matomo, Fathom, Plausible, or Google Analytics to your website with this simple plugin.

![Screenshot of Matomo Analytics](media/live/matomo.png){.center loading="lazy" width="820" height="369"}

The plugin integrates the tracking scripts at the bottom of the page. Right now you can choose between the free and open source tracking software [Matomo](https://matomo.org/), [Fathom Analytics](https://usefathom.com/), [Plausible Analytics](https://plausible.io/), and [Google Analytics](https://analytics.google.com).

## Matomo Analytics

If you care about data privacy, open source, and self hosted software, then Matomo is a recommended solution. You can download Matomo and install it on your own server. Matomo combines a modern design with powerful features and a very intuitive admin interface. The dashboard shows all important key metrics for a standard website and can be customized with drag & drop. Administrators can also use advanced features like segmentation or goal tracking without the need of coding or scripting.

The plugin requires two information:

* The url to your Matomo installation.
* The ID of your site in Matomo.

You can also disable cookies for Matomo.

If your Matomo URL is a Matomo Cloud subdomain (e.g. `mysite.matomo.cloud`), enable the **Matomo Cloud** option. The plugin will then load the tracker script from `cdn.matomo.cloud` while keeping the tracker endpoint on your subdomain.

It is not possible to integrate the iFrame with the opt-out-feature into a page right now. But you can simply link to the opt-out-feature of your matomo-installation. The link should look similar to this one: https://yourmatomoinstallation.com/index.php?module=CoreAdminHome&action=optOut&language=en

## Fathom Analytics

If you do not want to self-host your own analytics software but still need a good privacy solution, then [Fathom Analytics](https://usefathom.com/) might be a good option. Simply add the fathom-url and your fathom-id. The fathom integration has been added by [Andreas Zettl](https://azettl.net).

## Plausible Analytics

[Plausible Analytics](https://plausible.io/) is a lightweight, open source, privacy-friendly analytics tool. It does not use cookies and does not require a cookie banner under GDPR. Add the domain you registered in Plausible and, optionally, your custom or self-hosted script URL. If you leave the script URL empty, the plugin uses the default `https://plausible.io/js/script.js`.

## Google Analytics

Matomo is a perfect option if you care about privacy and usability. However, many webmasters use Google Analytics, just because they do not want to host another software. Just add your Google Site ID into the plugin configurations and you are ready to go. 

## GitHub

Found a bug? Or do you want to contribute some improvements? You can find the repository on https://github.com/typemill-resources/analytics . Create a pull request or open a new issue if you found a bug.

## Changelog

### Version 2.2.0

* Added Plausible Analytics integration.
* Added Matomo Cloud support with correct `cdn.matomo.cloud` script URL.
* Modernized the Matomo tracking snippet to the current `window._paq` form.
* Cleaned up the CSP whitelist logic so empty domains are no longer added.
* Verified support for php 8.5

### Version 2.0.1

Make compatible with version 2 of Typemill.

### Version 1.2.0

* Added fathom analytics ([Andreas Zettl](https://azettl.net/)).
* Added matomo-option for cookie-free tracking.
* Reordered plugin-settings with fieldsets.

