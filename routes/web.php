<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Auth::routes();
Route::post('reset_password_without_token', 'AccountsController@validatePasswordRequest')->name('reset_password_without_token');
Route::post('reset_password_with_token', 'AccountsController@resetPassword')->name('reset_password_with_token');

Route::get('/home', 'HomeController@index')->name('home');

/************** start super admin route**************/
Route::group(['as' => 'superadmin.', 'prefix' => 'superadmin', 'namespace' => 'SuperAdmin', 'middleware' => ['auth:superadmin', 'superadmin']], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');

    Route::get('dashboard', 'SuperAdminController@index')->name('dashboard');
    Route::post('vendor_wise_commission', 'SuperAdminController@vendorWiseCommission')->name('vendor_wise_commission.ajax');
    //start vendor controller
    Route::resource('admin_panel', 'AdminPanelController');
    //end vendor controller
    //start vendor controller
    Route::resource('vendor', 'VendorController');
    Route::post('vendor-datatable', 'VendorController@getListByAjax')->name('vendor.ajax');
    Route::get('vendor/status/active/unactive', 'VendorController@activeUnactive')->name('vendor.statusActiveUnactive');
    Route::get('vendor-dropdown', 'VendorController@vendorDropdownList')->name('vendor.dropdown.list');

    //end vendor controller
    //start country controller
    Route::resource('country', 'CountryController');
    Route::post('country-datatable', 'CountryController@getListByAjax')->name('country.ajax');

    //end country controller
    //start state controller
    Route::resource('state', 'StateController');
    Route::post('state-datatable', 'StateController@getListByAjax')->name('state.ajax');

    //end state controller
    //start city controller
    Route::resource('city', 'CityController');
    Route::post('city-datatable', 'CityController@getListByAjax')->name('city.ajax');

    //end city controller

    //start User role controller
    Route::resource('user_role', 'UserRoleController');
    Route::post('user_role-datatable', 'UserRoleController@getListByAjax')->name('user_role.ajax');

    //end User role controller


    //start Parent Product controller
    Route::resource('parent_product', 'ParentProductController');
    Route::post('parent_product-datatable', 'ParentProductController@getListByAjax')->name('parent_product.ajax');
    Route::post('parent_product/product_specification', 'ParentProductController@getProductSpecificationByAjax')->name('parent_product.product_specification.ajax');
    Route::post('parent_product/status/active/unactive', 'ParentProductController@activeUnactive')->name('parent_product.statusActiveUnactive');

    //end Parent Product controller


    // Route::view('user/register', 'auth.register')->name('user.register');
    Route::get('user/vendor/create', 'UserController@vendorUserCreate')->name('user.vendor.create');
    Route::get('user/super/create', 'UserController@superUserCreate')->name('user.super.create');
    Route::get('user/vendor/status/active/unactive', 'UserController@activeUnactiveVendor')->name('user.vendor.statusActiveUnactive');
    Route::get('user/status/active/unactive', 'UserController@activeUnactive')->name('user.statusActiveUnactive');

    Route::post('user/vendor/store', 'UserController@addVendorUserStore')->name('user.vendor.store');
    Route::post('user/super/store', 'UserController@addSuperUserStore')->name('user.super.store');
    // Route::view('user/super/register', 'auth.register')->name('user.super.register');
    // Route::view('user/register', 'auth.register')->name('user.register');
    Route::get('user/dynamic-value', 'UserController@userDynamicValue')->name('user.dynamic.value');
    Route::get('user', 'UserController@index')->name('user');
    Route::get('user/vendor', 'UserController@vendorUser')->name('user.vendor');
    Route::post('user/vendor-datatable', 'UserController@getVendorUserListByAjax')->name('user.vendor.ajax');

    Route::get('user/super', 'UserController@superUser')->name('user.super');
    Route::post('user/super-datatable', 'UserController@getSuperUserListByAjax')->name('user.super.ajax');

    // Product Management
    Route::get('product/child', 'ProductController@getChildProducts')->name('product.child');
    Route::post('product/child/filter', 'ProductController@getChildProductsByFilter')->name('product.child.filter');
    Route::post('product/child/details', 'ProductController@getChildProductDetails')->name('product.child.details');

    Route::post('product/parent/for/map', 'ProductController@getParentProductListForMaping')->name('product.parent.for.map');
    Route::post('product/parent/filter', 'ProductController@getParentProductsByFilter')->name('product.parent.filter');
    // Route::post('product/parent/map', 'ProductController@parentProductMap')->name('product.parent.map');
    Route::get('product/parent/approve', 'ProductController@parentProductApprove')->name('product.parent.approve');
    Route::get('product/categories/{parent}/{vendor?}', 'ProductController@getCategoriesByParent')->name('product.categories.by.parent');

    Route::get('product/approval', 'ProductController@productApproval')->name('product.approval');
    Route::get('product/{product}', 'ProductController@show')->name('product.show');
    Route::post('product/list/map', 'ProductController@getProductListForMap')->name('product.list.map');
    Route::get('product/list/map', 'ProductController@getProductListForMap')->name('product.list.map');
    Route::post('product/toggle/approval', 'ProductController@toggleProductApproval')->name('product.toggle.approval');

    Route::get('product/parent/featured', 'ProductController@getFeaturedProducts')->name('product.featured');
    Route::post('product_featured-datatable', 'ProductController@getFeaturedProductsByAjax')->name('product.featured.ajax');
    Route::post('product/parent/featured', 'ProductController@getFeaturedProductsMap')->name('product.featured.map');
    Route::post('product/parent/featured/filter', 'ProductController@getFeaturedProductsByFilter')->name('product.parent.filter');

    /*****start=>> ProductCategoryController controller*****/

//     Route::resource('product_category', 'ProductCategoryController');
//     Route::post('product_category-datatable', 'ProductCategoryController@getListByAjax')->name('product_category.ajax');
//     Route::get('product_category/status/active/unactive', 'ProductCategoryController@activeUnactive')->name('product_category.statusActiveUnactive');

//     Route::get('product/category/approval', 'ProductCategoryController@productCategoryApproval')->name('product.category.approval');
//     Route::post('product_child_category-datatable', 'ProductCategoryController@getChildListByAjax')->name('product_category.child.ajax');
//     Route::post('product/category/approval', 'ProductCategoryController@productCategoryApprovalStatus')->name('product.category.approval.status');
//     Route::post('product/category/disapproval', 'ProductCategoryController@productCategoryDisApprovalStatus')->name('product.category.disapproval.status');
//     Route::post('product/category/parent/approve/new', 'ProductCategoryController@approveAsNewParent')->name('product.category.parent.approve.new');
// //    Route::post('product/category/parent/for/map', 'ProductCategoryController@getParentProductCategoryListForMaping')->name('product.category.parent.for.map');
//     Route::post('product_parent_category-datatable', 'ProductCategoryController@getParentListByAjax')->name('product_category.parent.ajax');
//     Route::post('product/category/parent/map', 'ProductCategoryController@parentCategoryMap')->name('product.category.parent.map');
//     Route::post('product/category/parent/unmap', 'ProductCategoryController@parentCategoryUnMap')->name('product.category.parent.unmap');
//     Route::post('product_category/parent/store', 'ProductCategoryController@modalParentCategoryStore')->name('product_category.modal.store');

    Route::resource('product_category', 'ProductCategoryController');
    Route::post('product_category-datatable', 'ProductCategoryController@getListByAjax')->name('product_category.ajax');
    Route::post('product_category-liveSearch', 'ProductCategoryController@getliveSearchByAjax')->name('product_category.liveSearch.ajax');
    Route::get('product_category/status/active/unactive', 'ProductCategoryController@activeUnactive')->name('product_category.statusActiveUnactive');
    Route::get('product_category/is_homepage/active/unactive', 'ProductCategoryController@isHomepageActiveUnactive')->name('product_category.isHomepageActiveUnactive');

    /*****end=>> ProductCategoryController controller*****/

    /*****start=>> ProductBrandController controller*****/
    Route::resource('product_brand', 'ProductBrandController');
    Route::post('product_brand-datatable', 'ProductBrandController@getListByAjax')->name('product_brand.ajax');
    Route::get('product_brand/status/active/unactive', 'ProductBrandController@activeUnactive')->name('product_brand.statusActiveUnactive');


    Route::get('product/product_brand/approval', 'ProductBrandController@productBrandApproval')->name('product_brand.approval');
    Route::post('product_brand_child-datatable', 'ProductBrandController@getChildListByAjax')->name('product_brand.child.ajax');
    Route::post('product_brand/approval', 'ProductBrandController@productBrandApprovalStatus')->name('product.brand.approval.status');
    Route::post('product_brand/disapproval', 'ProductBrandController@productBrandDisApprovalStatus')->name('product.brand.disapproval.status');
    Route::post('product_brand/parent/approve/new', 'ProductBrandController@approveAsNewParent')->name('product.brand.parent.approve.new');
    Route::post('product_parent_brand-datatable', 'ProductBrandController@getParentListByAjax')->name('product.brand.parent.ajax');
    Route::post('product_brand/parent/map', 'ProductBrandController@parentBrandMap')->name('product.brand.parent.map');
    Route::post('product_brand/parent/unmap', 'ProductBrandController@parentBrandUnMap')->name('product.brand.parent.unmap');
    Route::post('product_brand/parent/store', 'ProductBrandController@modalParentBrandStore')->name('product.brand.modal.store');

    /*****end=>> ProductBrandController controller*****/


    /*****start=>> manufacturer controller*****/
    Route::resource('manufacturer', 'ManufacturerController');
    Route::post('manufacturer-datatable', 'ManufacturerController@getListByAjax')->name('manufacturer.ajax');
    Route::get('manufacturer/status/active/unactive', 'ManufacturerController@activeUnactive')->name('manufacturer.statusActiveUnactive');



    Route::get('product/manufacturer/approval', 'ManufacturerController@manufacturerApproval')->name('manufacturer.approval');
    Route::post('manufacturer_child-datatable', 'ManufacturerController@getChildListByAjax')->name('manufacturer.child.ajax');
    Route::post('manufacturer/approval', 'ManufacturerController@manufacturerApprovalStatus')->name('manufacturer.approval.status');
    Route::post('manufacturer/disapproval', 'ManufacturerController@manufacturerDisApprovalStatus')->name('manufacturer.disapproval.status');
    Route::post('manufacturer/parent/approve/new', 'ManufacturerController@approveAsNewParent')->name('manufacturer.parent.approve.new');
    Route::post('manufacturer_parent-datatable', 'ManufacturerController@getParentListByAjax')->name('manufacturer.parent.ajax');
    Route::post('manufacturer/parent/map', 'ManufacturerController@parentManufacturerMap')->name('manufacturer.parent.map');
    Route::post('manufacturer/parent/unmap', 'ManufacturerController@parentManufacturerUnMap')->name('manufacturer.parent.unmap');
    Route::post('manufacturer/parent/store', 'ManufacturerController@modalParentManufacturerStore')->name('manufacturer.modal.store');


    /*****end=>> manufacturer controller*****/

    //parent product map management
    Route::post('product/approval', 'ProductController@productApprovalStatus')->name('product.approval.status');
    Route::post('product_parent-datatable', 'ProductController@getParentListByAjax')->name('product.parent.ajax');
    Route::post('product/parent/map', 'ProductController@parentProductMap')->name('product.parent.map');
    Route::post('product/disapproval', 'ProductController@productDisApprovalStatus')->name('product.disapproval.status');
    Route::post('product/parent/unmap', 'ProductController@parentProductUnMap')->name('product.parent.unmap');
    Route::post('product/parent/approve/new', 'ProductController@approveAsNewParent')->name('product.parent.approve.new');

    //
    /*****start=>> Banner details controller*****/
    Route::resource('/banner', 'BannerController');
    Route::post('banner-datatable', 'BannerController@getListByAjax')->name('banner.ajax');
    Route::get('banner/status/active/unactive', 'BannerController@activeUnactive')->name('banner.statusActiveUnactive');

    /*****end=>> Banner details controller*****/

    /*****start=>> Shipping Method controller*****/
    Route::resource('/shipping_methods', 'ShippingMethodController');
    Route::post('shipping_methods-datatable', 'ShippingMethodController@getListByAjax')->name('shipping_methods.ajax');
    Route::get('shipping_methods/status/active/unactive', 'ShippingMethodController@activeUnactive')->name('shipping_methods.statusActiveUnactive');
    /*****end=>> Shipping Method controller*****/

    /*****start=>> Banner details controller*****/
    Route::resource('/payment_methods', 'PaymentMethodController');
    Route::post('payment_methods-datatable', 'PaymentMethodController@getListByAjax')->name('payment_methods.ajax');
    Route::get('payment_methods/status/active/unactive', 'PaymentMethodController@activeUnactive')->name('payment_methods.statusActiveUnactive');
    /*****end=>> Banner details controller*****/

    /**************** start==> Promotional Advertisement controller *****************/
    Route::resource('promotional_advertisement', 'PromotionalAdvertisementController');
    Route::get('p_m_a_d/status/update', 'PromotionalAdvertisementController@activeUnactive')->name('promotional_advertisement.statusActiveUnactive');

    /**************** end==> Promotional Advertisement controller *****************/

    /**************** start==> ServiceContracts controller *****************/
    Route::resource('service_contracts', 'ServiceContractsController');
    /**************** end==> ServiceContracts controller *****************/
    /**************** start==> ServiceContracts controller *****************/
    Route::resource('contact_us', 'ContactUsController');
    Route::post('contact_us-social', 'ContactUsController@getSocialByAjax')->name('contact_us.social.ajax');
    Route::resource('external_pages', 'ExternalPagesController');

    /**************** end==> ServiceContracts controller *****************/

    /**************** start==> AdminConfigController controller *****************/
    Route::resource('invoice_settings', 'AdminConfigController');
    /**************** end==> AdminConfigController controller *****************/

    /**************** start==> Report  controller *****************/
    Route::group(['as' => 'report.', 'namespace' => 'Report', 'prefix' => 'report'], function () {
    //start==> Pending Vendor report controller
        Route::get('pending-vendors', 'PendingVendorReportController@index')->name('pending_vendors');
        Route::post('pending-vendors', 'PendingVendorReportController@getReportByAjax')->name('pending_vendors.ajax');
    });
    /**************** end==> Report controller *****************/

    /**************** start==> Email  controller *****************/
    Route::get('email/registration', 'EmailController@registration')->name('email_registration');
    Route::post('email/registration', 'EmailController@registration_save')->name('email_registration_save');

    /**************** end==> Email controller *****************/

    /**************** start==> Artisan  controller *****************/
    Route::get('artisan', 'ArtisanController@index')->name('artisan');
    /**************** end==> Artisan controller *****************/

    Route::get('/sale-commission', 'SaleCommissionController@index')->name('commission.ajax');
    Route::post('/sale-commission', 'SaleCommissionController@index');
    Route::get('/sale-commission/global', 'SaleCommissionController@globalSaleCommission')->name('commission.global.ajax');
    Route::post('/sale-commission/global', 'SaleCommissionController@globalSaleCommission');
    Route::post('/sale-commission/vendor/render', 'SaleCommissionController@renderVendorSaleCommission')->name('commission.vendor.render');
    Route::get('sale-commission/status/toggle', 'SaleCommissionController@activeUnactive')->name('commission.status.toggle');
    Route::post('/sale-commission/vendor/store', 'SaleCommissionController@vendorSaleCommissionStore')->name('commission.vendor.store');
    Route::delete('/sale-commission/{saleCommission}', 'SaleCommissionController@destroy')->name('sale-commission.destroy');
    Route::get('sale-commission/received', 'SaleCommissionController@receivedCommissions')->name('commission.received');
    Route::post('sale-commission/received', 'SaleCommissionController@receivedCommissions');
    Route::get('sale-commission/receive_payment/{vct}', 'SaleCommissionController@receivePayment');

    Route::get('sale_invoices', 'Report\SaleReportController@salesInvoices')->name('sale.invoices');

    Route::get('sale-commission-detail', 'Report\SaleReportController@saleComissionDetail')->name('sale_commission.detail');
    Route::post('sale-commission-detail', 'Report\SaleReportController@saleComissionDetail')->name('sale_commission.detail.dt');
});
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['auth:vendor', 'vendor']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
/************** end super admin route**************/

/************** start common  route**************/
// user controller
Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('user/profile/{id}', 'UserController@userProfile')->name('user.profile');
    Route::post('user/profile/update/{id}', 'UserController@userProfileUpdate')->name('user.profile.update');
    Route::get('user/profile/{id}', 'UserController@userProfile')->name('user.profile');
    Route::post('user/profile/update/{id}', 'UserController@userProfileUpdate')->name('user.profile.update');
});
/************** end common  route**************/

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:vendor', 'vendor']], function () {

    // dashboard controller
    Route::get('/', 'DashboardController@index')->name('dashboard');

    /*********************** start only admin role section*************************/
    Route::group(['middleware' => ['auth:vendor']], function () {
        //start country controller
        Route::resource('country', 'CountryController');
        Route::post('country-datatable', 'CountryController@getListByAjax')->name('country.ajax');

        //end country controller
        //start state controller
        Route::resource('state', 'StateController');
        Route::post('state-datatable', 'StateController@getListByAjax')->name('state.ajax');

        //end state controller
        //start city controller
        Route::resource('city', 'CityController');
        Route::post('city-datatable', 'CityController@getListByAjax')->name('city.ajax');

        //end city controller
        /**************** start==> Promotional Advertisement controller *****************/
        Route::resource('promotional_advertisement', 'PromotionalAdvertisementController');
        /**************** end==> Promotional Advertisement controller *****************/

        /**************** start==> ServiceContracts controller *****************/
        Route::resource('service_contracts', 'ServiceContractsController');
        /**************** end==> ServiceContracts controller *****************/
        /**************** start==> ServiceContracts controller *****************/
        Route::resource('contact_us', 'ContactUsController');
        Route::post('contact_us-social', 'ContactUsController@getSocialByAjax')->name('contact_us.social.ajax');

        /**************** end==> ServiceContracts controller *****************/
        /*****start=>> Banner details controller*****/
        Route::resource('/banner', 'BannerController');
        Route::post('banner-datatable', 'BannerController@getListByAjax')->name('banner.ajax');
        Route::get('banner/status/active/unactive', 'BannerController@activeUnactive')->name('banner.statusActiveUnactive');

        /*****end=>> Banner details controller*****/

        //start==> create a mp user for your vendor
        Route::get('mp_users', 'UserController@mpUserList')->name('mp_user');
        Route::post('mp_users-datatable', 'UserController@getMpListByAjax')->name('mp_user.ajax');
        Route::get('mp_user', 'UserController@addMpUserForm')->name('add.mp_user');
        Route::post('mp_user/store', 'UserController@addMpUserStore')->name('add.mp_user.store');
        Route::get('mp_user/{mp_user}/edit', 'UserController@addMpUserEdit')->name('add.mp_user.edit');
        Route::post('mp_user/{mp_user}/update', 'UserController@addMpUserupdate')->name('add.mp_user.update');
        Route::delete('mp_user/{mp_user}/delete', 'UserController@addMpUserDelete')->name('add.mp_user.delete');
        Route::get('mp_user/status/active/unactive', 'UserController@mpActiveUnactive')->name('mp_user.statusActiveUnactive');

        //end==> create a mp user for your vendor

        //start==> create a user for your vendor
        Route::get('users', 'UserController@userList')->name('user');
        Route::post('users-datatable', 'UserController@getListByAjax')->name('user.ajax');
        Route::get('user', 'UserController@addUserForm')->name('add.user');
        Route::post('user/store', 'UserController@addUserStore')->name('add.user.store');
        Route::get('user/{user}/edit', 'UserController@addUserEdit')->name('add.user.edit');
        Route::post('user/{user}/update', 'UserController@addUserupdate')->name('add.user.update');
        Route::delete('user/{user}/delete', 'UserController@addUserDelete')->name('add.user.delete');
        Route::post('add/user/dynamic', 'UserController@addUserDynamic')->name('add.user.dynamic.value');
        Route::get('user/status/active/unactive', 'UserController@activeUnactive')->name('user.statusActiveUnactive');

        //end==> create a user for your vendor

        /*****start=>> warehouse controller*****/
        Route::resource('warehouse', 'WarehouseController');
        Route::post('warehouse-datatable', 'WarehouseController@getListByAjax')->name('warehouse.ajax');
        Route::get('warehouse_type/{ware_house_id}', 'UserController@getWarehouseType');
        Route::get('warehouse/status/active/unactive', 'WarehouseController@activeUnactive')->name('warehouse.statusActiveUnactive');
        Route::get('warehouse-list', 'WarehouseController@warehouseList')->name('warehouse.list');
        /*****end=>> warehouse controller*****/

        /*****start=>> discount controller*****/
        Route::resource('discounts', 'DiscountController');
        Route::get('discount/brand', 'DiscountController@brandDiscount')->name('discount.brand');
        // Route::get('discount/{edit}/brand', 'DiscountController@editBrandDiscount')->name('discount.brand.edit');
        Route::get('discount/product', 'DiscountController@productDiscount')->name('discount.product');
        Route::get('discount/product/selection', 'DiscountController@productSelection')->name('discount.product.selection');
        Route::get('discount/vendor', 'DiscountController@vendorDiscount')->name('discount.vendor');
        Route::get('discount/category', 'DiscountController@productCategoryDiscount')->name('discount.category');
        Route::post('discount/product/selection/list', 'DiscountController@getProductListForSelection')->name('discount.product.selction.list');
        Route::get('discounts/status/active/unactive', 'DiscountController@activeUnactive')->name('discounts.statusActiveUnactive');

        /*****end=>> discount controller*****/

        /*****start=>> warehouse details controller*****/
        Route::resource('/warehouse_detail', 'WarehouseDetailController');
        Route::post('warehouse_detail-datatable', 'WarehouseDetailController@getListByAjax')->name('warehouse_detail.ajax');

        Route::get('warehouse_detail/warehouse_type/{ware_house_id}', 'WarehouseDetailController@getWarehouseType');
        Route::get('warehouse_detail/status/active/unactive', 'WarehouseDetailController@activeUnactive')->name('warehouse_detail.statusActiveUnactive');
        /*****end=>> warehouse details controller*****/



        /*****start=>> manufacturer controller*****/
        Route::resource('manufacturer', 'ManufacturerController');
        Route::post('manufacturer-datatable', 'ManufacturerController@getListByAjax')->name('manufacturer.ajax');
        Route::post('manufacturer-liveSearch', 'ManufacturerController@getliveSearchByAjax')->name('manufacturer.liveSearch.ajax');
        Route::get('manufacturer/status/active/unactive', 'ManufacturerController@activeUnactive')->name('manufacturer.statusActiveUnactive');
        /*****end=>> manufacturer controller*****/

        /*****start=>> product stock transfer controller*****/
        Route::resource('/product_stock_transfer', 'ProductStockTransferController');
        Route::post('product_stock_transfer-datatable', 'ProductStockTransferController@getListByAjax')->name('product_stock_transfer.ajax');
        Route::get('product_stock_transfer/warehouse_type/{warehouse_id}/{warehouse_detail_id?}', 'ProductStockTransferController@getWarehouseType');
        /*****end=>> product stock transfer controller*****/

        //start==> product return controller
        Route::resource('product_returns', 'ProductReturnController');

        Route::get('return/product', 'ProductReturnController@index')->name('return.product');
        Route::get('return/request-endpoint-form', 'ProductReturnController@returnRequestEndpointForm')->name('return.request.endpoint.form');
        Route::post('return/request-endpoint-submit', 'ProductReturnController@returnRequestEndpointSubmit')->name('return.request.endpoint.submit');
        Route::post('return/product-stock-info', 'ProductReturnController@getReturnedProductInfoWithAjax')->name('return.product.info.with.ajax');
        Route::post('return/exchange-product-stock-info', 'ProductReturnController@getExchangedProductInfoWithAjax')->name('exchanged.product.info.with.ajax');
        Route::post('return/exchange-product-barcode-list', 'ProductReturnController@getExchangedProductBarcodeList')->name('exchanged.product.barcode.list');
        Route::post('return/action', 'ProductReturnController@returnRequestAction')->name('return.request.action');
        Route::post('return/request-reject', 'ProductReturnController@returnRequestReject')->name('return.request.reject');
        Route::post('return/detail', 'ProductReturnController@returnRequestDetail')->name('return.request.detail');
        Route::post('return/not-sold-product-detail', 'ProductReturnController@notSoldProductDetail')->name('return.prduct.not.sold.detail');



        //end=>> product return Controller
        Route::get('/vendor-commission', 'VendorCommissionController@index')->name('vendor.commission');
        Route::POST('/vendor-commission/store', 'VendorCommissionController@transactionStore')->name('vendor_commission_transaction.store');
        Route::get('/vendor-commission/create', 'VendorCommissionController@transactionCreate')->name('vendor_commission_transaction.create');


        /*****start=>> Shipping Method controller*****/
        Route::resource('/shipping_methods', 'ShippingMethodController');
        Route::post('shipping_methods-datatable', 'ShippingMethodController@getListByAjax')->name('shipping_methods.ajax');
        Route::get('shipping_methods/status/active/unactive', 'ShippingMethodController@activeUnactive')->name('shipping_methods.statusActiveUnactive');
        /*****end=>> Shipping Method controller*****/

        /*****start=>> Payment Method controller*****/
        Route::resource('/payment_methods', 'PaymentMethodController');
        Route::post('payment_methods-datatable', 'PaymentMethodController@getListByAjax')->name('payment_methods.ajax');
        Route::get('payment_methods/status/active/unactive', 'PaymentMethodController@activeUnactive')->name('payment_methods.statusActiveUnactive');
        /*****end=>> Payment Method controller*****/

    });
    /*********************** end only admin role section*************************/

    /*********************** start only admin and account role section*************************/
    Route::group(['middleware' => ['auth:vendor']], function () {


        Route::get('/supplier-all', 'SupplierController@index')->name('supplier.all.index');
        Route::get('/supplier-invoices', 'SupplierController@getPurchaseInvoiceList')->name('supplier.purchase.invoice.list');
        Route::get('/supplier-invoices/{supplier_id}', 'SupplierController@getPurchaseInvoiceList')->name('supplier_wise.purchase.invoice.list');
        Route::post('supplier-datatable', 'SupplierController@getListByAjax')->name('supplier.ajax');

        Route::get('/pos_customer-all', 'PosCustomerController@allList')->name('pos_customer.all.list');
        Route::get('pos_customer-invoices/{pos_customer_id}', 'PosCustomerController@getPosCustomerInvoiceList')->name('pos_customer.invoice.list');
        Route::post('pos_customer-datatable', 'PosCustomerController@getAllListByAjax')->name('pos_customer.all.ajax');

        //Marketplace Customer Due Payment
        Route::get('/mp_customer-all', 'MpCustomerController@allList')->name('mp_customer.all.list');
        Route::get('mp_customer-invoices/{mp_customer_id}', 'MpCustomerController@getMpCustomerInvoiceList')->name('mp_customer.invoice.list');
        Route::post('mp_customer-datatable', 'MpCustomerController@getAllListByAjax')->name('mp_customer.all.ajax');

        Route::get('/add/mp_customer_payment/{mp_customer_id}', 'MpCustomerController@addMpCustomerPayment')->name('mp_customer_payment');
        Route::post('/add/mp_customer_payment/', 'MpCustomerController@addMpCustomerPaymentStore')->name('mp_customer_payment.store');
        //Marketplace Customer Due Payment End

        // Route::get('/supplier/{supplier}/add/payment-method', 'SupplierController@AddPaymentMethod')->name('supplier.pay_method');
        // Route::post('/supplier/{supplier}/add/payment-method', 'SupplierController@PaymentMethodStore')->name('supplier.pay.method.store');
        Route::get('/supplier/{supplier}/add/bank', 'SupplierController@addBank')->name('supplier.bank');
        Route::post('/supplier/{supplier}/add/bank', 'SupplierController@addBankStore')->name('supplier.bank.store');

        // start==> Add new payment methods for each supplier
        Route::get('/add/payment_method/{supplier}', 'SupplierController@AddPaymentMethod')->name('supplier.payment_method');
        Route::post('/add/payment_method/{supplier}', 'SupplierController@PaymentMethodStore')->name('supplier.payment_method.store');
        Route::get('/edit/payment_method/{supplier}', 'SupplierController@editPaymentMethod')->name('supplier.payment_method.edit');
        Route::post('/update/payment_method/{supplier}', 'SupplierController@updatePaymentMethod')->name('supplier.payment_method.update');
        // Route::get('/{supplier}/destroy/payment_method', 'SupplierController@PaymentMethodDestroy')->name('supplier.payment_method.destroy');
        Route::get('status/active/unactive', 'SupplierController@activeUnactivePaymentMethod')->name('supplier.payment_method.statusActiveUnactive');
        Route::post('payment_method/destroy/{id}', 'SupplierController@PaymentMethodDestroy')->name('supplier.payment_method.destroy');

        //end==> Add new payment methods for each supplier
        //start==> Supplier Payment
        Route::get('/add/supplier_payment/{supplier}', 'SupplierController@addSupplierPayment')->name('supplier_payment');
        Route::post('/add/supplier_payment/', 'SupplierController@addSupplierPaymentStore')->name('supplier_payment.store');
        //end==> Supplier Payment

        //start==> Pos Customer Payment
        Route::get('/add/pos_customer_payment/{pos_customer}', 'PosCustomerController@addPosCustomerPayment')->name('pos_customer_payment');
        Route::post('/add/pos_customer_payment/', 'PosCustomerController@addPosCustomerPaymentStore')->name('pos_customer_payment.store');
        //end==> Pos Customer Payment

        Route::get('/supplier/ledger/print/{id}', 'PdfDownloadOrPrintController@supplierLedgerPrint')->name('supplier.ledger.print');
        Route::get('supplier-list', 'SupplierController@supplierList')->name('supplier.list');


        //start==> vendor expenses controller
        Route::resource('vendorexpenses', 'VendorExpensesController');
        Route::post('vendorexpenses-datatable', 'VendorExpensesController@getListByAjax')->name('vendorexpenses.ajax');

        //start==> vendor expenses controller

    });
    /*********************** start only admin and account role section*************************/

    /*********************** start only admin and operator role section*************************/
    Route::group(['middleware' => ['auth:vendor']], function () {
        /*****start=>> supplier controller*****/
        Route::resource('supplier', 'SupplierController');
        Route::get('supplier/status/active/unactive', 'SupplierController@activeUnactive')->name('supplier.statusActiveUnactive');
        /*****end=>> supplier controller*****/

        /*****start=>> tax controller*****/
        Route::resource('tax', 'TaxController');
        Route::post('tax-datatable', 'TaxController@getListByAjax')->name('tax.ajax');

        Route::get('tax/status/active/unactive', 'TaxController@activeUnactive')->name('tax.statusActiveUnactive');
        /*****end=>> tax controller*****/

        /*****start=>> product brand controller*****/
        Route::resource('product_brand', 'ProductBrandController');
        Route::post('product_brand-datatable', 'ProductBrandController@getListByAjax')->name('product_brand.ajax');
        Route::get('product_brand/status/active/unactive', 'ProductBrandController@activeUnactive')->name('product_brand.statusActiveUnactive');
        Route::post('product_brand-liveSearch', 'ProductBrandController@getliveSearchByAjax')->name('product_brand.liveSearch.ajax');

        /*****end=>> product brand controller*****/

        /*****start=>> product category controller*****/
        Route::resource('product_category', 'ProductCategoryController');
        Route::post('product_category-datatable', 'ProductCategoryController@getListByAjax')->name('product_category.ajax');
        Route::post('product_category-liveSearch', 'ProductCategoryController@getliveSearchByAjax')->name('product_category.liveSearch.ajax');
        Route::get('product_category/status/active/unactive', 'ProductCategoryController@activeUnactive')->name('product_category.statusActiveUnactive');
        /*****end=>> product category controller*****/

        /*****start=>> product attribute controller*****/
        Route::resource('product_attribute', 'ProductAttributeController');
        Route::post('product_attribute-datatable', 'ProductAttributeController@getListByAjax')->name('product_attribute.ajax');

        Route::get('product_attribute/status/active/unactive', 'ProductAttributeController@activeUnactive')->name('product_attribute.statusActiveUnactive');
        /*****end=>> product attribute controller*****/

        /*****start=>> product attribute map controller*****/
        Route::resource('product_attribute_map', 'ProductAttributeMapController');
        Route::post('product_attribute_map-datatable', 'ProductAttributeMapController@getListByAjax')->name('product_attribute_map.ajax');
        Route::get('product_attribute_map/status/active/unactive', 'ProductAttributeMapController@activeUnactive')->name('product_attribute_map.statusActiveUnactive');
        Route::get('product_attribute_map/get/product/attribute', 'ProductAttributeMapController@getProductAttribute')->name('get.product_attribute');
        /*****end=>> product attribute map controller*****/

        /*****start=>> product controller*****/
        Route::get('/product/categories/{parent}', 'ProductController@getCategoriesByParent')->name('product.categories.by.parent');
        Route::get('/product/parent', 'ProductController@parentProductSelection')->name('product.parent');
        Route::post('/product/search', 'ProductController@getProductsByAjax')->name('product.search');
        Route::post('/product/json', 'ProductController@getProductsJsonByAjax')->name('product.json');
        Route::post('/product/json/ajax', 'ProductController@getOnlyProductsJsonByAjax')->name('product.json.ajax');
        Route::resource('/product', 'ProductController');
        Route::post('product-datatable', 'ProductController@getListByAjax')->name('product.ajax');
        Route::post('product/product_specification', 'ProductController@getProductSpecificationByAjax')->name('product.product_specification.ajax');

        Route::get('/product/{product}/edit/{use_existing?}', 'ProductController@edit')->name('product.edit');
        Route::get('/product/{product}/show/{use_existing?}', 'ProductController@show')->name('product.show');
        Route::get('/product/create/{pre_category}', 'ProductController@create')->name('product.create');
        Route::get('/product/vendor/wise/component', 'ProductController@getproductcomponenetforvendor')->name('get.vendorwise.product.component');
        Route::post('/product/activeDeactive', 'ProductController@activeDeactive')->name('product.status.activeDeactive');
        Route::get('/product/vendor/wise/component', 'ProductController@getProductComponenetForVendor')->name('get.vendorwise.product.component');
        Route::get('product-dropdown', 'ProductController@productDropdownList')->name('product.dropdown.list');
        Route::get('brand-dropdown', 'ProductBrandController@brandDropdownList')->name('brand.dropdown.list');



        /*****end=>> product controller*****/

        /*****start=>> Product Image details controller*****/
        Route::resource('/product_image', 'ProductImageController');
        Route::get('product_image/get_image/{product_id}', 'ProductImageController@getProductImages');
//        Route::post('product_image/destroy', ['uses' => 'ProductImageController@destroy']);
        Route::post('product_image/destroy', 'ProductImageController@destroy')->name('product_image.destroy');
        Route::post('/product_image/sort', 'ProductImageController@itemsSort')->name('product_images_sort');

        /*****end=>> Product Image details controller*****/

        /*****start=>> purchase and stock controller*****/
        Route::resource('/purchase', 'PurchaseController');
        Route::post('purchase-datatable', 'PurchaseController@getListByAjax')->name('purchase.ajax');

        Route::post('purchase/supplier/details', 'PurchaseController@getSupplierDetails')->name('purchase.supplier.details');
        Route::post('purchase/vendor/attribute-map', 'PurchaseController@getProductAttributeMapForVendor')->name('purchase.vendor.attribute.map');
        Route::post('/purchase/invoice/check/unique', 'PurchaseController@CheckInvoiceNoUnique')->name('check.invoice_no.unique');
        Route::post('/purchase/autogenerate/invoice-unique', 'PurchaseController@autogenerateInvoiceNoUnique')->name('purchases.autogenerate.invoice.unique');
        Route::get('purchases/{purchase}/post-status', 'PurchaseController@purchsesPostStatus')->name('purchase.post_status');
        Route::get('purchases/{purchase}/stock', 'PurchaseController@purchsesStock')->name('purchase.stock');
        Route::get('get-barcode-list/{purchase_detail_id}', 'PurchaseController@getBarcodeList');
//        Route::get('purchase/create', 'PurchaseController@create')->name('purchase.create');
        Route::get('purchases/single-product/discard-status/{id}', 'PurchaseController@purchsesDiscardStatus')->name('purchase.discard_status');
        Route::post('/purchase/details/delete', 'PurchaseController@purchaseDetailProductDelete')->name('purchase.details.delete');

        Route::get('products/stocks', 'ProductStockController@index')->name('products.stocks');
        Route::post('products/stocks-datatable', 'ProductStockController@getListByAjax')->name('products_stocks.ajax');
        Route::post('products_stock-datatable', 'ProductStockController@getListByAjax')->name('products_stock.ajax');
        Route::post('/purchase/single-product/stock', 'ProductStockController@purchasesSubmitToStock')->name('purchase.single_product.stock');
        Route::get('/single/product-detail', 'ProductStockController@ProductDetailsToStockDetails')->name('single_product.stock_details_info');


        // Purchase additional Expenses
        Route::post('purchase/additional/expenses', 'PurchaseController@submitAdditionalExpenses')->name('purchase.additional_expense');
        Route::post('purchase/additional/expenses/update', 'PurchaseController@updateAdditionalExpenses')->name('purchase.additional_expense.update');
        Route::post('purchase/additional/expenses/delete', 'PurchaseController@deleteAdditionalExpenses')->name('purchase.additional_expense.delete');

        // For Barcode check

        Route::post('products/barcode_check', 'ProductStockController@barcodeCheck')->name('products.barcode.stocks.check');
        Route::post('products/warehouse_type', 'ProductStockController@getWarehouseType')->name('products.warehouse_type.check');

        // End of Barcode check

        //        generate pdf
        Route::get('purchase/create/pdf/{id}', 'PdfDownloadOrPrintController@purchasesPdf')->name('purchase.create.pdf');
        Route::get('purchase/store/price/pdf/{id}', 'PdfDownloadOrPrintController@purchasesStorePricePdf')->name('purchase.store.price.pdf');
        Route::get('purchase-invoices', 'PurchaseController@purchaseInvoiceList')->name('purchase.invoices');
        /*****end=>> purchase and stock  controller*****/

    });
    /*********************** end only admin and operator role section *************************/

    /*********************** start only admin and sale role section *************************/
    Route::group(['middleware' => ['auth:vendor']], function () {

        /****** start==>> sale related controller ******/
        // sale controller
        Route::resource('sale', 'SaleController'); //deprecated
        Route::get('sale/product/details-stock', 'SaleController@getsingleProductDetails')->name('single.product.details.from.stock'); //deprecated
        Route::get('sale/product/search-sale', 'SaleController@searchProduct')->name('product.search.for.sale'); //deprecated
        Route::post('sale/product-store', 'SaleController@saleProductStore')->name('store.sale.value');
        Route::get('sale/{sale}/details', 'SaleController@SaleDetail')->name('sale.detail');
        Route::get('sale/{sale}/payment', 'SaleController@SalePayment')->name('sale.payment');
        Route::post('sale/payment/store', 'SaleController@SalePaymentWithAjax')->name('sale.payment.store.with_ajax');
        Route::post('sale/{sale}/payment/store', 'SaleController@SalePaymentStore')->name('sale.payment.store');
        Route::get('get/poscustomer/info', 'SaleController@posCustomerInfo')->name('get.poscustomer.info');

        Route::get('sale/print/{id}', 'PdfDownloadOrPrintController@salePrint')->name('sale.print');

        // end sale controller
        // start sale order controller
        // Route::get('sale-order-details', 'SaleOrderController@sale_order_details')->name('sale.order.details');
        //wholesale
        Route::get('sale-order-wholesale', 'SaleOrderController@wholesaleOrder')->name('sale.order.wholesale');
        Route::post('sale-order-wholesale', 'SaleOrderController@wholesaleOrder')->name('sale.order.wholesale.ajax');
        // Route::get('sale-order-wholesale-admin', 'SaleOrderController@wholesaleOrderAdmin')->name('sale.order.wholesale.admin');
        // Route::post('sale-order-wholesale-admin', 'SaleOrderController@wholesaleOrderAdmin')->name('sale.order.wholesale.admin.ajax');
        //pos
        Route::get('sale-order', 'SaleOrderController@index')->name('sale.order');
        Route::post('sale-order', 'SaleOrderController@index')->name('sale.order.ajax');
        Route::get('sale/order/invoice-list', 'SaleOrderController@invoiceList')->name('sale.order.invoice.list');

        Route::get('sale-order-admin', 'SaleOrderController@saleOrderAdminDetails')->name('sale.order.admin');
        Route::post('sale-order-admin', 'SaleOrderController@saleOrderAdminDetails')->name('sale.order.admin.ajax');
        //chalan
        Route::get('sell/order/chalan/{sale_id}', 'PosController@chalan')->name('pos.chalan');

        // wholesale negotiation
        Route::get('negotiation', 'SaleNegotiationController@index')->name('sale.negotiation');
        Route::post('negotiation', 'SaleNegotiationController@index')->name('sale.negotiation.ajax');
        Route::post('negotiate', 'SaleNegotiationController@negotiateOrder')->name('sale.negotiate');
        Route::post('negotiation-confirm', 'SaleNegotiationController@confirmNegotiation')->name('sale.negotiation.confirm');
        Route::get('negotiation-reject', 'SaleNegotiationController@rejectNegotiation')->name('sale.negotiation.reject');

        Route::post('sale/warehouse_update', 'SaleOrderController@updateSaleOrderWarehouse')->name('sale.order.details.warehouse');
        Route::post('sale/order_status_update', 'SaleOrderController@updateSaleOrderStatus')->name('sale.order.details.status');
        Route::post('sale/order/confirm', 'SaleOrderController@confirmSaleOrderStatus')->name('sale.order.confirm');
        Route::post('confirm-without-barcode', 'SaleOrderController@confirmSaleOrderStatusWithoutBarcode')->name('sale.order.confirm.without.barcode');




        Route::post('sell/order/barcode/check', 'SaleOrderController@stockBarcodeCheck')->name('sale.order.barcode.check');
        Route::post('sell/order/detail/invoice', 'SaleOrderController@saleOrderDetailInvoice')->name('sale.order.detail.invoice');
        Route::post('sell/order/detail', 'SaleOrderController@saleOrderDetail')->name('sale.order.detail');
        // end sale order controller

        //start=>> PosCustomer Controller
        Route::get('poscustomer/list', 'PosCustomerController@posCustomerListJson')->name('poscustomer.list');
        Route::get('pos-product-category/list', 'PosCustomerController@productCategoryListJson')->name('pos-product-category.list');
        Route::get('pos-product-brand/list', 'PosCustomerController@productBrandListJson')->name('pos-product-brand.list');
        Route::resource('poscustomer', 'PosCustomerController');
        Route::post('poscustomer/store-with-ajax', 'PosCustomerController@posCustomerCreateWithAjax')->name('poscustomer.store.with_ajax');
        Route::get('poscustomer/{poscustomer}', 'PosCustomerController@addBankForm')->name('poscustomer.add.bank');
        Route::post('poscustomer/{poscustomer}', 'PosCustomerController@addBankStore')->name('poscustomer.bank.store');
        //end=>> PosCustomerController

        Route::get('vendor-expenses-all', 'VendorExpensesController@index')->name('vendorexpenses.all.index');
        Route::get('vendor-expenses-create', 'VendorExpensesController@create')->name('vendorexpenses.create');
        Route::post('vendor-expenses-store', 'VendorExpensesController@store')->name('vendorexpenses.store');
        Route::get('vendor-expenses-edit/{id}', 'VendorExpensesController@edit')->name('vendorexpenses.edit');
        Route::put('vendor-expenses-update/{id}', 'VendorExpensesController@update')->name('vendorexpense.update');
        Route::delete('vendor-expenses-destroy/{id}', 'VendorExpensesController@destroy')->name('vendorexpense.destroy');

        Route::get('bulk_uploads', 'BulkUploadController@index')->name('bulk.upload.admin');
        Route::get('import_sheet', 'BulkUploadController@importSheet')->name('import.sheet.admin');
        Route::post('store_sheet', 'BulkUploadController@store')->name('store.sheet.admin');

        // =>Start Retail Sale
        Route::get('retail_sell', 'RetailSellController@index');
        Route::get('retail_sell/products', 'RetailSellController@products');
        Route::get('retail_sell/receipt/{sale_id}', 'RetailSellController@receipt');
        Route::get('retail_sell/today_sales', 'RetailSellController@todaySales');
        Route::get('retail_sell/due_orders', 'RetailSellController@dueOrders');
        Route::post('retail_sell/due_orders', 'RetailSellController@dueOrdersListByAjax');
        Route::get('retail_sell/payment_option/{sale_id}', 'RetailSellController@payOption');
        Route::get('mp_customer_ajax', 'RetailSellController@mpCustomerAjax')->name('mp_customer.list');

        Route::post('retail_sale/product-store', 'RetailSellController@saleProductStore')->name('retail.store.sale.value');
        //productereturn
        Route::get('retail_sell/pending-request', 'RetailSellController@pendingReturnRequest');
        Route::get('retail_sell/return-request', 'RetailSellController@returnRequest');
        Route::get('retail_sell/exchange-product-detail', 'RetailSellController@exchangeProductDetail');
        Route::get('retail_sell/return-request-detail', 'RetailSellController@returnRequestDetail');
        Route::post('retail_sell/return-request-submit', 'RetailSellController@returnRequestSubmit');
        Route::get('retail_sell/edit-return-request/{id}', 'RetailSellController@editReturnRequest');
        Route::delete('retail_sell/destroy-return-request/{id}', 'RetailSellController@destroyReturnRequest');
        Route::post('retail_sale/payment/store', 'RetailSellController@SalePaymentWithAjax')->name('retail_sale.payment.store.with_ajax');
        // => End Retail Sale

        // Warehouse Section Tree
        Route::post('warehouse-section-tree', 'WarehouseDetailController@WarehouseSectionTreeAjax')->name('warehouse-section-tree.ajax');
        Route::post('warehouse-tree', 'WarehouseDetailController@WarehouseTreeAjax')->name('warehouse-tree.ajax');

    });
    /*********************** end only admin and sale role section*************************/

    /**************** start==> Report  controller *****************/
    Route::group(['as' => 'report.', 'namespace' => 'Report', 'prefix' => 'report'], function () {

        //start==> supplier report controller
        Route::get('vendors', 'SupplierReportController@index')->name('vendors');
        Route::get('supplier/{supplier}/detail', 'SupplierReportController@supplierDetail')->name('supplier.detail');
        //end==> supplier report controller

        //to do
        //start==> warehouses report controller
        // Route::get('warehouses', 'WarehouseController@index')->name('warehouses');
        // Route::get('warehouse/{warehouse}/detail', 'WarehouseController@warehouseDetail')->name('warehouse.detail');
        // Route::get('warehouse/{warehouse}/sale', 'WarehouseController@warehouseSale')->name('warehouse.sale');
        // Route::get('warehouse/{id}/detail-product-finish', 'WarehouseController@warehouseDetailProductFinish')->name('warehouse.detail.product.finish');
        //end==> warehouses report controller

        //start==> products report controller
        Route::get('products', 'ProductController@index')->name('products');
        Route::get('product/{product}/detail', 'ProductController@productDetail')->name('product.detail');
        //end==> products report controller

        //start==> purchases report controller
        Route::get('purchases', 'PurchasesReportController@index')->name('purchases');
        Route::post('purchases', 'PurchasesReportController@getReportByAjax')->name('purchases.ajax');
        Route::get('purchases/supplier', 'PurchasesReportController@supplierPurchaseReport')->name('purchases.supplier');
        Route::post('purchases/supplier', 'PurchasesReportController@supplierPurchaseReport')->name('purchases.supplier.ajax');
        Route::get('purchases/supplier/details', 'PurchasesReportController@supplierPurchaseDetailReport')->name('purchases.supplier.details');
        Route::post('purchases/supplier/details', 'PurchasesReportController@supplierPurchaseDetailReport')->name('purchases.supplier.details.ajax');

        Route::get('purchase/{purchase}/detail', 'PurchasesReportController@purchaseDetail')->name('purchase.detail');
        //end==> purchases report controller

        //start==> poscustomer report controller
        Route::get('poscustomers', 'PosCustomersReportController@posCustomerReports')->name('poscustomers');
        Route::get('poscustomer/{poscustomer}/details', 'PosCustomersReportController@showPosCustomerSale')->name('poscustomer.sale.detail');
        //end==> poscustomer report controller

        //start==> sale report controller
        Route::get('sales', 'SaleReportController@index')->name('sales');
        Route::post('sales', 'SaleReportController@getReportByAjax')->name('sales.ajax');
        Route::get('most_sale_products', 'SaleReportController@mostSaleProducts')->name('most_sale_products');
        Route::post('most_sale_products', 'SaleReportController@mostSaleProductsByAjax')->name('most_sale_products.ajax');
        //end==> sale report controller

        //Start ===> Profit Loss
        Route::get('profit-loss', 'SaleReportController@profitLoss')->name('profit_loss');
        Route::post('profit-loss', 'SaleReportController@profitLoss')->name('profit_loss.ajax');
        //End ===> Profit Loss

        Route::get('order_shipment', 'SaleReportController@orderShipment')->name('order_shipment');
        Route::post('order_shipment', 'SaleReportController@orderShipment')->name('order_shipment.ajax');

        Route::get('returns', 'ProductReceiveReportController@index')->name('returns');

        //start==> Product stock report controller
        Route::get('product-stocks', 'ProductStockReportController@index')->name('product_stocks');
        Route::post('product-stocks', 'ProductStockReportController@getReportByAjax')->name('product_stocks.ajax');
        Route::get('stock-detail', 'ProductStockReportController@stockDetail')->name('stock.detail');
        Route::post('stock-detail', 'ProductStockReportController@stockDetail')->name('stock.detail.ajax');
        Route::get('product-wise-warehouse-stock-detail', 'ProductStockReportController@productWiseWarehouseStockDetail')->name('product-wise.warehouse.stock.detail');
        Route::post('product-wise-warehouse-stock-detail', 'ProductStockReportController@productWiseWarehouseStockDetail')->name('product-wise.warehouse.stock.detail.ajax');
        //start==> Product Alert quantity report controller
        Route::get('alert-quantity-details', 'AlertQuantityReportController@index')->name('product_alert_quantity');
        Route::post('alert-quantity-details', 'AlertQuantityReportController@getReportByAjax')->name('product_alert_quantity.ajax');
        //start==> Purchase Details report controller
        Route::get('purchase-details', 'PurchaseDetailsReportController@index')->name('purchase_details');
        Route::post('purchase-details', 'PurchaseDetailsReportController@getReportByAjax')->name('purchase_details.ajax');
        //start==> Supplier Ledgers report controller
        Route::get('supplier-ledgers', 'SupplierLedgersReportController@index')->name('supplier_ledgers');
        Route::post('supplier-ledgers', 'SupplierLedgersReportController@getReportByAjax')->name('supplier_ledgers.ajax');

        //start==> Vendor Expenses report controller
        Route::get('vendor-expense', 'VendorExpenseReportController@index')->name('vendor_expense');
        Route::post('vendor-expense', 'VendorExpenseReportController@getReportByAjax')->name('vendor_expense.ajax');

        //start==> Customer wise Sales report controller
        Route::get('customer-sales', 'SaleReportController@customerSales')->name('customer_sales');
        Route::post('customer-sales-ajax', 'SaleReportController@customerSales')->name('customer_sales.ajax');
        Route::get('pos_mp_customer_ajax', 'SaleReportController@posMpCustomerAjax')->name('pos_mp_customer.list');
        Route::get('sale_invoices', 'SaleReportController@salesInvoices')->name('sale.invoices');
        Route::get('sale_status', 'SaleReportController@salesStatus')->name('sale_status.list');

        //start ==> Category Stock Details
        Route::get('category-stock-detail', 'ProductStockReportController@categoryStockDetail')->name('category.stock.detail');
        Route::post('category-stock-detail', 'ProductStockReportController@categoryStockDetail')->name('category.stock.detail.ajax');
        Route::post('product_category-Search', 'ProductStockReportController@categorySearchByAjax')->name('category.Search.ajax');
        //product brand search by ajax
        Route::post('product-brand-Search', 'ProductStockReportController@brandSearchByAjax')->name('brand.Search.ajax');

        //start ==> Category Stock Details
        Route::get('category-wise-stock-detail', 'ProductStockReportController@categoryWiseStockDetail')->name('category_wise.stock.detail');
        Route::post('category-wise-stock-detail', 'ProductStockReportController@categoryWiseStockDetail')->name('category_wise.stock.detail.ajax');

        //start ==> Item Wise Sales Reports
        Route::get('item-wise-total-sales', 'SaleReportController@itemWiseTotalSales')->name('item_wise.total.sales');
        Route::post('item-wise-total-sales', 'SaleReportController@itemWiseTotalSales')->name('item_wise.total.sales.ajax');

        //start ==> Brand Wise Sales Reports
        Route::get('brand-wise-total-sales', 'SaleReportController@brandWiseTotalSales')->name('brand_wise.total.sales');
        Route::post('brand-wise-total-sales', 'SaleReportController@brandWiseTotalSales')->name('brand_wise.total.sales.ajax');

        //start ==> Brand Wise Purchase Reports
        Route::get('brand-wise-total-purchases', 'PurchasesReportController@brandWiseTotalPurchases')->name('brand_wise.total.purchases');
        Route::post('brand-wise-total-purchases', 'PurchasesReportController@brandWiseTotalPurchases')->name('brand_wise.total.purchases.ajax');

        //start ==> customer Wise Product Buy Reports
        Route::get('customer-wise-product-buy', 'SaleReportController@customerWiseProductBuy')->name('customer_wise.product.buy');
        Route::post('customer-wise-product-buy', 'SaleReportController@customerWiseProductBuy')->name('customer_wise.product.buy.ajax');

        //start ==> Customer Wise Due Module
        Route::get('customer-wise-due', 'SaleReportController@customerWiseDue')->name('customer_wise.due');
        Route::post('customer-wise-due', 'SaleReportController@customerWiseDue')->name('customer_wise.due.ajax');
        //start ==> Sale Order Wise Due Module
        Route::get('sale-wise-due', 'SaleReportController@salesWiseDue')->name('sale_wise.due');
        Route::post('sale-wise-due', 'SaleReportController@salesWiseDue')->name('sale_wise.due.ajax');
        //start ==> Sale Brand Wise Due Module
        Route::get('brand-wise-due', 'SaleReportController@brandWiseDue')->name('brand_wise.due');
        Route::post('brand-wise-due', 'SaleReportController@brandWiseDue')->name('brand_wise.due.ajax');
        //start ==> Sale Commission Detail
        Route::get('sale-commission-detail', 'SaleReportController@saleComissionDetail')->name('sale_commission.detail');
        Route::post('sale-commission-detail', 'SaleReportController@saleComissionDetail')->name('sale_commission.detail.dt');
        //start ==> Warehouse Wise Stock
        Route::get('warehouse-wise-stock', 'ProductStockReportController@warehouseWiseStock')->name('warehouse_wise.stock');
        Route::post('warehouse-wise-stock', 'ProductStockReportController@warehouseWiseStock')->name('warehouse_wise.stock.ajax');

    });
    /**************** end==> Report  controller *****************/
});

Route::group(['middleware' => ['auth:vendor']], function () {
    Route::get('pos', 'PosController@index')->name('pos');
    Route::get('pos/products', 'PosController@products');
    Route::get('pos/receipt/{sale_id}', 'PosController@receipt');
    Route::get('pos/chalan/{sale_id}', 'PosController@chalan')->name('pos.chalan');
    Route::get('pos/today_sales', 'PosController@todaySales');
    Route::get('pos/due_orders', 'PosController@dueOrders');
    Route::post('pos/due_orders', 'PosController@dueOrdersListByAjax');
    Route::get('pos/payment_option/{sale_id}', 'PosController@payOption');

    //productereturn
    Route::get('pos/pending-request', 'PosController@pendingReturnRequest');
    Route::get('pos/return-request', 'PosController@returnRequest');
    Route::get('pos/exchange-product-detail', 'PosController@exchangeProductDetail');
    Route::get('pos/return-request-detail', 'PosController@returnRequestDetail');
    Route::post('pos/return-request-submit', 'PosController@returnRequestSubmit');
    Route::get('pos/edit-return-request/{id}', 'PosController@editReturnRequest');
    Route::delete('pos/destroy-return-request/{id}', 'PosController@destroyReturnRequest');
});
Route::get('point-of-sale', 'SaleController@point_of_sale');

Route::get('install', 'InstallController@install');
