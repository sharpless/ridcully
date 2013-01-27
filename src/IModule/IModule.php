<?php
/**
 * Interface for class(es) that require installation/updating/uninstallation
 *
 * The method should implement the required actions for installing, updating
 * or uninstalling the module.
 *
 * @package RidcullyCore
 */

interface IModule {
  public function Manage($action=null);
}