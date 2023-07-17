<?php

class Product
{
    public $id;
    public $name;
    public $price;
    public $created;
    public $sales_count;
    public $views_count;

    public function __construct($id, $name, $price, $created, $sales_count, $views_count)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->created = $created;
        $this->sales_count = $sales_count;
        $this->views_count = $views_count;
    }
}

interface SorterInterface
{
    public function sort(array $products): array;
}

class PriceSorter implements SorterInterface
{
    public function sort(array $products): array
    {
        usort($products, function ($a, $b) {
            return $a->price <=> $b->price;
        });

        return $products;
    }
}

class SalesPerViewSorter implements SorterInterface
{
    public function sort(array $products): array
    {
        usort($products, function ($a, $b) {
            $salesPerViewA = $a->sales_count / $a->views_count;
            $salesPerViewB = $b->sales_count / $b->views_count;

            return $salesPerViewA <=> $salesPerViewB;
        });

        return $products;
    }
}

class Catalog
{
    protected $products;
    protected $sorter;

    public function __construct(array $products)
    {
        $this->products = $products;
    }

    public function setSorter(SorterInterface $sorter)
    {
        $this->sorter = $sorter;
    }

    public function getProducts(): array
    {
        if (!$this->sorter) {
            return $this->products;
        }

        return $this->sorter->sort($this->products);
    }
}

$products = [
    new Product(1, 'Alabaster Table', 12.99, '2019-01-04', 32, 730),
    new Product(2, 'Zebra Table', 44.49, '2012-01-04', 301, 3279),
    new Product(3, 'Coffee Table', 10.00, '2014-05-28', 1048, 20123),
];

$catalog = new Catalog($products);

$productPriceSorter = new PriceSorter();
$catalog->setSorter($productPriceSorter);
$productsSortedByPrice = $catalog->getProducts();
var_dump($productsSortedByPrice);

$productSalesPerViewSorter = new SalesPerViewSorter();
$catalog->setSorter($productSalesPerViewSorter);
$productsSortedBySalesPerView = $catalog->getProducts();

echo "<h1><?php var_dump($productsSortedBySalesPerView); ?></h1>";
