<?php

namespace App\Console\Commands;

use App\SaleDetail;
use App\TopCategories;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TopCategoriesDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'top_categories:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Top Categories Dump';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = date('Y-m-d',strtotime("-1 days"));
        $sale_details = SaleDetail::select('id','product_id','quantity','total')->with('product')->whereDate('created_at', '=',$date)->get();
        $top_categories = [];
        if (!empty($sale_details)) {
            foreach ($sale_details as $sale_detail){
                if ($sale_detail->product->product_category_id) {
                    if (empty($top_categories[$sale_detail->product->product_category_id]['product_quantity'])) {
                        $top_categories[$sale_detail->product->product_category_id]['product_quantity'] = 0;
                    }
                    if (empty($top_categories[$sale_detail->product->product_category_id]['amount'])) {
                        $top_categories[$sale_detail->product->product_category_id]['amount'] = 0;
                    }
                    $top_categories[$sale_detail->product->product_category_id]['category_id'] = $sale_detail->product->product_category_id;
                    $top_categories[$sale_detail->product->product_category_id]['product_quantity'] += $sale_detail->quantity;
                    $top_categories[$sale_detail->product->product_category_id]['amount'] += $sale_detail->total;
                }
            }
        }
        DB::beginTransaction();
        try {
            if(!empty($top_categories)){
                foreach ($top_categories as $top_category){
                    TopCategories::create([
                       'category_id' => $top_category['category_id'],
                       'product_quantity' => $top_category['product_quantity'],
                       'amount' => $top_category['amount'],
                       'date' => $date,
                    ]);
                }
            }
        }catch (\Exception $exception){
            DB::rollBack();
        }
      DB::commit();
    }
}
