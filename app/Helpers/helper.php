<?php

use App\Models\Category;

function getCategories()
{
 return Category::orderBy('name', 'ASC')
  ->with('sub_Category')
  ->orderBy('id', 'DESC')
  ->where('status', 1)
  ->where('showHome', 'Yes')
  ->get();
}
