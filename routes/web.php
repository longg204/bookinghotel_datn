<?php

    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\HomeController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\AdminController;
    use App\Http\Middleware\AuthAdmin;
    use App\Http\Controllers\RoomController;
    use App\Http\Controllers\CartController;
    use App\Http\Controllers\WishlistController;
    use App\Http\Controllers\ContactController;

    Auth::routes();

    Route::get('/', [HomeController::class, 'index'])->name('home.index');

    /* room */
    Route::get("/room", [RoomController::class, "index"])->name("room.index");
    Route::get("/room/{slug}", [RoomController::class, "room_detail"])->name("room.detail");

    /* cart */
    Route::get("/cart", [CartController::class, "index"])->name("cart.index");
    Route::post("/cart", [CartController::class, "add_to_cart"])->name("cart.add");
    Route::put("/cart/increase-quantity/{rowId}", [CartController::class, "increase_cart_quantity"])->name("cart.qty.increase");
    Route::put("/cart/decrease-quantity/{rowId}", [CartController::class, "decrease_cart_quantity"])->name("cart.qty.decrease");
    Route::delete("/cart/remove/{rowId}", [CartController::class, "remove_item"])->name("cart.remove");
    Route::delete("/cart/clear", [CartController::class, "empty_cart"])->name("cart.empty");
    Route::post("/cart/apply-coupon", [CartController::class, "apply_coupon_code"])->name("cart.coupon.apply");
    Route::delete("/cart/remove-coupon", [CartController::class, "remove_coupon_code"])->name("cart.coupon.remove");


    /* contact */
    Route::get("/contact", [ContactController::class, "index"])->name("contact.index");
    Route::post("/contact", [ContactController::class, 'add_to_contact'])->name("contact.add");

    /* wishlist */
    Route::get("/wishlist", [WishlistController::class, "index"])->name("wishlist.index");
    Route::post("/wishlist", [WishlistController::class, 'add_to_wishlist'])->name("wishlist.add");
    Route::delete("/wishlist/remove/{rowId}", [WishlistController::class, "remove_item"])->name("wishlist.remove");
    Route::delete("/wishlist/clear", [WishlistController::class, "empty_wishlist"])->name("wishlist.empty");
    Route::post("/wishlist/move-to-cart/{rowId}", [WishlistController::class, "move_to_cart"])->name("wishlist.move.to.cart");

    /* checkout */
    Route::get('/checkout',[CartController::class,'checkout'])->name('cart.checkout');
    Route::post('/place-order',[CartController::class,'place_order'])->name('cart.place.order');
    Route::get('/order-confirmation',[CartController::class,'confirmation'])->name('cart.confirmation');

    Route::middleware(['auth'])->group(function () {
        Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
        Route::get('/account-orders',[UserController::class,'account_orders'])->name('user.account.orders');
        Route::get('/account-order-details/{order_id}',[UserController::class,'account_order_details'])->name('user.account.order.details');
        Route::put('/account-order/cancel-order',[UserController::class,'account_cancel_order'])->name('user.account_cancel_order');

    });

    Route::middleware(['auth', AuthAdmin::class])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');


        /* category */
        Route::get('/admin/category', [AdminController::class, 'category'])->name('admin.category');
        Route::get('/admin/category/add', [AdminController::class, 'category_add'])->name('admin.category.add');
        Route::post('admin/category/store', [AdminController::class, 'category_store'])->name('admin.category.store');
        Route::get('/admin/category/edit/{id}', [AdminController::class, 'category_edit'])->name('admin.category.edit');
        Route::put('/admin/category/update', [AdminController::class, 'category_update'])->name('admin.category.update');
        Route::delete('admin/category/delete/{id}', [AdminController::class, 'category_delete'])->name('admin.category.delete');

        /* room */
        Route::get('/admin/room', [AdminController::class, 'room'])->name('admin.room');
        Route::get('/admin/room/add', [AdminController::class, 'room_add'])->name('admin.room.add');
        Route::post('admin/room/store', [AdminController::class, 'room_store'])->name('admin.room.store');
        Route::get('/admin/room/edit/{id}', [AdminController::class, 'room_edit'])->name('admin.room.edit');
        Route::put('/admin/room/update', [AdminController::class, 'room_update'])->name('admin.room.update');
        Route::delete('admin/room/delete/{id}', [AdminController::class, 'room_delete'])->name('admin.room.delete');

        /* coupon */
        Route::get('/admin/coupon', [AdminController::class, 'coupons'])->name('admin.coupon');
        Route::get('/admin/coupon/add', [AdminController::class, 'coupon_add'])->name('admin.coupon.add');
        Route::post('admin/coupon/store', [AdminController::class, 'coupon_store'])->name('admin.coupon.store');
        Route::get('/admin/coupon/edit/{id}', [AdminController::class, 'coupon_edit'])->name('admin.coupon.edit');
        Route::put('/admin/coupon/update', [AdminController::class, 'coupon_update'])->name('admin.coupon.update');
        Route::delete('admin/coupon/delete/{id}', [AdminController::class, 'coupon_delete'])->name('admin.coupon.delete');

        /* orders */
        Route::get('/admin/orders',[AdminController::class,'orders'])->name('admin.orders');
        Route::get('/admin/order/items/{order_id}',[AdminController::class,'order_items'])->name('admin.order.items');
        Route::put('/admin/order/update-status',[AdminController::class,'update_order_status'])->name('admin.order.status.update');


        /* slides */
        Route::get('/admin/slides',[AdminController::class,'slides'])->name('admin.slides');
        Route::get('/admin/slide/add', [AdminController::class, 'slide_add'])->name('admin.slide.add');
        Route::post('admin/slide/store', [AdminController::class, 'slide_store'])->name('admin.slide.store');
        Route::get('/admin/slide/edit/{id}', [AdminController::class, 'slide_edit'])->name('admin.slide.edit');
        Route::put('/admin/slide/update', [AdminController::class, 'slide_update'])->name('admin.slide.update');
        Route::delete('admin/slide/delete/{id}', [AdminController::class, 'slide_delete'])->name('admin.slide.delete');

        /* users */
        Route::get('/admin/users',[AdminController::class,'users'])->name('admin.users');
        Route::delete('/admin/user/delete/{id}', [AdminController::class, 'user_delete'])->name('admin.user.delete');
    });
