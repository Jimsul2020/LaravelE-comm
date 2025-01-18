<?php

use App\Mail\OrderEmail;
use App\Models\Category;
use App\Models\LGA;
use App\Models\Order;
use App\Models\Page;
use App\Models\ProductImage;
use App\Models\State;
use Illuminate\Support\Facades\Mail;

function getCategories()
{
 return Category::orderBy('name', 'ASC')
  ->with('sub_Category')
  ->orderBy('id', 'DESC')
  ->where('status', 1)
  ->where('showHome', 'Yes')
  ->get();
}



function getProductImage($productId){
  return ProductImage::where('product_id', $productId)->first();
}


function orderEmail($orderId, $userType="customer"){
  $order = Order::where('id', $orderId)->with('items')->first();

  if($userType == 'customer'){
    $subject = 'Thanks for your order';
    $email = $order->email;
  }else {
    $subject = 'You have received an order';
    $email = env('ADMIN_EMAIL');
  }


  $mailData = [
    'subject' => $subject,
    'order' => $order,
    'userType' => $userType
  ];

  Mail::to($email)->send(new OrderEmail($mailData));

  // dd($order);
}

function getStateInfo($id){
  return State::where('id', $id)->first();
}
function getLgaInfo($id){
  return LGA::where('id', $id)->first();
}

function staticPages(){
  $pages = Page::orderBy('name', 'ASC')->get();
  return $pages;
}