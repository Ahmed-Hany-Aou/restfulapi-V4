<?php
namespace App\Http\Controllers\Buyer;
use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
class BuyerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $products = $buyer->transactions()->with('product')
            ->get()
            ->pluck('product')
            ->unique('id') // Ensure only unique products by their ID// not included in course i noticed the wrong data and fixed it
        ->values(); // Re-index the collection//ensures that the indices are sequential in the final collection

        return $this->showAll($products);
    }
}