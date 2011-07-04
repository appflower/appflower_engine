<?php
/**
 * This filter does exactly nothing :)
 * 
 * While developing I often need all the exceptions to pop up to the top
 * So changing 'ExceptionCatchingFilter' to 'ExceptionCatchingDisabledFilter' in 
 * your project filters.yml is IMO the easiest way to accomplish that
 * 
 * @author Lukasz Wojciechowski <luwo@appflower.com>
 */
class ExceptionCatchingDisabledFilter extends sfFilter
{
	public function execute ($filterChain)
	{
        $filterChain->execute();
	}
}