<?php

namespace App;

use App\Product;

class Seller extends User
{
    // The Seller model extends the User model and shares the same 'users' table.
// By default, Laravel assumes the table name is the plural form of the model class (i.e., 'sellers').
// Since there is no 'sellers' table in our database, we need to explicitly specify
// that the Seller model should use the 'users' table with `protected $table = 'users';`.
//
// My instructor didn't need this because they were using a virtual machine (e.g., Homestead) 
// with specific configurations that allowed Laravel to correctly infer relationships 
// without explicitly setting the table name. This likely worked due to their pre-configured
// environment or Laravel's behavior in their version/setup.
//
// Adding this ensures that the code works in any environment, making it more portable and 
// preventing Laravel from looking for a non-existent 'sellers' table.
    protected $table = 'users';///read above comment
    public function products()
    {
    	return $this->hasMany(Product::class);
    }
}