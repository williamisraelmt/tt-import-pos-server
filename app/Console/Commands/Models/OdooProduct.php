<?php


namespace App\Console\Commands\Models;


use App\Product;

class OdooProduct implements IOdooModel
{

    private $id;
    private $name;
    /**
     * @var OdooProductBrand|null
     */
    private $brand;
    private $defaultCode;
    private $image;

    private $availableQuantity;

    private $lstPrice;

    private $listPrice;

    private $standardPrice;

    /**
     * OdooProduct constructor.
     * @param $id
     * @param $name
     * @param OdooProductBrand | null $brand
     * @param $defaultCode
     * @param $availableQuantity
     * @param $lstPrice
     * @param $listPrice
     * @param $standardPrice
     */
    public function __construct(
        $id,
        $name,
        ?OdooProductBrand $brand,
        $defaultCode,
        $availableQuantity,
        $lstPrice,
        $listPrice,
        $standardPrice
        )
    {
        $this->id = $id;
        $this->name = $name;
        $this->brand = $brand;
        $this->defaultCode = $defaultCode;
        $this->availableQuantity = $availableQuantity;
        $this->lstPrice = $lstPrice;
        $this->listPrice = $listPrice;
        $this->standardPrice = $standardPrice;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return OdooProductBrand | null
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param mixed $brand
     */
    public function setCategory($brand): void
    {
        $this->brand = $brand;
    }

    /**
     * @return mixed
     */
    public function getDefaultCode()
    {
        return $this->defaultCode;
    }

    /**
     * @param mixed $defaultCode
     */
    public function setDefaultCode($defaultCode): void
    {
        $this->defaultCode = $defaultCode;
    }

    public function toStoreAsArray(): array
    {
        return [
            "id" => $this->id,
            "name" => trim($this->name, "\xC2\xA0"),
            "product_brand_id" => $this->brand === null ? null : $this->brand->getId(),
            "default_code" => trim($this->defaultCode, "\xC2\xA0"),
            "available_quantity" => $this->availableQuantity,
            "lst_price" => $this->lstPrice,
            "list_price" => $this->listPrice,
            "standard_price" => $this->standardPrice,
            "created_at" => Product::find($this->id)->created_at ?? now()->toDateTimeString(),
            "updated_at" => now()->toDateTimeString()
        ];
    }

}
