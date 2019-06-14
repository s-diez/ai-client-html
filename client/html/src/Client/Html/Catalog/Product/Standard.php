<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2019-2019
 * @package Client
 * @subpackage Html
 */


namespace Aimeos\Client\Html\Catalog\Product;

/**
 * Implementation of catalog product section HTML clients for a configurable list of products.
 *
 * @package Client
 * @subpackage Html
 */
class Standard
	extends \Aimeos\Client\Html\Catalog\Base
	implements \Aimeos\Client\Html\Common\Client\Factory\Iface
{
	private $subPartPath = 'client/html/catalog/product/standard/subparts';
	private $subPartNames = [];

	private $tags = [];
	private $expire;
	private $view;


	/**
	 * Returns the HTML code for insertion into the body.
	 *
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @return string HTML code
	 */
	public function getBody( $uid = '' )
	{
		$context = $this->getContext();

		/** client/html/catalog/product/cache
		 * Enables or disables caching only for the catalog product component
		 *
		 * Disable caching for components can be useful if you would have too much
		 * entries to cache or if the component contains non-cacheable parts that
		 * can't be replaced using the modifyBody() and modifyHeader() methods.
		 *
		 * @param boolean True to enable caching, false to disable
		 * @category Developer
		 * @category User
		 * @see client/html/catalog/detail/cache
		 * @see client/html/catalog/filter/cache
		 * @see client/html/catalog/stage/cache
		 * @see client/html/catalog/list/cache
		 */

		/** client/html/catalog/product
		 * All parameters defined for the catalog product component and its subparts
		 *
		 * Please refer to the single settings for details.
		 *
		 * @param array Associative list of name/value settings
		 * @category Developer
		 * @see client/html/catalog#product
		 */
		$confkey = 'client/html/catalog/product';

		if ( ($html = $this->getCached( 'body', $uid, [], $confkey )) === null ) {
			$view = $this->getView();
			$config = $this->getContext()->getConfig();

			/** client/html/catalog/product/standard/template-body
			 * Relative path to the HTML body template of the catalog product client.
			 *
			 * The template file contains the HTML code and processing instructions
			 * to generate the result shown in the body of the frontend. The
			 * configuration string is the path to the template file relative
			 * to the templates directory (usually in client/html/templates).
			 *
			 * You can overwrite the template file configuration in extensions and
			 * provide alternative templates. These alternative templates should be
			 * named like the default one but with the string "standard" replaced by
			 * an unique name. You may use the name of your project for this. If
			 * you've implemented an alternative client class as well, "standard"
			 * should be replaced by the name of the new class.
			 *
			 * @param string Relative path to the template creating code for the HTML page body
			 * @since 2019.06
			 * @category Developer
			 * @see client/html/catalog/product/standard/template-header
			 */
			$tplconf = 'client/html/catalog/product/standard/template-body';
			$default = 'catalog/product/body-standard';

			try {
				if ( !isset( $this->view ) ) {
					$view = $this->view = $this->getObject()->addData( $view, $this->tags, $this->expire );
				}

				$html = '';
				foreach ($this->getSubClients() as $subclient) {
					$html .= $subclient->setView( $view )->getBody( $uid );
				}
				$view->listBody = $html;

				$html = $view->render( $config->get( $tplconf, $default ) );
				$this->setCached( 'body', $uid, [], $confkey, $html, $this->tags, $this->expire );

				return $html;
			} catch (\Aimeos\Client\Html\Exception $e) {
				$error = array($context->getI18n()->dt( 'client', $e->getMessage() ));
				$view->productErrorList = $view->get( 'productErrorList', [] ) + $error;
			} catch (\Aimeos\Controller\Frontend\Exception $e) {
				$error = array($context->getI18n()->dt( 'controller/frontend', $e->getMessage() ));
				$view->productErrorList = $view->get( 'productErrorList', [] ) + $error;
			} catch (\Aimeos\MShop\Exception $e) {
				$error = array($context->getI18n()->dt( 'mshop', $e->getMessage() ));
				$view->productErrorList = $view->get( 'productErrorList', [] ) + $error;
			} catch (\Exception $e) {
				$error = array($context->getI18n()->dt( 'client', 'A non-recoverable error occured' ));
				$view->productErrorList = $view->get( 'productErrorList', [] ) + $error;
				$this->logException( $e );
			}

			$html = $view->render( $config->get( $tplconf, $default ) );
		} else {
			$html = $this->modifyBody( $html, $uid );
		}

		return $html;
	}


	/**
	 * Returns the HTML string for insertion into the header.
	 *
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @return string|null String including HTML tags for the header on error
	 */
	public function getHeader( $uid = '' )
	{
		$confkey = 'client/html/catalog/product';

		if ( ($html = $this->getCached( 'header', $uid, [], $confkey )) === null ) {
			$view = $this->getView();
			$config = $this->getContext()->getConfig();

			/** client/html/catalog/product/standard/template-header
			 * Relative path to the HTML header template of the catalog product client.
			 *
			 * The template file contains the HTML code and processing instructions
			 * to generate the HTML code that is inserted into the HTML page header
			 * of the rendered page in the frontend. The configuration string is the
			 * path to the template file relative to the templates directory (usually
			 * in client/html/templates).
			 *
			 * You can overwrite the template file configuration in extensions and
			 * provide alternative templates. These alternative templates should be
			 * named like the default one but with the string "standard" replaced by
			 * an unique name. You may use the name of your project for this. If
			 * you've implemented an alternative client class as well, "standard"
			 * should be replaced by the name of the new class.
			 *
			 * @param string Relative path to the template creating code for the HTML page head
			 * @since 2019.06
			 * @category Developer
			 * @see client/html/catalog/product/standard/template-body
			 */
			$tplconf = 'client/html/catalog/product/standard/template-header';
			$default = 'catalog/product/header-standard';

			try {
				if ( !isset( $this->view ) ) {
					$view = $this->view = $this->getObject()->addData( $view, $this->tags, $this->expire );
				}

				$html = '';
				foreach ($this->getSubClients() as $subclient) {
					$html .= $subclient->setView( $view )->getHeader( $uid );
				}
				$view->listHeader = $html;

				$html = $view->render( $config->get( $tplconf, $default ) );
				$this->setCached( 'header', $uid, [], $confkey, $html, $this->tags, $this->expire );

				return $html;
			} catch (\Exception $e) {
				$this->logException( $e );
			}
		} else {
			$html = $this->modifyHeader( $html, $uid );
		}

		return $html;
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return \Aimeos\Client\Html\Iface Sub-client object
	 */
	public function getSubClient( $type, $name = null )
	{
		/** client/html/catalog/product/decorators/excludes
		 * Excludes decorators added by the "common" option from the catalog product html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "client/html/common/decorators/default" before they are wrapped
		 * around the html client.
		 *
		 *  client/html/catalog/product/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Client\Html\Common\Decorator\*") added via
		 * "client/html/common/decorators/default" to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2019.06
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/catalog/product/decorators/global
		 * @see client/html/catalog/product/decorators/local
		 */

		/** client/html/catalog/product/decorators/global
		 * Adds a list of globally available decorators only to the catalog product html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Client\Html\Common\Decorator\*") around the html client.
		 *
		 *  client/html/catalog/product/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Client\Html\Common\Decorator\Decorator1" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2019.06
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/catalog/product/decorators/excludes
		 * @see client/html/catalog/product/decorators/local
		 */

		/** client/html/catalog/product/decorators/local
		 * Adds a list of local decorators only to the catalog product html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Client\Html\Catalog\Decorator\*") around the html client.
		 *
		 *  client/html/catalog/product/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Client\Html\Catalog\Decorator\Decorator2" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2019.06
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/catalog/product/decorators/excludes
		 * @see client/html/catalog/product/decorators/global
		 */

		return $this->createSubClient( 'catalog/product/' . $type, $name );
	}


	/**
	 * Processes the input, e.g. store given values.
	 * A view must be available and this method doesn't generate any output
	 * besides setting view variables.
	 */
	public function process()
	{
		$context = $this->getContext();
		$view = $this->getView();

		try {
			parent::process();
		} catch (\Aimeos\Client\Html\Exception $e) {
			$error = array($context->getI18n()->dt( 'client', $e->getMessage() ));
			$view->productErrorList = $view->get( 'productErrorList', [] ) + $error;
		} catch (\Aimeos\Controller\Frontend\Exception $e) {
			$error = array($context->getI18n()->dt( 'controller/frontend', $e->getMessage() ));
			$view->productErrorList = $view->get( 'productErrorList', [] ) + $error;
		} catch (\Aimeos\MShop\Exception $e) {
			$error = array($context->getI18n()->dt( 'mshop', $e->getMessage() ));
			$view->productErrorList = $view->get( 'productErrorList', [] ) + $error;
		} catch (\Exception $e) {
			$error = array($context->getI18n()->dt( 'client', 'A non-recoverable error occured' ));
			$view->productErrorList = $view->get( 'productErrorList', [] ) + $error;
			$this->logException( $e );
		}
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of HTML client names
	 */
	protected function getSubClientNames()
	{
		return $this->getContext()->getConfig()->get( $this->subPartPath, $this->subPartNames );
	}


	/**
	 * Modifies the cached body content to replace content based on sessions or cookies.
	 *
	 * @param string $content Cached content
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @return string Modified body content
	 */
	public function modifyBody( $content, $uid )
	{
		$content = parent::modifyBody( $content, $uid );

		return $this->replaceSection( $content, $this->getView()->csrf()->formfield(), 'catalog.lists.items.csrf' );
	}

	/**
	 * Sets the necessary parameter values in the view.
	 *
	 * @param \Aimeos\MW\View\Iface $view The view object which generates the HTML output
	 * @param array &$tags Result array for the list of tags that are associated to the output
	 * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	public function addData( \Aimeos\MW\View\Iface $view, array &$tags = [], &$expire = null )
	{
		$context = $this->getContext();
		$config = $context->getConfig();

		$productItems = [];

		/** client/html/catalog/domains
		 * A list of domain names whose items should be available in the catalog view templates
		 *
		 * The templates rendering catalog related data usually add the images and
		 * texts associated to each item. If you want to display additional
		 * content like the attributes, you can configure your own list of
		 * domains (attribute, media, price, product, text, etc. are domains)
		 * whose items are fetched from the storage. Please keep in mind that
		 * the more domains you add to the configuration, the more time is required
		 * for fetching the content!
		 *
		 * This configuration option can be overwritten by the "client/html/catalog/product/domains"
		 * configuration option that allows to configure the domain names of the
		 * items fetched specifically for all types of product listings.
		 *
		 * @param array List of domain names
		 * @since 2014.03
		 * @category Developer
		 * @see client/html/catalog/product/domains
		 */
		$domains = $config->get( 'client/html/catalog/domains', ['media', 'price', 'text'] );

		/** client/html/catalog/product/domains
		 * A list of domain names whose items should be available in the catalog product view template
		 *
		 * The templates rendering product lists usually add the images, prices
		 * and texts associated to each product item. If you want to display additional
		 * content like the product attributes, you can configure your own list of
		 * domains (attribute, media, price, product, text, etc. are domains)
		 * whose items are fetched from the storage. Please keep in mind that
		 * the more domains you add to the configuration, the more time is required
		 * for fetching the content!
		 *
		 * This configuration option overwrites the "client/html/catalog/domains"
		 * option that allows to configure the domain names of the items fetched
		 * for all catalog related data.
		 *
		 * @param array List of domain names
		 * @since 2019.06
		 * @category Developer
		 * @see client/html/catalog/domains
		 * @see client/html/catalog/detail/domains
		 * @see client/html/catalog/stage/domains
		 * @see client/html/catalog/lists/domains
		 */
		$domains = $config->get( 'client/html/catalog/product/domains', $domains );

		/** client/html/catalog/product/product-codes
		 * List of codes of products to load for the current list.
		 * Should be set dynamically through some integration plugin,
		 * to allow a list of products with configurable products.
		 *
		 * @param string List of codes of products to load for the current list
		 * @since 2019.06
		 * @category Developer
		 */
		$productCodes = $config->get( 'client/html/catalog/product/product-codes', [] );

		$products = \Aimeos\Controller\Frontend::create( $context, 'product' )
			->compare( '==', 'product.code', $productCodes )
			->slice( 0, count( $productCodes ) )
			->uses( $domains )
			->search();

		// Sort products by the order given in the configuration "client/html/catalog/product/product-codes".
		$productCodesOrder = array_flip( $productCodes );
		usort( $products, function ( $a, $b ) use ( $productCodesOrder ) {
			return $productCodesOrder[$a->getCode()] - $productCodesOrder[$b->getCode()];
		} );


		if ( $config->get( 'client/html/catalog/product/basket-add', false ) ) {
			foreach ($products as $product) {
				if ( $product->getType() === 'select' ) {
					$productItems += $product->getRefItems( 'product', 'default', 'default' );
				}
			}
		}

		/** client/html/catalog/product/stock/enable
		 * Enables or disables displaying product stock levels in product list views
		 *
		 * This configuration option allows shop owners to display product
		 * stock levels for each product in list views or to disable
		 * fetching product stock information.
		 *
		 * The stock information is fetched via AJAX and inserted via Javascript.
		 * This allows to cache product items by leaving out such highly
		 * dynamic content like stock levels which changes with each order.
		 *
		 * @param boolean Value of "1" to display stock levels, "0" to disable displaying them
		 * @since 2019.06
		 * @category User
		 * @category Developer
		 * @see client/html/catalog/detail/stock/enable
		 * @see client/html/catalog/stock/url/target
		 * @see client/html/catalog/stock/url/controller
		 * @see client/html/catalog/stock/url/action
		 * @see client/html/catalog/stock/url/config
		 */

		if ( !empty( $products ) && (bool)$config->get( 'client/html/catalog/product/stock/enable', true ) === true ) {
			$view->itemsStockUrl = $this->getStockUrl( $view, $products + $productItems );
		}

		// Delete cache when products are added or deleted even when in "tag-all" mode
		$this->addMetaItems( $products + $productItems, $expire, $tags, ['product'] );

		$view->listProductItems = $products;
		$view->listProductTotal = count( $products );
		$view->itemsProductItems = $productItems;

		return parent::addData( $view, $tags, $expire );
	}
}
