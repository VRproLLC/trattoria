<?php

namespace App\Http\Controllers;

use App\Models\CategoryPay;
use App\Models\IikoAccount;
use App\Models\Organization;
use App\Models\PaymentType;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\Translations\CategoryTranslation;
use App\Models\Translations\ProductTranslation;
use App\Services\Iiko\IikoApi;
use App\Services\Iiko\IikoCardApi;
use Intervention\Image\Facades\Image;

class SyncController extends Controller
{

    public function sync()
    {
        $accounts = IikoAccount::where('is_iiko', 1)->get();

        foreach ($accounts as $account) {
            $this->organizations($account->id, $account->login, $account->password);
        }

        $organizations = Organization::whereHas('account', function ($q) {
            $q->where('is_iiko', 1);
        })->get();

        foreach ($organizations as $organization) {
            $this->productCategories($organization);
        }

         foreach ($organizations as $organization) {
             $this->category($organization);
         }

         foreach ($organizations as $organization) {
             $this->product($organization);
         }

         foreach ($organizations as $organization) {
             $this->payment_type($organization);
         }

         foreach ($organizations as $organization) {
             $this->stop_lists($organization);
        }
    }

    public function organizations($account_id, $login, $password)
    {
        $iiko = new IikoApi($login, $password, null);

        foreach ($iiko->getOrganizationList()->organizations as $iiko_organization) {
            $organization = Organization::where('iiko_id', $iiko_organization->id)->first();

            if (!$organization) {
                $organization = new Organization();
            }
            $organization->ikko_account_id = $account_id;
            $organization->iiko_id = $iiko_organization->id;
//            $organization->isActive = 0;
            $organization->latitude = $iiko_organization->latitude ?? null;
            $organization->longitude = $iiko_organization->longitude?? null;
            $organization->fullName = $iiko_organization->name;
            $organization->description = $iiko_organization->description?? null;
            $organization->address = $iiko_organization->restaurantAddress?? null;
            $organization->name = $iiko_organization->name;
            $organization->organizationType = $iiko_organization->organizationType?? null;
            $organization->timezone = $iiko_organization->timezone?? null;
            $organization->workTime = $iiko_organization->workTime?? null;
            $organization->email = $iiko_organization->contact->email?? null;
            $organization->location = $iiko_organization->contact->location?? null;
            $organization->phone = $iiko_organization->contact->phone?? null;
            $organization->save();
        }
        return true;
    }


    public function productCategories($organization)
    {
        $iiko = new IikoApi($organization->account->login, $organization->account->password, $organization->iiko_id);
        $nomenclature = $iiko->getProducts();


        dd($nomenclature);

        foreach ($nomenclature->productCategories as $iiko_category) {
            CategoryPay::updateOrCreate([
                'organization_id' => $organization->id,
                'iiko_id' => $iiko_category->id,
            ],[
                'isDeleted' => $iiko_category->isDeleted,
                'name' => $iiko_category->name,
            ]);
        }
    }

    public function category($organization)
    {
        $iiko = new IikoApi($organization->account->login, $organization->account->password, $organization->iiko_id);

        $nomenclature = $iiko->getProducts();

        foreach ($nomenclature->groups as $iiko_category) {
            $new_category = Category::where('organization_id', $organization->id)->where('iiko_id', $iiko_category->id)->first();

            if (!$new_category) {
                $new_category = new Category();
            }

            $new_category->organization_id = $organization->id;
            $new_category->iiko_id = $iiko_category->id;
            $new_category->isDeleted = $iiko_category->isDeleted;
            $new_category->isIncludedInMenu = $iiko_category->isIncludedInMenu;
            $new_category->sort = $iiko_category->order;
//            $new_category->parentGroup = $iiko_category->parentGroup;
            $new_category->save();

            $new_category_translation = CategoryTranslation::where('language_id', 1)->where('category_id', $new_category->id)->first();

            if (!$new_category_translation) {
                $new_category_translation = new CategoryTranslation();
            }
            $new_category_translation->category_id = $new_category->id;
            $new_category_translation->language_id = 1;
            $new_category_translation->name = $iiko_category->name;
            $new_category_translation->description = $iiko_category->description;
            $new_category_translation->save();
        }

        return true;
    }

    public function product($organization)
    {
        $iiko = new IikoApi($organization->account->login, $organization->account->password, $organization->iiko_id);
        $iiko_products = $iiko->getProducts()->products;


        foreach ($iiko_products as $iiko_product) {
            $product = Product::where('organization_id', $organization->id)->where('iiko_id', $iiko_product->id)->first();
            if (!$product) {
                $product = new Product();
            }

            $category = Category::where('iiko_id', $iiko_product->parentGroup)->where('organization_id', $organization->id)->first();
            $product->organization_id = $organization->id;
            $product->category_id = $category ? $category->id : 0;
            $product->iiko_id = $iiko_product->id;
            $product->isIncludedInMenu = false;
            $product->isDeleted = $iiko_product->isDeleted;
            $product->code = $iiko_product->code;
            $product->price = $iiko_product->sizePrices[0]->price->currentPrice;
            $product->parentGroup = $iiko_product->parentGroup;
            $product->energyAmount = $iiko_product->energyAmount;
            $product->energyFullAmount = $iiko_product->energyFullAmount;
            $product->fatAmount = $iiko_product->fatAmount;
            $product->fatFullAmount = $iiko_product->fatFullAmount;
            $product->fiberAmount = 0;
            $product->fiberFullAmount = 0;
            $product->isIncludedInMenu = 1;
            $product->weight = $iiko_product->weight ?? null;
            $product->sort = $iiko_product->order;


            if($iiko_product->productCategoryId !== null){
                $payCategory = CategoryPay::query()
                    ->where('iiko_id', $iiko_product->productCategoryId)
                    ->first();

                if(isset($payCategory->id)){
                    $product->categoryPayId = $payCategory->id;
                }
            }

            if (is_array($iiko_product->imageLinks) && count($iiko_product->imageLinks) > 0) {
                $url = $iiko_product->imageLinks[0];

                $image_name = time() . '-' . rand(10, 999);
//                file_put_contents('images/' . $image_name . '.jpg', file_get_contents($url));
//                $product->image = ('images/' . $image_name . '.jpg');


                try {
                    $imgage = Image::make(file_get_contents($url));

                    if ($imgage->getWidth() > 500) {
                        $base_width = $imgage->getWidth();
                        $base_height = $imgage->getHeight();
                        $new_height = 500 / ($base_width / $base_height);
                        $imgage->resize(500, $new_height);
                    }
                    $product->image = 'images/' . $image_name . '.jpg';
                    $imgage->save(public_path('images/' . $image_name . '.jpg'));
                } catch (\ErrorException $exception) {
                    \Log::error('Iiko image ' . $exception->getMessage());
                }
            }
            $product->save();

            $product_translation = ProductTranslation::where('language_id', 1)->where('product_id', $product->id)->first();

            if (!$product_translation) {
                $product_translation = new ProductTranslation();
            }

            $product_translation->product_id = $product->id;
            $product_translation->language_id = 1;
            $product_translation->name = $iiko_product->name;
            $product_translation->description = $iiko_product->description;
            $product_translation->save();
        }
    }

    public function payment_type($organization)
    {
        $iiko = new IikoCardApi($organization->account->login, $organization->account->password, $organization->iiko_id);
        $iiko_payment_types = $iiko->getPaymentTypes();

        $existing_ids = [];

        foreach ($iiko_payment_types->paymentTypes as $iiko_payment_type) {
            $existing_ids[] = $iiko_payment_type->id;
            $payment_type = PaymentType::where('organization_id', $organization->id)->where('iiko_id', $iiko_payment_type->id)->first();

            if (!$payment_type) {
                $payment_type = new PaymentType();
            }

            $payment_type->organization_id = $organization->id;
            $payment_type->iiko_id = $iiko_payment_type->id;
            $payment_type->code = $iiko_payment_type->code;
            $payment_type->name = $iiko_payment_type->name;
            $payment_type->comment = $iiko_payment_type->comment;
            $payment_type->combinable = $iiko_payment_type->combinable;
            $payment_type->applicableMarketingCampaigns = json_encode($iiko_payment_type->applicableMarketingCampaigns);
            $payment_type->isDeleted = $iiko_payment_type->isDeleted;

            $payment_type->save();


        }

        PaymentType::where('organization_id', $organization->id)->whereNotIn('iiko_id', $existing_ids)->delete();
    }

    public function stop_lists($organization)
    {
        $iiko = new IikoCardApi($organization->account->login, $organization->account->password, $organization->iiko_id);
        $stop_lists = $iiko->getStopLists();
        $result = [];

        $stop_lists = $stop_lists;

        if ($stop_lists != false) {

            foreach ($stop_lists->terminalGroupStopLists as $item) {
                foreach ($item->items as $product) {
                    foreach ($product->items as $prod) {
                        $result[$prod->productId] = $prod->balance;
                    }
                }
            }
        }
        return $result;
    }
}
