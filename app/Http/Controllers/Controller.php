<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="OnDemand API Documentation",
 *     version="1.0.0",
 *     description="API documentation for the OnDemand Platform"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="passport",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 *
 * @OA\Post(
 *     path="/api/otp-login",
 *     tags={"Authentication"},
 *     summary="Send OTP to user or provider",
 *     description="Creates a user if phone+role does not exist and sends OTP.",
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="phone", type="string", example="9876543210"),
 *             @OA\Property(property="type", type="string", enum={"user","provider"}, example="user")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="OTP sent successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="OTP sent successfully."),
 *             @OA\Property(property="otp", type="string", example="123456")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=0),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Something went wrong while sending OTP.")
 *         )
 *     )
 * ),
 * * @OA\Post(
 *     path="/api/verify-otp",
 *     tags={"Authentication"},
 *     summary="Verify OTP for user or provider login",
 *     description="Verify sent OTP and return access token",
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="phone", type="string", example="9876543210"),
 *             @OA\Property(property="otp", type="string", example="123456"),
 *             @OA\Property(property="type", type="string", enum={"user","provider"}, example="user")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="OTP verified successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="OTP verified successfully."),
 *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGci..."),
 *             @OA\Property(property="user", type="object")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="User not found.")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=400,
 *         description="Invalid or expired OTP",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Invalid or expired OTP.")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Something went wrong during OTP verification.")
 *         )
 *     )
 * ),
 * * @OA\Post(
 *     path="/api/register",
 *     tags={"Authentication"},
 *     summary="Register a new user or provider",
 *     description="Registers a user or provider and optionally uploads KYC document",
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"name","phone","gender","role"},
 *                 @OA\Property(property="name", type="string", example="John Doe"),
 *                 @OA\Property(property="phone", type="string", example="9876543210"),
 *                 @OA\Property(property="email", type="string", example="john@example.com"),
 *                 @OA\Property(property="gender", type="string", enum={"male","female"}, example="male"),
 *                 @OA\Property(property="role", type="string", enum={"user","provider"}, example="provider"),
 *                 @OA\Property(property="category_id", type="integer", example=1),
 *                 @OA\Property(
 *                     property="kyc_document",
 *                     type="string",
 *                     format="binary",
 *                     description="KYC document file (pdf, jpg, png, jpeg)"
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Registration successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="User registered successfully."),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="user", type="object")
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Something went wrong.")
 *         )
 *     )
 * ),
 * * @OA\Get(
 *     path="/api/profile",
 *     tags={"Profile"},
 *     summary="Fetch logged-in user profile",
 *     description="Returns the profile details of the authenticated user",
 *     security={{"passport": {}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Profile fetched successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Profile fetched successfully."),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="John Doe"),
 *                 @OA\Property(property="phone", type="string", example="9876543210"),
 *                 @OA\Property(property="role", type="string", example="user"),
 *                 @OA\Property(property="profile_image", type="string", example="https://example.com/uploads/profile/abc.jpg")
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Unauthenticated.")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Failed to fetch profile.")
 *         )
 *     )
 * ),
* @OA\Post(
 *     path="/api/profile/update-image",
 *     summary="Update profile image of logged-in user",
 *     tags={"Profile"},
 *     security={{"passport":{}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"profile_image"},
 *                 @OA\Property(
 *                     property="profile_image",
 *                     type="string",
 *                     format="binary",
 *                     description="Profile image file (jpeg, png, jpg, webp)"
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Profile image updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Profile image updated successfully."),
 *             @OA\Property(property="profile_image", type="string", example="https://yourdomain.com/storage/profile/abc123.jpg")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=400,
 *         description="No image file provided"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation errors"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * ),
 * @OA\Post(
 *     path="/api/user/address",
 *     summary="Add new address for authenticated user",
 *     description="Allows only users (role=user) to add an address",
 *     tags={"Address"},
 *     security={{"passport":{}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="apartment_number", type="string", example="A-203"),
 *             @OA\Property(property="street_address", type="string", example="123 MG Road"),
 *             @OA\Property(property="pin_code", type="string", example="560001"),
 *             @OA\Property(property="state", type="string", example="Karnataka"),
 *             @OA\Property(property="city", type="string", example="Bangalore"),
 *             @OA\Property(property="country", type="string", example="India"),
 *             @OA\Property(property="contact_phone_number", type="string", example="9876543210"),
 *             @OA\Property(property="is_active", type="boolean", example=true)
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Address added successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Address added successfully."),
 *             @OA\Property(property="data", type="object")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Only users can add addresses",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Only users can add addresses.")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Validation failed",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Failed to add address.")
 *         )
 *     )
 * ),
  * @OA\Get(
 *     path="/api/user/addresses",
 *     summary="Get logged-in user's addresses",
 *     tags={"Address"},
 *     security={{"passport":{}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Addresses fetched successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="addresses",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="user_id", type="integer", example=5),
 *                     @OA\Property(property="street", type="string", example="123 Main St"),
 *                     @OA\Property(property="city", type="string", example="New York"),
 *                     @OA\Property(property="state", type="string", example="NY"),
 *                     @OA\Property(property="zipcode", type="string", example="10001"),
 *                     @OA\Property(property="created_at", type="string", example="2025-11-26 14:00:00"),
 *                     @OA\Property(property="updated_at", type="string", example="2025-11-26 14:00:00")
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Unauthorized role",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Only users can fetch addresses.")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Exception message here")
 *         )
 *     )
 * ),

 * * @OA\Post(
 *     path="/api/logout",
 *     summary="Logout the authenticated user",
 *     description="Revokes the current access token of the logged-in user",
 *     tags={"Authentication"},
 *     security={{"passport":{}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Successfully logged out",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Logged out successfully")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="User not authenticated",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="User not authenticated")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Something went wrong during logout"),
 *             @OA\Property(property="error", type="string", example="Optional error message")
 *         )
 *     )
 * ),
 *  * @OA\Get(
 *     path="/api/service-location",
 *     summary="Get provider service location",
 *     tags={"Provider"},
 *     security={{"passport": {}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Service location data"
 *     )
 * ),
 *  * @OA\Get(
 *     path="/api/categories",
 *     summary="Get paginated list of categories",
 *     tags={"Categories"},
 *     security={{"passport":{}}},
 *
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         required=false,
 *         description="Number of items per page (default: 10)",
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Successful response",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 description="Laravel pagination object",
 *                 @OA\Property(property="current_page", type="integer", example=1),
 *                 @OA\Property(property="data", type="array",
 *                     @OA\Items(
 *                         @OA\Property(property="id", type="integer", example=1),
 *                         @OA\Property(property="name", type="string", example="Electronics"),
 *                         @OA\Property(property="image", type="string", example="https://domain.com/storage/category/img.jpg")
 *                     )
 *                 ),
 *                 @OA\Property(property="per_page", type="integer", example=10),
 *                 @OA\Property(property="total", type="integer", example=50)
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * ),
 * * @OA\Get(
 *     path="/api/sub-category-items",
 *     summary="Get paginated list of sub-category items",
 *     tags={"Sub Category Items"},
 *     security={{"passport":{}}},
 *
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         required=false,
 *         description="Number of items per page (default: 10)",
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Successful response",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 description="Pagination result",
 *                 @OA\Property(property="current_page", type="integer", example=1),
 *                 @OA\Property(property="per_page", type="integer", example=10),
 *                 @OA\Property(property="total", type="integer", example=42),
 *                 @OA\Property(
 *                     property="data",
 *                     type="array",
 *                     @OA\Items(
 *                         @OA\Property(property="id", type="integer", example=1),
 *                         @OA\Property(property="name", type="string", example="AC Repair"),
 *                         @OA\Property(property="price", type="number", example=299),
 *                         @OA\Property(property="image", type="string", example="https://yourdomain.com/storage/sub_items/item.png"),
 *
 *                         @OA\Property(
 *                             property="category",
 *                             type="object",
 *                             description="Related Category",
 *                             @OA\Property(property="id", type="integer", example=3),
 *                             @OA\Property(property="name", type="string", example="Home Services"),
 *                             @OA\Property(property="image", type="string", example="https://yourdomain.com/storage/category/cat.jpg")
 *                         )
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * ),
 * * @OA\Get(
 *     path="/api/services",
 *     summary="Get paginated list of services",
 *     tags={"Services"},
 *     security={{"passport":{}}},
 *
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         required=false,
 *         description="Number of items per page (default: 10)",
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Successful response",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 description="Paginated services data",
 *                 @OA\Property(property="current_page", type="integer", example=1),
 *                 @OA\Property(property="per_page", type="integer", example=10),
 *                 @OA\Property(property="total", type="integer", example=50),
 *                 @OA\Property(
 *                     property="data",
 *                     type="array",
 *                     @OA\Items(
 *                         @OA\Property(property="id", type="integer", example=1),
 *                         @OA\Property(property="name", type="string", example="Plumbing Service"),
 *                         @OA\Property(property="description", type="string", example="Fix all types of plumbing issues"),
 *                         @OA\Property(property="image", type="string", example="https://yourdomain.com/storage/services/service.jpg"),
 *                         @OA\Property(property="price", type="number", example=199.99),
 *                         @OA\Property(property="status", type="integer", example=1)
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * ),
 * * @OA\Post(
 *     path="/api/cart/add",
 *     summary="Add a service item to the user's cart",
 *     tags={"Cart"},
 *     security={{"passport":{}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"services_id"},
 *             @OA\Property(
 *                 property="services_id",
 *                 type="integer",
 *                 example=5,
 *                 description="ID of the service to add to cart"
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Item added to cart successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Item added to cart successfully")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Something went wrong")
 *         )
 *     )
 * ),
 * * @OA\Post(
 *     path="/api/cart/increase",
 *     summary="Increase quantity of an item in the cart",
 *     tags={"Cart"},
 *     security={{"passport":{}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"cart_item_id"},
 *             @OA\Property(
 *                 property="cart_item_id",
 *                 type="integer",
 *                 example=12,
 *                 description="Cart item ID"
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Item quantity increased",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="item", type="object")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Unauthorized — item does not belong to user"
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * ),
 *
 * * @OA\Post(
 *     path="/api/cart/decrease",
 *     summary="Decrease cart item quantity",
 *     tags={"Cart"},
 *     security={{"passport": {}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"cart_item_id"},
 *             @OA\Property(property="cart_item_id", type="integer", example=12)
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Item quantity decreased successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="item", type="object")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Validation Error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="errors", type="object")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Server Error"
 *     )
 * ),
 *  * @OA\Get(
 *     path="/api/cart/view",
 *     summary="View user's cart",
 *     tags={"Cart"},
 *     security={{"passport": {}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Cart data fetched successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="cart",
 *                 type="object",
 *                 nullable=true,
 *                 description="Full cart details with items"
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Server Error"
 *     )
 * ),
 * @OA\Post(
 *     path="/api/service-slot",
 *     summary="Create service booking(s) from cart",
 *     tags={"User Booking"},
 *     security={{"passport": {}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"cart_id", "scheduled_date", "scheduled_time", "address_id"},
 *             @OA\Property(
 *                 property="cart_id",
 *                 type="integer",
 *                 example=5,
 *                 description="Cart ID belonging to the logged-in user"
 *             ),
 *             @OA\Property(
 *                 property="scheduled_date",
 *                 type="string",
 *                 format="date",
 *                 example="2025-12-01",
 *                 description="Booking service date (Y-m-d)"
 *             ),
 *             @OA\Property(
 *                 property="scheduled_time",
 *                 type="string",
 *                 example="14:30",
 *                 description="Time in HH:MM format (24-hour)"
 *             ),
 *             @OA\Property(
 *                 property="address_id",
 *                 type="integer",
 *                 example=3,
 *                 description="User address ID"
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Service scheduled successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Service scheduled successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=12),
 *                     @OA\Property(property="user_id", type="integer", example=10),
 *                     @OA\Property(property="service_id", type="integer", example=4),
 *                     @OA\Property(property="subtotal", type="number", example=299.00),
 *                     @OA\Property(property="tax", type="number", example=53.82),
 *                     @OA\Property(property="total_amount", type="number", example=352.82),
 *                     @OA\Property(property="status", type="string", example="pending")
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=404,
 *         description="Cart not found or empty"
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * ),
 * * @OA\Get(
 *     path="/api/provider/new-jobs",
 *     summary="Fetch pending bookings for provider",
 *     tags={"Provider Bookings"},
 *     security={{"passport": {}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Bookings fetched successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Bookings fetched successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=12),
 *                     @OA\Property(property="user_id", type="integer", example=10),
 *                     @OA\Property(property="service_id", type="integer", example=4),
 *                     @OA\Property(property="subtotal", type="number", example=299.00),
 *                     @OA\Property(property="tax", type="number", example=53.82),
 *                     @OA\Property(property="total_amount", type="number", example=352.82),
 *                     @OA\Property(property="status", type="string", example="pending"),
 *                     @OA\Property(property="scheduled_date", type="string", example="2025-12-01"),
 *                     @OA\Property(property="scheduled_time", type="string", example="14:30:00"),
 *                     @OA\Property(property="address_id", type="integer", example=3),
 *                     @OA\Property(property="provider_id", type="integer", example=null)
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=404,
 *         description="No pending bookings found",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="No pending bookings found")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Internal server error")
 *         )
 *     )
 * ),
 * * @OA\Post(
 *     path="/api/provider/bookings/{id}/status",
 *     summary="Change booking status by provider",
 *     tags={"Provider Bookings"},
 *     security={{"passport": {}}},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the booking to update",
 *         @OA\Schema(type="integer", example=12)
 *     ),
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="status",
 *                 type="string",
 *                 enum={"accepted","rejected","completed"},
 *                 example="accepted"
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Booking status updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Booking accepted")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Unauthorized user",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthorized")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=404,
 *         description="Booking not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Booking not found")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     )
 * ),
 * * @OA\Schema(
 *     schema="Booking",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=5),
 *     @OA\Property(property="provider_id", type="integer", example=2),
 *     @OA\Property(property="status", type="string", example="accepted"),
 *     @OA\Property(property="scheduled_date", type="string", example="2025-11-26"),
 *     @OA\Property(property="scheduled_time", type="string", example="10:30:00"),
 *     @OA\Property(property="subtotal", type="number", format="float", example=500.00),
 *     @OA\Property(property="tax", type="number", format="float", example=90.00),
 *     @OA\Property(property="total_amount", type="number", format="float", example=590.00),
 *     @OA\Property(
 *         property="bookingItems",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="services_id", type="integer", example=3),
 *             @OA\Property(property="name", type="string", example="Cleaning Service"),
 *             @OA\Property(property="price", type="number", format="float", example=200.00),
 *             @OA\Property(property="qty", type="integer", example=2),
 *             @OA\Property(property="subtotal", type="number", format="float", example=400.00)
 *         )
 *     ),
 *     @OA\Property(
 *         property="user",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=5),
 *         @OA\Property(property="name", type="string", example="John Doe"),
 *         @OA\Property(property="phone", type="string", example="9876543210")
 *     ),
 *     @OA\Property(
 *         property="address",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=10),
 *         @OA\Property(property="street_address", type="string", example="123 MG Road"),
 *         @OA\Property(property="city", type="string", example="Bangalore"),
 *         @OA\Property(property="state", type="string", example="Karnataka"),
 *         @OA\Property(property="pin_code", type="string", example="560001")
 *     )
 * ),
 *  * @OA\Get(
 *     path="/api/provider/job-list",
 *     summary="Get current and completed jobs for the logged-in provider",
 *     tags={"Provider Bookings"},
 *     security={{"passport":{}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Jobs fetched successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(
 *                     property="current_jobs",
 *                     type="array",
 *                     @OA\Items(ref="#/components/schemas/Booking")
 *                 ),
 *                 @OA\Property(
 *                     property="completed_jobs",
 *                     type="array",
 *                     @OA\Items(ref="#/components/schemas/Booking")
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Unauthorized — user is not a provider",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Unauthorized")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Something went wrong")
 *         )
 *     )
 * ),
 * * @OA\Post(
 *     path="/api/provider/rate-user",
 *     summary="Provider rates a user",
 *     tags={"Provider Reviews"},
 *     security={{"passport":{}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id","booking_id","rating"},
 *             @OA\Property(property="user_id", type="integer", example=5, description="ID of the user to rate"),
 *             @OA\Property(property="booking_id", type="integer", example=12, description="ID of the booking associated with the review"),
 *             @OA\Property(property="rating", type="number", format="float", example=4.5, description="Rating value between 1 and 5"),
 *             @OA\Property(property="comment", type="string", example="Very cooperative user", description="Optional comment about the user")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Review submitted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Review submitted successfully")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Unauthorized — user is not a provider",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthorized")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=400,
 *         description="Exception or server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Error message")
 *         )
 *     )
 * ),
 * * @OA\Get(
 *     path="/api/provider/service-parts",
 *     summary="Get all service parts grouped by category",
 *     tags={"Cost Estimation"},
 *     security={{"passport":{}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Service parts fetched successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="parts",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="category_id", type="integer", example=1),
 *                     @OA\Property(property="category_name", type="string", example="Electronics"),
 *                     @OA\Property(
 *                         property="items",
 *                         type="array",
 *                         @OA\Items(
 *                             type="object",
 *                             @OA\Property(property="id", type="integer", example=10),
 *                             @OA\Property(property="part_name", type="string", example="AC Filter"),
 *                             @OA\Property(property="base_cost", type="number", format="float", example=299.99),
 *                             @OA\Property(property="category_id", type="integer", example=1),
 *                             @OA\Property(property="description", type="string", example="High quality AC filter")
 *                         )
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Error fetching service parts",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="errors", type="string", example="Error message")
 *         )
 *     )
 * ),
 * * @OA\Post(
 *     path="/api/provider/cost-estimations",
 *     summary="Create a cost estimation for a booking",
 *     tags={"Cost Estimation"},
 *     security={{"passport":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="booking_id", type="integer", example=123),
 *             @OA\Property(
 *                 property="items",
 *                 type="array",
 *                 minItems=1,
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="service_part_id", type="integer", nullable=true, example=10),
 *                     @OA\Property(property="part_name", type="string", example="AC Filter"),
 *                     @OA\Property(property="base_price", type="number", format="float", nullable=true, example=200.5),
 *                     @OA\Property(property="provider_price", type="number", format="float", nullable=true, example=250),
 *                     @OA\Property(property="qty", type="integer", example=2)
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Cost estimation created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Cost estimation created successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="booking_id", type="integer", example=123),
 *                 @OA\Property(property="provider_id", type="integer", example=5),
 *                 @OA\Property(property="total_amount", type="number", format="float", example=500),
 *                 @OA\Property(property="status", type="string", example="sent"),
 *                 @OA\Property(
 *                     property="items",
 *                     type="array",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(property="id", type="integer", example=1),
 *                         @OA\Property(property="service_part_id", type="integer", nullable=true, example=10),
 *                         @OA\Property(property="part_name", type="string", example="AC Filter"),
 *                         @OA\Property(property="base_price", type="number", format="float", example=200.5),
 *                         @OA\Property(property="provider_price", type="number", format="float", example=250),
 *                         @OA\Property(property="qty", type="integer", example=2),
 *                         @OA\Property(property="subtotal", type="number", format="float", example=500)
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Failed to create cost estimation"),
 *             @OA\Property(property="error", type="string", example="Exception message here")
 *         )
 *     )
 * ),
 * * @OA\Post(
 *     path="/api/cost-estimations/update-status",
 *     summary="Update the status of a cost estimation",
 *     tags={"Cost Estimation"},
 *     security={{"passport":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="estimation_id", type="integer", example=1),
 *             @OA\Property(property="status", type="string", enum={"accepted","rejected"}, example="accepted")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Status updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Status updated successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="booking_id", type="integer", example=123),
 *                 @OA\Property(property="provider_id", type="integer", example=5),
 *                 @OA\Property(property="total_amount", type="number", format="float", example=500),
 *                 @OA\Property(property="status", type="string", example="accepted")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Failed to update the status from user side"),
 *             @OA\Property(property="error", type="string", example="Exception message here")
 *         )
 *     )
 * ),
 * * @OA\Post(
 *     path="/api/user/rate-provider",
 *     summary="Rate a service provider",
 *     tags={"Reviews"},
 *     security={{"passport":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="provider_id", type="integer", example=5, description="ID of the provider to rate"),
 *             @OA\Property(property="booking_id", type="integer", example=123, description="Booking ID associated with the provider"),
 *             @OA\Property(property="rating", type="number", format="float", minimum=1, maximum=5, example=4.5, description="Rating value"),
 *             @OA\Property(property="comment", type="string", example="Great service!", description="Optional comment")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review submitted successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Review submitted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation errors",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Unauthorized access",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Unauthorized")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error occurred",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Exception message here")
 *         )
 *     )
 * ),
 * * @OA\Get(
 *     path="/api/wallet/balance",
 *     summary="Get wallet balance",
 *     tags={"Wallet"},
 *     security={{"passport":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Wallet balance retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="balance", type="number", format="float", example=150.75)
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - User not authenticated",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Unauthenticated.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Internal server error")
 *         )
 *     )
 * ),
 *  * @OA\Get(
 *     path="/api/wallet/transactions",
 *     summary="Get wallet transactions",
 *     tags={"Wallet"},
 *     security={{"passport":{}}},
 *     @OA\Parameter(
 *         name="type",
 *         in="query",
 *         description="Filter transactions by type: cash_in or cash_out",
 *         required=false,
 *         @OA\Schema(type="string", enum={"cash_in","cash_out"})
 *     ),
 *     @OA\Parameter(
 *         name="range",
 *         in="query",
 *         description="Filter transactions by date range, e.g., 30days",
 *         required=false,
 *         @OA\Schema(type="string", example="30days")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Wallet transactions retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="transactions",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="user_id", type="integer", example=5),
 *                     @OA\Property(property="type", type="string", example="cash_in"),
 *                     @OA\Property(property="amount", type="number", format="float", example=150.75),
 *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-11-26T12:00:00Z")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - User not authenticated",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Unauthenticated.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Internal server error")
 *         )
 *     )
 * ),
 *  * @OA\Post(
 *     path="/api/wallet/add",
 *     summary="Add money to wallet",
 *     tags={"Wallet"},
 *     security={{"passport":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="amount", type="number", format="float", example=500, description="Amount to add to wallet"),
 *             @OA\Property(property="description", type="string", example="Top-up for service payment", description="Optional description for the transaction")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Wallet topped-up successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Wallet topped-up successfully"),
 *             @OA\Property(property="balance", type="number", format="float", example=1500.50)
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation errors",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - User not authenticated",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Unauthenticated.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="error", type="string", example="Internal server error")
 *         )
 *     )
 * ),
 * * @OA\Post(
 *     path="/api/wallet/withdraw",
 *     summary="Request wallet withdrawal",
 *     tags={"Wallet"},
 *     security={{"passport":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="amount", type="number", format="float", example=500, description="Amount to withdraw from wallet")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Withdrawal requested successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Withdrawal requested successfully"),
 *             @OA\Property(property="balance", type="number", format="float", example=1000.50)
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Insufficient balance or bad request",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Insufficient wallet balance")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation errors",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - User not authenticated",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Unauthenticated.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="error", type="string", example="Internal server error")
 *         )
 *     )
 * ),
 * * @OA\Get(
 *     path="/api/conversations",
 *     summary="Get all conversations for the authenticated user",
 *     tags={"Messages"},
 *     security={{"passport":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="List of conversations",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="conversations",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="job_id", type="integer", example=123),
 *                     @OA\Property(
 *                         property="messages",
 *                         type="array",
 *                         @OA\Items(
 *                             type="object",
 *                             @OA\Property(property="booking_id", type="integer", example=123),
 *                             @OA\Property(property="sender_id", type="integer", example=5),
 *                             @OA\Property(property="receiver_id", type="integer", example=10),
 *                             @OA\Property(property="text", type="string", example="Hello, your service is on the way!"),
 *                             @OA\Property(property="created_at", type="string", format="date-time", example="2025-11-26T12:34:56Z")
 *                         )
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - User not authenticated",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Unauthenticated.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="error", type="string", example="Internal server error")
 *         )
 *     )
 * ),
 *  * @OA\Get(
 *     path="/api/conversations/{bookingId}",
 *     summary="Get messages for a specific booking",
 *     tags={"Messages"},
 *     security={{"passport":{}}},
 *     @OA\Parameter(
 *         name="bookingId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="Booking ID to fetch messages for"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Messages fetched successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="booking_id", type="integer", example=123),
 *             @OA\Property(
 *                 property="messages",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="text", type="string", example="Hello"),
 *                     @OA\Property(property="side", type="string", example="provider"),
 *                     @OA\Property(property="sender_id", type="integer", example=5),
 *                     @OA\Property(property="receiver_id", type="integer", example=10),
 *                     @OA\Property(property="time", type="string", example="02:30 PM"),
 *                     @OA\Property(property="date", type="string", example="26-11-2025")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Booking not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Booking not found")
 *         )
 *     )
 * ),
 *  * @OA\Post(
 *     path="/api/conversations/send",
 *     summary="Send a message in a booking conversation",
 *     tags={"Messages"},
 *     security={{"passport":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="booking_id", type="integer", example=123, description="ID of the booking"),
 *             @OA\Property(property="receiver_id", type="integer", example=5, description="ID of the message receiver"),
 *             @OA\Property(property="message", type="string", example="Hello, your service is confirmed!", description="Message content")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Message sent successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Message sent successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="booking_id", type="integer", example=123),
 *                 @OA\Property(property="sender_id", type="integer", example=10),
 *                 @OA\Property(property="receiver_id", type="integer", example=5),
 *                 @OA\Property(property="message", type="string", example="Hello, your service is confirmed!"),
 *                 @OA\Property(property="created_at", type="string", example="2025-11-26T14:30:00Z")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation errors",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Booking not found or not authorized",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Booking not found")
 *         )
 *     )
 * )


 *
 *
 */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
