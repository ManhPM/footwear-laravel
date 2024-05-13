<?php

namespace Database\Seeders;

use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['name' => 'Nike Air Max 90', 'description' => 'Giày thể thao phong cách, màu đen trắng.', 'price' => '500000', 'image' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/9f684ace-3163-4227-8f85-2d2a067dd4f5/air-max-90-gore-tex-shoes-K3mBRb.png'],
            ['name' => 'Havaianas Slim', 'description' => ' Chất lượng cao, màu nâu', 'price' => '500000', 'image' => 'https://images.timberland.com/is/image/timberland/18094231-ALT4?hei=650&wid=650&qlt=50&resMode=sharp2&op_usm=0.9,1.0,8,0'],
            ['name' => 'Timberland Classic 6-Inch', 'description' => 'Đơn giản, màu hồng pastel', 'price' => '1000000', 'image' => 'https://www.melissashoes.vn/cdn/shop/files/Thi_tk_ch_acoten_13_1360x.png?v=1698633343'],
            ['name' => 'Melissa Ultragirl Sweet', 'description' => 'Giày bệt dễ thương, màu hồng', 'price' => '500000', 'image' => 'https://storage.sg.content-cdn.io/cdn-cgi/image/%7Bwidth%7D,%7Bheight%7D,quality=75,format=auto,fit=cover,g=top/in-resources/92ab8ec8-2216-4f1c-8333-c10c5e7d01c9/Images/ProductImages/Source/1011B189_005_0020031644_RT.jpg'],
            ['name' => 'Asics Gel-Kayano 28', 'description' => 'Giày chạy bộ thoải mái, màu xanh dương', 'price' => '1000000', 'image' => 'https://news.harvard.edu/wp-content/uploads/2022/02/20220218_dresscode.jpg'],
            ['name' => 'Christian Louboutin So Kate', 'description' => 'Giày cao gót sang trọng, màu đỏ', 'price' => '500000', 'image' => 'https://product.hstatic.net/1000376021/product/10017831_1_a92ee7951dac4464ba294814d5b0264a_6b3938c8f44c428a8ad4463ccbe3b74e_master.jpg'],
            ['name' => 'TOMS Alpargata', 'description' => 'Giày lười thoải mái, màu xám', 'price' => '1000000', 'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRA6XLi633cPqkH05azfzL5BIc1n3GUoWM23qt8jD7xcA&s'],
            ['name' => 'Reef Fanning', 'description' => 'Đôi dép quai hậu thể thao, màu xanh dương', 'price' => '500000', 'image' => 'https://cdn.authentic-shoes.com/wp-content/uploads/2023/04/151213_61b873f3c7624e0994db6201da5e3241.png'],
            ['name' => 'Birkenstock Arizona', 'description' => 'Chất lượng, màu nâu', 'price' => '1000000', 'image' => 'https://bizweb.dktcdn.net/100/455/705/products/nbnam2.jpg?v=1703005071660'],
            ['name' => 'New Balance Fresh Foam 1080', 'description' => 'Giày chạy bộ êm ái, màu xám đen', 'price' => '500000', 'image' => 'https://cdn-images.farfetch-contents.com/11/44/67/88/11446788_6857896_600.jpg'],
            ['name' => 'Jimmy Choo Romy', 'description' => 'Giày cao gót sang trọng, màu đen', 'price' => '1000000', 'image' => 'https://lh5.googleusercontent.com/proxy/mIjwPWNT7q5TBFt5XmPHvUXx36gXIoMiCvmK-5kLhM9Aa7ExLqkSDVQ7eWKRD0IAdhimLyBAN8U5PnMeB_nV28nCR4IHqyG7KWSxZYvhiA'],
            ['name' => 'Dr. Martens 1460', 'description' => 'Da thời trang, màu đen', 'price' => '500000', 'image' => 'https://media.gucci.com/style/DarkGray_Center_0_0_490x490/1449789307/423513_BLM00_1000_001_094_0000_Light.jpg'],
            ['name' => 'Gucci Princetown', 'description' => 'Giày cao gót sang trọng, màu đen', 'price' => '1000000', 'image' => 'https://bizweb.dktcdn.net/100/106/923/products/3-3f682079-9cae-44ad-ab22-de8d5b8bc110.jpg?v=1684685861720'],
            ['name' => 'Chanel Ballerina', 'description' => 'Giày búp bê đẳng cấp, màu trắng đen', 'price' => '500000', 'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSya4hk3TszNgpqyOozHrU9jqc2dwJ6WHGlKOBc6SVUgg&s'],
            ['name' => 'Saint Laurent Loulou', 'description' => 'Mắt cáo thanh lịch, màu đen', 'price' => '1000000', 'image' => 'https://assets.adidas.com/images/h_840,f_auto,q_auto,fl_lossy,c_fill,g_auto/7ed0855435194229a525aad6009a0497_9366/Giay_Superstar_trang_EG4958_01_standard.jpg'],
            ['name' => 'Adidas Superstar', 'description' => 'Giày thể thao phong cách, màu trắng đen', 'price' => '500000', 'image' => 'https://www.cln.com.ph/cdn/shop/files/Sunset_Beige_1_1024x1024.jpg?v=1701820779'],
            ['name' => 'Stuart Weitzman Nudistsong', 'description' => 'Gót vuông, màu nude', 'price' => '1000000', 'image' => 'https://www.pedroshoes.com/dw/image/v2/BCWJ_PRD/on/demandware.static/-/Sites-pd_vn-products/default/dwc1c1f299/images/hi-res/2023-L3-PW1-46680006-01-1.jpg?sw=1152&sh=1536'],
            ['name' => 'Church’s Shannon', 'description' => 'Cao gót, có logo Prada', 'price' => '500000', 'image' => 'https://assets.herringshoes.co.uk/_shop/imagelib/4/25/166/church_shannon_in_black_polished_binder_1.jpg'],
            ['name' => 'Castañer Carina', 'description' => 'Giày lười thoải mái, màu xám', 'price' => '1000000', 'image' => 'https://img01.ztat.net/article/spp-media-p1/80105d79859541e4b7cd48e3f631f2bf/27fc6e8b329b4e2fb65ef33f6ca7cf0e.jpg?imwidth=1800&filter=packshot'],
            ['name' => 'Stuart Weitzman Nudistsong', 'description' => '2 gót vuông, màu nude', 'price' => '500000', 'image' => 'https://eu.stuartweitzman.com/dw/image/v2/AAGA_PRD/on/demandware.static/-/Sites-04/default/dw8a52a805/images/zoom/NUDISTSOANI_ADO_1.JPG?sw=320&sh=364&sm=fit'],
            ['name' => 'Prada Logo', 'description' => '2 cao gót, có logo Prada', 'price' => '1000000', 'image' => 'https://cdn.saksfifthavenue.com/is/image/saks/0400016927069_NERO?wid=600&hei=800&qlt=90&resMode=sharp2&op_usm=0.9%2C1.0%2C8%2C0'],
        ];

        foreach ($items as $item) {
            Product::updateOrCreate($item);
        }

        $category_products = [
            ['product_id' => '1', 'category_id' => '1'],
            ['product_id' => '2', 'category_id' => '1'],
            ['product_id' => '3', 'category_id' => '1'],
            ['product_id' => '4', 'category_id' => '1'],
            ['product_id' => '5', 'category_id' => '2'],
            ['product_id' => '6', 'category_id' => '2'],
            ['product_id' => '7', 'category_id' => '2'],
            ['product_id' => '8', 'category_id' => '2'],
            ['product_id' => '9', 'category_id' => '3'],
            ['product_id' => '10', 'category_id' => '3'],
            ['product_id' => '11', 'category_id' => '3'],
            ['product_id' => '12', 'category_id' => '4'],
            ['product_id' => '13', 'category_id' => '4'],
            ['product_id' => '14', 'category_id' => '4'],
            ['product_id' => '15', 'category_id' => '5'],
            ['product_id' => '16', 'category_id' => '6'],
            ['product_id' => '17', 'category_id' => '6'],
            ['product_id' => '18', 'category_id' => '7'],
            ['product_id' => '19', 'category_id' => '7'],
            ['product_id' => '20', 'category_id' => '8'],
            ['product_id' => '21', 'category_id' => '9'],
        ];
        foreach ($category_products as $item) {
            CategoryProduct::updateOrCreate($item);
        }

        $i = 21;
        while ($i > 0) {
            $product_details = [
                ['product_id' => "$i", 'size' => '39', 'quantity' => '1000'],
                ['product_id' => "$i", 'size' => '40', 'quantity' => '1000'],
                ['product_id' => "$i", 'size' => '41', 'quantity' => '1000'],
                ['product_id' => "$i", 'size' => '42', 'quantity' => '1000'],
                ['product_id' => "$i", 'size' => '43', 'quantity' => '1000'],
            ];

            foreach ($product_details as $item) {
                ProductDetail::updateOrCreate($item);
            }

            $i--;
        }
    }
}
