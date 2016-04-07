# Changelog

All Notable changes to `RoadworkRah/Ecosystem` will be documented in this file

## NEXT

### 0.1.0
Full-Testing Coverage

## Current and Previous

0.0.2
-----
* update namespace and packagist package to reflect current ownership

0.0.1
-----
* fix external assets being disregarded

0.0.0 - Initial Beta version
-----
* use [Ecosystem][ecosystem-link] to
  * build the functional relationship between AssetCollectionContract and HtmlOutputContract
  * provide front-facing methods to output assets
  * add cache-busting string to end of asset href/src
  * dynamically add items to collection in a certain position (before another asset)
  * turn off cache busting string in certain envs in [config][config-link]
* use [stubs\ecosystem.stub][stub-link] by generator to create new Ecosystem instances
* use [Providers\EcosystemServiceProvider][service-provider-link] to
  * register middleware
  * allow config overwriting
  * bind instances and implementations
  * register generator command
  * publish package assets
* use [Middleware\CheckEcosystemAction][middleware-link] as entry point for using Ecosystem instance
* use [Entities\AbstractEcosystem][entity-link] to bolster Ecosystem classes
* use [Builders\HtmlBuilder][builderlink] to output html elements
* use [Commands\GenerateNewEcosystemCommand][command-link] to generate new Ecosystem

[ecosystem-link]: src/Ecosystem.php
[stub-link]: src/stubs/ecosystem.stub
[service-provider-link]: src/Providers/EcosystemServiceProvider.php
[middleware-link]: src/Middleware/CheckEcosystemAction.php
[entity-link]: src/Entities/AbstractEcosystem.php
[builder-link]: src/Builders/HtmlBuilder.php
[command-link]: src/Commands/GenerateNewEcosystemCommand.php
[config-link]: src/config/ecosystem.php
