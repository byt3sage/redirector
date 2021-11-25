# Redirector plugin for Craft CMS 3.x

A simple way to add 301/302 redirects within CraftCMS. This is the first CraftCMS plugin written by myself so please do let me know any bugs you may find.

![Screenshot](https://i.ibb.co/hsd6Pqj/image.png)

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require jaetooledev/redirector

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Redirector.

## Redirector Overview

Are you wanting to implement some 301 and/or 302 redirects within your CraftCMS projects? If so, Redirector provides a simple utility to make that happen. You can redirect to internal and external links and (coming soon) interact with Redirector via GraphQL.

## Redirector Roadmap

Some things to do, and ideas for potential features:

* GraphQL support
* CSV uploading
* Using datatables to display redirects
* Analytics support
* Event emitting
* More translations
* Cleaning codebase
* Adding tests

Brought to you by [Jae Toole](https://github.com/jaetooledev)
