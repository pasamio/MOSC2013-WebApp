<?php
/**
 * @package     Gris-Gris.Skeleton
 * @subpackage  Application
 *
 * @copyright   Copyright (C) 2013 Respective authors. All rights reserved.
 * @license     Licensed under the MIT License; see LICENSE.md
 */

namespace Grisgris\Application;

/**
 * 303 See Other web Response class.
 *
 * @package     Gris-Gris.Skeleton
 * @subpackage  Application
 * @since       13.1
 */
class WebResponseSeeOther extends WebResponseRedirect
{
	/**
	 * @var    string  Response HTTP status code.
	 * @since  13.1
	 */
	protected $status = '303 See Other';
}
